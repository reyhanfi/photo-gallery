<?php
$page_title = 'Edit Katalog';
require_once '../../includes/header.php';
requireLogin();

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) redirect('/admin/katalog/index.php');

// Ambil data katalog
$stmt = mysqli_prepare($conn, "SELECT * FROM katalog WHERE id_katalog = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$katalog = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$katalog) redirect('/admin/katalog/index.php');

// Ambil tags existing
$stmt_tags = mysqli_prepare($conn, "SELECT GROUP_CONCAT(nama_tag SEPARATOR ', ') AS tags FROM tags WHERE id_katalog = ?");
mysqli_stmt_bind_param($stmt_tags, 'i', $id);
mysqli_stmt_execute($stmt_tags);
$existing_tags = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_tags))['tags'] ?? '';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul         = trim($_POST['judul'] ?? '');
    $deskripsi     = trim($_POST['deskripsi'] ?? '');
    $tanggal_event = trim($_POST['tanggal_event'] ?? '');
    $tags          = trim($_POST['tags'] ?? '');

    if (empty($judul))         $errors[] = 'Judul katalog wajib diisi.';
    if (empty($tanggal_event)) $errors[] = 'Tanggal event wajib diisi.';

    // Proses upload thumbnail baru (opsional)
    $thumbnail = $katalog['thumbnail']; // default pakai yang lama
    if (!empty($_FILES['thumbnail']['name'])) {
        $allowed  = ['jpg', 'jpeg', 'png', 'webp'];
        $ext      = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        $max_size = 2 * 1024 * 1024;

        if (!in_array($ext, $allowed)) {
            $errors[] = 'Thumbnail hanya boleh JPG, PNG, atau WEBP.';
        } elseif ($_FILES['thumbnail']['size'] > $max_size) {
            $errors[] = 'Ukuran thumbnail maksimal 2MB.';
        } else {
            // Hapus thumbnail lama kalau ada
            if ($katalog['thumbnail'] && file_exists(THUMBNAIL_PATH . $katalog['thumbnail'])) {
                unlink(THUMBNAIL_PATH . $katalog['thumbnail']);
            }
            $thumbnail = 'thumb_' . time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], THUMBNAIL_PATH . $thumbnail);
        }
    }

    if (empty($errors)) {
        $stmt_update = mysqli_prepare($conn, "
            UPDATE katalog
            SET judul=?, deskripsi=?, tanggal_event=?, thumbnail=?
            WHERE id_katalog=?
        ");
        mysqli_stmt_bind_param($stmt_update, 'ssssi',
            $judul, $deskripsi, $tanggal_event, $thumbnail, $id
        );

        if (mysqli_stmt_execute($stmt_update)) {
            // Update tags: hapus lama, insert baru
            $stmt_del = mysqli_prepare($conn, "DELETE FROM tags WHERE id_katalog = ?");
            mysqli_stmt_bind_param($stmt_del, 'i', $id);
            mysqli_stmt_execute($stmt_del);

            if (!empty($tags)) {
                $tag_list = array_filter(array_map('trim', explode(',', $tags)));
                foreach ($tag_list as $tag) {
                    $stmt_tag = mysqli_prepare($conn, "INSERT INTO tags (id_katalog, nama_tag) VALUES (?, ?)");
                    mysqli_stmt_bind_param($stmt_tag, 'is', $id, $tag);
                    mysqli_stmt_execute($stmt_tag);
                }
            }

            redirect('/admin/katalog/index.php?msg=Katalog berhasil diperbarui!');
        } else {
            $errors[] = 'Gagal memperbarui katalog.';
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
                    <li class="breadcrumb-item active">Edit Katalog</li>
                </ol>
            </nav>

            <h2 class="fw-bold mb-4">Edit Katalog</h2>

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

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="judul">
                                    Judul Katalog <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="judul" name="judul"
                                       value="<?= e($_POST['judul'] ?? $katalog['judul']) ?>">
                                <div class="text-danger small mt-1 d-none" id="judulError"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3"
                                          ><?= e($_POST['deskripsi'] ?? $katalog['deskripsi']) ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="tanggal_event">
                                    Tanggal Event <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control"
                                       id="tanggal_event" name="tanggal_event"
                                       value="<?= e($_POST['tanggal_event'] ?? $katalog['tanggal_event']) ?>">
                                <div class="text-danger small mt-1 d-none" id="tanggalError"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tags</label>
                                <input type="text" class="form-control" name="tags"
                                       placeholder="wisuda, ugm (pisahkan koma)"
                                       value="<?= e($_POST['tags'] ?? $existing_tags) ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Thumbnail</label>
                                <!-- Thumbnail saat ini -->
                                <?php
                                $thumb = '../../assets/uploads/thumbnails/' . $katalog['thumbnail'];
                                if ($katalog['thumbnail'] && file_exists($thumb)): ?>
                                <div class="mb-2">
                                    <small class="text-muted d-block mb-1">Thumbnail saat ini:</small>
                                    <img src="<?= BASE_URL ?>/assets/uploads/thumbnails/<?= e($katalog['thumbnail']) ?>"
                                         style="height:80px;object-fit:cover;border-radius:6px">
                                </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="thumbnail"
                                       name="thumbnail" accept="image/jpeg,image/png,image/webp">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah thumbnail</small>
                                <div class="mt-2">
                                    <img id="previewThumbnail" src="" alt="Preview"
                                         class="d-none rounded"
                                         style="height:100px;object-fit:cover">
                                </div>
                            </div>

                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-warning px-4 fw-semibold">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="<?= BASE_URL ?>/admin/katalog/index.php"
                               class="btn btn-outline-secondary">Batal</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>