<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Inventory ERP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">
    <main class="min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="card shadow-sm w-100" style="max-width: 400px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-1">Inventory ERP</h3>
                    <p class="text-secondary mb-0">Masuk untuk melanjutkan</p>
                </div>

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('login.attempt') }}" method="POST" id="loginForm" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username') }}"
                            maxlength="30"
                            autocomplete="username"
                            autofocus
                            required>
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group has-validation">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                maxlength="72"
                                autocomplete="current-password"
                                required>
                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="togglePassword"
                                aria-label="Tampilkan password">
                                Lihat
                            </button>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="loginButton">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');

        toggleButton.addEventListener('click', function() {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            toggleButton.textContent = isHidden ? 'Sembunyikan' : 'Lihat';
        });

        document.getElementById('loginForm').addEventListener('submit', function() {
            const loginButton = document.getElementById('loginButton');
            loginButton.disabled = true;
            loginButton.textContent = 'Memproses...';
        });
    </script>
</body>

</html>