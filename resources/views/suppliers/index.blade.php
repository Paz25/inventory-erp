@extends('layouts.index')

@section('content')
<div class="">
    <h3 class="fw-bold font-black mb-1">Manajemen Supplier</h3>
    <div class="my-3 border-bottom border-secondary-subtle w-100"></div>
</div>
<div class="d-flex flex-column justify-content-between align-items-end gap-2">
    <div class="d-flex justify-content-end align-items-center gap-2">
        <button
            type="button"
            class="btn btn-success text-nowrap"
            data-bs-toggle="modal"
            data-bs-target="#supplierModal"
            onclick="openCreateSupplierModal()">
            Tambah Supplier
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

            <table id="suppliersTable" class="table w-100">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Perusahaan</th>
                        <th>Alamat</th>
                        <th>PIC</th>
                        <th>Contact PIC</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@include('suppliers.partials.create-edit-modal')
@include('suppliers.partials.delete-modal')

@push('scripts')
<script>
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

    DataTable.ext.errMode = 'none';

    const suppliersTable = new DataTable('#suppliersTable', {
        processing: true,
        serverSide: true,
        ajax: "{{ route('suppliers.data') }}",
        order: [],
        searchDelay: 400,
        columns: [{
                data: 'no',
                orderable: false,
                searchable: false
            },
            {
                data: 'supplier_name'
            },
            {
                data: 'supplier_address'
            },
            {
                data: 'pic_name'
            },
            {
                data: 'pic_contact_number'
            },
            {
                data: 'action',
                orderable: false,
                searchable: false
            },
        ],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Tidak ada supplier yang cocok.',
            emptyTable: 'Belum ada data supplier.',
            processing: 'Memuat...',
        },
    });

    suppliersTable.on('error', (e, settings, techNote, message) => {
        console.error(message);
        showAlert('danger', 'Gagal memuat data supplier.');
    });

    function openCreateSupplierModal() {
        const supplierForm = document.getElementById('supplierForm');

        supplierForm.reset();
        supplierForm.action = "{{ route('suppliers.store') }}";
        document.getElementById('supplierMethodField').innerHTML = '';

        document.getElementById('supplierModalLabel').textContent = 'Tambah Supplier';
        document.getElementById('supplierSubmitButton').textContent = 'Simpan Supplier';
    }

    async function openEditSupplierModal(button) {
        const supplierForm = document.getElementById('supplierForm');
        const supplierModal = document.getElementById('supplierModal');

        try {
            const res = await fetch(button.dataset.url, {
                headers: {
                    'Accept': 'application/json'
                },
            });
            const data = await res.json();

            if (!res.ok) {
                bootstrap.Modal.getInstance(supplierModal)?.hide();
                alert(data.message ?? 'Gagal memuat data barang.');
                return;
            }

            supplierForm.action = data.update_url;
            document.getElementById('supplierMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('supplierModalLabel').textContent = 'Edit Supplier';
            document.getElementById('supplierSubmitButton').textContent = 'Simpan Perubahan';

            document.getElementById('supplierNamaPerusahaan').value = data.nama_perusahaan;
            document.getElementById('supplierAlamatPerusahaan').value = data.alamat_perusahaan;
            document.getElementById('supplierNamaPic').value = data.nama_pic;
            document.getElementById('supplierNoTelpPic').value = data.no_telp_pic;
            document.getElementById('supplierEmailPic').value = data.email_pic;

            bootstrap.Modal.getOrCreateInstance(supplierModal).show();
        } catch (e) {
            console.error(e);
            // alert('Gagal memuat data barang.');
        }
    }

    function openDeleteSupplierModal(button) {
        const submitBtn = document.getElementById('submitDeleteButton');

        document.getElementById('deleteForm').action = button.dataset.url;
        document.getElementById('deleteSupplierName').textContent = button.dataset.name;

        submitBtn.disabled = false;
        submitBtn.textContent = 'Hapus';

        bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteSupplierModal')).show();
    }
</script>
@endpush

@endsection