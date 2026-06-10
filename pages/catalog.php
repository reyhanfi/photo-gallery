<?php
$page_title = 'Katalog';
require_once '../includes/header.php';

// ===========================
// SEARCH & PAGINATION
// ===========================
$search    = isset($_GET['search']) ? trim($_GET['search']) : '';
$per_page  = 6;
$page      = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset    = ($page - 1) * $per_page;

// Query 3 (Complex): Search dengan EXISTS subquery + pagination
// Hitung total dulu untuk pagination
$query_count = "
    SELECT COUNT(*) AS total
    FROM katalog k
    WHERE k.judul LIKE ?
       OR k.deskripsi LIKE ?
       OR EXISTS (
           SELECT 1 FROM tags t
           WHERE t.id_katalog = k.id_katalog
             AND t.nama_tag LIKE ?
       )
";
$keyword = '%' . $search . '%';
$stmt_count = mysqli_prepare($conn, $query_count);
mysqli_stmt_bind_param($stmt_count, 'sss', $keyword, $keyword, $keyword);
mysqli_stmt_execute($stmt_count);
$total_rows  = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_count))['total'];
$total_pages = ceil($total_rows / $per_page);

// Query data katalog dengan pagination
$query_katalog = "
    SELECT
        k.id_katalog,
        k.judul,
        k.deskripsi,
        k.thumbnail,
        k.jumlah_foto,
        DATE_FORMAT(k.tanggal_event, '%M %Y') AS bulan_tahun,
        fn_ukuran_total_foto(k.id_katalog)    AS ukuran_kb,
        (
            SELECT GROUP_CONCAT(nama_tag SEPARATOR ', ')
            FROM tags
            WHERE id_katalog = k.id_katalog
        ) AS tag_list
    FROM katalog k
    WHERE k.judul LIKE ?
       OR k.deskripsi LIKE ?
       OR EXISTS (
           SELECT 1 FROM tags t
           WHERE t.id_katalog = k.id_katalog
             AND t.nama_tag LIKE ?
       )
    ORDER BY k.tanggal_event DESC
    LIMIT ? OFFSET ?
";
$stmt = mysqli_prepare($conn, $query_katalog);
mysqli_stmt_bind_param($stmt, 'sssii', $keyword, $keyword, $keyword, $per_page, $offset);
mysqli_stmt_execute($stmt);
$result_katalog = mysqli_stmt_get_result($stmt);
?>

<!-- PAGE HEADER -->
<div class="bg-dark text-white py-4">
    <div class="container">
        <h1 class="fw-bold mb-1">
            <i class="bi bi-grid me-2"></i>Katalog Foto
        </h1>
        <p class="text-muted mb-0">
            Semua dokumentasi kegiatan tersimpan di sini
        </p>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="container py-5">

    <!-- SEARCH BAR -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="" id="formSearch">
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           name="search"
                           id="inputSearch"
                           placeholder="Cari katalog atau tag..."
                           value="<?= e($search) ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if ($search): ?>
                    <a href="<?= BASE_URL ?>/pages/catalog.php"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-md-end mt-2 mt-md-0">
            <small class="text-muted">
                <?php if ($search): ?>
                    Hasil pencarian "<strong><?= e($search) ?></strong>":
                <?php endif; ?>
                <strong><?= $total_rows ?></strong> katalog ditemukan
            </small>
        </div>
    </div>

    <!-- GRID KATALOG -->
    <div class="row g-4" id="catalogGrid">
        <?php if (mysqli_num_rows($result_katalog) > 0): ?>
            <?php while ($katalog = mysqli_fetch_assoc($result_katalog)): ?>
            <div class="col-sm-6 col-lg-4">
                <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= $katalog['id_katalog'] ?>"
                   class="text-decoration-none">
                    <div class="card catalog-card h-100">

                        <!-- Thumbnail -->
                        <?php
                        $thumb = '../assets/uploads/thumbnails/' . $katalog['thumbnail'];
                        if ($katalog['thumbnail'] && file_exists($thumb)):
                        ?>
                            <img src="<?= BASE_URL ?>/assets/uploads/thumbnails/<?= e($katalog['thumbnail']) ?>"
                                 class="card-img-top"
                                 alt="<?= e($katalog['judul']) ?>">
                        <?php else: ?>
                            <div class="card-img-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Body -->
                        <div class="card-body">
                            <h5 class="card-title fw-semibold text-dark mb-1">
                                <?= e($katalog['judul']) ?>
                            </h5>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?= e($katalog['bulan_tahun']) ?>
                            </p>
                            <?php if ($katalog['deskripsi']): ?>
                            <p class="text-muted small mb-2" style="
                                display:-webkit-box;
                                -webkit-line-clamp:2;
                                -webkit-box-orient:vertical;
                                overflow:hidden">
                                <?= e($katalog['deskripsi']) ?>
                            </p>
                            <?php endif; ?>

                            <!-- Tags -->
                            <?php if ($katalog['tag_list']): ?>
                            <div class="mb-1">
                                <?php foreach (explode(', ', $katalog['tag_list']) as $tag): ?>
                                <span class="badge bg-secondary me-1" style="font-size:0.7rem">
                                    <?= e(trim($tag)) ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Footer -->
                        <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-images me-1"></i>
                                <?= $katalog['jumlah_foto'] ?> foto
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-hdd me-1"></i>
                                <?= number_format($katalog['ukuran_kb'], 1) ?> KB
                            </small>
                        </div>

                    </div>
                </a>
            </div>
            <?php endwhile; ?>

        <?php else: ?>
            <!-- Empty state -->
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-search" style="font-size:3rem;color:#dee2e6"></i>
                    <h5 class="mt-3 text-muted">
                        <?= $search ? 'Tidak ada hasil untuk "' . e($search) . '"' : 'Belum ada katalog' ?>
                    </h5>
                    <?php if ($search): ?>
                    <a href="<?= BASE_URL ?>/pages/catalog.php"
                       class="btn btn-outline-primary mt-2">
                        Lihat semua katalog
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- PAGINATION -->
    <?php if ($total_pages > 1): ?>
    <nav class="mt-5 d-flex justify-content-center" aria-label="Pagination">
        <ul class="pagination">

            <!-- Tombol Prev -->
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>

            <!-- Nomor halaman -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link"
                   href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                    <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>

            <!-- Tombol Next -->
            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>

        </ul>
    </nav>
    <?php endif; ?>

</div>

<?php require_once '../includes/footer.php'; ?>