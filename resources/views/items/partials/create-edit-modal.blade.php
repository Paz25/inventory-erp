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