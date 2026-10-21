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
                <form action="{{ route('items') }}" method="GET" class="d-flex justify-content-between gap-2 w-100">
                    <input type="text" name="search" class="form-control w-100" placeholder="..." value="{{ $search ?? ''}}">
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

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>kategori</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($items as $item)
                        <tr>
                            <td>{{ $items->firstItem() + $loop->index }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->product_category }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                @if ($item->quantity > 0)
                                <span class="badge bg-success">Tersedia</span>
                                @else
                                <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <div class="w-100 d-flex flex-wrap gap-1">
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm flex-grow-1"
                                        data-url="{{ route('items.show', Crypt::encrypt($item->id)) }}"
                                        onclick="openEditModal(this)">
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm flex-grow-1"
                                        data-url="{{ route('items.destroy', Crypt::encrypt($item->id)) }}"
                                        data-name="{{ $item->product_name }}"
                                        onclick="openDeleteModal(this)">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada data barang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('items.partials.create-edit-modal')
@include('items.partials.delete-modal')

<script>
    function openCreateModal() {
        const itemForm = document.getElementById('itemForm');

        itemForm.reset();
        itemForm.action = "{{ route('items.store') }}";
        document.getElementById('methodField').innerHTML = '';

        document.getElementById('itemModalLabel').textContent = 'Tambah Barang';
        document.getElementById('submitButton').textContent = 'Simpan Barang';
        document.getElementById('jumlah').value = 0;
    }

    async function openEditModal(button) {
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

    document.getElementById('deleteForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitDeleteButton');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menghapus...';
    });
</script>

@endsection