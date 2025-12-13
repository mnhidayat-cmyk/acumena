-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 12, 2025 at 06:56 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `acumena`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_pair_filtered`
--

CREATE TABLE `ai_pair_filtered` (
  `id` bigint UNSIGNED NOT NULL,
  `run_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `pair_type` enum('S-O','W-O','S-T','W-T') NOT NULL,
  `left_id` bigint DEFAULT NULL,
  `right_id` bigint DEFAULT NULL,
  `left_text` text,
  `right_text` text,
  `priority` decimal(10,4) DEFAULT NULL,
  `rel` decimal(10,4) DEFAULT NULL,
  `final` decimal(10,4) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ai_pair_filtered`
--

INSERT INTO `ai_pair_filtered` (`id`, `run_id`, `project_id`, `pair_type`, `left_id`, `right_id`, `left_text`, `right_text`, `priority`, `rel`, `final`, `created_at`) VALUES
(1, 3, 3, 'W-T', 40, 45, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'Kompetisi sangat ketat', '0.1200', '0.5000', '0.7000', '2025-11-09 05:24:49'),
(2, 3, 3, 'W-T', 39, 45, 'Skalabilitas produksi masih terbatas', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-09 05:24:49'),
(3, 3, 3, 'W-T', 41, 45, 'bergantung pada brand', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-09 05:24:49'),
(4, 3, 3, 'W-T', 42, 45, 'pemasaran kurang terstruktur', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-09 05:24:49'),
(5, 3, 3, 'W-T', 40, 46, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'Fluktuasi harga bahan baku', '0.0200', '0.5000', '0.3667', '2025-11-09 05:24:49'),
(6, 4, 3, 'S-O', 37, 43, 'Minimum order kecil (mulai dari 6 pcs)', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '1.4400', '0.5000', '0.7000', '2025-11-12 14:32:11'),
(7, 4, 3, 'S-O', 36, 43, 'Harga terjangkau dengan kualitas semi-butik', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.7200', '0.5000', '0.5000', '2025-11-12 14:32:11'),
(8, 4, 3, 'S-O', 37, 44, 'Minimum order kecil (mulai dari 6 pcs)', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.7200', '0.5000', '0.5000', '2025-11-12 14:32:11'),
(9, 4, 3, 'S-O', 38, 43, 'Komunikasi responsif & fleksibel', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.4800', '0.5000', '0.4333', '2025-11-12 14:32:11'),
(10, 4, 3, 'S-O', 36, 44, 'Harga terjangkau dengan kualitas semi-butik', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.3600', '0.5000', '0.4000', '2025-11-12 14:32:11'),
(11, 5, 3, 'S-O', 37, 43, 'Minimum order kecil (mulai dari 6 pcs)', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '1.4400', '0.5000', '0.7000', '2025-11-12 14:36:04'),
(12, 5, 3, 'S-O', 36, 43, 'Harga terjangkau dengan kualitas semi-butik', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.7200', '0.5000', '0.5000', '2025-11-12 14:36:04'),
(13, 5, 3, 'S-O', 37, 44, 'Minimum order kecil (mulai dari 6 pcs)', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.7200', '0.5000', '0.5000', '2025-11-12 14:36:04'),
(14, 5, 3, 'S-O', 38, 43, 'Komunikasi responsif & fleksibel', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.4800', '0.5000', '0.4333', '2025-11-12 14:36:04'),
(15, 5, 3, 'S-O', 36, 44, 'Harga terjangkau dengan kualitas semi-butik', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.3600', '0.5000', '0.4000', '2025-11-12 14:36:04'),
(16, 6, 3, 'S-T', 37, 45, 'Minimum order kecil (mulai dari 6 pcs)', 'Kompetisi sangat ketat', '0.7200', '0.5000', '0.7000', '2025-11-12 14:36:34'),
(17, 6, 3, 'S-T', 36, 45, 'Harga terjangkau dengan kualitas semi-butik', 'Kompetisi sangat ketat', '0.3600', '0.5000', '0.5000', '2025-11-12 14:36:34'),
(18, 6, 3, 'S-T', 38, 45, 'Komunikasi responsif & fleksibel', 'Kompetisi sangat ketat', '0.2400', '0.5000', '0.4333', '2025-11-12 14:36:34'),
(19, 6, 3, 'S-T', 37, 46, 'Minimum order kecil (mulai dari 6 pcs)', 'Fluktuasi harga bahan baku', '0.1200', '0.5000', '0.3667', '2025-11-12 14:36:34'),
(20, 6, 3, 'S-T', 37, 47, 'Minimum order kecil (mulai dari 6 pcs)', 'Margin mudah tergerus jika hanya perang harga', '0.1200', '0.5000', '0.3667', '2025-11-12 14:36:34'),
(21, 7, 3, 'W-O', 40, 43, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.2400', '0.5000', '0.7000', '2025-11-12 14:37:51'),
(22, 7, 3, 'W-O', 39, 43, 'Skalabilitas produksi masih terbatas', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.1200', '0.5000', '0.5000', '2025-11-12 14:37:51'),
(23, 7, 3, 'W-O', 40, 44, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.1200', '0.5000', '0.5000', '2025-11-12 14:37:51'),
(24, 7, 3, 'W-O', 41, 43, 'bergantung pada brand', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.1200', '0.5000', '0.5000', '2025-11-12 14:37:51'),
(25, 7, 3, 'W-O', 42, 43, 'pemasaran kurang terstruktur', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.1200', '0.5000', '0.5000', '2025-11-12 14:37:51'),
(26, 8, 3, 'W-T', 40, 45, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'Kompetisi sangat ketat', '0.1200', '0.5000', '0.7000', '2025-11-12 14:38:40'),
(27, 8, 3, 'W-T', 39, 45, 'Skalabilitas produksi masih terbatas', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-12 14:38:40'),
(28, 8, 3, 'W-T', 41, 45, 'bergantung pada brand', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-12 14:38:40'),
(29, 8, 3, 'W-T', 42, 45, 'pemasaran kurang terstruktur', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-12 14:38:40'),
(30, 8, 3, 'W-T', 40, 46, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'Fluktuasi harga bahan baku', '0.0200', '0.5000', '0.3667', '2025-11-12 14:38:40'),
(31, 9, 3, 'W-T', 40, 45, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'Kompetisi sangat ketat', '0.1200', '0.5000', '0.7000', '2025-11-12 14:40:49'),
(32, 9, 3, 'W-T', 39, 45, 'Skalabilitas produksi masih terbatas', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-12 14:40:49'),
(33, 9, 3, 'W-T', 41, 45, 'bergantung pada brand', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-12 14:40:49'),
(34, 9, 3, 'W-T', 42, 45, 'pemasaran kurang terstruktur', 'Kompetisi sangat ketat', '0.0600', '0.5000', '0.5000', '2025-11-12 14:40:49'),
(35, 9, 3, 'W-T', 40, 46, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'Fluktuasi harga bahan baku', '0.0200', '0.5000', '0.3667', '2025-11-12 14:40:49'),
(36, 10, 3, 'S-T', 37, 45, 'Minimum order kecil (mulai dari 6 pcs)', 'Kompetisi sangat ketat', '0.7200', '0.5000', '0.7000', '2025-11-12 14:50:32'),
(37, 10, 3, 'S-T', 36, 45, 'Harga terjangkau dengan kualitas semi-butik', 'Kompetisi sangat ketat', '0.3600', '0.5000', '0.5000', '2025-11-12 14:50:32'),
(38, 10, 3, 'S-T', 38, 45, 'Komunikasi responsif & fleksibel', 'Kompetisi sangat ketat', '0.2400', '0.5000', '0.4333', '2025-11-12 14:50:32'),
(39, 10, 3, 'S-T', 37, 46, 'Minimum order kecil (mulai dari 6 pcs)', 'Fluktuasi harga bahan baku', '0.1200', '0.5000', '0.3667', '2025-11-12 14:50:32'),
(40, 10, 3, 'S-T', 37, 47, 'Minimum order kecil (mulai dari 6 pcs)', 'Margin mudah tergerus jika hanya perang harga', '0.1200', '0.5000', '0.3667', '2025-11-12 14:50:32'),
(41, 11, 3, 'S-O', 37, 43, 'Minimum order kecil (mulai dari 6 pcs)', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '1.4400', '0.5000', '0.7000', '2025-11-17 08:43:34'),
(42, 11, 3, 'S-O', 36, 43, 'Harga terjangkau dengan kualitas semi-butik', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.7200', '0.5000', '0.5000', '2025-11-17 08:43:34'),
(43, 11, 3, 'S-O', 37, 44, 'Minimum order kecil (mulai dari 6 pcs)', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.7200', '0.5000', '0.5000', '2025-11-17 08:43:34'),
(44, 11, 3, 'S-O', 38, 43, 'Komunikasi responsif & fleksibel', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.4800', '0.5000', '0.4333', '2025-11-17 08:43:34'),
(45, 11, 3, 'S-O', 36, 44, 'Harga terjangkau dengan kualitas semi-butik', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.3600', '0.5000', '0.4000', '2025-11-17 08:43:34'),
(46, 12, 3, 'S-O', 37, 43, 'Minimum order kecil (mulai dari 6 pcs)', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '1.4400', '0.9000', '0.9400', '2025-11-17 08:44:05'),
(47, 12, 3, 'S-O', 36, 43, 'Harga terjangkau dengan kualitas semi-butik', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.7200', '0.9500', '0.7700', '2025-11-17 08:44:05'),
(48, 12, 3, 'S-O', 38, 44, 'Komunikasi responsif & fleksibel', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.2400', '1.0000', '0.6667', '2025-11-17 08:44:05'),
(49, 12, 3, 'S-O', 38, 43, 'Komunikasi responsif & fleksibel', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.4800', '0.8000', '0.6133', '2025-11-17 08:44:05'),
(50, 12, 3, 'S-O', 37, 44, 'Minimum order kecil (mulai dari 6 pcs)', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.7200', '0.6000', '0.5600', '2025-11-17 08:44:05'),
(51, 13, 3, 'S-O', 37, 43, 'Minimum order kecil (mulai dari 6 pcs)', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '1.4400', '0.5000', '0.7000', '2025-11-18 05:34:15'),
(52, 13, 3, 'S-O', 36, 43, 'Harga terjangkau dengan kualitas semi-butik', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.7200', '0.5000', '0.5000', '2025-11-18 05:34:15'),
(53, 13, 3, 'S-O', 37, 44, 'Minimum order kecil (mulai dari 6 pcs)', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.7200', '0.5000', '0.5000', '2025-11-18 05:34:15'),
(54, 13, 3, 'S-O', 38, 43, 'Komunikasi responsif & fleksibel', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.4800', '0.5000', '0.4333', '2025-11-18 05:34:15'),
(55, 13, 3, 'S-O', 36, 44, 'Harga terjangkau dengan kualitas semi-butik', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.3600', '0.5000', '0.4000', '2025-11-18 05:34:15'),
(56, 14, 3, 'S-O', 37, 43, 'Minimum order kecil (mulai dari 6 pcs)', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '1.4400', '0.5000', '0.7000', '2025-11-18 05:37:23'),
(57, 14, 3, 'S-O', 36, 43, 'Harga terjangkau dengan kualitas semi-butik', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.7200', '0.5000', '0.5000', '2025-11-18 05:37:23'),
(58, 14, 3, 'S-O', 37, 44, 'Minimum order kecil (mulai dari 6 pcs)', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.7200', '0.5000', '0.5000', '2025-11-18 05:37:23'),
(59, 14, 3, 'S-O', 38, 43, 'Komunikasi responsif & fleksibel', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.4800', '0.5000', '0.4333', '2025-11-18 05:37:23'),
(60, 14, 3, 'S-O', 36, 44, 'Harga terjangkau dengan kualitas semi-butik', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.3600', '0.5000', '0.4000', '2025-11-18 05:37:23'),
(61, 15, 3, 'S-O', 37, 43, 'Minimum order kecil (mulai dari 6 pcs)', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '1.4400', '0.5000', '0.7000', '2025-11-19 01:26:12'),
(62, 15, 3, 'S-O', 36, 43, 'Harga terjangkau dengan kualitas semi-butik', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.7200', '0.5000', '0.5000', '2025-11-19 01:26:12'),
(63, 15, 3, 'S-O', 37, 44, 'Minimum order kecil (mulai dari 6 pcs)', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.7200', '0.5000', '0.5000', '2025-11-19 01:26:12'),
(64, 15, 3, 'S-O', 38, 43, 'Komunikasi responsif & fleksibel', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.4800', '0.5000', '0.4333', '2025-11-19 01:26:12'),
(65, 15, 3, 'S-O', 36, 44, 'Harga terjangkau dengan kualitas semi-butik', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.3600', '0.5000', '0.4000', '2025-11-19 01:26:12'),
(66, 16, 3, 'S-O', 37, 43, 'Minimum order kecil (mulai dari 6 pcs)', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '1.4400', '0.5000', '0.7000', '2025-12-11 23:14:19'),
(67, 16, 3, 'S-O', 36, 43, 'Harga terjangkau dengan kualitas semi-butik', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.7200', '0.5000', '0.5000', '2025-12-11 23:14:19'),
(68, 16, 3, 'S-O', 37, 44, 'Minimum order kecil (mulai dari 6 pcs)', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.7200', '0.5000', '0.5000', '2025-12-11 23:14:19'),
(69, 16, 3, 'S-O', 38, 43, 'Komunikasi responsif & fleksibel', 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', '0.4800', '0.5000', '0.4333', '2025-12-11 23:14:19'),
(70, 16, 3, 'S-O', 36, 44, 'Harga terjangkau dengan kualitas semi-butik', 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', '0.3600', '0.5000', '0.4000', '2025-12-11 23:14:19'),
(71, 17, 1, 'S-O', 25, 55, 'Customisasi Product', 'Kemungkinan membuka workshop kedua atau tim sales lapangan untuk memperluas cak…', '0.3200', '0.5000', '0.7000', '2025-12-11 23:22:13'),
(72, 17, 1, 'S-O', 31, 55, 'Jumlah minimal order rendah (12pcs)', 'Kemungkinan membuka workshop kedua atau tim sales lapangan untuk memperluas cak…', '0.1600', '0.5000', '0.5000', '2025-12-11 23:22:13'),
(73, 17, 1, 'S-O', 32, 55, 'Pekerja Berpengalaman', 'Kemungkinan membuka workshop kedua atau tim sales lapangan untuk memperluas cak…', '0.1600', '0.5000', '0.5000', '2025-12-11 23:22:13'),
(74, 17, 1, 'S-O', 33, 55, 'Proses produksi dilakukan di satu lokasi terpusat', 'Kemungkinan membuka workshop kedua atau tim sales lapangan untuk memperluas cak…', '0.1600', '0.5000', '0.5000', '2025-12-11 23:22:13'),
(75, 17, 1, 'S-O', 25, 52, 'Customisasi Product', 'Pemanfaatan AI & otomasi (n8n + OpenAI) bisa mengurangi biaya operasional dan m…', '0.1200', '0.5000', '0.4500', '2025-12-11 23:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `ai_strategy`
--

CREATE TABLE `ai_strategy` (
  `id` bigint UNSIGNED NOT NULL,
  `run_id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `pair_type` enum('S-O','W-O','S-T','W-T') COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statement` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority_score` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ai_strategy`
--

INSERT INTO `ai_strategy` (`id`, `run_id`, `project_id`, `pair_type`, `code`, `statement`, `priority_score`, `created_at`, `is_active`) VALUES
(27, 3, 3, 'W-T', 'WT1', 'Tingkatkan sistem penagihan untuk mempercepat pembayaran dari pelanggan.', '0.7000', '2025-11-12 14:20:59', 1),
(28, 3, 3, 'W-T', 'WT2', 'Diversifikasi produk untuk mengurangi ketergantungan pada satu brand.', '0.5000', '2025-11-12 14:20:59', 1),
(29, 3, 3, 'W-T', 'WT3', 'Kembangkan strategi pemasaran yang lebih terstruktur dan terarah.', '0.5000', '2025-11-12 14:20:59', 1),
(30, 3, 3, 'W-T', 'WT4', 'Tingkatkan efisiensi produksi untuk mendukung skalabilitas.', '0.5000', '2025-11-12 14:20:59', 1),
(31, 3, 3, 'W-T', 'WT5', 'Buat perjanjian jangka panjang dengan pemasok untuk stabilitas harga.', '0.3670', '2025-11-12 14:20:59', 1),
(32, 3, 3, 'W-T', 'WT6', 'Lakukan analisis pasar secara rutin untuk memahami kompetisi.', '0.3000', '2025-11-12 14:20:59', 1),
(33, 4, 3, 'S-O', 'SO1', 'Tawarkan minimum order kecil untuk mendukung brand fashion lokal.', '0.7000', '2025-11-12 14:32:41', 1),
(34, 4, 3, 'S-O', 'SO2', 'Jaga harga terjangkau untuk menarik UMKM yang berkembang.', '0.5000', '2025-11-12 14:32:41', 1),
(35, 4, 3, 'S-O', 'SO3', 'Sediakan minimum order kecil untuk vendor CMT yang amanah.', '0.5000', '2025-11-12 14:32:41', 1),
(36, 4, 3, 'S-O', 'SO4', 'Tingkatkan komunikasi responsif untuk mendukung pertumbuhan brand lokal.', '0.4330', '2025-11-12 14:32:41', 1),
(37, 4, 3, 'S-O', 'SO5', 'Tawarkan kualitas semi-butik dengan harga terjangkau untuk UMKM.', '0.4000', '2025-11-12 14:32:41', 1),
(38, 4, 3, 'S-O', 'SO6', 'Fokus pada pelayanan yang mudah dan komunikasi yang jelas.', '0.3000', '2025-11-12 14:32:41', 1),
(39, 5, 3, 'S-O', 'SO1', 'Manfaatkan minimum order kecil untuk menarik brand fashion lokal.', '0.7000', '2025-11-12 14:36:15', 1),
(40, 5, 3, 'S-O', 'SO2', 'Tawarkan harga terjangkau untuk mendukung UMKM yang berkembang.', '0.5000', '2025-11-12 14:36:15', 1),
(41, 5, 3, 'S-O', 'SO3', 'Fasilitasi komunikasi yang mudah untuk vendor CMT.', '0.5000', '2025-11-12 14:36:15', 1),
(42, 5, 3, 'S-O', 'SO4', 'Tingkatkan responsivitas untuk menarik brand fashion lokal.', '0.4330', '2025-11-12 14:36:15', 1),
(43, 5, 3, 'S-O', 'SO5', 'Gabungkan kualitas semi-butik dengan harga terjangkau.', '0.4000', '2025-11-12 14:36:15', 1),
(44, 5, 3, 'S-O', 'SO6', 'Ciptakan program loyalitas untuk brand fashion lokal.', '0.3000', '2025-11-12 14:36:15', 1),
(45, 6, 3, 'S-T', 'SO1', 'Tawarkan minimum order kecil untuk menarik pelanggan baru.', '0.7000', '2025-11-12 14:36:41', 1),
(46, 6, 3, 'S-T', 'SO2', 'Pertahankan harga terjangkau sambil menjaga kualitas produk.', '0.5000', '2025-11-12 14:36:41', 1),
(47, 6, 3, 'S-T', 'SO3', 'Tingkatkan komunikasi yang responsif untuk membangun kepercayaan.', '0.4330', '2025-11-12 14:36:41', 1),
(48, 6, 3, 'S-T', 'SO4', 'Diversifikasi sumber bahan baku untuk mengurangi fluktuasi harga.', '0.3670', '2025-11-12 14:36:41', 1),
(49, 6, 3, 'S-T', 'SO5', 'Fokus pada nilai tambah produk untuk menghindari perang harga.', '0.3670', '2025-11-12 14:36:41', 1),
(50, 6, 3, 'S-T', 'SO6', 'Kembangkan program loyalitas untuk mempertahankan pelanggan.', '0.3000', '2025-11-12 14:36:41', 1),
(51, 7, 3, 'W-O', 'WO1', 'Tingkatkan sistem pembayaran untuk mempercepat cash flow.', '0.7000', '2025-11-12 14:37:59', 1),
(52, 7, 3, 'W-O', 'WO2', 'Kembangkan kapasitas produksi untuk memenuhi permintaan pasar.', '0.5000', '2025-11-12 14:37:59', 1),
(53, 7, 3, 'W-O', 'WO3', 'Tawarkan layanan komunikasi yang lebih baik kepada pelanggan.', '0.5000', '2025-11-12 14:37:59', 1),
(54, 7, 3, 'W-O', 'WO4', 'Diversifikasi produk untuk mengurangi ketergantungan pada brand.', '0.5000', '2025-11-12 14:37:59', 1),
(55, 7, 3, 'W-O', 'WO5', 'Buat rencana pemasaran yang lebih terstruktur dan efektif.', '0.5000', '2025-11-12 14:37:59', 1),
(56, 7, 3, 'W-O', 'WO6', 'Lakukan pelatihan untuk meningkatkan manajemen cash flow.', '0.5000', '2025-11-12 14:37:59', 1),
(57, 8, 3, 'W-T', 'WT1', 'Tingkatkan sistem penagihan untuk mempercepat pembayaran pelanggan.', '0.7000', '2025-11-12 14:38:48', 1),
(58, 8, 3, 'W-T', 'WT2', 'Diversifikasi produk untuk mengurangi ketergantungan pada satu brand.', '0.5000', '2025-11-12 14:38:48', 1),
(59, 8, 3, 'W-T', 'WT3', 'Implementasikan sistem manajemen produksi yang lebih efisien.', '0.5000', '2025-11-12 14:38:48', 1),
(60, 8, 3, 'W-T', 'WT4', 'Kembangkan strategi pemasaran yang lebih terstruktur dan terarah.', '0.5000', '2025-11-12 14:38:48', 1),
(61, 8, 3, 'W-T', 'WT5', 'Negosiasikan kontrak jangka panjang dengan pemasok untuk stabilitas harga.', '0.3670', '2025-11-12 14:38:48', 1),
(62, 8, 3, 'W-T', 'WT6', 'Buat cadangan kas untuk mengatasi fluktuasi cash flow.', '0.3000', '2025-11-12 14:38:48', 1),
(63, 9, 3, 'W-T', 'WT1', 'Tingkatkan sistem penagihan untuk mempercepat pembayaran pelanggan.', '0.7000', '2025-11-12 14:40:57', 1),
(64, 9, 3, 'W-T', 'WT2', 'Diversifikasi produk untuk mengurangi ketergantungan pada satu brand.', '0.5000', '2025-11-12 14:40:57', 1),
(65, 9, 3, 'W-T', 'WT3', 'Terapkan sistem manajemen persediaan yang lebih efisien.', '0.5000', '2025-11-12 14:40:57', 1),
(66, 9, 3, 'W-T', 'WT4', 'Kembangkan strategi pemasaran yang lebih terstruktur dan terarah.', '0.5000', '2025-11-12 14:40:57', 1),
(67, 9, 3, 'W-T', 'WT5', 'Buat perjanjian jangka panjang dengan pemasok untuk stabilitas harga.', '0.3670', '2025-11-12 14:40:57', 1),
(68, 9, 3, 'W-T', 'WT6', 'Lakukan analisis pasar secara rutin untuk memahami kompetisi.', '0.3000', '2025-11-12 14:40:57', 1),
(69, 10, 3, 'S-T', 'SO1', 'Tawarkan minimum order kecil untuk menarik pelanggan baru.', '0.7000', '2025-11-12 14:50:40', 1),
(70, 10, 3, 'S-T', 'SO2', 'Jaga harga terjangkau sambil mempertahankan kualitas produk.', '0.5000', '2025-11-12 14:50:40', 1),
(71, 10, 3, 'S-T', 'SO3', 'Tingkatkan komunikasi responsif untuk membangun kepercayaan pelanggan.', '0.4330', '2025-11-12 14:50:40', 1),
(72, 10, 3, 'S-T', 'SO4', 'Diversifikasi pemasok untuk mengurangi dampak fluktuasi harga.', '0.3670', '2025-11-12 14:50:40', 1),
(73, 10, 3, 'S-T', 'SO5', 'Tawarkan nilai tambah untuk menghindari perang harga.', '0.3670', '2025-11-12 14:50:40', 1),
(74, 10, 3, 'S-T', 'SO6', 'Fokus pada pelayanan pelanggan untuk membedakan dari kompetitor.', '0.3000', '2025-11-12 14:50:40', 1),
(75, 14, 3, 'S-O', 'SO1', 'Tawarkan minimum order kecil untuk mendukung brand fashion lokal.', '0.7000', '2025-11-18 05:37:30', 1),
(76, 14, 3, 'S-O', 'SO2', 'Jaga harga terjangkau untuk menarik UMKM yang berkembang.', '0.5000', '2025-11-18 05:37:30', 1),
(77, 14, 3, 'S-O', 'SO3', 'Sediakan minimum order kecil untuk vendor CMT yang amanah.', '0.5000', '2025-11-18 05:37:30', 1),
(78, 14, 3, 'S-O', 'SO4', 'Tingkatkan komunikasi responsif untuk mendukung pertumbuhan UMKM.', '0.4330', '2025-11-18 05:37:30', 1),
(79, 14, 3, 'S-O', 'SO5', 'Tawarkan kualitas semi-butik dengan harga terjangkau untuk vendor CMT.', '0.4000', '2025-11-18 05:37:30', 1),
(80, 14, 3, 'S-O', 'SO6', 'Fokus pada pelayanan pelanggan untuk membangun kepercayaan.', '0.3000', '2025-11-18 05:37:30', 1),
(81, 15, 3, 'S-O', 'SO1', 'Manfaatkan minimum order kecil untuk mendukung brand fashion lokal.', '0.7000', '2025-11-19 01:26:19', 1),
(82, 15, 3, 'S-O', 'SO2', 'Tawarkan harga terjangkau untuk menarik UMKM yang berkembang.', '0.5000', '2025-11-19 01:26:19', 1),
(83, 15, 3, 'S-O', 'SO3', 'Gunakan minimum order kecil untuk menarik konsumen yang mencari vendor CMT.', '0.5000', '2025-11-19 01:26:19', 1),
(84, 15, 3, 'S-O', 'SO4', 'Tingkatkan komunikasi responsif untuk mendukung pertumbuhan brand lokal.', '0.4330', '2025-11-19 01:26:19', 1),
(85, 15, 3, 'S-O', 'SO5', 'Tawarkan kualitas semi-butik dengan harga terjangkau untuk UMKM.', '0.4000', '2025-11-19 01:26:19', 1),
(86, 15, 3, 'S-O', 'SO6', 'Fokus pada komunikasi yang mudah untuk menarik lebih banyak klien.', '0.3000', '2025-11-19 01:26:19', 1),
(87, 16, 3, 'S-O', 'SO1', 'Manfaatkan minimum order kecil untuk menarik brand fashion lokal.', '0.7000', '2025-12-11 23:14:22', 1),
(88, 16, 3, 'S-O', 'SO2', 'Tawarkan harga terjangkau untuk mendukung UMKM yang berkembang.', '0.5000', '2025-12-11 23:14:22', 1),
(89, 16, 3, 'S-O', 'SO3', 'Sediakan minimum order kecil untuk vendor CMT yang amanah.', '0.5000', '2025-12-11 23:14:22', 1),
(90, 16, 3, 'S-O', 'SO4', 'Tingkatkan komunikasi responsif untuk menarik brand fashion lokal.', '0.4330', '2025-12-11 23:14:22', 1),
(91, 16, 3, 'S-O', 'SO5', 'Tawarkan kualitas semi-butik dengan harga terjangkau untuk UMKM.', '0.4000', '2025-12-11 23:14:22', 1),
(92, 16, 3, 'S-O', 'SO6', 'Fokus pada komunikasi yang fleksibel untuk membangun kepercayaan.', '0.3000', '2025-12-11 23:14:22', 1),
(93, 17, 1, 'S-O', 'SO1', 'Manfaatkan customisasi produk untuk menarik lebih banyak pelanggan di workshop kedua.', '0.7000', '2025-12-11 23:22:17', 1),
(94, 17, 1, 'S-O', 'SO2', 'Tawarkan jumlah minimal order rendah untuk meningkatkan penjualan di workshop kedua.', '0.5000', '2025-12-11 23:22:17', 1),
(95, 17, 1, 'S-O', 'SO3', 'Gunakan pekerja berpengalaman untuk meningkatkan kualitas layanan di workshop kedua.', '0.5000', '2025-12-11 23:22:17', 1),
(96, 17, 1, 'S-O', 'SO4', 'Optimalkan proses produksi terpusat untuk mendukung ekspansi ke workshop kedua.', '0.5000', '2025-12-11 23:22:17', 1),
(97, 17, 1, 'S-O', 'SO5', 'Implementasikan AI untuk mengurangi biaya operasional dan meningkatkan efisiensi.', '0.4500', '2025-12-11 23:22:17', 1),
(98, 17, 1, 'S-O', 'SO6', 'Kembangkan tim sales lapangan untuk memperluas jangkauan pasar secara efektif.', '0.4000', '2025-12-11 23:22:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `matrix_ie_quadrant_strategies`
--

CREATE TABLE `matrix_ie_quadrant_strategies` (
  `id` int NOT NULL,
  `quadrant` varchar(5) NOT NULL DEFAULT '1',
  `strategy` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `matrix_ie_quadrant_strategies`
--

INSERT INTO `matrix_ie_quadrant_strategies` (`id`, `quadrant`, `strategy`, `date_created`, `last_update`, `is_deleted`) VALUES
(1, 'I', 'Prioritaskan strategi SO (Strength–Opportunity).', '2025-11-04 13:02:38', '2025-11-04 13:35:15', NULL),
(2, 'I', 'Gunakan kekuatan untuk menangkap peluang pasar dan melakukan ekspansi agresif.', '2025-11-04 13:02:38', '2025-11-04 13:35:18', NULL),
(3, 'I', 'Perkuat posisi kompetitif melalui inovasi, peningkatan kapasitas, dan pengembangan pasar.', '2025-11-04 13:03:07', '2025-11-04 13:35:21', NULL),
(4, 'II', 'Prioritaskan strategi SO dengan dukungan ST.', '2025-11-04 13:03:07', '2025-11-04 13:36:30', NULL),
(5, 'II', 'Dorong pertumbuhan melalui pemanfaatan peluang sambil menjaga keunggulan kompetitif.', '2025-11-04 13:03:29', '2025-11-04 13:36:32', NULL),
(6, 'II', 'Investasi pemasaran dan peningkatan efisiensi untuk menghadapi rivalitas pasar.', '2025-11-04 13:03:29', '2025-11-04 13:36:34', NULL),
(7, 'III', 'Kombinasikan strategi WO dan ST secara seimbang.', '2025-11-04 13:04:03', '2025-11-04 13:36:37', NULL),
(8, 'III', 'Pertahankan pasar saat ini sambil meningkatkan sistem internal.', '2025-11-04 13:04:03', '2025-11-04 13:36:40', NULL),
(9, 'III', 'Optimalkan kualitas layanan dan efisiensi operasional.', '2025-11-04 13:04:25', '2025-11-04 13:36:42', NULL),
(10, 'IV', 'Prioritaskan strategi SO.', '2025-11-04 13:04:25', '2025-11-04 13:36:05', NULL),
(11, 'IV', 'Manfaatkan kekuatan internal untuk memperbesar peluang yang ada di segmen tertentu.', '2025-11-04 13:04:50', '2025-11-04 13:36:03', NULL),
(12, 'IV', 'Penetrasi pasar dan inovasi produk untuk mengakselerasi pertumbuhan.', '2025-11-04 13:04:50', '2025-11-04 13:36:01', NULL),
(13, 'V', 'Kombinasikan strategi WO dan ST.', '2025-11-04 13:05:20', '2025-11-04 13:35:32', NULL),
(14, 'V', 'Pertahankan market share sambil memperbaiki kelemahan proses dan manajemen.', '2025-11-04 13:05:20', '2025-11-04 13:35:29', NULL),
(15, 'V', 'Tingkatkan loyalitas pelanggan dan perbaiki efisiensi biaya.', '2025-11-04 13:05:38', '2025-11-04 13:35:34', NULL),
(16, 'VI', 'Prioritaskan strategi WT.', '2025-11-04 13:06:04', '2025-11-04 13:36:10', NULL),
(17, 'VI', 'Minimalkan kerugian dan fokus hanya pada unit yang masih menghasilkan.', '2025-11-04 13:06:04', '2025-11-04 13:36:12', NULL),
(18, 'VI', 'Pertimbangkan pemangkasan biaya, restrukturisasi, atau penghentian produk tidak profit.', '2025-11-04 13:06:25', '2025-11-04 13:36:14', NULL),
(19, 'VII', 'Dominan strategi WO, dibantu WT jika diperlukan.', '2025-11-04 13:06:25', '2025-11-04 13:36:17', NULL),
(20, 'VII', 'Tingkatkan kapasitas internal agar lebih kompetitif sebelum mengejar peluang.', '2025-11-04 13:06:45', '2025-11-04 13:36:23', NULL),
(21, 'VII', 'Atur ulang prioritas sumber daya untuk memperkuat fondasi bisnis.', '2025-11-04 13:06:45', '2025-11-04 13:36:24', NULL),
(22, 'VIII', 'Prioritaskan strategi WT.', '2025-11-04 13:07:04', '2025-11-04 13:36:52', NULL),
(23, 'VIII', 'Kurangi investasi dan amankan sumber daya inti dari risiko eksternal.', '2025-11-04 13:07:04', '2025-11-04 13:36:55', NULL),
(24, 'VIII', 'Fokus pada pengurangan kerugian, downsizing, atau pivot jika memungkinkan.', '2025-11-04 13:07:27', '2025-11-04 13:37:10', NULL),
(25, 'IX', 'Strategi utama WT secara agresif.', '2025-11-04 13:07:27', '2025-11-04 13:37:17', NULL),
(26, 'IX', 'Pertimbangkan program keluar (exit strategy) pada produk atau divisi yang paling lemah.', '2025-11-04 13:07:47', '2025-11-04 13:37:23', NULL),
(27, 'IX', 'Alokasikan ulang modal hanya ke bagian bisnis dengan potensi bertahan.', '2025-11-04 13:07:47', '2025-11-04 13:37:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `industry` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text,
  `vision` text,
  `mission` text,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `uuid`, `user_id`, `company_name`, `industry`, `description`, `vision`, `mission`, `date_created`, `last_update`, `is_deleted`) VALUES
(1, 'eaf48476-e75b-4edf-a7b0-1c421d6c5e58', 1, 'Sevencols', 'Garment', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'Menjadi konveksi terpercaya di Indonesia yang menghadirkan produk apparel berkualitas, tepat waktu, dan ramah pelanggan, dengan layanan yang cepat, mudah, dan hasil yang selalu konsisten.', 'Menghadirkan Produk Berkualitas Tinggi\nMenggunakan bahan terbaik, teknik jahit rapi, serta standar kualitas yang konsisten untuk setiap pesanan, baik dalam jumlah kecil maupun besar.\n\nMenyediakan Layanan Desain Gratis & Profesional\nMembantu pelanggan mewujudkan identitas brand, komunitas, atau tim melalui layanan desain yang kreatif dan responsif.\n\nMembangun Pengalaman Pelanggan yang Nyaman\nMemberikan proses pemesanan yang mudah, komunikasi cepat, dan layanan ramah agar setiap pelanggan merasa terbantu dari awal hingga pesanan selesai.\n\nTepat Waktu Dalam Produksi & Pengiriman\nMengoptimalkan proses produksi dan manajemen waktu agar semua pesanan selesai sesuai jadwal yang disepakati.\n\nMengembangkan Tim yang Terampil & Inovatif\nMenumbuhkan SDM yang kompeten, kreatif, dan selalu memperbarui keterampilan di dunia produksi dan desain.\n\nMendukung Komunitas, UMKM, dan Pelaku Brand Lokal\nMenjadi partner terpercaya dalam pembuatan merchandise, seragam, jersey, ataupun apparel untuk kegiatan bisnis, organisasi, sekolah, dan komunitas.\n\nMenjaga Kejujuran & Profesionalitas\nMengedepankan transparansi harga, keterbukaan informasi, dan komunikasi yang jelas dalam setiap tahap kerja.', '2025-10-24 23:25:49', '2025-12-11 16:16:28', NULL),
(2, '9b0fbd55-667a-4e90-a320-d8b9acc12bde', 1, 'Bright Site', 'Technology', 'BrightSite adalah perusahaan pengembang website dan aplikasi yang berfokus pada penyediaan solusi digital untuk mendukung efisiensi, produktivitas, dan pertumbuhan bisnis.Kami percaya bahwa teknologi bukan hanya alat, tetapi fondasi penting untuk membangun daya saing di era digital.\n\nDengan tim yang berpengalaman di bidang desain, pengembangan sistem, dan transformasi digital, BrightSite menghadirkan layanan yang menggabungkan kreativitas, fungsionalitas, dan hasil nyata bagi klien kami.', 'Menjadi mitra digital terpercaya bagi bisnis dan organisasi yang ingin bertransformasi melalui teknologi.', '- Menghadirkan solusi digital yang efektif, aman, dan mudah digunakan.\n- Meningkatkan efisiensi bisnis melalui pengembangan sistem berbasis web dan aplikasi.\n- Memberikan layanan konsultatif dan kolaboratif untuk memahami kebutuhan klien secara mendalam.\n- Menjaga kualitas, ketepatan waktu, dan inovasi dalam setiap proyek.', '2025-10-28 05:26:05', '2025-11-17 22:33:07', NULL),
(3, '09e4261e-1672-41f4-aaeb-eaf253331889', 1, 'Pengen Jahit', 'Konveksi', 'Sebuah pergerakan kecil dari ujung timur Malang, dengan mimpi besar untuk tumbuh bersama. Dari ruang-ruang sederhana dan tangan-tangan perempuan yang terampil,\nkami menghadirkan karya yang bukan sekadar pakaian - tapi wujud dari ketulusan, kolaborasi, dan harapan untuk bertumbuh bersama para pelaku usaha di seluruh Indonesia.', 'Menjadi mitra produksi terpercaya bagi brand-brand lokal Indonesia, dengan menghadirkan hasil jahitan berkualitas dan memberikan dampak nyata bagi pemberdayaan perempuan di sekitar kami.', '- Memberikan layanan CMT (Cut, Make, Trim) dengan kualitas butik namun harga terjangkau.\n- Membantu brand lokal tumbuh dan berkembang bersama kami.\n- Memberdayakan perempuan di lingkungan sekitar melalui lapangan kerja dan pelatihan keterampilan menjahit.\n- Menjaga konsistensi, ketepatan waktu, dan detail dalam setiap hasil produksi.', '2025-10-28 05:30:20', '2025-12-11 16:27:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_ai_generation_run`
--

CREATE TABLE `project_ai_generation_run` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `pair_type` enum('S-O','W-O','S-T','W-T') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jenis pasangan SWOT',
  `stage` enum('initialized','semantic_done','strategy_done','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'initialized' COMMENT 'Status proses',
  `model` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model AI yang digunakan (opsional)',
  `temperature` decimal(4,2) DEFAULT NULL COMMENT 'Temperature AI (opsional)',
  `max_output_tokens` int DEFAULT NULL COMMENT 'Batas token output (opsional)',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = tidak aktif (sudah diganti run baru)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archived_at` datetime DEFAULT NULL COMMENT 'Menandai kapan run ini digantikan oleh run baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_ai_generation_run`
--

INSERT INTO `project_ai_generation_run` (`id`, `project_id`, `pair_type`, `stage`, `model`, `temperature`, `max_output_tokens`, `is_active`, `created_at`, `archived_at`) VALUES
(1, 3, 'W-T', 'initialized', 'gemini-1.5-flash', '0.20', 1200, 0, '2025-11-09 04:22:13', '2025-11-09 05:19:49'),
(2, 3, 'W-T', 'initialized', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-09 05:19:49', '2025-11-09 05:19:56'),
(3, 3, 'W-T', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-09 05:19:56', '2025-11-12 14:38:32'),
(4, 3, 'S-O', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-12 14:31:58', '2025-11-12 14:35:56'),
(5, 3, 'S-O', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-12 14:35:56', '2025-11-17 08:43:22'),
(6, 3, 'S-T', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-12 14:36:27', '2025-11-12 14:50:25'),
(7, 3, 'W-O', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 1, '2025-11-12 14:37:44', NULL),
(8, 3, 'W-T', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-12 14:38:32', '2025-11-12 14:40:33'),
(9, 3, 'W-T', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 1, '2025-11-12 14:40:33', NULL),
(10, 3, 'S-T', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 1, '2025-11-12 14:50:25', NULL),
(11, 3, 'S-O', 'failed', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-17 08:43:22', '2025-11-17 08:43:59'),
(12, 3, 'S-O', 'failed', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-17 08:43:59', '2025-11-18 05:34:02'),
(13, 3, 'S-O', 'failed', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-18 05:34:02', '2025-11-18 05:37:13'),
(14, 3, 'S-O', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-18 05:37:13', '2025-11-19 01:26:01'),
(15, 3, 'S-O', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 0, '2025-11-19 01:26:01', '2025-12-11 23:14:09'),
(16, 3, 'S-O', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 1, '2025-12-11 23:14:09', NULL),
(17, 1, 'S-O', 'strategy_done', 'gemini-1.5-flash', '0.20', 1000, 1, '2025-12-11 23:22:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_prioritized_strategies`
--

CREATE TABLE `project_prioritized_strategies` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Auto-increment ID',
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'UUID v4 untuk API reference',
  `project_id` bigint UNSIGNED NOT NULL COMMENT 'Link ke projects table',
  `ai_strategy_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Link ke ai_strategy table (nullable)',
  `created_by_user_id` bigint UNSIGNED NOT NULL COMMENT 'User yang create/save',
  `pair_type` enum('S-O','W-O','S-T','W-T') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tipe pasangan SWOT',
  `strategy_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Kode short: SO1, ST2, WO3, WT4',
  `strategy_statement` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Isi strategi lengkap',
  `priority_rank` int NOT NULL DEFAULT '1' COMMENT 'Urutan prioritas (1=tertinggi)',
  `priority_score` decimal(5,4) DEFAULT NULL COMMENT 'Score dari AI (0.0000-1.0000)',
  `status` enum('draft','approved','in_progress','completed','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft' COMMENT 'Status eksekusi strategi',
  `selected_by_user` tinyint(1) DEFAULT '0' COMMENT 'User explicitly pilih?',
  `selection_justification` text COLLATE utf8mb4_unicode_ci COMMENT 'Alasan user memilih strategy ini',
  `internal_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes internal untuk tim',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu dibuat',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Terakhir diupdate',
  `is_deleted` timestamp NULL DEFAULT NULL COMMENT 'Soft delete timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel untuk menyimpan prioritized strategies per project';

-- --------------------------------------------------------

--
-- Table structure for table `project_swot`
--

CREATE TABLE `project_swot` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `category` enum('S','W','O','T') NOT NULL,
  `weight` decimal(4,2) DEFAULT NULL,
  `rating` tinyint DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_swot`
--

INSERT INTO `project_swot` (`id`, `project_id`, `description`, `category`, `weight`, `rating`, `date_created`, `last_update`, `is_deleted`) VALUES
(25, 1, 'Customisasi Product', 'S', '0.20', 4, '2025-10-25 01:19:37', '2025-12-11 16:19:40', NULL),
(26, 1, 'S2', 'S', NULL, NULL, '2025-10-25 01:19:37', '2025-10-25 08:20:02', '2025-10-25 01:20:02'),
(27, 1, 'Marketing kurang terstruktur dan konsisten', 'W', '0.10', 1, '2025-10-25 01:19:37', '2025-12-11 16:19:40', NULL),
(28, 1, 'Tender Perusahaan dan Instansi', 'O', '0.10', 1, '2025-10-25 01:19:37', '2025-12-11 16:21:48', NULL),
(29, 1, 'banyak bisnis serupa bermunculan', 'T', '0.05', 2, '2025-10-25 01:19:37', '2025-12-11 16:21:48', NULL),
(30, 1, 'S234', 'S', NULL, NULL, '2025-10-25 01:20:02', '2025-10-25 08:24:27', '2025-10-25 01:24:27'),
(31, 1, 'Jumlah minimal order rendah (12pcs)', 'S', '0.10', 4, '2025-10-25 22:55:20', '2025-12-11 16:19:40', NULL),
(32, 1, 'Pekerja Berpengalaman', 'S', '0.10', 4, '2025-10-25 22:55:20', '2025-12-11 16:19:40', NULL),
(33, 1, 'Proses produksi dilakukan di satu lokasi terpusat', 'S', '0.10', 4, '2025-10-25 22:55:20', '2025-12-11 16:19:40', NULL),
(34, 1, 'Perubahan tren fashion cepat dan preferensi pelanggan dinamis.', 'T', '0.03', 1, '2025-10-25 22:55:20', '2025-12-11 16:21:48', NULL),
(35, 1, 'Sebagian besar pekerja sudah memasuki  usia senja', 'T', '0.10', 1, '2025-10-25 22:55:20', '2025-12-11 16:21:48', NULL),
(36, 3, 'Harga terjangkau dengan kualitas semi-butik', 'S', '0.20', 3, '2025-10-29 06:17:00', '2025-12-11 16:27:47', NULL),
(37, 3, 'Minimum order kecil (mulai dari 6 pcs)', 'S', '0.30', 4, '2025-10-29 06:17:00', '2025-12-11 16:27:47', NULL),
(38, 3, 'Komunikasi responsif & fleksibel', 'S', '0.10', 4, '2025-10-29 06:17:00', '2025-12-11 16:27:47', NULL),
(39, 3, 'Skalabilitas produksi masih terbatas', 'W', '0.10', 1, '2025-10-29 06:17:00', '2025-12-11 16:27:47', NULL),
(40, 3, 'Cash flow berpotensi terganggu bila pembayaran pelanggan terlambat', 'W', '0.10', 2, '2025-10-29 06:17:00', '2025-12-11 16:27:47', NULL),
(41, 3, 'bergantung pada brand', 'W', '0.10', 1, '2025-10-29 06:17:00', '2025-12-11 16:27:47', NULL),
(42, 3, 'pemasaran kurang terstruktur', 'W', '0.10', 1, '2025-10-29 06:17:00', '2025-12-11 16:27:47', NULL),
(43, 3, 'Pertumbuhan brand fashion lokal & UMKM meningkat pesat', 'O', '0.30', 4, '2025-10-29 06:17:00', '2025-12-11 16:27:49', NULL),
(44, 3, 'Konsumen mencari vendor CMT yg amanah & mudah komunikasi', 'O', '0.20', 3, '2025-10-29 06:17:00', '2025-12-11 16:27:49', NULL),
(45, 3, 'Kompetisi sangat ketat', 'T', '0.30', 2, '2025-10-29 06:17:00', '2025-12-11 16:27:49', NULL),
(46, 3, 'Fluktuasi harga bahan baku', 'T', '0.10', 1, '2025-10-29 06:17:00', '2025-12-11 16:27:49', NULL),
(47, 3, 'Margin mudah tergerus jika hanya perang harga', 'T', '0.10', 1, '2025-10-29 06:17:00', '2025-12-11 16:27:49', NULL),
(48, 1, 'Skala produksi belum sebesar pabrik besar, sehingga kapasitas bisa terbatas saat peak season.', 'W', '0.10', 1, '2025-12-11 16:18:38', '2025-12-11 16:19:40', NULL),
(49, 1, 'Ketergantungan pada pemasok bahan, sehingga jika harga naik atau stok kosong dapat menghambat produksi.', 'W', '0.10', 2, '2025-12-11 16:18:38', '2025-12-11 16:19:40', NULL),
(50, 1, 'Brand awareness masih dalam tahap berkembang, perlu strategi marketing yang lebih agresif.', 'W', '0.10', 1, '2025-12-11 16:18:38', '2025-12-11 16:19:40', NULL),
(51, 1, 'Workflow internal mungkin masih manual (meski sedang dikembangkan AI & otomasi).', 'W', '0.10', 2, '2025-12-11 16:18:38', '2025-12-11 16:19:40', NULL),
(52, 1, 'Pemanfaatan AI & otomasi (n8n + OpenAI) bisa mengurangi biaya operasional dan meningkatkan kecepatan layanan.', 'O', '0.05', 3, '2025-12-11 16:18:38', '2025-12-11 16:21:48', NULL),
(53, 1, 'Perluasan kemitraan dengan sekolah, kampus, event organizer, dan UMKM sebagai pelanggan langganan.', 'O', '0.05', 3, '2025-12-11 16:18:38', '2025-12-11 16:21:48', NULL),
(54, 1, 'Pemasaran digital (TikTok, Instagram, Google Business) memiliki potensi besar untuk menjaring 100.000+ leads.', 'O', '0.02', 3, '2025-12-11 16:18:38', '2025-12-11 16:21:48', NULL),
(55, 1, 'Kemungkinan membuka workshop kedua atau tim sales lapangan untuk memperluas cakupan wilayah.', 'O', '0.10', 4, '2025-12-11 16:18:38', '2025-12-11 16:21:48', NULL),
(56, 1, 'Ketergantungan pada teknologi printing, jika mesin mengalami gangguan produksi bisa terhambat.', 'T', '0.10', 1, '2025-12-11 16:18:38', '2025-12-11 16:21:48', NULL),
(57, 1, 'Komplain kualitas/ketidaksesuaian desain jika tidak ada SOP QC yang konsisten.', 'T', '0.20', 1, '2025-12-11 16:18:38', '2025-12-11 16:21:48', NULL),
(58, 1, 'Pelanggan sensitif terhadap harga, terutama di segmen komunitas dan event organizer.', 'T', '0.20', 1, '2025-12-11 16:18:38', '2025-12-11 16:21:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'so_prompt', NULL),
(2, 'st_prompt', NULL),
(3, 'wo_prompt', NULL),
(4, 'wt_prompt', NULL),
(5, 'ie_prompt', NULL),
(6, 'default_subscription_id', '1'),
(7, 'user_register_otp_expired', '240'),
(8, 'email_from', 'no-reply@acumena.my.id'),
(9, 'email_from_name', 'Acumena'),
(10, 'smtp_user', 'anam@sevencols.com'),
(11, 'smtp_pass', 'Anamsukses12,'),
(12, 'mailjet_api_key', '4a6e5429b91dbc33bdb97bf0c6d92b09'),
(13, 'mailjet_secret_key', 'ac9a0d2a677ff89598444247296efa8d'),
(14, 'gemini_api_key', 'AIzaSyCMgDbQDxeZtmL-SRLOHXcCM4BuNJ2S9RY'),
(15, 'sendgrid_api_key', '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'),
(16, 'openai_api_key', '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'),
(17, 'main_domain', 'http://acumena.test'),
(18, 'admin_domain', 'http://console-acumena.test'),
(19, 'sumopod_api_key', 'sk-nZFXZP9AYgyEeJloQi4b-Q');

-- --------------------------------------------------------

--
-- Table structure for table `strategic_recommendations`
--

CREATE TABLE `strategic_recommendations` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `strategic_theme` longtext,
  `alignment_with_position` longtext,
  `short_term_actions` json DEFAULT NULL,
  `long_term_actions` json DEFAULT NULL,
  `resource_implications` json DEFAULT NULL,
  `risk_mitigation` json DEFAULT NULL,
  `ife_score` decimal(5,2) DEFAULT NULL,
  `efe_score` decimal(5,2) DEFAULT NULL,
  `quadrant` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_subscription_plans`
--

CREATE TABLE `m_subscription_plans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `project_qty` int DEFAULT '1',
  `max_step` varchar(100) DEFAULT 'all',
  `project_api_generate_quota` int DEFAULT '0',
  `price_monthly` decimal(15,2) DEFAULT '0.00',
  `price_yearly` decimal(15,2) DEFAULT '0.00',
  `label` varchar(50),
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` timestamp NULL DEFAULT NULL,
  `max_projects` int DEFAULT '1',
  `max_ai_generation` int DEFAULT '0',
  `max_ai_per_project` int DEFAULT NULL,
  `max_team_members` int DEFAULT '1',
  `enable_export` boolean DEFAULT '0',
  `enable_api_access` boolean DEFAULT '0',
  `enable_custom_branding` boolean DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`, `is_deleted`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_subscription_plans`
--

INSERT INTO `m_subscription_plans` (`id`, `name`, `project_qty`, `max_step`, `project_api_generate_quota`, `price_monthly`, `price_yearly`, `label`, `date_created`, `last_update`, `is_deleted`, `max_projects`, `max_ai_generation`, `max_team_members`, `enable_export`, `enable_api_access`) VALUES
(1, 'Trial', 1, 'matrix-ie', 0, '0.00', '0.00', NULL, '2025-10-24 12:45:07', '2025-11-04 12:34:50', NULL, 1, 0, 1, 0, 0),
(2, 'Pro Plan', 0, NULL, 0, '200000.00', '2000000.00', NULL, '2025-10-24 12:48:27', '2025-10-31 03:09:32', NULL, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `is_active` int NOT NULL DEFAULT '0',
  `remember_token` varchar(225) DEFAULT NULL,
  `remember_expires_at` datetime DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `full_name`, `email`, `image`, `password`, `role_id`, `is_active`, `remember_token`, `remember_expires_at`, `date_created`, `last_update`, `is_deleted`) VALUES
(1, '447d5f23-bb59-4ed6-b421-868d82459273', 'Choirul Anam', 'cranam21@gmail.com', 'default.jpg', '$2y$10$BdRFBQWPr9qJzmxeRcSdCuPf2JQXFcuPU.vjIuUxhM2JILGoc3tqq', 1, 1, 'bb823b407dc8aa038b5fed98e3c4e4405cc9117009cc1b85108e3cd40764ce91', '2025-12-18 22:19:32', '2025-10-24 21:25:18', '2025-12-11 15:28:12', NULL),
(2, 'add3b54a-b15e-11f0-ab02-00ffd72871d1', 'Test User', 'test@example.com', 'default.jpg', '.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NULL, NULL, '2025-10-25 04:54:24', '2025-11-15 12:59:09', NULL),
(3, '429d6b45-062a-4e03-b3bd-5b952b076651', 'Sevencols', 'sevencols@gmail.com', 'default.jpg', '$2y$10$IB2g8vRe/TIbuGW5bR5QYO4SvBlUOtIe1FGFjVkZFP0LPefXGqPI.', 1, 0, NULL, NULL, '2025-11-04 17:09:35', '2025-11-15 12:59:14', '0000-00-00 00:00:00'),
(4, 'ce72eb48-95c1-4294-917d-f6781aaa1ffe', 'Choirul Anam', 'iyunk21@gmail.com', 'default.jpg', '$2y$10$JgqSlclURDWUGvHXaZyB/uzfoFJ84fmiMDBls73BEc6y7G2j9eKXe', 1, 1, NULL, NULL, '2025-11-14 06:03:21', '2025-11-15 12:59:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `id` bigint UNSIGNED NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short` varchar(50) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_password_resets`
--

CREATE TABLE `user_password_resets` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `token_hash` char(64) NOT NULL,
  `created_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT '0',
  `request_ip` varchar(45) DEFAULT NULL,
  `request_user_agent` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_password_resets`
--

INSERT INTO `user_password_resets` (`id`, `user_id`, `token_hash`, `created_at`, `expires_at`, `used`, `request_ip`, `request_user_agent`) VALUES
(1, 1, '91c8cc3ea4a481018c00ed5731a4e9ee67bd7324b683d9bdd6ce205a8a457219', '2025-11-05 00:05:21', '2025-11-05 01:05:21', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),
(2, 1, '431236b036a8b68986d4641f98f717db7d9bb5fa4c0514695553407ad0894172', '2025-11-05 01:07:46', '2025-11-05 02:07:46', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36'),
(3, 1, '1981fd56878e97a10bb8794cd7d2b7fb502421ecfce93ab24dd5e737e7f65877', '2025-11-12 13:50:38', '2025-11-12 14:50:38', 0, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` bigint UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role_name`, `date_created`, `last_update`, `is_deleted`) VALUES
(1, 'User', '2025-10-24 12:49:33', '2025-10-24 12:49:33', 0),
(2, 'Admin', '2025-10-24 12:49:33', '2025-10-24 12:49:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_role_menu`
--

CREATE TABLE `user_role_menu` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `menu_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_subscription`
--

CREATE TABLE `user_subscription` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_subscription`
--

INSERT INTO `user_subscription` (`id`, `user_id`, `subscription_id`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_subscription_history`
--

CREATE TABLE `user_subscription_history` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `subscription_id` varchar(64) NOT NULL,
  `subscription_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_discount` decimal(10,2) DEFAULT '0.00',
  `date_start` timestamp NOT NULL,
  `date_end` timestamp NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_proof_notes` text NOT NULL,
  `status` enum('paid','unpaid','processing','canceled','rejected','cenceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'unpaid',
  `is_active` varchar(64) NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_subscription_history`
--

INSERT INTO `user_subscription_history` (`id`, `uuid`, `user_id`, `invoice_number`, `subscription_id`, `subscription_name`, `price`, `price_discount`, `date_start`, `date_end`, `payment_proof`, `payment_proof_notes`, `status`, `is_active`, `date_created`, `last_update`, `is_deleted`) VALUES
(1, '838d7d9f-b676-4b72-a527-ee3a16f52f19', 1, '2510001', '1', 'Trial', '0.00', '0.00', '2025-10-24 21:25:18', '2025-11-17 21:25:18', '2025-11-17-22-30-53-838d7d9f-b676-4b72-a527-ee3a16f52f19.png', 'Jadi ini ditolak karena', 'paid', '0', '2025-10-24 21:25:18', '2025-11-18 05:25:03', 0),
(2, '3dbebc17-3c3c-4310-ba41-51e5f66526ca', 3, '2511001', '1', 'Trial', '0.00', '0.00', '2025-11-04 17:09:35', '2025-12-04 17:09:35', '', '', 'paid', '1', '2025-11-04 17:09:35', '2025-11-17 12:13:23', 0),
(3, 'c9e2d1a1-e0b9-483e-baac-a1e89b044891', 4, '2511002', '1', 'Trial', '0.00', '0.00', '2025-11-14 06:03:21', '2025-12-14 06:03:21', '', '', 'paid', '1', '2025-11-14 06:03:21', '2025-11-17 12:13:23', 0),
(4, '6106f6f1-fabb-4bb5-bc46-631591aab388', 1, '2511003', '1', 'Trial', '0.00', '0.00', '2025-11-17 21:25:19', '2025-12-17 21:25:19', '', '', 'paid', '1', '2025-11-17 22:24:48', '2025-11-18 05:25:24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_verify`
--

CREATE TABLE `user_verify` (
  `id` int NOT NULL,
  `email` varchar(64) NOT NULL,
  `otp` int NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_expired` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_verify`
--

INSERT INTO `user_verify` (`id`, `email`, `otp`, `date_created`, `date_expired`) VALUES
(3, 'sevencols@gmail.com', 670546, '2025-11-04 17:09:35', '2025-11-04 21:09:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_pair_filtered`
--
ALTER TABLE `ai_pair_filtered`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ai_strategy`
--
ALTER TABLE `ai_strategy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_run` (`run_id`),
  ADD KEY `idx_project_pair` (`project_id`,`pair_type`),
  ADD KEY `idx_project_run` (`project_id`,`pair_type`,`run_id`);

--
-- Indexes for table `matrix_ie_quadrant_strategies`
--
ALTER TABLE `matrix_ie_quadrant_strategies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `project_ai_generation_run`
--
ALTER TABLE `project_ai_generation_run`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_pair` (`project_id`,`pair_type`,`created_at`);

--
-- Indexes for table `project_prioritized_strategies`
--
ALTER TABLE `project_prioritized_strategies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `idx_project_id` (`project_id`) COMMENT 'Filter by project',
  ADD KEY `idx_pair_type` (`pair_type`) COMMENT 'Filter by SWOT type',
  ADD KEY `idx_status` (`status`) COMMENT 'Filter by status',
  ADD KEY `idx_priority_rank` (`priority_rank`) COMMENT 'Sort by priority',
  ADD KEY `idx_ai_strategy_id` (`ai_strategy_id`) COMMENT 'Join dengan ai_strategy',
  ADD KEY `idx_created_by_user_id` (`created_by_user_id`) COMMENT 'Track by user',
  ADD KEY `idx_is_deleted` (`is_deleted`) COMMENT 'Filter deleted records',
  ADD KEY `idx_project_pair_status` (`project_id`,`pair_type`,`status`),
  ADD KEY `idx_project_priority` (`project_id`,`priority_rank`),
  ADD KEY `idx_user_created` (`created_by_user_id`,`created_at`);

--
-- Indexes for table `project_swot`
--
ALTER TABLE `project_swot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `strategic_recommendations`
--
ALTER TABLE `strategic_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_id` (`project_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_password_resets`
--
ALTER TABLE `user_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `token_hash` (`token_hash`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role_menu`
--
ALTER TABLE `user_role_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `user_subscription`
--
ALTER TABLE `user_subscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subscription_id` (`subscription_id`);

--
-- Indexes for table `user_subscription_history`
--
ALTER TABLE `user_subscription_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `uuid` (`uuid`);

--
-- Indexes for table `user_verify`
--
ALTER TABLE `user_verify`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_pair_filtered`
--
ALTER TABLE `ai_pair_filtered`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `ai_strategy`
--
ALTER TABLE `ai_strategy`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `matrix_ie_quadrant_strategies`
--
ALTER TABLE `matrix_ie_quadrant_strategies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `project_ai_generation_run`
--
ALTER TABLE `project_ai_generation_run`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `project_prioritized_strategies`
--
ALTER TABLE `project_prioritized_strategies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Auto-increment ID';

--
-- AUTO_INCREMENT for table `project_swot`
--
ALTER TABLE `project_swot`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `strategic_recommendations`
--
ALTER TABLE `strategic_recommendations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_password_resets`
--
ALTER TABLE `user_password_resets`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_role_menu`
--
ALTER TABLE `user_role_menu`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_subscription`
--
ALTER TABLE `user_subscription`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_subscription_history`
--
ALTER TABLE `user_subscription_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_verify`
--
ALTER TABLE `user_verify`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ai_strategy`
--
ALTER TABLE `ai_strategy`
  ADD CONSTRAINT `fk_ai_strategy_run` FOREIGN KEY (`run_id`) REFERENCES `project_ai_generation_run` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `project_prioritized_strategies`
--
ALTER TABLE `project_prioritized_strategies`
  ADD CONSTRAINT `fk_ps_ai_strategy_id` FOREIGN KEY (`ai_strategy_id`) REFERENCES `ai_strategy` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ps_created_by_user_id` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ps_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_swot`
--
ALTER TABLE `project_swot`
  ADD CONSTRAINT `project_swot_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `strategic_recommendations`
--
ALTER TABLE `strategic_recommendations`
  ADD CONSTRAINT `strategic_recommendations_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`);

--
-- Constraints for table `user_role_menu`
--
ALTER TABLE `user_role_menu`
  ADD CONSTRAINT `user_role_menu_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`),
  ADD CONSTRAINT `user_role_menu_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `user_menu` (`id`);

--
-- Constraints for table `user_subscription`
--
ALTER TABLE `user_subscription`
  ADD CONSTRAINT `user_subscription_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_subscription_ibfk_2` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`);

--
-- Constraints for table `user_subscription_history`
--
ALTER TABLE `user_subscription_history`
  ADD CONSTRAINT `user_subscription_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
