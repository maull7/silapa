-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 16 Jun 2025 pada 11.47
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
(20, 10, 28, 'approve', 'okkk', '2025-06-16 11:33:03', '2025-06-16 11:33:03');

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
  `desc` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `notif`
--

INSERT INTO `notif` (`id`, `id_request`, `id_user`, `desc`, `created_at`) VALUES
(11, 10, 25, 'Pengajuan dilakukan daffa', '2025-06-15 14:44:42'),
(12, 11, 24, 'Pengajuan dilakukan rehan', '2025-06-16 10:52:47'),
(13, 12, 24, 'Pengajuan dilakukan rehan', '2025-06-16 10:53:07'),
(14, 10, 21, 'Pengajuan Telah Di Approve oleh Bu Wiar', '2025-06-16 10:54:31'),
(15, 10, 27, 'Pengajuan Telah Di Approve oleh Erna', '2025-06-16 11:32:18'),
(16, 10, 28, 'Pengajuan Telah Di setujui final', '2025-06-16 11:33:03');

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
(12, 24, 'ss', 'sss', '1750071187_Cv Teuku.pdf', 1, 'pending', 'Baru diajukan', '2025-06-16', '2025-06-16 10:53:07', '2025-06-16 10:53:07');

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
(46, 16, 28, 'read', '2025-06-16 11:33:04');

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
  MODIFY `id_approvals` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reply`
--
ALTER TABLE `reply`
  MODIFY `id_reply` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `request`
--
ALTER TABLE `request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `status_notif`
--
ALTER TABLE `status_notif`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
