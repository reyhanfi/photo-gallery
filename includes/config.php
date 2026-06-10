<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'photo_gallery');

// Koneksi ke database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, 'utf8mb4');

// Konfigurasi umum
define('BASE_URL', 'http://localhost/photo-gallery');
define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/');
define('THUMBNAIL_PATH', __DIR__ . '/../assets/uploads/thumbnails/');
?>