@extends('layouts.index')

@section('content')
<div class="">
    <h3 class="fw-bold font-black mb-1">Riwayat Stok</h3>
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
            </div>

            <div class="d-flex justify-content-between align-items-center gap-2">
                <form action="{{ route('stock-movements') }}" method="GET" class="d-flex justify-content-between gap-2 w-100">
                    <input type="text" name="search" class="form-control w-100" placeholder="Cari kode atau nama barang..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-info">
                        Search
                    </button>
                </form>
                <button
                    type="button"
                    class="btn btn-success text-nowrap"
                    data-bs-toggle="modal"
                    data-bs-target="#stockMovementModal"
                    style="min-width: 15%"
                    onclick="openCreateStockModal()">
                    Catat Stok
                </button>
            </div>

            <div class="table-responsive">
                <table class="table">
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

                    <tbody>
                        @forelse ($movements as $movement)
                        @php
                        [$badgeClass, $badgeLabel] = match ($movement->type) {
                        'initial' => ['secondary', 'Stok Awal'],
                        'masuk' => ['success', 'Masuk'],
                        'keluar' => ['danger', 'Keluar'],
                        default => ['warning', ucfirst($movement->type)],
                        };
                        @endphp
                        <tr>
                            <td>{{ $movements->firstItem() + $loop->index }}</td>
                            <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $movement->item?->code ?? '-' }}</td>
                            <td>
                                {{ $movement->item?->product_name ?? '-' }}
                                @if ($movement->item?->trashed())
                                <span class="badge bg-secondary">Dihapus</span>
                                @endif
                            </td>
                            <td><span class="badge bg-{{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                            <td>{{ $movement->type === 'keluar' ? '-' : '+' }}{{ $movement->quantity }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada riwayat stok.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('stock-movements.partials.create-modal')

<script>
    function openCreateStockModal() {
        document.getElementById('stockItem').value = '';
        document.getElementById('stockTipe').value = '';
        document.getElementById('stockJumlah').value = '';
        document.getElementById('stockModalErrors')?.remove();

        const submitBtn = document.getElementById('stockSubmitButton');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Simpan';
    }

    document.getElementById('stockMovementForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('stockSubmitButton');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';
    });
</script>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('stockMovementModal')).show();
    });
</script>
@endif

@endsection