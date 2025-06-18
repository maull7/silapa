-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 18 Jun 2025 pada 17.24
-- Versi server: 8.0.30
-- Versi PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `approval`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `approvals`
--

CREATE TABLE `approvals` (
  `id_approvals` int NOT NULL,
  `request_id` int NOT NULL,
  `user_id` int NOT NULL,
  `status` varchar(100) NOT NULL,
  `komentar` varchar(255) NOT NULL,
  `approved_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `approvals`
--

INSERT INTO `approvals` (`id_approvals`, `request_id`, `user_id`, `status`, `komentar`, `approved_at`, `created_at`) VALUES
(18, 10, 21, 'approve', 'ok', '2025-06-16 10:54:31', '2025-06-16 10:54:31'),
(19, 10, 27, 'approve', 'es', '2025-06-16 11:32:18', '2025-06-16 11:32:18'),
(20, 10, 28, 'approve', 'okkk', '2025-06-16 11:33:03', '2025-06-16 11:33:03'),
(21, 20, 21, 'approve', 'okee', '2025-06-18 22:25:06', '2025-06-18 22:25:06'),
(22, 20, 27, 'approve', 'baik di acc', '2025-06-18 22:26:01', '2025-06-18 22:26:01'),
(23, 20, 28, 'approve', 'sip', '2025-06-18 22:26:45', '2025-06-18 22:26:45'),
(24, 21, 21, 'approve', 'okey', '2025-06-18 22:36:02', '2025-06-18 22:36:02'),
(25, 21, 27, 'approve', 'sipp', '2025-06-18 22:39:48', '2025-06-18 22:39:48'),
(26, 21, 28, 'approve', 'oke di acc', '2025-06-18 22:41:02', '2025-06-18 22:41:02'),
(27, 22, 21, 'approve', 'ss', '2025-06-18 22:44:50', '2025-06-18 22:44:50'),
(28, 23, 21, 'approve', 'siap', '2025-06-18 22:50:58', '2025-06-18 22:50:58'),
(29, 22, 27, 'approve', 'y', '2025-06-18 23:06:43', '2025-06-18 23:06:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `map`
--

CREATE TABLE `map` (
  `id_map` int NOT NULL,
  `id_parent` int NOT NULL,
  `id_child` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `map`
--

INSERT INTO `map` (`id_map`, `id_parent`, `id_child`) VALUES
(1, 21, 24),
(2, 21, 25),
(3, 21, 25);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_jabatan`
--

CREATE TABLE `master_jabatan` (
  `id` int NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `master_jabatan`
--

INSERT INTO `master_jabatan` (`id`, `nama_jabatan`) VALUES
(1, 'admin'),
(3, 'siswa'),
(4, 'wali kelas'),
(5, 'kurikulum'),
(6, 'kepala sekolah'),
(7, 'ketua yayasan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notif`
--

CREATE TABLE `notif` (
  `id` int NOT NULL,
  `id_request` int NOT NULL,
  `id_user` int NOT NULL,
  `id_penerima` int NOT NULL,
  `desc` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `notif`
--

INSERT INTO `notif` (`id`, `id_request`, `id_user`, `id_penerima`, `desc`, `status`, `created_at`) VALUES
(28, 10, 25, 21, 'daffaTelah Menjawab Komentar Anda', 'read', '2025-06-19 00:18:35'),
(29, 22, 27, 21, 'Pengajuan Berhasil di setujui Oleh Erna', 'read', '2025-06-19 00:18:35'),
(30, 22, 27, 28, 'Pengajuan Berhasil di setujui Oleh Erna', 'unread', '2025-06-18 23:06:43'),
(31, 22, 27, 25, 'ErnaTelah melakukan komentar ke pengajuan anda', 'unread', '2025-06-18 23:06:43'),
(32, 22, 27, 21, 'ErnaTelah Menjawab Komentar Anda', 'read', '2025-06-19 00:18:35'),
(33, 22, 21, 21, 'Bu WiarTelah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(34, 22, 27, 21, 'ErnaTelah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(35, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(36, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(37, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(38, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(39, 22, 27, 21, 'Erna Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(40, 22, 27, 21, 'Erna Telah Melakukan Komentar', 'read', '2025-06-19 00:18:35'),
(41, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(42, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(43, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(44, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(45, 22, 27, 21, 'Erna Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(46, 22, 27, 21, 'Erna Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(47, 22, 27, 21, 'Erna Telah Melakukan Komentar', 'read', '2025-06-19 00:18:35'),
(48, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(49, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(50, 22, 27, 21, 'Erna Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(51, 22, 27, 21, 'Erna Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(52, 22, 27, 21, 'Erna Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(53, 22, 27, 21, 'Erna Telah Melakukan Komentar', 'read', '2025-06-19 00:18:35'),
(54, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(55, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(56, 22, 21, 21, 'Bu Wiar Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35'),
(57, 22, 25, 21, 'daffa Telah Membalas Komentar Anda', 'read', '2025-06-19 00:18:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reply`
--

CREATE TABLE `reply` (
  `id_reply` int NOT NULL,
  `approvals_id` int NOT NULL,
  `user_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `komentar` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `reply`
--

INSERT INTO `reply` (`id_reply`, `approvals_id`, `user_id`, `parent_id`, `komentar`, `created_at`) VALUES
(26, 27, 27, NULL, 'misi bu', '2025-06-19 00:13:22'),
(27, 27, 21, NULL, 'iya bu', '2025-06-19 00:14:03'),
(28, 27, 21, 27, 'iya bu', '2025-06-19 00:15:31'),
(29, 27, 21, 21, 'ada apa bu', '2025-06-19 00:16:46'),
(30, 27, 25, 21, 'ada apa bu', '2025-06-19 00:18:07'),
(31, 27, 21, 21, 'tes', '2025-06-19 00:22:36'),
(32, 27, 21, 21, 'tes', '2025-06-19 00:23:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `request`
--

CREATE TABLE `request` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `bukti` varchar(255) NOT NULL,
  `level` int NOT NULL,
  `status` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `request`
--

INSERT INTO `request` (`id`, `user_id`, `title`, `desc`, `bukti`, `level`, `status`, `keterangan`, `tanggal`, `created_at`, `updated_at`) VALUES
(10, 25, 's', 'sss', '1749998682_CV Delva.pdf', 4, 'approve', 'Disetujui final oleh Uwoh', '2025-06-15', '2025-06-15 14:44:42', '2025-06-16 11:33:03'),
(11, 24, 'sss', 'sss', '1750071167_Cv Teuku.pdf', 1, 'pending', 'Baru diajukan', '2025-06-16', '2025-06-16 10:52:47', '2025-06-16 10:52:47'),
(12, 24, 'ss', 'sss', '1750071187_Cv Teuku.pdf', 1, 'pending', 'Baru diajukan', '2025-06-16', '2025-06-16 10:53:07', '2025-06-16 10:53:07'),
(13, 25, 'permintaan dana bansos', '20000', '1750078129_Cv Teuku.pdf', 1, 'pending', 'Baru diajukan', '2025-06-16', '2025-06-16 19:48:49', '2025-06-16 19:48:49'),
(14, 25, 'sss', 'ssss', '1750078336_image.jpeg', 1, 'pending', 'Baru diajukan', '2025-06-16', '2025-06-16 19:52:16', '2025-06-16 19:52:16'),
(15, 25, 'bansos', 'bansos', '1750078452_CV Delva.pdf', 1, 'pending', 'Baru diajukan', '2025-06-16', '2025-06-16 19:54:12', '2025-06-16 19:54:12'),
(16, 22, 'zz', 'zz', '1750087188_image.jpeg', 2, 'pending', 'Baru diajukan', '2025-06-16', '2025-06-16 22:19:48', '2025-06-16 22:19:48'),
(17, 22, 'sss', 'sss', '1750087235_bg.png', 2, 'pending', 'Baru diajukan', '2025-06-16', '2025-06-16 22:20:35', '2025-06-16 22:20:35'),
(18, 22, 'sss', 'ssss', '1750141075_image.jpeg', 2, 'pending', 'Baru diajukan', '2025-06-17', '2025-06-17 13:17:55', '2025-06-17 13:17:55'),
(19, 22, 's', 's', '1750141232_image.jpeg', 2, 'pending', 'Baru diajukan', '2025-06-17', '2025-06-17 13:20:32', '2025-06-17 13:20:32'),
(20, 25, 'dana buku', 'buku pelajaran', '1750259893_image.jpeg', 4, 'approve', 'Disetujui final oleh Uwoh', '2025-06-18', '2025-06-18 22:18:13', '2025-06-18 22:26:45'),
(21, 25, 'eee', 'eee', '1750260924_image.jpeg', 4, 'approve', 'Disetujui final oleh Uwoh', '2025-06-18', '2025-06-18 22:35:24', '2025-06-18 22:41:02'),
(22, 25, 'ss', 'ss', '1750261458_image.jpeg', 3, 'approve', 'Disetujui oleh Erna', '2025-06-18', '2025-06-18 22:44:18', '2025-06-18 23:06:43'),
(23, 25, 's', 's', '1750261784_bg.png', 2, 'approve', 'Disetujui oleh Bu Wiar', '2025-06-18', '2025-06-18 22:49:44', '2025-06-18 22:50:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_notif`
--

CREATE TABLE `status_notif` (
  `id` int NOT NULL,
  `id_notif` int NOT NULL,
  `id_user` int NOT NULL,
  `status` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `status_notif`
--

INSERT INTO `status_notif` (`id`, `id_notif`, `id_user`, `status`, `created_at`) VALUES
(25, 11, 21, 'read', '2025-06-15 14:45:09'),
(26, 11, 22, 'read', '2025-06-15 14:45:32'),
(27, 11, 12, 'read', '2025-06-15 15:09:53'),
(28, 11, 26, 'read', '2025-06-16 10:38:38'),
(29, 11, 24, 'read', '2025-06-16 10:52:49'),
(30, 12, 24, 'read', '2025-06-16 10:52:49'),
(31, 12, 22, 'read', '2025-06-16 10:53:41'),
(32, 13, 22, 'read', '2025-06-16 10:53:41'),
(33, 12, 21, 'read', '2025-06-16 10:54:23'),
(34, 13, 21, 'read', '2025-06-16 10:54:23'),
(35, 14, 21, 'read', '2025-06-16 10:54:32'),
(36, 11, 27, 'read', '2025-06-16 11:31:46'),
(37, 12, 27, 'read', '2025-06-16 11:31:46'),
(38, 13, 27, 'read', '2025-06-16 11:31:46'),
(39, 14, 27, 'read', '2025-06-16 11:31:46'),
(40, 15, 27, 'read', '2025-06-16 11:32:20'),
(41, 11, 28, 'read', '2025-06-16 11:32:44'),
(42, 12, 28, 'read', '2025-06-16 11:32:44'),
(43, 13, 28, 'read', '2025-06-16 11:32:44'),
(44, 14, 28, 'read', '2025-06-16 11:32:44'),
(45, 15, 28, 'read', '2025-06-16 11:32:44'),
(46, 16, 28, 'read', '2025-06-16 11:33:04'),
(47, 12, 12, 'read', '2025-06-16 18:49:10'),
(48, 13, 12, 'read', '2025-06-16 18:49:10'),
(49, 14, 12, 'read', '2025-06-16 18:49:10'),
(50, 15, 12, 'read', '2025-06-16 18:49:10'),
(51, 16, 12, 'read', '2025-06-16 18:49:10'),
(52, 17, 12, 'read', '2025-06-17 12:41:53'),
(53, 18, 12, 'read', '2025-06-17 12:41:53'),
(54, 19, 12, 'read', '2025-06-17 12:41:53'),
(55, 20, 12, 'read', '2025-06-17 12:41:53'),
(56, 21, 12, 'read', '2025-06-17 12:41:53'),
(57, 14, 22, 'read', '2025-06-17 13:17:01'),
(58, 15, 22, 'read', '2025-06-17 13:17:01'),
(59, 16, 22, 'read', '2025-06-17 13:17:01'),
(60, 17, 22, 'read', '2025-06-17 13:17:01'),
(61, 18, 22, 'read', '2025-06-17 13:17:01'),
(62, 19, 22, 'read', '2025-06-17 13:17:01'),
(63, 20, 22, 'read', '2025-06-17 13:17:01'),
(64, 21, 22, 'read', '2025-06-17 13:17:01'),
(65, 1, 21, 'read', '2025-06-18 22:24:50'),
(66, 2, 21, 'read', '2025-06-18 22:25:07'),
(67, 3, 21, 'read', '2025-06-18 22:25:07'),
(68, 4, 21, 'read', '2025-06-18 22:25:07'),
(69, 1, 27, 'read', '2025-06-18 22:25:51'),
(70, 2, 27, 'read', '2025-06-18 22:25:51'),
(71, 3, 27, 'read', '2025-06-18 22:25:51'),
(72, 4, 27, 'read', '2025-06-18 22:25:51'),
(73, 5, 27, 'read', '2025-06-18 22:26:02'),
(74, 6, 27, 'read', '2025-06-18 22:26:02'),
(75, 7, 27, 'read', '2025-06-18 22:26:02'),
(76, 1, 28, 'read', '2025-06-18 22:26:37'),
(77, 2, 28, 'read', '2025-06-18 22:26:37'),
(78, 3, 28, 'read', '2025-06-18 22:26:37'),
(79, 4, 28, 'read', '2025-06-18 22:26:37'),
(80, 5, 28, 'read', '2025-06-18 22:26:37'),
(81, 6, 28, 'read', '2025-06-18 22:26:37'),
(82, 7, 28, 'read', '2025-06-18 22:26:37'),
(83, 8, 28, 'read', '2025-06-18 22:26:45'),
(84, 9, 28, 'read', '2025-06-18 22:26:45'),
(85, 5, 21, 'read', '2025-06-18 22:35:53'),
(86, 6, 21, 'read', '2025-06-18 22:35:53'),
(87, 7, 21, 'read', '2025-06-18 22:35:53'),
(88, 8, 21, 'read', '2025-06-18 22:35:53'),
(89, 9, 21, 'read', '2025-06-18 22:35:53'),
(90, 10, 21, 'read', '2025-06-18 22:35:53'),
(91, 8, 27, 'read', '2025-06-18 22:36:34'),
(92, 9, 27, 'read', '2025-06-18 22:36:34'),
(93, 10, 27, 'read', '2025-06-18 22:36:34'),
(94, 10, 28, 'read', '2025-06-18 22:40:51'),
(95, 17, 28, 'read', '2025-06-18 22:41:03'),
(96, 18, 28, 'read', '2025-06-18 22:41:03'),
(97, 19, 28, 'read', '2025-06-18 22:41:03'),
(98, 15, 21, 'read', '2025-06-18 22:44:43'),
(99, 16, 21, 'read', '2025-06-18 22:44:43'),
(100, 17, 21, 'read', '2025-06-18 22:44:43'),
(101, 18, 21, 'read', '2025-06-18 22:44:43'),
(102, 19, 21, 'read', '2025-06-18 22:44:43'),
(103, 20, 21, 'read', '2025-06-18 22:44:43'),
(104, 21, 21, 'read', '2025-06-18 22:44:51'),
(105, 22, 21, 'read', '2025-06-18 22:44:51'),
(106, 23, 21, 'read', '2025-06-18 22:50:04'),
(107, 24, 21, 'read', '2025-06-18 22:50:59'),
(108, 25, 21, 'read', '2025-06-18 22:50:59'),
(109, 26, 21, 'read', '2025-06-18 22:50:59'),
(110, 28, 21, 'read', '2025-06-18 23:01:11'),
(111, 28, 27, 'read', '2025-06-18 23:06:35'),
(112, 29, 27, 'read', '2025-06-18 23:06:44'),
(113, 30, 27, 'read', '2025-06-18 23:06:44'),
(114, 31, 27, 'read', '2025-06-18 23:06:44'),
(115, 29, 21, 'read', '2025-06-18 23:11:09'),
(116, 30, 21, 'read', '2025-06-18 23:11:09'),
(117, 31, 21, 'read', '2025-06-18 23:11:09'),
(118, 32, 21, 'read', '2025-06-18 23:11:09'),
(119, 32, 27, 'read', '2025-06-18 23:12:00'),
(120, 33, 27, 'read', '2025-06-18 23:12:00'),
(121, 33, 21, 'read', '2025-06-18 23:12:52'),
(122, 34, 21, 'read', '2025-06-18 23:12:52'),
(123, 34, 27, 'read', '2025-06-18 23:27:19'),
(124, 35, 27, 'read', '2025-06-18 23:27:19'),
(125, 36, 27, 'read', '2025-06-18 23:27:19'),
(126, 37, 27, 'read', '2025-06-18 23:27:19'),
(127, 38, 27, 'read', '2025-06-18 23:27:19'),
(128, 35, 21, 'read', '2025-06-18 23:35:09'),
(129, 36, 21, 'read', '2025-06-18 23:35:09'),
(130, 37, 21, 'read', '2025-06-18 23:35:09'),
(131, 38, 21, 'read', '2025-06-18 23:35:09'),
(132, 39, 21, 'read', '2025-06-18 23:35:09'),
(133, 40, 21, 'read', '2025-06-18 23:35:09'),
(134, 39, 27, 'read', '2025-06-18 23:50:19'),
(135, 40, 27, 'read', '2025-06-18 23:50:19'),
(136, 41, 27, 'read', '2025-06-18 23:50:19'),
(137, 42, 27, 'read', '2025-06-18 23:50:19'),
(138, 43, 27, 'read', '2025-06-18 23:50:19'),
(139, 44, 27, 'read', '2025-06-18 23:50:19'),
(140, 41, 21, 'read', '2025-06-18 23:54:07'),
(141, 42, 21, 'read', '2025-06-18 23:54:07'),
(142, 43, 21, 'read', '2025-06-18 23:54:07'),
(143, 44, 21, 'read', '2025-06-18 23:54:07'),
(144, 45, 21, 'read', '2025-06-18 23:54:07'),
(145, 46, 21, 'read', '2025-06-18 23:54:07'),
(146, 47, 21, 'read', '2025-06-18 23:54:07'),
(147, 45, 27, 'read', '2025-06-18 23:56:30'),
(148, 46, 27, 'read', '2025-06-18 23:56:30'),
(149, 47, 27, 'read', '2025-06-18 23:56:30'),
(150, 48, 27, 'read', '2025-06-18 23:56:30'),
(151, 49, 27, 'read', '2025-06-18 23:56:30'),
(152, 48, 21, 'read', '2025-06-19 00:10:01'),
(153, 49, 21, 'read', '2025-06-19 00:10:01'),
(154, 50, 21, 'read', '2025-06-19 00:10:01'),
(155, 51, 21, 'read', '2025-06-19 00:10:01'),
(156, 52, 21, 'read', '2025-06-19 00:10:01'),
(157, 50, 27, 'read', '2025-06-19 00:12:56'),
(158, 51, 27, 'read', '2025-06-19 00:12:56'),
(159, 52, 27, 'read', '2025-06-19 00:12:56'),
(160, 53, 21, 'read', '2025-06-19 00:13:48'),
(161, 54, 21, 'read', '2025-06-19 00:16:24'),
(162, 55, 21, 'read', '2025-06-19 00:16:24'),
(163, 56, 21, 'read', '2025-06-19 00:22:14'),
(164, 57, 21, 'read', '2025-06-19 00:22:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tele_bot`
--

CREATE TABLE `tele_bot` (
  `id` int NOT NULL,
  `user_id` bigint NOT NULL,
  `chat_id` bigint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tele_bot`
--

INSERT INTO `tele_bot` (`id`, `user_id`, `chat_id`, `created_at`, `updated_at`) VALUES
(1, 12, 6601266194, '2025-06-17 13:05:25', '2025-06-17 13:05:25'),
(2, 24, 1346728973, '2025-06-17 13:19:25', '2025-06-17 13:19:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_jabatan` int NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `id_jabatan`, `nip`, `remember_token`, `created_at`, `updated_at`) VALUES
(12, 'rehan maulana', 'r@gmail.com', NULL, '$2y$10$bXJNVIHyQceNupPMOyvOIeiWWmU4i7x6T3yXlSBIotI4ulxqYG8Ne', '0', 1, '12345678', NULL, NULL, NULL),
(21, 'Bu Wiar', 'wiaar@mail.com', NULL, '$2y$10$qs1qoehaP9CganASbqXDduKuTsIKMtuDJIhe6xaN0CJEQRtU9wyX6', '2', 4, '123', NULL, '2025-06-15 07:35:26', '2025-06-15 07:35:26'),
(22, 'ari januar', 'ari@gmail.com', NULL, '$2y$10$GYQn0wkuCiFQgwLiLRmJveOA.rtqN4LCiTttJOTvxT9tVfwmBRmvK', '2', 4, '1234', NULL, '2025-06-15 07:35:48', '2025-06-15 07:35:48'),
(24, 'rehan', 'rehanmaul1@gmail.com', NULL, '$2y$10$8w8oN.t84yU5Q.KGiIiMteAuylePrnQkehesQMdgQfZrt1tHW0Q7S', '1', 3, '12345', NULL, '2025-06-15 07:38:37', '2025-06-15 07:38:37'),
(25, 'daffa', 'dap1@gmail.com', NULL, '$2y$10$0taipDPW5jJOTG1RiKOcaOiYm5Mh/OECe1MVZO57hPTyOnhxe4oyW', '1', 3, '122', NULL, '2025-06-15 07:39:21', '2025-06-15 07:39:21'),
(26, 'ketua', 'admin@gmail.com', NULL, '$2y$10$bXJNVIHyQceNupPMOyvOIeiWWmU4i7x6T3yXlSBIotI4ulxqYG8Ne', '5', 7, '1221', NULL, '2025-06-16 02:18:12', '2025-06-16 02:18:12'),
(27, 'Erna', 'a21@gmail.com', NULL, '$2y$10$9YQ4S1drkSS24HQ.PSlwZOKizZ53xWrpaoVDClBW/9k2My/SaQ/wO', '3', 5, '122111', NULL, '2025-06-16 03:51:26', '2025-06-16 03:51:26'),
(28, 'Uwoh', 're@gmail.com', NULL, '$2y$10$.wabYDQ49ne9sf9GwHVroObEZkahnTIea3XJpKAO8ONsVyU3ZnY9i', '4', 6, '11', NULL, '2025-06-16 03:51:57', '2025-06-16 03:51:57');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id_approvals`);

--
-- Indeks untuk tabel `map`
--
ALTER TABLE `map`
  ADD PRIMARY KEY (`id_map`);

--
-- Indeks untuk tabel `master_jabatan`
--
ALTER TABLE `master_jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`id_reply`);

--
-- Indeks untuk tabel `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `status_notif`
--
ALTER TABLE `status_notif`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tele_bot`
--
ALTER TABLE `tele_bot`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id_approvals` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `map`
--
ALTER TABLE `map`
  MODIFY `id_map` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `master_jabatan`
--
ALTER TABLE `master_jabatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `notif`
--
ALTER TABLE `notif`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reply`
--
ALTER TABLE `reply`
  MODIFY `id_reply` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `request`
--
ALTER TABLE `request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `status_notif`
--
ALTER TABLE `status_notif`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT untuk tabel `tele_bot`
--
ALTER TABLE `tele_bot`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
