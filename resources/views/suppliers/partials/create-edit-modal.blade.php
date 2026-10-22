<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('suppliers.store') }}" method="POST" id="supplierForm">
                @csrf
                <div id="supplierMethodField"></div>

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="supplierModalLabel">
                        Tambah Supplier
                    </h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="supplierNamaPerusahaan" class="form-label">
                                Nama Supplier
                            </label>
                            <input type="text" id="supplierNamaPerusahaan" name="nama_perusahaan"
                                maxlength="255" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label for="supplierNamaPic" class="form-label">
                                Nama PIC
                            </label>
                            <input type="tel" id="supplierNamaPic" name="nama_pic"
                                maxlength="255" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="supplierNoTelpPic" class="form-label">
                                No. Telp PIC
                            </label>
                            <input type="tel" id="supplierNoTelpPic" name="no_telp_pic"
                                maxlength="20" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label for="supplierEmailPic" class="form-label">
                                Email PIC
                            </label>
                            <input type="email" id="supplierEmailPic" name="email_pic"
                                maxlength="255" class="form-control">
                        </div>

                        <div class="col-12">
                            <label for="supplierAlamatPerusahaan" class="form-label">
                                Alamat Supplier
                            </label>
                            <textarea id="supplierAlamatPerusahaan" name="alamat_perusahaan"
                                rows="3" maxlength="255"
                                class="form-control" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary" id="supplierSubmitButton">
                        Simpan Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>