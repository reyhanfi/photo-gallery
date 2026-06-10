<?php
// Ambil id dari URL, validasi
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) {
    header('Location: ' . 'http://localhost/photo-gallery/pages/catalog.php');
    exit;
}

require_once '../includes/header.php';

// Query detail katalog + info admin
$stmt_katalog = mysqli_prepare($conn, "
    SELECT
        k.*,
        DATE_FORMAT(k.tanggal_event, '%d %M %Y') AS tanggal_format,
        DATE_FORMAT(k.tanggal_event, '%M %Y')     AS bulan_tahun,
        u.username AS nama_admin,
        fn_ukuran_total_foto(k.id_katalog)        AS ukuran_kb
    FROM katalog k
    JOIN users u ON k.id_admin = u.id_user
    WHERE k.id_katalog = ?
");
mysqli_stmt_bind_param($stmt_katalog, 'i', $id);
mysqli_stmt_execute($stmt_katalog);
$katalog = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_katalog));

// Kalau katalog tidak ditemukan
if (!$katalog) {
    echo '<div class="container py-5"><div class="alert alert-danger">Katalog tidak ditemukan.</div></div>';
    require_once '../includes/footer.php';
    exit;
}

$page_title = $katalog['judul'];

// Query semua foto dalam katalog
$stmt_foto = mysqli_prepare($conn, "
    SELECT * FROM foto
    WHERE id_katalog = ?
    ORDER BY urutan ASC, uploaded_at ASC
");
mysqli_stmt_bind_param($stmt_foto, 'i', $id);
mysqli_stmt_execute($stmt_foto);
$result_foto = mysqli_stmt_get_result($stmt_foto);
$semua_foto  = mysqli_fetch_all($result_foto, MYSQLI_ASSOC);

// Query tags katalog ini
$stmt_tags = mysqli_prepare($conn, "
    SELECT nama_tag FROM tags WHERE id_katalog = ?
");
mysqli_stmt_bind_param($stmt_tags, 'i', $id);
mysqli_stmt_execute($stmt_tags);
$result_tags = mysqli_stmt_get_result($stmt_tags);
?>

<!-- BREADCRUMB -->
<div class="bg-light border-bottom py-2">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    <a href="<?= BASE_URL ?>">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= BASE_URL ?>/pages/catalog.php">Katalog</a>
                </li>
                <li class="breadcrumb-item active"><?= e($katalog['judul']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- DETAIL HEADER -->
<div class="bg-dark text-white py-5">
    <div class="container">
        <div class="row align-items-center">

            <!-- Info katalog -->
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h1 class="fw-bold mb-2"><?= e($katalog['judul']) ?></h1>

                <div class="d-flex flex-wrap gap-3 mb-3 text-white-50 small">
                    <span>
                        <i class="bi bi-calendar3 me-1"></i>
                        <?= e($katalog['tanggal_format']) ?>
                    </span>
                    <span>
                        <i class="bi bi-images me-1"></i>
                        <?= $katalog['jumlah_foto'] ?> foto
                    </span>
                    <span>
                        <i class="bi bi-hdd me-1"></i>
                        <?= number_format($katalog['ukuran_kb'], 1) ?> KB
                    </span>
                    <span>
                        <i class="bi bi-person me-1"></i>
                        <?= e($katalog['nama_admin']) ?>
                    </span>
                </div>

                <?php if ($katalog['deskripsi']): ?>
                <p class="text-white-50 mb-3"><?= e($katalog['deskripsi']) ?></p>
                <?php endif; ?>

                <!-- Tags -->
                <?php while ($tag = mysqli_fetch_assoc($result_tags)): ?>
                <span class="badge bg-secondary me-1"><?= e($tag['nama_tag']) ?></span>
                <?php endwhile; ?>
            </div>

            <!-- Tombol admin -->
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= BASE_URL ?>/admin/foto/add.php?katalog_id=<?= $id ?>"
                   class="btn btn-success me-2 mb-2">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Foto
                </a>
                <a href="<?= BASE_URL ?>/admin/katalog/edit.php?id=<?= $id ?>"
                   class="btn btn-warning mb-2">
                    <i class="bi bi-pencil me-1"></i>Edit Katalog
                </a>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- GALLERY GRID -->
<div class="container py-5">

    <?php if (count($semua_foto) > 0): ?>

    <div class="row g-3 photo-grid">
        <?php foreach ($semua_foto as $index => $foto): ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="position-relative overflow-hidden rounded"
                 style="cursor:pointer"
                 data-bs-toggle="modal"
                 data-bs-target="#modalFoto"
                 data-index="<?= $index ?>"
                 onclick="bukaModal(<?= $index ?>)">

                <!-- Foto -->
                <?php
                $foto_path = '../assets/uploads/' . $foto['nama_file'];
                if (file_exists($foto_path)):
                ?>
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= e($foto['nama_file']) ?>"
                         alt="<?= e($foto['judul_foto'] ?? '') ?>"
                         class="w-100"
                         style="height:180px;object-fit:cover;
                                border-radius:8px;cursor:pointer;
                                transition:opacity 0.2s"
                         onmouseover="this.style.opacity=0.8"
                         onmouseout="this.style.opacity=1">
                <?php else: ?>
                    <div class="bg-secondary d-flex align-items-center justify-content-center"
                         style="height:180px;border-radius:8px">
                        <i class="bi bi-image text-white" style="font-size:2rem"></i>
                    </div>
                <?php endif; ?>

                <!-- Overlay judul -->
                <?php if ($foto['judul_foto']): ?>
                <div class="position-absolute bottom-0 start-0 end-0 p-2"
                     style="background:linear-gradient(transparent,rgba(0,0,0,0.7));
                            border-radius:0 0 8px 8px">
                    <small class="text-white"><?= e($foto['judul_foto']) ?></small>
                </div>
                <?php endif; ?>

            </div>

            <!-- Tombol edit/hapus foto (admin only) -->
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="d-flex gap-1 mt-1">
                <a href="<?= BASE_URL ?>/admin/foto/edit.php?id=<?= $foto['id_foto'] ?>"
                   class="btn btn-warning btn-sm flex-fill" style="font-size:0.7rem">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="<?= BASE_URL ?>/admin/foto/delete.php?id=<?= $foto['id_foto'] ?>&katalog_id=<?= $id ?>"
                   class="btn btn-danger btn-sm flex-fill btn-hapus"
                   data-nama="foto ini"
                   style="font-size:0.7rem">
                    <i class="bi bi-trash"></i>
                </a>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <!-- Empty state -->
    <div class="text-center py-5">
        <i class="bi bi-images" style="font-size:3rem;color:#dee2e6"></i>
        <h5 class="mt-3 text-muted">Belum ada foto dalam katalog ini</h5>
        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= BASE_URL ?>/admin/foto/add.php?katalog_id=<?= $id ?>"
           class="btn btn-primary mt-2">
            <i class="bi bi-plus-lg me-1"></i>Tambah Foto Pertama
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<!-- MODAL FOTO (Bootstrap Modal) -->
<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white">

            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalJudul">—</h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center p-2">
                <img id="modalGambar" src="" alt=""
                     class="img-fluid rounded"
                     style="max-height:70vh;object-fit:contain">
            </div>

            <div class="modal-footer border-secondary justify-content-between">
                <small class="text-muted" id="modalDeskripsi">—</small>
                <div>
                    <button class="btn btn-outline-light btn-sm me-2"
                            onclick="navigasiModal(-1)">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn btn-outline-light btn-sm"
                            onclick="navigasiModal(1)">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Data foto untuk JavaScript -->
<script>
const semuaFoto = <?= json_encode(array_map(function($f) {
    return [
        'url'       => BASE_URL . '/assets/uploads/' . $f['nama_file'],
        'judul'     => $f['judul_foto'] ?? 'Foto',
        'deskripsi' => $f['deskripsi_foto'] ?? ''
    ];
}, $semua_foto)) ?>;

let indexAktif = 0;

function bukaModal(index) {
    indexAktif = index;
    tampilkanFoto();
}

function tampilkanFoto() {
    const foto = semuaFoto[indexAktif];
    document.getElementById('modalGambar').src     = foto.url;
    document.getElementById('modalJudul').textContent    = foto.judul || '—';
    document.getElementById('modalDeskripsi').textContent = foto.deskripsi || '—';
}

function navigasiModal(arah) {
    indexAktif = (indexAktif + arah + semuaFoto.length) % semuaFoto.length;
    tampilkanFoto();
}
</script>

<?php require_once '../includes/footer.php'; ?>