@extends('layouts.index')

@section('content')
<div class="">
    <h3 class="fw-bold font-black mb-1">Manajemen Barang</h3>
    <div class="my-3 border-bottom border-secondary-subtle w-100"></div>
</div>
<div class="d-flex justify-content-between align-items-center">
    <div class="card shadow-sm w-100">
        <div class="card-body">
            <div class="alert-body">
                @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center gap-2">
                <form id="searchForm"
                    action="{{ route('items') }}"
                    method="GET"
                    class="d-flex justify-content-between gap-2 w-100">
                    <input id="searchFormInput"
                        type="text"
                        name="search"
                        class="form-control w-100"
                        placeholder="Cari kode, nama, atau kategori..."
                        value="{{ $search ?? ''}}">
                    <button type="submit" class="btn btn-info">
                        Search
                    </button>
                </form>
                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#itemModal"
                    style="width: 20%"
                    onclick="openCreateModal()">
                    Tambah Barang
                </button>
            </div>

            <div id="itemTable" class="mt-3">
                @include('items.partials.table')
            </div>
        </div>
    </div>
</div>

@include('items.partials.create-edit-modal')
@include('items.partials.delete-modal')

<!-- Ajax script -->
<script>
    const itemTable = document.getElementById('itemTable');
    const searchForm = document.getElementById('searchForm');
    const searchFormInput = document.getElementById('searchFormInput');

    let tableRequest = null;
    let searchTimer = null;

    async function loadItems(url) {
        tableRequest?.abort();
        const current = new AbortController();
        tableRequest = current;

        try {
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
                signal: current.signal,
            });

            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            itemTable.innerHTML = await res.text();

            history.replaceState(null, '', url);
        } catch (e) {
            if (e.name === 'AbortError') return;
            console.error(e);
            showAlert('danger', 'Gagal memuat data barang.');
        }
    }

    function buildSearchUrl() {
        const url = new URL(searchForm.action);
        const keyword = searchFormInput.value.trim();
        if (keyword) url.searchParams.set('search', keyword);
        return url.toString();
    }

    // debounce
    searchFormInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => loadItems(buildSearchUrl()), 400);
    })

    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        clearTimeout(searchTimer);
        loadItems(buildSearchUrl());
    })

    itemTable.addEventListener('click', (e) => {
        const link = e.target.closest('.pagination a');
        if (!link) return;
        e.preventDefault();
        loadItems(link.href);
    });

    function showAlert(type, message) {
        const wrapper = document.createElement('div');
        wrapper.className = `alert alert-${type} alert-dismissible fade show`;
        wrapper.setAttribute('role', 'alert');

        const text = document.createElement('span');
        text.textContent = message;

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'btn-close';
        close.setAttribute('data-bs-dismiss', 'alert');
        close.setAttribute('aria-label', 'Close');

        wrapper.append(text, close);
        document.querySelector('.alert-body').replaceChildren(wrapper);
    }
</script>

<script>
    const itemForm = document.getElementById('itemForm');

    function openCreateModal() {
        clearFormErrors();
        itemForm.reset();
        itemForm.action = "{{ route('items.store') }}";
        document.getElementById('methodField').innerHTML = '';

        document.getElementById('itemModalLabel').textContent = 'Tambah Barang';
        document.getElementById('submitButton').textContent = 'Simpan Barang';
        document.getElementById('jumlah').value = 0;
    }

    async function openEditModal(button) {
        clearFormErrors();
        const itemForm = document.getElementById('itemForm');
        const itemModal = document.getElementById('itemModal');

        try {
            const res = await fetch(button.dataset.url, {
                headers: {
                    'Accept': 'application/json'
                },
            });
            const data = await res.json();

            if (!res.ok) {
                bootstrap.Modal.getInstance(itemModal)?.hide();
                alert(data.message ?? 'Gagal memuat data barang.');
                return;
            }

            itemForm.action = data.update_url;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('itemModalLabel').textContent = 'Edit Barang';
            document.getElementById('submitButton').textContent = 'Simpan Perubahan';

            document.getElementById('kode').value = data.kode;
            document.getElementById('nama').value = data.nama;
            document.getElementById('kategori').value = data.kategori;
            document.getElementById('jumlah').value = data.jumlah;

            bootstrap.Modal.getOrCreateInstance(itemModal).show();
        } catch (e) {
            console.error(e);
            bootstrap.Modal.getInstance(itemModal)?.hide();
            // alert('Gagal memuat data barang.');
        }
    }

    function openDeleteModal(button) {
        const submitBtn = document.getElementById('submitDeleteButton');

        document.getElementById('deleteForm').action = button.dataset.url;
        document.getElementById('deleteItemName').textContent = button.dataset.name;

        submitBtn.disabled = false;
        submitBtn.textContent = 'Hapus';

        bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
    }

    function clearFormErrors() {
        itemForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        itemForm.querySelectorAll('.invalid-feedback[data-ajax]').forEach(el => el.remove());
    }

    function showFormErrors(errors) {
        Object.entries(errors).forEach(([field, messages]) => {
            const input = itemForm.querySelector(`[name="${field}"]`);
            if (!input) return;

            input.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.dataset.ajax = '';
            feedback.textContent = messages[0];
            input.insertAdjacentElement('afterend', feedback);
        });
    }

    itemForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submitButton');
        const originalText = submitBtn.textContent;

        clearFormErrors();
        submitBtn.disabled = true;
        submitBtn.text = 'Menyimpan...';

        try {
            const res = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(this),
            });
            const data = await res.json();

            if (res.status === 422) {
                showFormErrors(data.errors);
                return;
            }

            bootstrap.Modal.getInstance(document.getElementById('itemModal'))?.hide();
            showAlert(res.ok ? 'success' : 'danger', data.message ?? 'Terjadi kesalahan.');

            if (res.ok) loadItems(location.href);
        } catch (err) {
            console.error(err);
            showAlert('danger', 'Gagal menghubungi server.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });

    document.getElementById('deleteForm').addEventListener('submit', async function() {
        const submitBtn = document.getElementById('submitDeleteButton');
        const itemDeleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));

        submitBtn.disabled = true;
        submitBtn.textContent = 'Menghapus...';

        try {
            const res = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(this),
            });
            const data = await res.json();

            itemDeleteModal?.hide();
            showAlert(res.ok ? 'success' : 'danger', data.message ?? 'Terjadi kesalahan.');

            if (res.ok) loadItems(location.href);
        } catch (err) {
            console.log(err);
            showAlert('danger', 'Gagal menghubungi server.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Hapus';
        }
    });
</script>

@endsection