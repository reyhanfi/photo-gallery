<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Tentukan halaman aktif untuk navbar
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? e($page_title) . ' — ' : '' ?>PhotoGallery</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
            <i class="bi bi-camera2 me-2"></i>PhotoGallery
        </a>

        <!-- Hamburger toggle untuk mobile -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'catalog.php' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/pages/catalog.php">
                        <i class="bi bi-grid me-1"></i>Catalog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page === 'about.php' ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/pages/about.php">
                        <i class="bi bi-person me-1"></i>About Me
                    </a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Kalau sudah login, tampilkan menu admin -->
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle" href="#"
                       data-bs-toggle="dropdown">
                        <i class="bi bi-shield-lock me-1"></i>
                        <?= e($_SESSION['username']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/admin/dashboard.php">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/admin/katalog/index.php">
                                <i class="bi bi-collection me-2"></i>Kelola Katalog
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger"
                               href="<?= BASE_URL ?>/auth/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                <!-- Kalau belum login -->
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-light btn-sm"
                       href="<?= BASE_URL ?>/auth/login.php">
                        <i class="bi bi-lock me-1"></i>Login
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<!-- END NAVBAR -->