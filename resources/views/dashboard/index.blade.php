@extends('layouts.index')

@section('content')
<div class="">
    <h2 class="fw-bold font-black">Dashboard</h2>
</div>
<div class="d-flex justify-content-between align-items-center">
    <div class="card w-100">
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show"
                role="alert">
                {{ session('success') }}

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show"
                role="alert">
                {{ session('error') }}

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Data belum berhasil disimpan.</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="d-flex justify-content-between align-items-center gap-1">
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex justify-content-between gap-1 w-100">
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
                            <td>{{ $item->kode }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kategori }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>
                                @if ($item->jumlah > 0)
                                <span class="badge bg-success">Tersedia</span>
                                @else
                                <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#itemModal"
                                    data-id="{{ $item->id }}"
                                    data-kode="{{ $item->kode }}"
                                    data-nama="{{ $item->nama }}"
                                    data-kategori="{{ $item->kategori }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    onclick="openEditModal(this)">
                                    Edit
                                </button>

                                <form
                                    action="{{ route('items.destroy', $item->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
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

<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('items.store') }}" method="POST" id="itemForm">
                @csrf
                <div id="methodField"></div>

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="itemModalLabel">
                        Tambah Barang
                    </h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kode" class="form-label">
                                Kode Barang
                            </label>
                            <input type="text" id="kode" name="kode"
                                class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="nama" class="form-label">
                                Nama Barang
                            </label>
                            <input type="text" id="nama" name="nama"
                                class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="kategori" class="form-label">
                                Kategori
                            </label>
                            <input type="text" id="kategori" name="kategori"
                                class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="jumlah" class="form-label">
                                Jumlah
                            </label>
                            <input type="number" id="jumlah" name="jumlah"
                                min="0" value="0"
                                class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary" id="submitButton">
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCreateModal() {
        const form = document.getElementById('itemForm');

        form.reset();
        form.action = "{{ route('items.store') }}";

        document.getElementById('methodField').innerHTML = '';
        document.getElementById('itemModalLabel').textContent = 'Tambah Barang';
        document.getElementById('submitButton').textContent = 'Simpan Barang';
        document.getElementById('jumlah').value = 0;
    }

    function openEditModal(button) {
        const form = document.getElementById('itemForm');
        const id = button.dataset.id;

        form.action = "{{ url('/items') }}/" + id;

        document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('itemModalLabel').textContent = 'Edit Barang';
        document.getElementById('submitButton').textContent = 'Simpan Perubahan';

        document.getElementById('kode').value = button.dataset.kode;
        document.getElementById('nama').value = button.dataset.nama;
        document.getElementById('kategori').value = button.dataset.kategori;
        document.getElementById('jumlah').value = button.dataset.jumlah;
    }
</script>

@endsection