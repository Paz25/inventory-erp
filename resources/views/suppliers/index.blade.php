@extends('layouts.index')

@section('content')
<div class="">
    <h3 class="fw-bold font-black mb-1">Manajemen Supplier</h3>
    <div class="my-3 border-bottom border-secondary-subtle w-100"></div>
</div>
<div class="d-flex justify-content-between align-suppliers-center">
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
                <form action="{{  route('suppliers') }}" class="d-flex justify-content-between gap-2 w-100">
                    <input type="text" name="search" class="form-control w-100" placeholder="..." value="{{ $search ?? ''}}">
                    <button type="submit" class="btn btn-info">
                        Search
                    </button>
                </form>
                <button
                    type="button"
                    class="btn btn-success text-nowrap"
                    data-bs-toggle="modal"
                    data-bs-target="#supplierModal"
                    style="min-width: 15%"
                    onclick="openCreateSupplierModal()">
                    Tambah Supplier
                </button>
            </div>

            <div class="table-responsive">
                <table class="table">
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

                    <tbody>
                        @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $suppliers->firstItem() + $loop->index }}</td>
                            <td>{{ $supplier->supplier_name }}</td>
                            <td>{{ $supplier->supplier_address }}</td>
                            <td>{{ $supplier->pic_name }}</td>
                            <td>{{ $supplier->pic_contact_number }}</td>
                            <td>
                                <div class="w-100 d-flex flex-wrap gap-1">
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm flex-grow-1"
                                        data-url="{{ route('suppliers.show', Crypt::encrypt($supplier->id)) }}"
                                        onclick="openEditSupplierModal(this)">
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm flex-grow-1"
                                        data-url="{{ route('suppliers.destroy', Crypt::encrypt($supplier->id)) }}"
                                        data-name="{{ $supplier->supplier_name }}"
                                        onclick="openDeleteSupplierModal(this)">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada data supplier.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('suppliers.partials.create-edit-modal')
@include('suppliers.partials.delete-modal')

<script>
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

@endsection