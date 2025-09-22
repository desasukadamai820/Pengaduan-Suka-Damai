-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 22, 2025 at 05:15 PM
-- Server version: 8.4.3
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengaduan_sukadamai`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subcategory` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Baru','Diproses','Selesai','Ditolak') COLLATE utf8mb4_unicode_ci DEFAULT 'Baru',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `category`, `subcategory`, `subject`, `content`, `location`, `attachment`, `status`, `created_at`, `nama`, `nik`, `alamat`) VALUES
(2, 1, 'Pelayanan Pemerintahan', 'KDRT', 'Istri Pukul Anak', 'Istri saya memukul anak saya di bagian pipi', 'Rumah kami RT 14 RW 05', NULL, 'Diproses', '2025-09-05 16:05:00', 'Bagas Rahmadani', '1406041811030001', 'Sialang rindang'),
(3, 3, 'Fasilitas Umum', 'Jalan Rusak', 'Terjadi jalan rusak di rt 14', 'Jalan Terdapat air yg bisa menyebabkan jalan becek', 'RT 14 RW 06 Dusun 3', NULL, 'Diproses', '2025-09-05 17:35:42', 'Bagas Rahmadani', '1314214125215', 'Sialang rindang'),
(4, 3, 'Pelayanan Pemerintahan', 'Kekerasan Anak', 'Kekerasan Anak Kandung Ke 2', 'Istri saya memukul pada bagian pipi anak saya sehingga menyebabkan memar', 'RT 14 RW 06 Dusun 3', NULL, 'Diproses', '2025-09-05 17:38:47', 'Bagas Rahmadani', '14121510', 'Sialang rindang');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('warga','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'warga',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Bagas rahmadani', 'bagas.br101@gmail.com', '$2y$10$qPYOdubOEXegH10oWc1j1uagI2oDoan9LjbsG8YSVMSt.P1eCfcqq', 'warga', '2025-09-05 14:59:38'),
(2, 'admin', 'admin@gmail.com', '$2y$10$zkv3yTxY1hUOKACQn8G2Fe9IUye5.AOHlf8osMYCEC0IhCISBiiw2', 'admin', '2025-09-05 15:02:04'),
(3, 'Bagas rahmadani', 'bagas@gmail.com', '$2y$10$ZHK1VT0SVyup/1XeghSTLeEmGSjVJrnwHWmXjuUHd/yA/SSturrFi', 'warga', '2025-09-05 16:06:05'),
(4, 'Bagas', 'bagasrh@gmail.com', '$2y$10$EqCetbffYWtbVVdxb6H5ueXDiK9rx14tik7THpOugg4e/QtdZkCaa', 'warga', '2025-09-22 16:36:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
