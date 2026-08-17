-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: absensi_lab
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `absensi`
--

DROP TABLE IF EXISTS `absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi` (
  `id_absensi` int NOT NULL AUTO_INCREMENT,
  `pendaftaran_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `pertemuan_ke` int DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `deskripsi_tugas` text NOT NULL,
  `foto_kegiatan` varchar(255) NOT NULL,
  `foto_selfie` varchar(255) NOT NULL,
  `status_verifikasi` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `pesan_dosen` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_absensi`),
  KEY `pendaftaran_id` (`pendaftaran_id`),
  CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran_kegiatan` (`id_pendaftaran`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absensi`
--

LOCK TABLES `absensi` WRITE;
/*!40000 ALTER TABLE `absensi` DISABLE KEYS */;
/*!40000 ALTER TABLE `absensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kegiatan`
--

DROP TABLE IF EXISTS `kegiatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kegiatan` (
  `id_kegiatan` int NOT NULL AUTO_INCREMENT,
  `dosen_id` int NOT NULL,
  `nama_kegiatan` varchar(150) NOT NULL,
  `periode_mulai` date NOT NULL,
  `periode_selesai` date NOT NULL,
  `deskripsi_tugas` text NOT NULL,
  `insentif` varchar(100) NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `kuota` int DEFAULT NULL,
  PRIMARY KEY (`id_kegiatan`),
  KEY `dosen_id` (`dosen_id`),
  CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kegiatan`
--

LOCK TABLES `kegiatan` WRITE;
/*!40000 ALTER TABLE `kegiatan` DISABLE KEYS */;
INSERT INTO `kegiatan` (`id_kegiatan`, `dosen_id`, `nama_kegiatan`, `periode_mulai`, `periode_selesai`, `deskripsi_tugas`, `insentif`, `status`, `created_at`, `kuota`) VALUES (4,13,'PemWeb','2026-08-11','2026-08-14','goodlooking','200000','closed','2026-08-11 15:35:43',2);
/*!40000 ALTER TABLE `kegiatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pendaftaran_kegiatan`
--

DROP TABLE IF EXISTS `pendaftaran_kegiatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendaftaran_kegiatan` (
  `id_pendaftaran` int NOT NULL AUTO_INCREMENT,
  `kegiatan_id` int NOT NULL,
  `asdos_id` int NOT NULL,
  `status_pendaftaran` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `pesan_lamaran` text,
  `catatan_dosen` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pendaftaran`),
  UNIQUE KEY `unique_pendaftaran` (`kegiatan_id`,`asdos_id`),
  KEY `asdos_id` (`asdos_id`) USING BTREE,
  CONSTRAINT `pendaftaran_kegiatan_ibfk_1` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id_kegiatan`) ON DELETE CASCADE,
  CONSTRAINT `pendaftaran_kegiatan_ibfk_2` FOREIGN KEY (`asdos_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendaftaran_kegiatan`
--

LOCK TABLES `pendaftaran_kegiatan` WRITE;
/*!40000 ALTER TABLE `pendaftaran_kegiatan` DISABLE KEYS */;
/*!40000 ALTER TABLE `pendaftaran_kegiatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `identity_number` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('dosen','asdos') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(80) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `FK` (`identity_number`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id_user`, `nama`, `identity_number`, `password`, `role`, `created_at`, `email`, `no_hp`) VALUES (2,'AAN','25082010046','$2y$10$H.RRD/h4c1BVnLSUA5AXROIfNapwRydWPQ5MC44e9RMWera.lgbnG','asdos','2026-08-09 17:06:27',NULL,NULL),(3,'ola','25082010001','$2y$10$C7Cbi2D82w9CGeQGYBxz9eeaBPC5tLohkvdJe0SPA3kbn4l9EGB8e','asdos','2026-08-11 14:41:38','ola@gmail.com','08123456789'),(11,'halo','25082010100','$2y$10$wLqnthPvvwwplRte9Gc.B.e5H77ZhfOTWTyrOjck/wAOM5MU33.Be','asdos','2026-08-11 14:55:57','WinNoLimitz@gmail.com','08123456789'),(13,'a','1','$2y$10$7k84a0VkzmYZXgBEucxLYew17dMsB1cK9t5UOY4HiQVGP2Ns.75he','dosen','2026-08-11 15:04:51','cozuu101@edumail.edu.rs','123'),(14,'aaaa','25082010111','$2y$10$ONcGtrQY5xIqAZI1SSrf9exFWJSeY12fyQR8ueOW8HoxGPn0AfZmm','dosen','2026-08-11 15:19:07','25082010046@student.upnjatim.ac.id','5555');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'absensi_lab'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-11 23:27:28
