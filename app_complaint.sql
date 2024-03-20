-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2024 at 03:42 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_complaint`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nipp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `nipp`, `jabatan`, `email`, `password`, `created_at`, `updated_at`) VALUES
(2, 'Admin Aplikasi 1', '20013001', 'Teknisi IT', 'admin1@gmail.com', '$2y$10$3ZkUBRR8mw/6hx/NfKhC5uyZ7QdZUEG7ULNzvUWpVwR6fimRbGlvO', '2024-03-19 21:45:37', '2024-03-19 21:45:37');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pelapor` bigint(20) DEFAULT NULL,
  `id_pengawas` bigint(20) DEFAULT NULL,
  `id_admin` bigint(20) DEFAULT NULL,
  `tgl_masuk` datetime NOT NULL,
  `no_inv_aset` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `kat_layanan` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_layanan` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `det_layanan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_awal_pengerjaan` datetime DEFAULT NULL,
  `tgl_akhir_pengerjaan` datetime DEFAULT NULL,
  `waktu_tambahan` int(11) DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `id_pelapor`, `id_pengawas`, `id_admin`, `tgl_masuk`, `no_inv_aset`, `tgl_selesai`, `kat_layanan`, `jenis_layanan`, `det_layanan`, `tgl_awal_pengerjaan`, `tgl_akhir_pengerjaan`, `waktu_tambahan`, `foto`, `created_at`, `updated_at`) VALUES
(2, 4, NULL, NULL, '2024-03-20 04:06:25', '01/INV/003', '2024-03-20 20:14:35', 'Throubleshooting', 'PC/Laptop', 'PC tidak mau menyala', '2024-03-01 17:17:23', '2024-03-08 17:17:23', NULL, NULL, '2024-03-19 21:06:25', '2024-03-19 21:06:25'),
(3, 4, NULL, NULL, '2024-03-20 04:07:04', '02/INV/001', NULL, 'Throubleshooting', 'Software', 'Aplikasi tidak bisa dijalankan', '2024-03-04 18:23:00', '2024-03-02 06:22:00', NULL, NULL, '2024-03-19 21:07:04', '2024-03-19 21:07:04'),
(4, 4, NULL, NULL, '2024-03-20 04:19:31', '03/INV/001', NULL, 'Throubleshooting', 'Jaringan', 'jaringan terkendala', '2024-01-04 17:17:52', '2024-03-01 17:17:52', NULL, NULL, '2024-03-19 21:19:31', '2024-03-19 21:19:31'),
(5, 4, NULL, NULL, '2024-03-20 04:37:14', '01/INV/004', NULL, 'Throubleshooting', 'Printer', 'Printer tidak bisa dijalankan', NULL, NULL, NULL, NULL, '2024-03-19 21:37:14', '2024-03-19 21:37:14'),
(6, 4, NULL, NULL, '2024-03-20 07:39:01', '01/INV/005', NULL, 'Instalasi', 'Sistem Operasi', 'Sistem tidak berjalan dengan baik', NULL, NULL, NULL, NULL, '2024-03-20 00:39:01', '2024-03-20 00:39:01'),
(7, 4, NULL, NULL, '2024-03-20 00:00:00', '01/INV/100', NULL, 'Throubleshooting', 'PC/Laptop', 'gdfhfgfhfgjhfjghgh fgfgh', '2024-03-12 09:25:00', '2024-03-30 21:26:00', NULL, NULL, '2024-03-20 14:23:27', '2024-03-20 14:23:27');

-- --------------------------------------------------------

--
-- Table structure for table `laporanakhir`
--

CREATE TABLE `laporanakhir` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_laoran` bigint(20) DEFAULT NULL,
  `no_ref` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_akhir` datetime DEFAULT NULL,
  `bisnis_area` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporanhist`
--

CREATE TABLE `laporanhist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_laporan` bigint(20) DEFAULT NULL,
  `status_laporan` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporanhist`
--

INSERT INTO `laporanhist` (`id`, `id_laporan`, `status_laporan`, `tanggal`, `keterangan`, `foto`, `created_at`, `updated_at`) VALUES
(1, 2, 'Pengajuan', '2024-03-20 04:06:25', NULL, NULL, '2024-03-19 21:06:25', '2024-03-19 21:06:25'),
(2, 3, 'Pengajuan', '2024-03-20 04:07:04', NULL, NULL, '2024-03-19 21:07:04', '2024-03-19 21:07:04'),
(3, 2, 'Diproses', '2024-03-20 11:10:51', NULL, NULL, '2024-03-20 04:10:51', '2024-03-20 04:10:51'),
(4, 4, 'Pengajuan', '2024-03-20 04:19:31', NULL, NULL, '2024-03-19 21:19:31', '2024-03-19 21:19:31'),
(5, 4, 'Diproses', '2024-03-20 11:20:01', NULL, NULL, '2024-03-20 04:20:01', '2024-03-20 04:20:01'),
(6, 5, 'Pengajuan', '2024-03-20 04:37:14', NULL, NULL, '2024-03-19 21:37:14', '2024-03-19 21:37:14'),
(7, 6, 'Pengajuan', '2024-03-20 07:39:01', NULL, NULL, '2024-03-20 00:39:01', '2024-03-20 00:39:01'),
(11, 2, 'CheckedU', '2024-03-20 18:55:20', NULL, NULL, '2024-03-20 11:55:20', '2024-03-20 11:55:20'),
(12, 4, 'CheckedU', '2024-03-20 18:22:00', 'Sudah selesai', NULL, '2024-03-20 11:22:00', '2024-03-20 11:22:00'),
(13, 3, 'Diproses', '2024-03-20 18:30:00', NULL, NULL, '2024-03-20 11:30:00', '2024-03-20 11:30:00'),
(14, 3, 'Diproses', '2024-03-20 18:30:07', NULL, NULL, '2024-03-20 11:30:07', '2024-03-20 11:30:07'),
(18, 2, 'Selesai', '2024-03-20 20:14:35', NULL, NULL, '2024-03-20 13:14:35', '2024-03-20 13:14:35'),
(19, 7, 'Pengajuan', '2024-03-20 00:00:00', NULL, NULL, '2024-03-20 14:23:27', '2024-03-20 14:23:27'),
(20, 7, 'Diproses', '2024-03-20 21:24:39', NULL, NULL, '2024-03-20 14:24:39', '2024-03-20 14:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2024_03_20_025824_create_pelapor_table', 1),
(4, '2024_03_20_030031_create_pengawas_table', 1),
(5, '2024_03_20_030252_create_admin_table', 1),
(6, '2024_03_20_030444_create_laporan_table', 1),
(7, '2024_03_20_030551_create_laporanhist_table', 1),
(8, '2024_03_20_031049_create_laporanakhir_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelapor`
--

CREATE TABLE `pelapor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nipp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `divisi` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelapor`
--

INSERT INTO `pelapor` (`id`, `nama`, `nipp`, `email`, `password`, `jabatan`, `divisi`, `telepon`, `created_at`, `updated_at`) VALUES
(4, 'Pelapor 1', '123456789', 'pelapor@gmail.com', '$2y$10$1YUlOgoolnU4ysHY5fLRgOT63g7veTDX6t9viSeKir.LsokEO3iOO', 'Marketing', 'Pemasaran', '08123456789', '2024-03-19 20:59:44', '2024-03-19 20:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `pengawas`
--

CREATE TABLE `pengawas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nipp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$10$lULCtRIgB.v7H22CeqicBuyeQBj7F7e0MX.12nHX7wI5sYVGJ7wYW', NULL, '2024-03-19 20:51:12', '2024-03-19 20:51:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporanakhir`
--
ALTER TABLE `laporanakhir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporanhist`
--
ALTER TABLE `laporanhist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pelapor`
--
ALTER TABLE `pelapor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengawas`
--
ALTER TABLE `pengawas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `laporanakhir`
--
ALTER TABLE `laporanakhir`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporanhist`
--
ALTER TABLE `laporanhist`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pelapor`
--
ALTER TABLE `pelapor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengawas`
--
ALTER TABLE `pengawas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
