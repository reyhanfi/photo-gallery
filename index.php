<?php
$page_title = 'Home';
require_once 'includes/header.php';

// Query 1 (Complex): Statistik global untuk hero section
$query_stats = "
    SELECT 
        COUNT(DISTINCT k.id_katalog)         AS total_katalog,
        COUNT(f.id_foto)                     AS total_foto,
        COALESCE(SUM(f.ukuran_file), 0) / 1048576 AS total_ukuran_mb
    FROM katalog k
    LEFT JOIN foto f ON k.id_katalog = f.id_katalog
";
$result_stats = mysqli_query($conn, $query_stats);
$stats = mysqli_fetch_assoc($result_stats);

// Query 2 (Complex): Katalog terbaru + subquery tag
$query_katalog = "
    SELECT
        k.id_katalog,
        k.judul,
        k.thumbnail,
        k.jumlah_foto,
        DATE_FORMAT(k.tanggal_event, '%M %Y') AS bulan_tahun,
        (
            SELECT GROUP_CONCAT(nama_tag SEPARATOR ', ')
            FROM tags
            WHERE id_katalog = k.id_katalog
        ) AS tag_list
    FROM katalog k
    ORDER BY k.created_at DESC
    LIMIT 6
";
$result_katalog = mysqli_query($conn, $query_katalog);
?>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="container py-5">
        <div class="row align-items-center">

            <!-- Teks hero -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="mb-3">
                    Moments<br>
                    <span style="color:#4fc3f7;font-style:bold">Worth Keeping</span>
                </h1>
                <p class="lead mb-4" style="color:rgba(255,255,255,0.85);
                font-size:1.1rem;line-height:1.7">
                Kumpulan jepretan yang menyimpan cerita, emosi, dan jejak<br class="d-none d-md-block">
                waktu dalam satu bingkai sederhana.
                </p>
                <a href="<?= BASE_URL ?>/pages/catalog.php"
                   class="btn btn-primary btn-lg me-3">
                    <i class="bi bi-grid me-2"></i>Lihat Katalog
                </a>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/auth/login.php"
                   class="btn btn-outline-light btn-lg">
                    <i class="bi bi-lock me-2"></i>Login Admin
                </a>
                <?php endif; ?>
            </div>

            <!-- Statistik -->
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number">
                                <?= number_format($stats['total_katalog']) ?>
                            </div>
                            <div style="color:rgba(255,255,255,0.7)">
                                <i class="bi bi-collection me-1"></i>Katalog
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <div class="stat-number">
                                <?= number_format($stats['total_foto']) ?>
                            </div>
                            <div style="color:rgba(255,255,255,0.7)">
                                <i class="bi bi-images me-1"></i>Foto
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="stat-card">
                            <div class="stat-number" style="font-size:1.8rem">
                                <?= number_format($stats['total_ukuran_mb'], 1) ?> MB
                            </div>
                            <div style="color:rgba(255,255,255,0.7)">
                                <i class="bi bi-hdd me-1"></i>Total Ukuran Foto
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION: KATALOG TERBARU -->
<section class="py-5">
    <div class="container">

        <!-- Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Katalog Terbaru</h2>
                <p class="text-muted mb-0">Dokumentasi kegiatan paling baru</p>
            </div>
            <a href="<?= BASE_URL ?>/pages/catalog.php"
               class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <!-- Grid Katalog -->
        <div class="row g-4">
            <?php if (mysqli_num_rows($result_katalog) > 0): ?>
                <?php while ($katalog = mysqli_fetch_assoc($result_katalog)): ?>
                <div class="col-sm-6 col-lg-4">
                    <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= $katalog['id_katalog'] ?>"
                       class="text-decoration-none">
                        <div class="card catalog-card h-100">

                            <!-- Thumbnail -->
                            <?php
                            $thumb_path = 'assets/uploads/thumbnails/' . $katalog['thumbnail'];
                            if ($katalog['thumbnail'] && file_exists($thumb_path)):
                            ?>
                                <img src="<?= BASE_URL ?>/<?= $thumb_path ?>"
                                     class="card-img-top"
                                     alt="<?= e($katalog['judul']) ?>">
                            <?php else: ?>
                                <div class="card-img-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Card Body -->
                            <div class="card-body">
                                <h5 class="card-title fw-semibold text-dark mb-1">
                                    <?= e($katalog['judul']) ?>
                                </h5>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= e($katalog['bulan_tahun']) ?>
                                </p>

                                <!-- Tags -->
                                <?php if ($katalog['tag_list']): ?>
                                <div class="mb-2">
                                    <?php foreach (explode(', ', $katalog['tag_list']) as $tag): ?>
                                    <span class="badge bg-secondary me-1" style="font-size:0.7rem">
                                        <?= e(trim($tag)) ?>
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer bg-transparent border-top-0">
                                <small class="text-muted">
                                    <i class="bi bi-images me-1"></i>
                                    <?= $katalog['jumlah_foto'] ?> foto
                                </small>
                            </div>

                        </div>
                    </a>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        Belum ada katalog tersedia.
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>