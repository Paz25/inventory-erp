@extends('layouts.index')

@section('content')
<div class="">
    <h3 class="fw-bold font-black mb-1">Manajemen Supplier</h3>
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
                <form action="{{  route('suppliers') }}" class="d-flex justify-content-between gap-2 w-100">
                    <input type="text" name="search" class="form-control w-100" placeholder="..." value="{{ $search ?? ''}}">
                    <button type="submit" class="btn btn-info">
                        Search
                    </button>
                </form>
                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#supplierModal"
                    style="width: 20%"
                    onclick="openCreateModal()">
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
                                        onclick="">
                                        Edit
                                    </button>
                                    <form
                                        action=""
                                        method="POST"
                                        class="d-inline flex-grow-1"
                                        onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            Hapus
                                        </button>
                                    </form>
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
            </div>
        </div>
    </div>
</div>
@endsection