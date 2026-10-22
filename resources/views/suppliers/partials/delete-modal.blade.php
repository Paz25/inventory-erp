<div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="deleteSupplierModalLabel">
                        Hapus Data Supplier
                    </h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    Anda yakin ingin menghapus supplier <strong id="deleteSupplierName"></strong> ? <br>
                    Tindakan ini tidak bisa dibatalkan.
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger" id="submitDeleteButton">
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>