<?php
$page_title = 'Tambah Katalog';
require_once '../../includes/header.php';
requireLogin();

$errors = [];
$input  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil & sanitasi input
    $input['judul']         = trim($_POST['judul'] ?? '');
    $input['deskripsi']     = trim($_POST['deskripsi'] ?? '');
    $input['tanggal_event'] = trim($_POST['tanggal_event'] ?? '');
    $input['tags']          = trim($_POST['tags'] ?? '');

    // Validasi server-side
    if (empty($input['judul']))         $errors[] = 'Judul katalog wajib diisi.';
    if (empty($input['tanggal_event'])) $errors[] = 'Tanggal event wajib diisi.';

    // Proses upload thumbnail
    $thumbnail = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];
        $ext       = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        $max_size  = 2 * 1024 * 1024; // 2MB

        if (!in_array($ext, $allowed)) {
            $errors[] = 'Thumbnail hanya boleh JPG, PNG, atau WEBP.';
        } elseif ($_FILES['thumbnail']['size'] > $max_size) {
            $errors[] = 'Ukuran thumbnail maksimal 2MB.';
        } else {
            $thumbnail = 'thumb_' . time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], THUMBNAIL_PATH . $thumbnail);
        }
    }

    // Simpan ke database kalau tidak ada error
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "
            INSERT INTO katalog (id_admin, judul, deskripsi, tanggal_event, thumbnail)
            VALUES (?, ?, ?, ?, ?)
        ");
        mysqli_stmt_bind_param($stmt, 'issss',
            $_SESSION['user_id'],
            $input['judul'],
            $input['deskripsi'],
            $input['tanggal_event'],
            $thumbnail
        );

        if (mysqli_stmt_execute($stmt)) {
            $new_id = mysqli_insert_id($conn);

            // Simpan tags kalau ada
            if (!empty($input['tags'])) {
                $tag_list = array_filter(array_map('trim', explode(',', $input['tags'])));
                foreach ($tag_list as $tag) {
                    $stmt_tag = mysqli_prepare($conn, "INSERT INTO tags (id_katalog, nama_tag) VALUES (?, ?)");
                    mysqli_stmt_bind_param($stmt_tag, 'is', $new_id, $tag);
                    mysqli_stmt_execute($stmt_tag);
                }
            }

            redirect('/admin/katalog/index.php?msg=Katalog berhasil ditambahkan!');
        } else {
            $errors[] = 'Gagal menyimpan katalog. Silakan coba lagi.';
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

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/admin/katalog/index.php">Kelola Katalog</a>
                    </li>
                    <li class="breadcrumb-item active">Tambah Katalog</li>
                </ol>
            </nav>

            <h2 class="fw-bold mb-4">Tambah Katalog Baru</h2>

            <!-- Error alert -->
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
                    <form method="POST" enctype="multipart/form-data" id="formKatalog">

                        <div class="row g-3">

                            <!-- Judul -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="judul">
                                    Judul Katalog <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="judul"
                                       name="judul"
                                       placeholder="Contoh: Wisuda UGM 2026"
                                       value="<?= e($input['judul'] ?? '') ?>">
                                <div class="text-danger small mt-1 d-none" id="judulError"></div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="deskripsi">
                                    Deskripsi
                                </label>
                                <textarea class="form-control"
                                          id="deskripsi"
                                          name="deskripsi"
                                          rows="3"
                                          placeholder="Deskripsi singkat tentang katalog ini..."
                                          ><?= e($input['deskripsi'] ?? '') ?></textarea>
                            </div>

                            <!-- Tanggal Event -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="tanggal_event">
                                    Tanggal Event <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       class="form-control"
                                       id="tanggal_event"
                                       name="tanggal_event"
                                       value="<?= e($input['tanggal_event'] ?? '') ?>">
                                <div class="text-danger small mt-1 d-none" id="tanggalError"></div>
                            </div>

                            <!-- Tags -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="tags">
                                    Tags
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="tags"
                                       name="tags"
                                       placeholder="wisuda, ugm, akademik (pisahkan dengan koma)"
                                       value="<?= e($input['tags'] ?? '') ?>">
                                <small class="text-muted">Pisahkan setiap tag dengan koma</small>
                            </div>

                            <!-- Thumbnail -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="thumbnail">
                                    Thumbnail Katalog
                                </label>
                                <input type="file"
                                       class="form-control"
                                       id="thumbnail"
                                       name="thumbnail"
                                       accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                                <!-- Preview thumbnail -->
                                <div class="mt-2">
                                    <img id="previewThumbnail"
                                         src="" alt="Preview"
                                         class="d-none rounded"
                                         style="height:120px;object-fit:cover">
                                </div>
                            </div>

                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Simpan Katalog
                            </button>
                            <a href="<?= BASE_URL ?>/admin/katalog/index.php"
                               class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>