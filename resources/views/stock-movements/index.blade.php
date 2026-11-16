@extends('layouts.index')

@section('content')
<div class="">
    <h3 class="fw-bold font-black mb-1">Transaksi Stok</h3>
    <div class="my-3 border-bottom border-secondary-subtle w-100"></div>
</div>
<div class="d-flex flex-column justify-content-between align-items-end gap-2">
    <div class="d-flex justify-content-between align-items-center gap-2">
        <button
            type="button"
            class="btn btn-success text-nowrap"
            data-bs-toggle="modal"
            data-bs-target="#stockMovementModal"
            style="min-width: 15%"
            onclick="openCreateStockModal()">
            Catat Transaksi Stok
        </button>
    </div>

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


            <div class="mt-3">
                <table id="stockMovementsTable" class="table w-100">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Waktu</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@include('stock-movements.partials.create-modal')

@push('scripts')
<script>
    const stockMovementForm = document.getElementById('stockMovementForm');

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

    function clearFormErrors() {
        stockMovementForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        stockMovementForm.querySelectorAll('.invalid-feedback[data-ajax]').forEach(el => el.remove());
    }

    function openCreateStockModal() {
        clearFormErrors();
        document.getElementById('stockItem').value = '';
        document.getElementById('stockTipe').value = '';
        document.getElementById('stockJumlah').value = '';
        document.getElementById('stockModalErrors')?.remove();

        const submitBtn = document.getElementById('stockSubmitButton');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Simpan';
    }

    stockMovementForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('stockSubmitButton');
        const originalText = submitBtn.textContent;
        const selectedOption = document.getElementById('stockItem').selectedOptions[0];

        clearFormErrors();
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';

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

            bootstrap.Modal.getInstance(document.getElementById('stockMovementModal'))?.hide();
            showAlert(res.ok ? 'success' : 'danger', data.message ?? 'Terjadi kesalahan.');

            if (res.ok) {
                updateItemOptionStock(selectedOption, data.remaining_stock);
                stockMovementsTable.ajax.reload();
            }
        } catch (err) {
            console.error(err);
            showAlert('danger', 'Gagal menghubungi server.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });

    function showFormErrors(errors) {
        Object.entries(errors).forEach(([field, messages]) => {
            const input = stockMovementForm.querySelector(`[name="${field}"]`);
            if (!input) return;

            input.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.dataset.ajax = '';
            feedback.textContent = messages[0];
            input.insertAdjacentElement('afterend', feedback);
        });
    }

    function updateItemOptionStock(option, stock) {
        if (!option || stock === undefined) return;
        option.textContent = `${option.dataset.label} | Stok: ${stock}`;
    }

    DataTable.ext.errMode = 'none';

    const typeBadges = {
        initial: ['primary', 'Stok Awal'],
        masuk: ['success', 'Masuk'],
        keluar: ['danger', 'Keluar'],
    };

    const stockMovementsTable = new DataTable('#stockMovementsTable', {
        processing: true,
        serverSide: true,
        ajax: "{{ route('stock-movements.data') }}",
        order: [],
        searchDelay: 400,
        columns: [{
                data: 'no',
                orderable: false,
                searchable: false
            },
            {
                data: 'created_at',
                searchable: false
            },
            {
                data: 'item_code'
            },
            {
                data: 'item_name',
                render: (data, type, row) => row.item_inactive ?
                    `${data} <span class="badge bg-secondary">Inactive</span>` : data
            },
            {
                data: 'type',
                searchable: false,
                render: (data) => {
                    const [color, label] = typeBadges[data] ?? ['warning', 'Lainnya'];
                    return `<span class="badge bg-${color}">${label}</span>`;
                }

            },
            {
                data: 'quantity',
                searchable: false,
                render: (data, type, row) => row.type === 'keluar' ?
                    `<span class="text-danger fw-semibold">-${data}</span>` : `<span class="text-success fw-semibold">+${data}</span>`
            }
        ],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Tidak ada transaksi barang yang cocok.',
            emptyTable: 'Belum ada data transaksi barang.',
            processing: 'Memuat...',
        },
    });

    stockMovementsTable.on('error', (e, settings, techNote, message) => {
        console.error(message);
        showAlert('danger', 'Gagal memuat data transaksi barang');
    })
</script>
@endpush

@endsection