<?php
require_once '../../includes/header.php';
requireLogin();

$id         = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
$katalog_id = isset($_GET['katalog_id']) && is_numeric($_GET['katalog_id']) ? (int)$_GET['katalog_id'] : 0;

if ($id === 0) redirect('/admin/katalog/index.php');

// Ambil data foto
$stmt = mysqli_prepare($conn, "SELECT * FROM foto WHERE id_foto = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$foto = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$foto) redirect('/pages/detail.php?id=' . $katalog_id . '&err=Foto tidak ditemukan.');

// Hapus file dari server
$path = UPLOAD_PATH . $foto['nama_file'];
if (file_exists($path)) unlink($path);

// Hapus dari DB — trigger otomatis:
// 1. Kurangi jumlah_foto di katalog
// 2. Simpan log ke activity_log
$stmt_del = mysqli_prepare($conn, "DELETE FROM foto WHERE id_foto = ?");
mysqli_stmt_bind_param($stmt_del, 'i', $id);

if (mysqli_stmt_execute($stmt_del)) {
    redirect('/pages/detail.php?id=' . $foto['id_katalog'] . '&msg=Foto berhasil dihapus.');
} else {
    redirect('/pages/detail.php?id=' . $foto['id_katalog'] . '&err=Gagal menghapus foto.');
}
?>