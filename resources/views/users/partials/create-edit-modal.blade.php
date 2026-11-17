<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="userForm">
                @csrf
                <div id="userMethodField"></div>

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="userModalLabel">
                        Create New User
                    </h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="username" class="form-label">
                                Username
                            </label>
                            <input type="text" id="username" name="user_username"
                                maxlength="255" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                Password
                            </label>
                            <input type="password" id="password" name="user_password"
                                maxlength="72" autocomplete="new-password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="confirmPassword" class="form-label">
                                Confirm Password
                            </label>
                            <input type="password" id="password" name="confirm_password"
                                maxlength="72" autocomplete="new-password" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label for="user_role" class="form-label">
                                Role
                            </label>
                            <select name="user_role" id="userRole" class="form-select" required>
                                <option value="">Pilih role</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary" id="userSubmitButton">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>