<?php
require_once '../../includes/header.php';
requireLogin();

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) redirect('/admin/katalog/index.php');

// Ambil data katalog dulu (untuk hapus thumbnail)
$stmt = mysqli_prepare($conn, "SELECT * FROM katalog WHERE id_katalog = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$katalog = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$katalog) redirect('/admin/katalog/index.php?err=Katalog tidak ditemukan.');

// Hapus thumbnail dari server
if ($katalog['thumbnail'] && file_exists(THUMBNAIL_PATH . $katalog['thumbnail'])) {
    unlink(THUMBNAIL_PATH . $katalog['thumbnail']);
}

// Hapus file foto dari server (ON DELETE CASCADE akan hapus dari DB)
$stmt_foto = mysqli_prepare($conn, "SELECT nama_file FROM foto WHERE id_katalog = ?");
mysqli_stmt_bind_param($stmt_foto, 'i', $id);
mysqli_stmt_execute($stmt_foto);
$result_foto = mysqli_stmt_get_result($stmt_foto);
while ($foto = mysqli_fetch_assoc($result_foto)) {
    $path = UPLOAD_PATH . $foto['nama_file'];
    if (file_exists($path)) unlink($path);
}

// Hapus katalog dari DB (CASCADE hapus foto & tags otomatis)
$stmt_del = mysqli_prepare($conn, "DELETE FROM katalog WHERE id_katalog = ?");
mysqli_stmt_bind_param($stmt_del, 'i', $id);

if (mysqli_stmt_execute($stmt_del)) {
    redirect('/admin/katalog/index.php?msg=Katalog berhasil dihapus.');
} else {
    redirect('/admin/katalog/index.php?err=Gagal menghapus katalog.');
}
?>