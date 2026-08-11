-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 11, 2026 at 03:37 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi_lab`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int NOT NULL,
  `pendaftaran_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi_tugas` text NOT NULL,
  `foto_kegiatan` varchar(255) NOT NULL,
  `foto_selfie` varchar(255) NOT NULL,
  `status_verifikasi` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `pesan_dosen` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id_kegiatan` int NOT NULL,
  `dosen_id` int NOT NULL,
  `nama_kegiatan` varchar(150) NOT NULL,
  `periode_mulai` date NOT NULL,
  `periode_selesai` date NOT NULL,
  `deskripsi_tugas` text NOT NULL,
  `insentif` varchar(100) NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `kuota` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id_kegiatan`, `dosen_id`, `nama_kegiatan`, `periode_mulai`, `periode_selesai`, `deskripsi_tugas`, `insentif`, `status`, `created_at`, `kuota`) VALUES
(4, 13, 'PemWeb', '2026-08-11', '2026-08-14', 'goodlooking', '200000', 'closed', '2026-08-11 15:35:43', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_kegiatan`
--

CREATE TABLE `pendaftaran_kegiatan` (
  `id_pendaftaran` int NOT NULL,
  `kegiatan_id` int NOT NULL,
  `asdos_id` int NOT NULL,
  `status_pendaftaran` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `identity_number` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('dosen','asdos') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(80) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `identity_number`, `password`, `role`, `created_at`, `email`, `no_hp`) VALUES
(2, 'AAN', '25082010046', '$2y$10$H.RRD/h4c1BVnLSUA5AXROIfNapwRydWPQ5MC44e9RMWera.lgbnG', 'asdos', '2026-08-09 17:06:27', NULL, NULL),
(3, 'ola', '25082010001', '$2y$10$C7Cbi2D82w9CGeQGYBxz9eeaBPC5tLohkvdJe0SPA3kbn4l9EGB8e', 'asdos', '2026-08-11 14:41:38', 'ola@gmail.com', '08123456789'),
(11, 'halo', '25082010100', '$2y$10$wLqnthPvvwwplRte9Gc.B.e5H77ZhfOTWTyrOjck/wAOM5MU33.Be', 'asdos', '2026-08-11 14:55:57', 'WinNoLimitz@gmail.com', '08123456789'),
(13, 'a', '1', '$2y$10$7k84a0VkzmYZXgBEucxLYew17dMsB1cK9t5UOY4HiQVGP2Ns.75he', 'dosen', '2026-08-11 15:04:51', 'cozuu101@edumail.edu.rs', '123'),
(14, 'aaaa', '25082010111', '$2y$10$ONcGtrQY5xIqAZI1SSrf9exFWJSeY12fyQR8ueOW8HoxGPn0AfZmm', 'dosen', '2026-08-11 15:19:07', '25082010046@student.upnjatim.ac.id', '5555');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `pendaftaran_id` (`pendaftaran_id`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `pendaftaran_kegiatan`
--
ALTER TABLE `pendaftaran_kegiatan`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD UNIQUE KEY `unique_pendaftaran` (`kegiatan_id`,`asdos_id`),
  ADD KEY `asdos_id` (`asdos_id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `FK` (`identity_number`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id_kegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pendaftaran_kegiatan`
--
ALTER TABLE `pendaftaran_kegiatan`
  MODIFY `id_pendaftaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran_kegiatan` (`id_pendaftaran`) ON DELETE CASCADE;

--
-- Constraints for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftaran_kegiatan`
--
ALTER TABLE `pendaftaran_kegiatan`
  ADD CONSTRAINT `pendaftaran_kegiatan_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_kegiatan_ibfk_2` FOREIGN KEY (`asdos_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
