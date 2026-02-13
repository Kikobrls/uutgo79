-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2026 at 06:00 AM
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
-- Database: `keuangan_tpq`
--

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `level` enum('admin','guru') NOT NULL DEFAULT 'guru',
  `foto` varchar(255) DEFAULT 'default.png',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `username`, `password`, `nama_guru`, `level`, `foto`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 'default.png', 'active', '2026-02-10 17:31:04', '2026-02-05 07:31:13', '2026-02-10 10:31:04'),
(2, 'calson', '$2y$10$HcTxdGZrvxgpZdTnGXCtm.M9oC3umQPv.z17uwsElB8FBAjHHRqD.', 'hsadg', 'guru', 'default.png', 'active', '2026-02-07 19:22:57', '2026-02-07 12:22:45', '2026-02-07 12:22:57'),
(3, 'dasads', '$2y$10$KT6Y7czmqaImhNZ.zuWUy.dGwnPZMkdSMp2JVySl4y4BLv83EHHcm', 'tr5tusdh', 'guru', 'default.png', 'active', NULL, '2026-02-07 13:38:29', '2026-02-07 13:38:29'),
(4, 'dsuat79', '$2y$10$0CFUZQbDKdeHkdI6DQd5s.Emcl84qHYyY2CYeio5UCnVTrjA1PDq2', 'kadjis', 'guru', 'default.png', 'active', NULL, '2026-02-10 10:56:09', '2026-02-10 10:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `kas`
--

CREATE TABLE `kas` (
  `id_kas` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `saldo_sebelum` decimal(15,2) NOT NULL,
  `debit` decimal(15,2) DEFAULT 0.00,
  `kredit` decimal(15,2) DEFAULT 0.00,
  `saldo_sesudah` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_keuangan`
--

CREATE TABLE `kategori_keuangan` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_keuangan`
--

INSERT INTO `kategori_keuangan` (`id_kategori`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(2, 'Donasi', '2026-02-07 01:42:43', '2026-02-07 01:42:43'),
(3, 'Bantuan Operasional', '2026-02-07 01:42:43', '2026-02-07 01:42:43'),
(4, 'Lain-lain (Masuk)', '2026-02-07 01:42:43', '2026-02-07 01:42:43'),
(5, 'Gaji Guru', '2026-02-07 01:42:43', '2026-02-07 13:45:13'),
(6, 'Listrik & Air', '2026-02-07 01:42:43', '2026-02-07 13:45:13'),
(7, 'ATK', '2026-02-07 01:42:43', '2026-02-07 13:45:13'),
(9, 'Lain-lain (Keluar)', '2026-02-07 01:42:43', '2026-02-07 13:45:13'),
(11, 'Uang Kas', '2026-02-07 03:34:13', '2026-02-07 03:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `deskripsi`, `status`, `created_at`) VALUES
(1, 'Kelas A', 'Kelas A - Pemula', 'active', '2026-02-05 07:31:13'),
(2, 'Kelas B1', 'Kelas B1 - Menengah', 'active', '2026-02-05 07:31:13'),
(3, 'Kelas B2', 'Kelas B2 - Menengah', 'active', '2026-02-05 07:31:13'),
(4, 'Kelas C', 'Kelas C - Lanjutan', 'active', '2026-02-05 07:31:13'),
(5, 'Kelas D', 'Kelas D - Tahfidz', 'active', '2026-02-05 07:31:13'),
(6, 'Kelas F', 'Kelas F - Tahfidz Lanjutan', 'active', '2026-02-05 07:31:13'),
(7, 'X RPL 1', NULL, 'active', '2026-02-10 11:54:26'),
(8, 'X RPL 2', NULL, 'active', '2026-02-10 11:54:26'),
(9, 'X TKJ 1', NULL, 'active', '2026-02-10 11:54:26'),
(10, 'X TKJ 2', NULL, 'active', '2026-02-10 11:54:26'),
(11, 'XI RPL 1', NULL, 'active', '2026-02-10 11:54:26'),
(12, 'XI RPL 2', NULL, 'active', '2026-02-10 11:54:26'),
(13, 'XI TKJ 1', NULL, 'active', '2026-02-10 11:54:26'),
(14, 'XI TKJ 2', NULL, 'active', '2026-02-10 11:54:26'),
(15, 'XII RPL 1', NULL, 'active', '2026-02-10 11:54:26'),
(16, 'XII RPL 2', NULL, 'active', '2026-02-10 11:54:26'),
(17, 'XII TKJ 1', NULL, 'active', '2026-02-10 11:54:26'),
(18, 'XII TKJ 2', NULL, 'active', '2026-02-10 11:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_guru` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `tabel` varchar(50) DEFAULT NULL,
  `data_id` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_santri` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `bulan_dibayar` varchar(20) NOT NULL,
  `tahun_dibayar` varchar(4) NOT NULL,
  `id_spp` int(11) NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `metode_bayar` enum('tunai','transfer','online') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `santri`
--

CREATE TABLE `santri` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_spp` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telp_wali` varchar(15) DEFAULT NULL,
  `status` enum('active','inactive','alumni') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `santri`
--

INSERT INTO `santri` (`id`, `nama`, `tempat_lahir`, `id_kelas`, `id_spp`, `alamat`, `telp_wali`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Indra Pratama', 'Jakarta', 11, 1, 'Jl. Contoh No. 9', '087636857901', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(2, 'Kiki Santoso', 'Jakarta', 15, 3, 'Jl. Contoh No. 82', '087564913644', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(3, 'Ahmad Wibowo', 'Jakarta', 7, 2, 'Jl. Contoh No. 73', '081552048822', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(4, 'Rina Baskoro', 'Jakarta', 9, 2, 'Jl. Contoh No. 87', '081062118978', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(5, 'Candra Pratama', 'Jakarta', 10, 2, 'Jl. Contoh No. 88', '083003557344', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(6, 'Ahmad Santoso', 'Jakarta', 14, 1, 'Jl. Contoh No. 93', '086601119498', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(7, 'Yudi Baskoro', 'Jakarta', 16, 1, 'Jl. Contoh No. 25', '081350721850', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(8, 'Nina Kusuma', 'Jakarta', 18, 1, 'Jl. Contoh No. 28', '083679105323', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(9, 'Nina Wibowo', 'Jakarta', 16, 3, 'Jl. Contoh No. 89', '086432409857', 'active', '2026-02-10 11:54:26', '2026-02-10 11:54:26'),
(10, 'Nina Santoso', 'Jakarta', 8, 1, 'Jl. Contoh No. 58', '088787415050', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(11, 'Joko Wijaya', 'Jakarta', 14, 1, 'Jl. Contoh No. 11', '081349984930', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(12, 'Oscar Baskoro', 'Jakarta', 13, 3, 'Jl. Contoh No. 19', '085279629592', 'active', '2026-02-10 11:54:26', '2026-02-10 11:54:26'),
(13, 'Kiki Nugroho', 'Jakarta', 8, 3, 'Jl. Contoh No. 25', '084692859731', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(14, 'Sari Wijaya', 'Jakarta', 10, 1, 'Jl. Contoh No. 86', '082071155468', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(15, 'Maya Nugroho', 'Jakarta', 11, 2, 'Jl. Contoh No. 20', '088709464196', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(16, 'Indra Baskoro', 'Jakarta', 16, 2, 'Jl. Contoh No. 11', '085475456075', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(17, 'Hana Santoso', 'Jakarta', 13, 1, 'Jl. Contoh No. 49', '087217716361', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(18, 'Maya Lestari', 'Jakarta', 11, 1, 'Jl. Contoh No. 61', '085527323864', 'active', '2026-02-10 11:54:26', '2026-02-10 11:54:26'),
(19, 'Oscar Santoso', 'Jakarta', 12, 3, 'Jl. Contoh No. 81', '081136888188', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(20, 'Hana Santoso', 'Jakarta', 12, 2, 'Jl. Contoh No. 47', '082330090060', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(21, 'Budi Nugroho', 'Jakarta', 12, 2, 'Jl. Contoh No. 30', '086222982404', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(22, 'Indra Hidayat', 'Jakarta', 12, 3, 'Jl. Contoh No. 73', '088697752506', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(23, 'Gita Nugroho', 'Jakarta', 7, 2, 'Jl. Contoh No. 46', '083583272549', 'active', '2026-02-10 11:54:26', '2026-02-10 11:54:26'),
(24, 'Nina Lestari', 'Jakarta', 12, 1, 'Jl. Contoh No. 67', '084923245006', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(25, 'Nina Pratama', 'Jakarta', 10, 1, 'Jl. Contoh No. 95', '088870280308', 'active', '2026-02-10 11:54:26', '2026-02-10 11:54:26'),
(26, 'Nina Pratama', 'Jakarta', 8, 2, 'Jl. Contoh No. 16', '084785049104', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(27, 'Dewi Baskoro', 'Jakarta', 14, 3, 'Jl. Contoh No. 100', '082217594620', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(28, 'Hana Wibowo', 'Jakarta', 16, 2, 'Jl. Contoh No. 85', '087516263451', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(29, 'Gita Hidayat', 'Jakarta', 11, 3, 'Jl. Contoh No. 27', '083961103318', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(30, 'Eka Baskoro', 'Jakarta', 18, 1, 'Jl. Contoh No. 7', '086255822739', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(31, 'Dewi Pratama', 'Jakarta', 11, 1, 'Jl. Contoh No. 44', '087341606624', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(32, 'Dewi Pratama', 'Jakarta', 10, 1, 'Jl. Contoh No. 41', '087210660605', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(33, 'Putri Kusuma', 'Jakarta', 14, 2, 'Jl. Contoh No. 7', '081806202891', 'active', '2026-02-10 11:54:26', '2026-02-10 11:54:26'),
(34, 'Candra Nugroho', 'Jakarta', 8, 2, 'Jl. Contoh No. 90', '083271575077', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(35, 'Budi Nugroho', 'Jakarta', 8, 3, 'Jl. Contoh No. 67', '082362448987', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(36, 'Candra Santoso', 'Jakarta', 11, 2, 'Jl. Contoh No. 99', '083316737657', 'active', '2026-02-10 11:54:26', '2026-02-10 11:54:26'),
(37, 'Tono Wibowo', 'Jakarta', 18, 2, 'Jl. Contoh No. 92', '086006264230', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(38, 'Sari Kusuma', 'Jakarta', 7, 3, 'Jl. Contoh No. 87', '086338412912', 'active', '2026-02-10 11:54:26', '2026-02-10 11:54:26'),
(39, 'Lina Wijaya', 'Jakarta', 9, 2, 'Jl. Contoh No. 50', '089871146066', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27'),
(40, 'Candra Saputra', 'Jakarta', 12, 1, 'Jl. Contoh No. 16', '085026563530', 'active', '2026-02-10 11:54:27', '2026-02-10 11:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','password','email','number','boolean','json') DEFAULT 'text',
  `setting_group` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `description`, `created_at`, `updated_at`) VALUES
(1, 'nama_sekolah', 'TPQ Al-Ikhlas Metro Parung', 'text', 'general', 'Nama sekolah/lembaga', '2026-02-05 07:31:13', '2026-02-05 07:31:13'),
(2, 'alamat_sekolah', 'PERUM METRO PARUNG, BLOK A5, Rt. 02, Rw. 07', 'text', 'general', 'Alamat', '2026-02-05 07:31:13', '2026-02-05 07:31:13'),
(3, 'telp_sekolah', '021-12345678', 'text', 'general', 'Telepon', '2026-02-05 07:31:13', '2026-02-05 07:31:13'),
(4, 'email_sekolah', 'info@tpq-alikhlas.sch.id', 'email', 'general', 'Email', '2026-02-05 07:31:13', '2026-02-05 07:31:13'),
(5, 'kepala_sekolah', 'Ustadz Ahmad', 'text', 'general', 'Nama Kepala Sekolah', '2026-02-05 07:31:13', '2026-02-05 07:31:13'),
(6, 'bendahara', 'Ustadzah Fatimah', 'text', 'general', 'Nama Bendahara', '2026-02-05 07:31:13', '2026-02-05 07:31:13');

-- --------------------------------------------------------

--
-- Table structure for table `spp`
--

CREATE TABLE `spp` (
  `id_spp` int(11) NOT NULL,
  `tahun` int(4) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spp`
--

INSERT INTO `spp` (`id_spp`, `tahun`, `nominal`, `created_at`) VALUES
(1, 2026, 100000.00, '2026-02-03 05:17:30'),
(2, 2024, 250000.00, '2026-02-10 11:54:26'),
(3, 2025, 300000.00, '2026-02-10 11:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `tarif_spp`
--

CREATE TABLE `tarif_spp` (
  `id_tarif` int(11) NOT NULL,
  `tahun_ajaran` varchar(9) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tarif_spp`
--

INSERT INTO `tarif_spp` (`id_tarif`, `tahun_ajaran`, `nominal`, `keterangan`, `status`, `created_at`) VALUES
(1, '2025/2026', 50000.00, 'Tarif SPP tahun ajaran 2025/2026', 'active', '2026-02-05 07:31:13'),
(2, '2026/2027', 50000.00, 'Tarif SPP tahun ajaran 2026/2027', 'active', '2026-02-05 07:31:13');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_santri` int(11) DEFAULT NULL COMMENT 'NULL jika bukan transaksi per santri',
  `uraian` varchar(255) NOT NULL,
  `debit` decimal(15,2) DEFAULT 0.00 COMMENT 'Pemasukan',
  `kredit` decimal(15,2) DEFAULT 0.00 COMMENT 'Pengeluaran',
  `bulan_spp` varchar(20) DEFAULT NULL COMMENT 'Untuk SPP: bulan yang dibayar',
  `tahun_spp` varchar(4) DEFAULT NULL COMMENT 'Untuk SPP: tahun',
  `metode_bayar` enum('tunai','transfer','online') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `id_guru` int(11) NOT NULL COMMENT 'User yang input',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal`, `id_kategori`, `id_santri`, `uraian`, `debit`, `kredit`, `bulan_spp`, `tahun_spp`, `metode_bayar`, `keterangan`, `id_guru`, `created_at`, `updated_at`) VALUES
(3, '2026-02-11', 7, NULL, 'hdgua', 10000.00, 0.00, NULL, NULL, 'tunai', NULL, 1, '2026-02-11 05:38:23', '2026-02-11 05:38:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `kas`
--
ALTER TABLE `kas`
  ADD PRIMARY KEY (`id_kas`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `kategori_keuangan`
--
ALTER TABLE `kategori_keuangan`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_guru` (`id_guru`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_spp` (`id_spp`),
  ADD KEY `pembayaran_ibfk_2` (`id_santri`),
  ADD KEY `pembayaran_ibfk_1` (`id_guru`);

--
-- Indexes for table `santri`
--
ALTER TABLE `santri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `spp`
--
ALTER TABLE `spp`
  ADD PRIMARY KEY (`id_spp`);

--
-- Indexes for table `tarif_spp`
--
ALTER TABLE `tarif_spp`
  ADD PRIMARY KEY (`id_tarif`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_santri` (`id_santri`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `tanggal` (`tanggal`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `kas`
  MODIFY `id_kas` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `kategori_keuangan`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `santri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `spp`
  MODIFY `id_spp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tarif_spp`
  MODIFY `id_tarif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

ALTER TABLE `kas`
  ADD CONSTRAINT `kas_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE;

ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL;

ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id`),
  ADD CONSTRAINT `pembayaran_ibfk_3` FOREIGN KEY (`id_spp`) REFERENCES `spp` (`id_spp`);

ALTER TABLE `santri`
  ADD CONSTRAINT `santri_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`);

ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_keuangan` (`id_kategori`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
