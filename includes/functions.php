<?php
// Sanitasi output — wajib dipakai di semua output user
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Redirect ke URL lain
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}

// Cek apakah admin sudah login
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        redirect('/auth/login.php');
    }
}

// Format ukuran file bytes → KB/MB
function formatFileSize($bytes) {
    if ($bytes >= 1048576) {
        return round($bytes / 1048576, 2) . ' MB';
    }
    return round($bytes / 1024, 2) . ' KB';
}

// Format tanggal ke bahasa Indonesia
function formatTanggal($date) {
    $bulan = [
        '01' => 'Januari',  '02' => 'Februari', '03' => 'Maret',
        '04' => 'April',    '05' => 'Mei',       '06' => 'Juni',
        '07' => 'Juli',     '08' => 'Agustus',   '09' => 'September',
        '10' => 'Oktober',  '11' => 'November',  '12' => 'Desember'
    ];
    $parts = explode('-', $date);
    return $bulan[$parts[1]] . ' ' . $parts[0];
}
?>