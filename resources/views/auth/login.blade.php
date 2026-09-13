<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Telaga Bagus Butik</title>
    
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Link CSS Custom -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="login-page">

    <div class="login-card">
        <!-- Header Brand (Dark Gradient Navy) -->
        <div class="login-header">
            <div class="login-brand-icon">
                <i class="bi bi-scissors"></i>
            </div>
            <h4 class="fw-bold text-white mb-1 tracking-wide">TELAGA BAGUS BUTIK</h4>
            <p class="text-white opacity-75 small mb-0">Sistem Manajemen & Operasional Butik</p>
        </div>

        <!-- Body Form -->
        <div class="login-body">
            <div class="text-center mb-4">
                <h5 class="fw-bold text-dark mb-1">Masuk ke Akun Anda</h5>
                <p class="text-muted small mb-0">Gunakan akun terdaftar untuk melanjutkan</p>
            </div>

            <!-- Pesan Error / Validasi -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm py-2 px-3 small d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-exclamation-octagon-fill fs-6 flex-shrink-0 text-danger"></i>
                    <div class="flex-grow-1">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-close btn-close-sm shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm py-2 px-3 small d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-6 flex-shrink-0 text-danger"></i>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close btn-close-sm shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Formulir Login -->
            <form action="/login" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold small text-secondary">Username</label>
                    <div class="input-group login-input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text ps-3"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus autocomplete="username">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold small text-secondary">Password</label>
                    <div class="input-group login-input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text ps-3"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
                        <button class="btn btn-light border-start-0 border-secondary-subtle text-muted px-3" type="button" id="togglePasswordBtn" title="Lihat/Sembunyikan Password">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary login-btn-submit w-100 shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-in-right fs-5"></i> Masuk Sistem
                </button>
            </form>

            <!-- Info Role & Footer -->
            <div class="mt-4 pt-3 border-top text-center">
                <p class="text-muted fs-8 mt-2 mb-0">© {{ date('Y') }} Telaga Bagus Butik | Akses Internal</p>
            </div>
        </div>
    </div>

    <!-- Script Bootstrap & Toggle Password Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                toggleIcon.classList.toggle('bi-eye', !isPassword);
                toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    </script>
</body>
</html>