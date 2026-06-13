-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2026 at 04:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `photo_gallery`
--
CREATE DATABASE IF NOT EXISTS `photo_gallery` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `photo_gallery`;

DELIMITER $$
--
-- Functions
--
DROP FUNCTION IF EXISTS `fn_hitung_foto`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_hitung_foto` (`p_id_katalog` INT) RETURNS INT(11) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE jumlah INT;
    SELECT COUNT(*) INTO jumlah
    FROM foto
    WHERE id_katalog = p_id_katalog;
    RETURN jumlah;
END$$

DROP FUNCTION IF EXISTS `fn_ukuran_total_foto`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_ukuran_total_foto` (`p_id_katalog` INT) RETURNS DECIMAL(10,2) DETERMINISTIC READS SQL DATA RETURN (
    SELECT COALESCE(SUM(ukuran_file), 0) / 1024
    FROM foto
    WHERE id_katalog = p_id_katalog
)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `id_log` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `aksi` varchar(100) NOT NULL,
  `tabel_terkait` varchar(50) DEFAULT NULL,
  `id_record` int(11) DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foto`
--

DROP TABLE IF EXISTS `foto`;
CREATE TABLE `foto` (
  `id_foto` int(11) NOT NULL,
  `id_katalog` int(11) NOT NULL,
  `judul_foto` varchar(150) DEFAULT NULL,
  `deskripsi_foto` text DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `ukuran_file` int(11) DEFAULT 0,
  `urutan` int(11) DEFAULT 0,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foto`
--

INSERT INTO `foto` (`id_foto`, `id_katalog`, `judul_foto`, `deskripsi_foto`, `nama_file`, `ukuran_file`, `urutan`, `uploaded_at`) VALUES
(14, 6, 'Creepy', '', 'foto_1781351955_6a2d461379a24.jpg', 4049961, 0, '2026-06-13 11:59:15'),
(15, 6, 'Slow Shutter', '', 'foto_1781352036_6a2d4664f330c.jpg', 3798687, 0, '2026-06-13 12:00:36'),
(16, 6, 'Star', '', 'foto_1781352049_6a2d467185e0b.jpg', 4063812, 0, '2026-06-13 12:00:49'),
(17, 6, 'Awarding', '', 'foto_1781352062_6a2d467e4cafe.jpg', 4126337, 0, '2026-06-13 12:01:02'),
(18, 6, 'Crowd', '', 'foto_1781352074_6a2d468a43ffa.jpg', 4905063, 0, '2026-06-13 12:01:14'),
(19, 11, 'Salmon', '', 'foto_1781354047_6a2d4e3f1aab7.jpg', 2993959, 0, '2026-06-13 12:34:07'),
(20, 11, 'Angkat Aset', '', 'foto_1781354074_6a2d4e5a02275.jpg', 3459461, 0, '2026-06-13 12:34:34'),
(21, 11, 'Divisi Acara', '', 'foto_1781354092_6a2d4e6c84617.jpg', 5012622, 0, '2026-06-13 12:34:52'),
(22, 11, 'DDDekor', '', 'foto_1781354105_6a2d4e794f94b.jpg', 4904020, 0, '2026-06-13 12:35:05'),
(23, 11, 'DDDekor Lagi', '', 'foto_1781354118_6a2d4e86693c7.jpg', 3458811, 0, '2026-06-13 12:35:18'),
(24, 7, 'Core Team', '', 'foto_1781357357_6a2d5b2d4f429.jpg', 4351007, 0, '2026-06-13 13:29:17'),
(25, 7, 'Humas', '', 'foto_1781357373_6a2d5b3d45d62.jpg', 3406388, 0, '2026-06-13 13:29:33'),
(26, 7, 'TechDev', '', 'foto_1781357410_6a2d5b62213ea.jpg', 3769534, 0, '2026-06-13 13:30:10'),
(27, 7, 'Medinfo', '', 'foto_1781357427_6a2d5b73bc100.jpg', 3897480, 0, '2026-06-13 13:30:27'),
(28, 8, 'Crowd 1', '', 'foto_1781357463_6a2d5b97c3a7b.jpg', 4235786, 0, '2026-06-13 13:31:03'),
(29, 8, 'Crowd 2', '', 'foto_1781357474_6a2d5ba2acc9c.jpg', 4770003, 0, '2026-06-13 13:31:14'),
(30, 8, 'Games', '', 'foto_1781357487_6a2d5baf14f69.jpg', 3374593, 0, '2026-06-13 13:31:27'),
(31, 8, 'QnA', '', 'foto_1781357500_6a2d5bbcd024f.jpg', 3386547, 0, '2026-06-13 13:31:40'),
(32, 8, 'Fotbar', '', 'foto_1781357510_6a2d5bc6afd93.jpg', 3686721, 0, '2026-06-13 13:31:50'),
(33, 9, 'Skema Toilet', '', 'foto_1781357537_6a2d5be1256c6.jpg', 4884078, 0, '2026-06-13 13:32:17'),
(34, 9, 'Dimes Lompat', '', 'foto_1781357549_6a2d5bed23f47.jpg', 4126172, 0, '2026-06-13 13:32:29'),
(35, 9, 'Dimes Duduk', '', 'foto_1781357562_6a2d5bfaaf881.jpg', 4657810, 0, '2026-06-13 13:32:42'),
(36, 9, 'Compe', '', 'foto_1781357572_6a2d5c04a8322.jpg', 3540612, 0, '2026-06-13 13:32:52'),
(37, 10, 'Master', '', 'foto_1781357604_6a2d5c24e8360.jpg', 4777559, 0, '2026-06-13 13:33:24'),
(38, 10, 'Acara', '', 'foto_1781357614_6a2d5c2e35ed3.jpg', 3878583, 0, '2026-06-13 13:33:34'),
(39, 10, 'FGD', '', 'foto_1781357626_6a2d5c3a04f53.jpg', 4180970, 0, '2026-06-13 13:33:46'),
(40, 10, 'MC', '', 'foto_1781357635_6a2d5c4363d98.jpg', 4963816, 0, '2026-06-13 13:33:55'),
(41, 10, 'Fotbar', '', 'foto_1781357645_6a2d5c4d57030.jpg', 5094439, 0, '2026-06-13 13:34:05');

--
-- Triggers `foto`
--
DROP TRIGGER IF EXISTS `trg_after_foto_delete`;
DELIMITER $$
CREATE TRIGGER `trg_after_foto_delete` AFTER DELETE ON `foto` FOR EACH ROW UPDATE katalog
SET jumlah_foto = GREATEST(jumlah_foto - 1, 0),
    updated_at  = CURRENT_TIMESTAMP
WHERE id_katalog = OLD.id_katalog
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_after_foto_insert`;
DELIMITER $$
CREATE TRIGGER `trg_after_foto_insert` AFTER INSERT ON `foto` FOR EACH ROW UPDATE katalog
SET jumlah_foto = jumlah_foto + 1,
    updated_at  = CURRENT_TIMESTAMP
WHERE id_katalog = NEW.id_katalog
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_log_foto_delete`;
DELIMITER $$
CREATE TRIGGER `trg_log_foto_delete` AFTER DELETE ON `foto` FOR EACH ROW INSERT INTO activity_log (id_admin, aksi, tabel_terkait, id_record)
VALUES (1, CONCAT('Hapus foto: ', OLD.nama_file), 'foto', OLD.id_foto)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `katalog`
--

DROP TABLE IF EXISTS `katalog`;
CREATE TABLE `katalog` (
  `id_katalog` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_event` date NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `jumlah_foto` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `katalog`
--

INSERT INTO `katalog` (`id_katalog`, `id_admin`, `judul`, `deskripsi`, `tanggal_event`, `thumbnail`, `jumlah_foto`, `created_at`, `updated_at`) VALUES
(6, 1, 'First Gathering Panitia PIONIR 2026', 'Langkah awal sinergi kita dimulai di sini! First Gathering Panitia PIONIR UGM: Momen perdana untuk saling mengenal, menyatukan visi, dan membakar semangat kolaborasi demi menyambut Gadjah Mada Muda.', '2026-05-02', 'thumb_1781350981_6a2d424564ff3.jpg', 5, '2026-06-13 11:43:01', '2026-06-13 12:01:14'),
(7, 1, 'Rilis Kabinet ASSETS 2026', 'Menjawab tantangan, membawa perubahan. Rilis Kabinet ASSETS 2026 memperkenalkan nahkoda dan struktur baru yang siap mengintegrasikan kreativitas, teknologi, dan semangat kekeluargaan demi memberikan dampak nyata.', '2026-04-19', 'thumb_1781352352_6a2d47a02ff67.jpg', 4, '2026-06-13 12:03:01', '2026-06-13 13:30:27'),
(8, 1, 'First Gathering KOMATIK 2026', 'Inisialisasi koneksi, satukan sinergi! Selamat datang di First Gathering KOMATIK UGM 2026. Momen perdana bagi para pegiat TIK UGM untuk saling terhubung, menyatukan visi, dan bersiap menciptakan inovasi digital yang berdampak.', '2026-04-04', 'thumb_1781353741_6a2d4d0d9386b.jpg', 5, '2026-06-13 12:29:01', '2026-06-13 13:31:50'),
(9, 1, 'Rilis Panitia TGES 2026 (Dump)', 'Roster resmi telah dirilis! Inilah barisan panitia TGES 2026 yang siap di balik layar untuk merancang arena kompetisi, menyatukan komunitas, dan menyajikan turnamen esport paling kompetitif tahun ini. The game is on!', '2026-03-29', 'thumb_1781353854_6a2d4d7ed0951.jpg', 4, '2026-06-13 12:30:54', '2026-06-13 13:32:52'),
(10, 1, 'First Gathering Panitia TGES 2026', 'Koneksi pertama, langkah awal menuju kemenangan! Selamat datang di First Gathering Panitia TGES 2026. Mari rekatkan kebersamaan, siapkan energi terbaikmu, dan mari bentuk tim paling solid di balik layar TGES periode ini.', '2026-03-29', 'thumb_1781353937_6a2d4dd13af48.jpg', 5, '2026-06-13 12:32:17', '2026-06-13 13:34:05'),
(11, 1, 'H-1 First Gathering Panitia PIONIR 2026', 'Final check, tim! H-1 Persiapan Panitia PIONIR UGM 2026 hadir untuk memastikan setiap detail aman, setiap divisi sinkron, dan mental kita siap. Mari jaga koordinasi, jaga kesehatan, dan bersiap menjadi pelopor kesuksesan orientasi tahun ini.', '2026-05-01', 'thumb_1781354013_6a2d4e1d2a8a1.jpg', 5, '2026-06-13 12:33:33', '2026-06-13 12:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id_tag` int(11) NOT NULL,
  `id_katalog` int(11) NOT NULL,
  `nama_tag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id_tag`, `id_katalog`, `nama_tag`) VALUES
(16, 6, 'pionir'),
(17, 6, 'ugm'),
(18, 6, 'event'),
(22, 8, 'komatik'),
(23, 8, 'ugm'),
(24, 8, 'komunitas'),
(25, 7, 'assets'),
(26, 7, 'ugm'),
(27, 7, 'himpunan'),
(28, 9, 'tges'),
(29, 9, 'ugm'),
(30, 9, 'event'),
(31, 10, 'tges'),
(32, 10, 'ugm'),
(33, 10, 'event'),
(34, 11, 'pionir'),
(35, 11, 'ugm'),
(36, 11, 'event');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@photogallery.com', '2026-06-10 09:09:23');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_catalog_summary`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_catalog_summary`;
CREATE TABLE `view_catalog_summary` (
`id_katalog` int(11)
,`judul` varchar(150)
,`tanggal_event` date
,`thumbnail` varchar(255)
,`jumlah_foto` int(11)
,`bulan_tahun` varchar(69)
,`nama_admin` varchar(50)
,`daftar_tag` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_photo_statistics`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `view_photo_statistics`;
CREATE TABLE `view_photo_statistics` (
`id_katalog` int(11)
,`judul_katalog` varchar(150)
,`total_foto` bigint(21)
,`total_ukuran_bytes` decimal(32,0)
,`rata_ukuran_bytes` decimal(14,4)
,`foto_terbaru` timestamp
,`foto_pertama` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `view_catalog_summary`
--
DROP TABLE IF EXISTS `view_catalog_summary`;

DROP VIEW IF EXISTS `view_catalog_summary`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_catalog_summary`  AS SELECT `k`.`id_katalog` AS `id_katalog`, `k`.`judul` AS `judul`, `k`.`tanggal_event` AS `tanggal_event`, `k`.`thumbnail` AS `thumbnail`, `k`.`jumlah_foto` AS `jumlah_foto`, date_format(`k`.`tanggal_event`,'%M %Y') AS `bulan_tahun`, `u`.`username` AS `nama_admin`, group_concat(`t`.`nama_tag` separator ', ') AS `daftar_tag` FROM ((`katalog` `k` join `users` `u` on(`k`.`id_admin` = `u`.`id_user`)) left join `tags` `t` on(`k`.`id_katalog` = `t`.`id_katalog`)) GROUP BY `k`.`id_katalog`, `k`.`judul`, `k`.`tanggal_event`, `k`.`thumbnail`, `k`.`jumlah_foto`, `u`.`username` ;

-- --------------------------------------------------------

--
-- Structure for view `view_photo_statistics`
--
DROP TABLE IF EXISTS `view_photo_statistics`;

DROP VIEW IF EXISTS `view_photo_statistics`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_photo_statistics`  AS SELECT `k`.`id_katalog` AS `id_katalog`, `k`.`judul` AS `judul_katalog`, count(`f`.`id_foto`) AS `total_foto`, coalesce(sum(`f`.`ukuran_file`),0) AS `total_ukuran_bytes`, coalesce(avg(`f`.`ukuran_file`),0) AS `rata_ukuran_bytes`, max(`f`.`uploaded_at`) AS `foto_terbaru`, min(`f`.`uploaded_at`) AS `foto_pertama` FROM (`katalog` `k` left join `foto` `f` on(`k`.`id_katalog` = `f`.`id_katalog`)) GROUP BY `k`.`id_katalog`, `k`.`judul` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `id_katalog` (`id_katalog`);

--
-- Indexes for table `katalog`
--
ALTER TABLE `katalog`
  ADD PRIMARY KEY (`id_katalog`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id_tag`),
  ADD KEY `id_katalog` (`id_katalog`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foto`
--
ALTER TABLE `foto`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `katalog`
--
ALTER TABLE `katalog`
  MODIFY `id_katalog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id_tag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `foto_ibfk_1` FOREIGN KEY (`id_katalog`) REFERENCES `katalog` (`id_katalog`) ON DELETE CASCADE;

--
-- Constraints for table `katalog`
--
ALTER TABLE `katalog`
  ADD CONSTRAINT `katalog_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`id_katalog`) REFERENCES `katalog` (`id_katalog`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
