@php
$selectedItemId = null;

if (is_string(old('item_id'))) {
try {
$selectedItemId = \Illuminate\Support\Facades\Crypt::decrypt(old('item_id'));
} catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
$selectedItemId = null;
}
}
@endphp

<div class="modal fade" id="stockMovementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('stock-movements.store') }}" method="POST" id="stockMovementForm">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="stockMovementModalLabel">
                        Catat Stok
                    </h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert" id="stockModalErrors">
                        <strong>Gagal!</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="stockItem" class="form-label">
                                Barang
                            </label>
                            <select id="stockItem" name="item_id" class="form-select" required>
                                <option value="">Pilih barang...</option>
                                @foreach ($items as $item)
                                <option value="{{ Crypt::encrypt($item->id) }}" @selected($selectedItemId===$item->id)>
                                    {{ $item->code }} | {{ $item->product_name }} | Stok: {{ $item->quantity }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="stockTipe" class="form-label">
                                Tipe
                            </label>
                            <select id="stockTipe" name="tipe" class="form-select" required>
                                <option value="">Pilih tipe...</option>
                                <option value="masuk" @selected(old('tipe')==='masuk' )>Barang Masuk</option>
                                <option value="keluar" @selected(old('tipe')==='keluar' )>Barang Keluar</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="stockJumlah" class="form-label">
                                Jumlah
                            </label>
                            <input type="number" id="stockJumlah" name="jumlah"
                                min="1" max="1000000" value="{{ old('jumlah') }}"
                                class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary" id="stockSubmitButton">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>