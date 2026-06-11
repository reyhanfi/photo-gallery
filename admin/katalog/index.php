<?php
$page_title = 'Kelola Katalog';
require_once '../../includes/header.php';
requireLogin();

// Ambil semua katalog pakai VIEW
$result = mysqli_query($conn, "
    SELECT * FROM view_catalog_summary
    ORDER BY tanggal_event DESC
");

// Pesan sukses/error dari redirect
$msg     = $_GET['msg'] ?? '';
$msg_err = $_GET['err'] ?? '';
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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Kelola Katalog</h2>
                    <small class="text-muted">Tambah, edit, dan hapus katalog foto</small>
                </div>
                <a href="<?= BASE_URL ?>/admin/katalog/add.php"
                   class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Katalog
                </a>
            </div>

            <!-- Alert pesan -->
            <?php if ($msg): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i><?= e($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php if ($msg_err): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i><?= e($msg_err) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Tabel katalog -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="60">No</th>
                                    <th>Thumbnail</th>
                                    <th>Judul</th>
                                    <th>Tanggal Event</th>
                                    <th class="text-center">Foto</th>
                                    <th>Tags</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (mysqli_num_rows($result) > 0):
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="text-muted"><?= $no++ ?></td>
                                    <td>
                                        <?php
                                        $thumb = '../../assets/uploads/thumbnails/' . $row['thumbnail'];
                                        if ($row['thumbnail'] && file_exists($thumb)): ?>
                                            <img src="<?= BASE_URL ?>/assets/uploads/thumbnails/<?= e($row['thumbnail']) ?>"
                                                 style="width:60px;height:45px;object-fit:cover;border-radius:6px">
                                        <?php else: ?>
                                            <div class="bg-secondary d-flex align-items-center justify-content-center"
                                                 style="width:60px;height:45px;border-radius:6px">
                                                <i class="bi bi-image text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?= e($row['judul']) ?></div>
                                        <small class="text-muted"><?= e($row['nama_admin']) ?></small>
                                    </td>
                                    <td>
                                        <small><?= e($row['bulan_tahun']) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">
                                            <?= $row['jumlah_foto'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['daftar_tag']): ?>
                                        <?php foreach (explode(', ', $row['daftar_tag']) as $tag): ?>
                                        <span class="badge bg-secondary me-1" style="font-size:0.65rem">
                                            <?= e(trim($tag)) ?>
                                        </span>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <small class="text-muted">—</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="<?= BASE_URL ?>/pages/detail.php?id=<?= $row['id_katalog'] ?>"
                                               class="btn btn-info btn-sm text-white"
                                               title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>/admin/katalog/edit.php?id=<?= $row['id_katalog'] ?>"
                                               class="btn btn-warning btn-sm"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?= BASE_URL ?>/admin/katalog/delete.php?id=<?= $row['id_katalog'] ?>"
                                               class="btn btn-danger btn-sm btn-hapus"
                                               data-nama="katalog '<?= e($row['judul']) ?>'"
                                               title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox" style="font-size:2rem"></i>
                                        <p class="mt-2 mb-0">Belum ada katalog</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>