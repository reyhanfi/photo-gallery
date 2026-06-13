# 📸 Arey's Catalog — Photo Gallery Management System

Aplikasi web untuk menyimpan dan mengelompokkan foto berdasarkan event atau kegiatan.
Dibangun menggunakan PHP Native, MySQL, dan Bootstrap 5.

---

## 👤 Identitas

| Info  | Detail |
|-------|--------|
| Nama  | Reyhan Abelard Fikri |
| NIM   | 25/562000/SV/26713 |
| Prodi | Teknologi Rekayasa Perangkat Lunak |
| MK    | Praktikum Pemrograman Web 1 & Praktikum Basis Data — UAS 2025/2026 |

---

## 🚀 Fitur

- Landing page hero section dengan statistik dinamis
- Katalog foto dengan search dan pagination
- Detail katalog dengan gallery grid dan modal foto
- CRUD lengkap: tambah, edit, hapus katalog dan foto
- Upload thumbnail dan foto
- Autentikasi admin dengan session
- Validasi form JavaScript
- Responsif mobile (375px – 1440px)

---

## 🗄️ Database

- 5 tabel: `users`, `katalog`, `foto`, `tags`, `activity_log`
- 2 VIEW: `view_catalog_summary`, `view_photo_statistics`
- 2 FUNCTION: `fn_hitung_foto`, `fn_ukuran_total_foto`
- 2 TRIGGER: `trg_after_foto_insert`, `trg_after_foto_delete`
- 3 Query Complex: JOIN + Aggregate, JOIN + Subquery, EXISTS Subquery

---

## 🛠️ Teknologi

![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-yellow)

---

## ⚙️ Cara Menjalankan

1. Install XAMPP, pastikan Apache dan MySQL aktif
2. Clone repository:

        git clone https://github.com/reyhanfi/photo-gallery.git

3. Pindahkan folder ke `htdocs/`
4. Buka phpMyAdmin, buat database `photo_gallery`
5. Import file `database.sql`
6. Buat file `includes/config.php` dengan isi berikut:

        <?php
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('DB_NAME', 'photo_gallery');
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        mysqli_set_charset($conn, 'utf8mb4');
        define('BASE_URL', 'http://localhost/photo-gallery');
        define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/');
        define('THUMBNAIL_PATH', __DIR__ . '/../assets/uploads/thumbnails/');
        ?>

7. Buka browser: `http://localhost/photo-gallery`
8. Login dengan username `admin` dan password `password`

---

## 📁 Struktur Folder

    photo-gallery/
    ├── assets/
    │   ├── css/style.css
    │   ├── js/main.js
    │   └── uploads/
    ├── includes/
    │   ├── config.php        (tidak di-push, lihat .gitignore)
    │   ├── header.php
    │   ├── footer.php
    │   └── functions.php
    ├── pages/
    ├── auth/
    ├── admin/
    ├── index.php
    ├── database.sql
    ├── .gitignore
    └── README.md

---

## 📸 Screenshot

*(tambahkan screenshot setelah ambil gambar)*

---

## 📝 Kontak

| Info   | Detail |
|--------|--------|
| Email  | reyhanaf110@gmail.com |
| GitHub | https://github.com/reyhanfi |