# 📸 PhotoGallery — Photo Gallery Management System

Aplikasi web untuk menyimpan dan mengelompokkan foto berdasarkan event atau kegiatan.
Dibangun menggunakan PHP Native, MySQL, dan Bootstrap 5.

---

## 👤 Identitas

| Info  | Detail |
|-------|--------|
| Nama  | [Reyhan Abelard Fikri] |
| NIM   | [25/562000/SV/26713] |
| Prodi | Teknologi Rekayasa Perangkat Lunak |
| MK    | Praktikum Pemrograman Web 1 — UAS 2025/2026 |

---

## 🚀 Fitur

- Landing page dengan hero section dan statistik dinamis
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
