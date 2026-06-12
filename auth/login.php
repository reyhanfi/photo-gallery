<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Kalau sudah login, langsung ke dashboard
if (isset($_SESSION['user_id'])) {
    redirect('/admin/dashboard.php');
}

$error = '';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi.';
    } else {
        // Cek user di database — prepared statement
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($user && password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id']  = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            redirect('/admin/dashboard.php');
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

$page_title = 'Login Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PhotoGallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); min-height: 100vh; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4">

            <!-- Card Login -->
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <i style="font-size:3rem;color:#0f3460"></i>
                        <h4 class="fw-bold mt-2 mb-0">Arey's Catalog</h4>
                        <small class="text-muted">Admin Panel</small>
                    </div>

                    <!-- Alert error -->
                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= e($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Form Login -->
                    <form method="POST" action="" id="formLogin">

                        <!-- Username -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="username">
                                Username
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="username"
                                       name="username"
                                       placeholder="Masukkan username"
                                       value="<?= e($_POST['username'] ?? '') ?>"
                                       autocomplete="username">
                            </div>
                            <div class="text-danger small mt-1 d-none" id="usernameError"></div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" for="password">
                                Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       placeholder="Masukkan password"
                                       autocomplete="current-password">
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div class="text-danger small mt-1 d-none" id="passwordError"></div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>

                    </form>

                    <!-- Back to home -->
                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>" class="text-muted small">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke website
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validasi form login
document.getElementById('formLogin').addEventListener('submit', function(e) {
    let valid = true;

    const username      = document.getElementById('username');
    const usernameError = document.getElementById('usernameError');
    const password      = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');

    // Validasi username
    if (username.value.trim() === '') {
        usernameError.textContent = 'Username wajib diisi.';
        usernameError.classList.remove('d-none');
        username.classList.add('is-invalid');
        valid = false;
    } else {
        usernameError.classList.add('d-none');
        username.classList.remove('is-invalid');
    }

    // Validasi password
    if (password.value === '') {
        passwordError.textContent = 'Password wajib diisi.';
        passwordError.classList.remove('d-none');
        password.classList.add('is-invalid');
        valid = false;
    } else {
        passwordError.classList.add('d-none');
        password.classList.remove('is-invalid');
    }

    if (!valid) e.preventDefault();
});

// Toggle show/hide password — addEventListener selain onclick
document.getElementById('togglePassword').addEventListener('click', function() {
    const input   = document.getElementById('password');
    const icon    = document.getElementById('eyeIcon');
    const isHidden = input.type === 'password';
    input.type     = isHidden ? 'text' : 'password';
    icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
});
</script>

</body>
</html>