<?php
$page_title = 'Tambah Foto';
require_once '../../includes/header.php';
requireLogin();

// Ambil katalog_id dari URL
$katalog_id = isset($_GET['katalog_id']) && is_numeric($_GET['katalog_id'])
              ? (int)$_GET['katalog_id'] : 0;
if ($katalog_id === 0) redirect('/admin/katalog/index.php');

// Ambil info katalog
$stmt_k = mysqli_prepare($conn, "SELECT * FROM katalog WHERE id_katalog = ?");
mysqli_stmt_bind_param($stmt_k, 'i', $katalog_id);
mysqli_stmt_execute($stmt_k);
$katalog = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_k));
if (!$katalog) redirect('/admin/katalog/index.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_foto     = trim($_POST['judul_foto'] ?? '');
    $deskripsi_foto = trim($_POST['deskripsi_foto'] ?? '');
    $urutan         = (int)($_POST['urutan'] ?? 0);

    // Validasi server-side
    if (empty($judul_foto)) $errors[] = 'Judul foto wajib diisi.';

    // Validasi file
    if (empty($_FILES['file_foto']['name'])) {
        $errors[] = 'File foto wajib dipilih.';
    } else {
        $allowed  = ['jpg', 'jpeg', 'png', 'webp'];
        $ext      = strtolower(pathinfo($_FILES['file_foto']['name'], PATHINFO_EXTENSION));
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($ext, $allowed)) {
            $errors[] = 'File hanya boleh JPG, PNG, atau WEBP.';
        } elseif ($_FILES['file_foto']['size'] > $max_size) {
            $errors[] = 'Ukuran file maksimal 5MB.';
        }
    }

    if (empty($errors)) {
        // Upload file
        $nama_file  = 'foto_' . time() . '_' . uniqid() . '.' . $ext;
        $ukuran     = $_FILES['file_foto']['size'];

        if (move_uploaded_file($_FILES['file_foto']['tmp_name'], UPLOAD_PATH . $nama_file)) {
            // INSERT — trigger otomatis tambah jumlah_foto di katalog
            $stmt = mysqli_prepare($conn, "
                INSERT INTO foto (id_katalog, judul_foto, deskripsi_foto, nama_file, ukuran_file, urutan)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            mysqli_stmt_bind_param($stmt, 'isssii',
                $katalog_id, $judul_foto, $deskripsi_foto,
                $nama_file, $ukuran, $urutan
            );

            if (mysqli_stmt_execute($stmt)) {
                redirect('/pages/detail.php?id=' . $katalog_id . '&msg=Foto berhasil ditambahkan!');
            } else {
                $errors[] = 'Gagal menyimpan foto ke database.';
            }
        } else {
            $errors[] = 'Gagal mengupload file. Cek permission folder uploads/.';
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 px-0 admin-sidebar d-none d-md-block">
            <div class="p-3">
                <p class="text-white-50 small text-uppercase fw-semibold mb-3 mt-2">Menu Admin</p>
                <nav class="nav flex-column">
                    <a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link active" href="<?= BASE_URL ?>/admin/katalog/index.php">
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

        <!-- MAIN -->
        <div class="col-md-10 py-4 px-4">

            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/admin/katalog/index.php">Kelola Katalog</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= $katalog_id ?>">
                            <?= e($katalog['judul']) ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Foto</li>
                </ol>
            </nav>

            <h2 class="fw-bold mb-1">Tambah Foto</h2>
            <p class="text-muted mb-4">
                ke katalog: <strong><?= e($katalog['judul']) ?></strong>
            </p>

            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                    <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data" id="formFoto">

                        <div class="row g-3">

                            <!-- Judul Foto -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="judul_foto">
                                    Judul Foto <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control"
                                       id="judul_foto" name="judul_foto"
                                       placeholder="Contoh: Prosesi Wisuda"
                                       value="<?= e($_POST['judul_foto'] ?? '') ?>">
                                <div class="text-danger small mt-1 d-none" id="judulFotoError"></div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Foto</label>
                                <textarea class="form-control" name="deskripsi_foto"
                                          rows="2"
                                          placeholder="Keterangan singkat foto..."
                                          ><?= e($_POST['deskripsi_foto'] ?? '') ?></textarea>
                            </div>

                            <!-- Upload File -->
                            <div class="col-md-8">
                                <label class="form-label fw-semibold" for="file_foto">
                                    File Foto <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control"
                                       id="file_foto" name="file_foto"
                                       accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 5MB.</small>
                                <div class="text-danger small mt-1 d-none" id="fileFotoError"></div>
                            </div>

                            <!-- Urutan -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" for="urutan">
                                    Urutan Tampil
                                </label>
                                <input type="number" class="form-control"
                                       id="urutan" name="urutan"
                                       min="0" value="<?= e($_POST['urutan'] ?? '0') ?>">
                                <small class="text-muted">0 = otomatis</small>
                            </div>

                            <!-- Preview foto -->
                            <div class="col-12">
                                <div id="previewContainer" class="d-none">
                                    <label class="form-label fw-semibold">Preview:</label><br>
                                    <img id="previewFoto" src="" alt="Preview"
                                         class="rounded"
                                         style="max-height:200px;object-fit:contain">
                                </div>
                            </div>

                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-upload me-2"></i>Upload Foto
                            </button>
                            <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= $katalog_id ?>"
                               class="btn btn-outline-secondary">Batal</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview foto sebelum upload -->
<script>
document.getElementById('file_foto').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewFoto').src = e.target.result;
            document.getElementById('previewContainer').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>