<?php
$page_title = 'Dashboard';
require_once '../includes/header.php';
requireLogin();

// Statistik untuk dashboard
$stats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        (SELECT COUNT(*) FROM katalog)      AS total_katalog,
        (SELECT COUNT(*) FROM foto)         AS total_foto,
        (SELECT COUNT(*) FROM users)        AS total_admin,
        (SELECT COUNT(*) FROM activity_log) AS total_log
"));

// Katalog dengan foto terbanyak (pakai VIEW)
$result_top = mysqli_query($conn, "
    SELECT * FROM view_photo_statistics
    ORDER BY total_foto DESC
    LIMIT 5
");

// Log aktivitas terbaru
$result_log = mysqli_query($conn, "
    SELECT l.*, u.username
    FROM activity_log l
    JOIN users u ON l.id_admin = u.id_user
    ORDER BY l.waktu DESC
    LIMIT 10
");
?>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 px-0 admin-sidebar d-none d-md-block">
            <div class="p-3">
                <p class="text-white-50 small text-uppercase fw-semibold mb-3 mt-2">
                    Menu Admin
                </p>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="<?= BASE_URL ?>/admin/dashboard.php">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="<?= BASE_URL ?>/admin/katalog/index.php">
                        <i class="bi bi-collection me-2"></i>Kelola Katalog
                    </a>
                    <a class="nav-link" href="<?= BASE_URL ?>/pages/catalog.php">
                        <i class="bi bi-eye me-2"></i>Lihat Website
                    </a>
                    <hr style="border-color:rgba(255,255,255,0.1)">
                    <a class="nav-link text-danger" href="<?= BASE_URL ?>/auth/logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </nav>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10 py-4 px-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Dashboard</h2>
                    <small class="text-muted">
                        Selamat datang, <?= e($_SESSION['username']) ?>!
                    </small>
                </div>
                <a href="<?= BASE_URL ?>/admin/katalog/add.php"
                   class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Katalog
                </a>
            </div>

            <!-- STAT CARDS -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <div style="font-size:2rem;color:#0f3460" class="fw-bold">
                            <?= $stats['total_katalog'] ?>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-collection me-1"></i>Katalog
                        </small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <div style="font-size:2rem;color:#198754" class="fw-bold">
                            <?= $stats['total_foto'] ?>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-images me-1"></i>Foto
                        </small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <div style="font-size:2rem;color:#fd7e14" class="fw-bold">
                            <?= $stats['total_admin'] ?>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-person me-1"></i>Admin
                        </small>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <div style="font-size:2rem;color:#dc3545" class="fw-bold">
                            <?= $stats['total_log'] ?>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-clock-history me-1"></i>Aktivitas
                        </small>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <!-- TOP KATALOG (pakai VIEW) -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white fw-semibold border-0 pt-3">
                            <i class="bi bi-bar-chart me-2 text-primary"></i>
                            Katalog dengan Foto Terbanyak
                            <small class="text-muted fw-normal ms-1">(dari view_photo_statistics)</small>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Katalog</th>
                                            <th class="text-center">Foto</th>
                                            <th class="text-end">Ukuran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result_top)): ?>
                                        <tr>
                                            <td class="small"><?= e($row['judul_katalog']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">
                                                    <?= $row['total_foto'] ?>
                                                </span>
                                            </td>
                                            <td class="text-end small text-muted">
                                                <?= number_format($row['total_ukuran_bytes'] / 1024, 1) ?> KB
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LOG AKTIVITAS -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white fw-semibold border-0 pt-3">
                            <i class="bi bi-clock-history me-2 text-danger"></i>
                            Log Aktivitas Terbaru
                        </div>
                        <div class="card-body p-0">
                            <?php if (mysqli_num_rows($result_log) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Aksi</th>
                                            <th>Admin</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($log = mysqli_fetch_assoc($result_log)): ?>
                                        <tr>
                                            <td class="small"><?= e($log['aksi']) ?></td>
                                            <td class="small"><?= e($log['username']) ?></td>
                                            <td class="small text-muted">
                                                <?= date('d/m H:i', strtotime($log['waktu'])) ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4 text-muted small">
                                <i class="bi bi-inbox" style="font-size:2rem"></i>
                                <p class="mt-2 mb-0">Belum ada aktivitas</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>