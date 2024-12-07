-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 09:25 AM
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
use resto
-- Database: `resto`
--

-- --------------------------------------------------------

--
-- Table structure for table `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` int(11) NOT NULL,
  `nama_bahan` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `stok`, `harga_beli`) VALUES
(8, 'beras', 25, 3000.00),
(10, 'sambal', 35, 2500.00),
(11, 'Ikan Mujair', 20, 14000.00);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `jenis_menu` varchar(255) NOT NULL,
  `nama_menu` varchar(255) NOT NULL,
  `harga_menu` int(11) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `jenis_menu`, `nama_menu`, `harga_menu`, `stok`) VALUES
(11, 'Makanan', 'Nasi Goreng', 10000, 70),
(12, 'Makanan', 'Soto Ayam', 9000, 85),
(13, 'Makanan', 'Nasi Padang', 16000, 84),
(15, 'Makanan', 'Bakso', 8000, 79),
(16, 'Makanan', 'Rawon', 12000, 73),
(17, 'Makanan', 'Ayam Geprek', 9000, 81),
(19, 'Makanan', 'Rendang', 15000, 91),
(20, 'Minuman', 'Es Teh', 3000, 72),
(21, 'Minuman', 'Es Degan', 5000, 67),
(22, 'Minuman', 'Es Cincau', 4000, 91),
(23, 'Minuman', 'Es Buah', 7000, 83),
(24, 'Minuman', 'Es Campur', 6000, 66),
(26, 'Minuman', 'Es Jeruk', 5000, 86),
(30, 'Minuman', 'Es Dawet', 6000, 88),
(34, 'Minuman', 'Es Marimas', 3000, 91),
(37, 'Makanan', 'Nasi Goreng', 20000, 50),
(38, 'Makanan', 'ayam gprek', 12000, 30),
(39, 'Makanan', 'ayam bakar', 12000, 23),
(48, 'makanan', 'babi goreng', 13000, 10),
(52, 'makanan', 'babi kuah', 13000, 5),
(53, 'makanan', 'Mujair Sambal matah', 250000, 7);

-- --------------------------------------------------------

--
-- Table structure for table `menu_bahan`
--

CREATE TABLE `menu_bahan` (
  `id_menu_bahan` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `jumlah_bahan` float NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_bahan`
--

INSERT INTO `menu_bahan` (`id_menu_bahan`, `id_menu`, `id_bahan`, `jumlah_bahan`, `keterangan`) VALUES
(13, 53, 10, 1, 'beras,jair,sambal\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id_order_detail` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah_order` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id_order_detail`, `id_order`, `id_menu`, `jumlah_order`, `harga`, `subtotal`) VALUES
(14, 5, 16, 2, 12000, 24000),
(15, 5, 21, 2, 5000, 10000),
(17, 12, 11, 3, 10000, 30000),
(20, 15, 16, 2, 12000, 24000),
(21, 15, 17, 2, 9000, 18000),
(22, 15, 20, 1, 3000, 3000),
(23, 16, 15, 3, 8000, 24000),
(24, 16, 24, 1, 6000, 6000),
(25, 16, 24, 2, 6000, 12000),
(38, 25, 17, 1, 9000, 9000),
(39, 25, 21, 1, 5000, 5000),
(40, 26, 13, 2, 16000, 32000),
(41, 26, 23, 1, 7000, 7000),
(42, 26, 22, 1, 4000, 4000),
(49, 31, 17, 2, 9000, 18000),
(50, 31, 23, 1, 7000, 7000),
(51, 31, 20, 1, 3000, 3000),
(52, 32, 17, 3, 9000, 27000),
(53, 32, 13, 2, 16000, 32000),
(54, 32, 20, 5, 3000, 15000),
(55, 33, 21, 2, 5000, 10000),
(62, 33, 26, 10, 5000, 50000),
(63, 33, 17, 2, 9000, 18000),
(68, 33, 12, 10, 9000, 90000),
(69, 33, 13, 2, 16000, 32000),
(70, 33, 16, 2, 12000, 24000),
(75, 12, 20, 3, 3000, 9000),
(76, 15, 21, 2, 5000, 10000),
(77, 15, 26, 1, 5000, 5000),
(78, 30, 17, 1, 9000, 9000),
(79, 30, 21, 1, 5000, 5000),
(80, 36, 11, 3, 10000, 30000),
(90, 36, 23, 3, 7000, 21000),
(91, 38, 11, 2, 10000, 20000),
(93, 38, 21, 2, 5000, 10000),
(94, 38, 17, 1, 9000, 9000),
(95, 39, 17, 2, 9000, 18000),
(96, 39, 15, 1, 8000, 8000),
(97, 39, 26, 2, 5000, 10000),
(98, 40, 11, 3, 10000, 30000),
(104, 40, 30, 2, 6000, 12000),
(107, 41, 20, 3, 3000, 9000),
(109, 41, 13, 3, 16000, 48000),
(111, 45, 13, 3, 16000, 48000),
(112, 45, 24, 2, 6000, 12000),
(113, 45, 12, 4, 9000, 36000),
(114, 45, 21, 5, 5000, 25000),
(115, 45, 22, 5, 4000, 20000),
(120, 47, 13, 3, 16000, 48000),
(121, 47, 15, 4, 8000, 32000),
(122, 48, 16, 3, 12000, 36000),
(123, 54, 11, 2, 10000, 20000),
(124, 54, 26, 2, 5000, 10000),
(125, 55, 17, 3, 9000, 27000),
(126, 55, 24, 2, 6000, 12000),
(127, 55, 20, 1, 3000, 3000),
(128, 56, 16, 3, 12000, 36000),
(129, 56, 17, 2, 9000, 18000),
(130, 56, 20, 5, 3000, 15000),
(131, 57, 20, 3, 3000, 9000),
(132, 57, 11, 3, 10000, 30000),
(133, 58, 26, 3, 5000, 15000),
(134, 59, 16, 3, 12000, 36000),
(135, 60, 15, 2, 8000, 16000),
(136, 60, 23, 2, 7000, 14000),
(137, 61, 13, 3, 16000, 48000),
(138, 61, 20, 3, 3000, 9000),
(139, 62, 17, 2, 9000, 18000),
(140, 62, 20, 1, 3000, 3000),
(141, 62, 21, 1, 5000, 5000),
(142, 63, 11, 3, 10000, 30000),
(143, 63, 34, 3, 3000, 9000),
(144, 64, 17, 2, 9000, 18000),
(145, 64, 24, 2, 6000, 12000),
(146, 65, 16, 3, 12000, 36000),
(147, 65, 20, 1, 3000, 3000),
(148, 65, 22, 2, 4000, 8000),
(149, 66, 19, 2, 15000, 30000),
(150, 66, 30, 2, 6000, 12000),
(151, 67, 12, 3, 9000, 27000),
(152, 67, 30, 2, 6000, 12000),
(153, 67, 20, 1, 3000, 3000),
(154, 68, 15, 2, 8000, 16000),
(155, 68, 24, 2, 6000, 12000),
(156, 69, 13, 2, 16000, 32000),
(157, 69, 24, 2, 6000, 12000),
(158, 70, 19, 1, 15000, 15000),
(159, 70, 26, 1, 5000, 5000),
(160, 71, 13, 2, 16000, 32000),
(161, 71, 20, 2, 3000, 6000),
(162, 72, 12, 3, 9000, 27000),
(163, 72, 21, 3, 5000, 15000),
(164, 73, 13, 1, 16000, 16000),
(165, 73, 22, 1, 4000, 4000),
(166, 74, 15, 3, 8000, 24000),
(167, 74, 23, 3, 7000, 21000),
(168, 75, 16, 4, 12000, 48000),
(169, 75, 24, 2, 6000, 12000),
(170, 75, 23, 2, 7000, 14000),
(171, 76, 19, 2, 15000, 30000),
(172, 76, 34, 2, 3000, 6000),
(173, 77, 17, 3, 9000, 27000),
(174, 77, 30, 3, 6000, 18000),
(175, 78, 16, 1, 12000, 12000),
(176, 78, 26, 1, 5000, 5000),
(177, 79, 15, 1, 8000, 8000),
(178, 80, 13, 2, 16000, 32000),
(179, 80, 24, 2, 6000, 12000),
(180, 81, 11, 2, 10000, 20000),
(181, 81, 20, 2, 3000, 6000),
(182, 82, 12, 4, 9000, 36000),
(183, 82, 21, 4, 5000, 20000),
(184, 83, 13, 1, 16000, 16000),
(185, 83, 22, 1, 4000, 4000),
(186, 84, 15, 3, 8000, 24000),
(187, 84, 23, 3, 7000, 21000),
(188, 85, 16, 2, 12000, 24000),
(189, 85, 24, 2, 6000, 12000),
(190, 86, 19, 1, 15000, 15000),
(191, 86, 26, 1, 5000, 5000),
(192, 87, 17, 3, 9000, 27000),
(193, 87, 30, 3, 6000, 18000),
(194, 88, 16, 2, 12000, 24000),
(195, 88, 26, 2, 5000, 10000),
(196, 89, 15, 3, 8000, 24000),
(197, 89, 24, 3, 6000, 18000),
(198, 90, 13, 1, 16000, 16000),
(199, 90, 23, 1, 7000, 7000),
(200, 91, 12, 2, 9000, 18000),
(201, 91, 20, 2, 3000, 6000),
(202, 92, 11, 1, 10000, 10000),
(203, 92, 21, 1, 5000, 5000),
(204, 93, 19, 2, 15000, 30000),
(205, 93, 23, 2, 7000, 14000),
(206, 94, 17, 3, 9000, 27000),
(207, 94, 26, 3, 5000, 15000),
(208, 95, 16, 2, 12000, 24000),
(209, 95, 30, 2, 6000, 12000),
(210, 97, 11, 3, 10000, 30000),
(211, 97, 20, 2, 3000, 6000),
(213, 98, 11, 2, 10000, 20000),
(214, 98, 20, 2, 3000, 6000),
(215, 99, 12, 3, 9000, 27000),
(216, 99, 21, 3, 5000, 15000),
(217, 100, 13, 1, 16000, 16000),
(218, 100, 22, 1, 4000, 4000),
(219, 101, 15, 3, 8000, 24000),
(220, 101, 23, 2, 7000, 14000),
(221, 101, 24, 1, 6000, 6000),
(222, 102, 16, 2, 12000, 24000),
(223, 102, 26, 1, 5000, 5000),
(224, 102, 34, 1, 3000, 3000),
(226, 104, 16, 2, 12000, 24000),
(227, 104, 19, 1, 15000, 15000),
(228, 104, 34, 3, 3000, 9000),
(229, 105, 21, 2, 5000, 10000),
(230, 106, 21, 8, 5000, 40000),
(231, 107, 24, 9, 6000, 54000),
(232, 108, 21, 7, 5000, 35000),
(233, 109, 24, 7, 6000, 42000),
(234, 110, 52, 1, 13000, 13000),
(235, 111, 53, 5, 250000, 1250000),
(236, 112, 52, 4, 13000, 52000),
(237, 113, 22, 4, 4000, 16000),
(238, 114, 23, 2, 7000, 14000);

-- --------------------------------------------------------

--
-- Table structure for table `order_pesanan`
--

CREATE TABLE `order_pesanan` (
  `id_order` int(11) NOT NULL,
  `tanggal_order` date NOT NULL,
  `jam_order` time NOT NULL,
  `nama_pelayan` varchar(255) NOT NULL,
  `no_meja` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `status_order` enum('Dalam proses','Selesai') NOT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_pesanan`
--

INSERT INTO `order_pesanan` (`id_order`, `tanggal_order`, `jam_order`, `nama_pelayan`, `no_meja`, `total_bayar`, `status_order`, `snap_token`, `payment_type`) VALUES
(5, '2023-10-18', '21:22:19', 'Dewi', 4, 34000, 'Selesai', NULL, NULL),
(12, '2023-10-19', '21:48:26', 'Budi', 3, 39000, 'Selesai', NULL, NULL),
(15, '2023-10-19', '23:30:07', 'Rudi', 2, 60000, 'Selesai', NULL, NULL),
(16, '2023-10-19', '23:33:29', 'Lina', 5, 42000, 'Selesai', NULL, NULL),
(25, '2023-10-24', '13:57:36', 'Eko', 1, 14000, 'Selesai', NULL, NULL),
(26, '2023-10-25', '17:12:48', 'Siti', 3, 43000, 'Selesai', NULL, NULL),
(30, '2023-11-01', '13:37:36', 'Dewi', 5, 14000, 'Selesai', NULL, NULL),
(31, '2023-11-01', '16:49:31', 'Eko', 2, 28000, 'Selesai', NULL, NULL),
(32, '2023-11-01', '17:22:55', 'Siti', 1, 74000, 'Selesai', NULL, NULL),
(33, '2023-11-01', '17:37:22', 'Eko', 4, 224000, 'Selesai', NULL, NULL),
(36, '2023-11-02', '00:48:10', 'Siti', 2, 51000, 'Selesai', NULL, NULL),
(38, '2023-11-02', '03:33:51', 'Budi', 5, 39000, 'Selesai', NULL, NULL),
(39, '2023-11-02', '06:21:26', 'Rudi', 3, 36000, 'Selesai', NULL, NULL),
(40, '2023-11-08', '23:12:36', 'Lina', 1, 42000, 'Selesai', NULL, NULL),
(41, '2023-11-09', '01:53:34', 'Dewi', 4, 57000, 'Selesai', NULL, NULL),
(45, '2023-11-09', '14:45:26', 'Budi', 3, 141000, 'Selesai', NULL, NULL),
(47, '2023-11-14', '15:45:10', 'Dewi', 5, 80000, 'Selesai', NULL, NULL),
(48, '2023-11-17', '17:10:08', 'Eko', 2, 36000, 'Selesai', NULL, NULL),
(54, '2023-11-17', '18:21:16', 'Dewi', 4, 30000, 'Selesai', NULL, NULL),
(55, '2023-11-17', '19:02:31', 'Dewi', 1, 42000, 'Selesai', NULL, NULL),
(56, '2023-11-17', '19:34:11', 'Eko', 3, 69000, 'Selesai', NULL, NULL),
(57, '2023-11-17', '19:50:02', 'Dewi', 4, 39000, 'Selesai', NULL, NULL),
(58, '2023-11-18', '09:48:15', 'Rudi', 2, 15000, 'Selesai', NULL, NULL),
(59, '2023-11-18', '09:55:46', 'Rudi', 1, 36000, 'Selesai', NULL, NULL),
(60, '2023-11-18', '09:56:42', 'Eko', 5, 30000, 'Selesai', NULL, NULL),
(61, '2023-11-18', '10:28:30', 'Budi', 1, 57000, 'Selesai', NULL, NULL),
(62, '2023-11-18', '10:41:44', 'Rudi', 2, 26000, 'Selesai', NULL, NULL),
(63, '2023-11-18', '10:42:40', 'Dewi', 3, 39000, 'Selesai', NULL, NULL),
(64, '2023-11-18', '10:43:50', 'Lina', 4, 30000, 'Selesai', NULL, NULL),
(65, '2023-11-18', '10:44:19', 'Siti', 5, 47000, 'Selesai', NULL, NULL),
(66, '2023-11-18', '11:10:20', 'Dewi', 1, 42000, 'Selesai', NULL, NULL),
(67, '2023-11-18', '11:39:59', 'Lina', 2, 42000, 'Selesai', NULL, NULL),
(68, '2023-11-18', '11:40:47', 'Rudi', 3, 28000, 'Selesai', NULL, NULL),
(69, '2023-11-18', '11:41:15', 'Siti', 4, 44000, 'Selesai', NULL, NULL),
(70, '2023-11-18', '11:41:46', 'Dewi', 5, 20000, 'Selesai', NULL, NULL),
(71, '2023-11-18', '12:10:36', 'Budi', 1, 38000, 'Selesai', NULL, NULL),
(72, '2023-11-18', '12:15:39', 'Siti', 2, 42000, 'Selesai', NULL, NULL),
(73, '2023-11-18', '12:20:36', 'Eko', 3, 20000, 'Selesai', NULL, NULL),
(74, '2023-11-18', '12:22:13', 'Dewi', 4, 45000, 'Selesai', NULL, NULL),
(75, '2023-11-18', '12:22:37', 'Rudi', 5, 74000, 'Selesai', NULL, NULL),
(76, '2023-11-18', '12:46:08', 'Lina', 1, 36000, 'Selesai', NULL, NULL),
(77, '2023-11-18', '12:46:10', 'Budi', 2, 45000, 'Selesai', NULL, NULL),
(78, '2023-11-18', '12:46:12', 'Siti', 3, 17000, 'Selesai', NULL, NULL),
(79, '2023-11-18', '12:46:13', 'Eko', 4, 8000, 'Selesai', NULL, NULL),
(80, '2023-11-18', '12:46:15', 'Dewi', 5, 44000, 'Selesai', NULL, NULL),
(81, '2023-11-18', '13:09:31', 'Rudi', 1, 26000, 'Selesai', NULL, NULL),
(82, '2023-11-18', '13:09:53', 'Lina', 2, 56000, 'Selesai', NULL, NULL),
(83, '2023-11-18', '13:10:16', 'Budi', 3, 20000, 'Selesai', NULL, NULL),
(84, '2023-11-18', '13:10:38', 'Siti', 4, 45000, 'Selesai', NULL, NULL),
(85, '2023-11-18', '13:11:20', 'Eko', 5, 36000, 'Selesai', NULL, NULL),
(86, '2023-11-18', '13:33:08', 'Dewi', 1, 20000, 'Selesai', NULL, NULL),
(87, '2023-11-18', '13:33:35', 'Rudi', 2, 45000, 'Selesai', NULL, NULL),
(88, '2023-11-18', '13:33:52', 'Lina', 3, 34000, 'Selesai', NULL, NULL),
(89, '2023-11-18', '13:34:15', 'Budi', 4, 42000, 'Selesai', NULL, NULL),
(90, '2023-11-18', '13:34:40', 'Siti', 5, 23000, 'Selesai', NULL, NULL),
(91, '2023-11-18', '13:56:28', 'Eko', 1, 24000, 'Selesai', NULL, NULL),
(92, '2023-11-18', '13:57:01', 'Dewi', 2, 15000, 'Selesai', NULL, NULL),
(93, '2023-11-18', '13:57:24', 'Rudi', 3, 44000, 'Selesai', NULL, NULL),
(94, '2023-11-18', '13:57:52', 'Lina', 4, 42000, 'Selesai', NULL, NULL),
(95, '2023-11-18', '13:58:15', 'Budi', 5, 36000, 'Selesai', NULL, NULL),
(97, '2023-11-18', '15:09:24', 'Eko', 3, 36000, 'Selesai', NULL, NULL),
(98, '2023-11-22', '15:32:54', 'Budi', 1, 26000, 'Dalam proses', NULL, NULL),
(99, '2023-11-22', '15:37:00', 'Siti', 2, 42000, 'Dalam proses', NULL, NULL),
(100, '2023-11-22', '15:39:22', 'Eko', 3, 20000, 'Dalam proses', NULL, NULL),
(101, '2023-11-22', '15:45:19', 'Dewi', 4, 44000, 'Dalam proses', NULL, NULL),
(102, '2023-11-22', '15:47:38', 'Rudi', 5, 32000, 'Dalam proses', NULL, NULL),
(104, '2023-11-29', '05:12:01', 'Siti', 1, 48000, 'Dalam proses', NULL, NULL),
(105, '2024-11-11', '20:17:43', 'Budi', 1, 10000, 'Dalam proses', 'fade2314-49a4-4dd9-afe7-185b68ff4d98', NULL),
(106, '2024-11-11', '20:36:25', 'Siti', 2, 40000, 'Selesai', NULL, 'kasir'),
(107, '2024-11-11', '20:36:48', 'Siti', 4, 54000, 'Dalam proses', '047eb29e-74dd-4c30-ba3f-712de81fa4ca', NULL),
(108, '2024-11-11', '20:54:08', 'Siti', 1, 35000, 'Dalam proses', '9a65ddcf-8208-4cd2-a477-7df3b06ba1da', NULL),
(109, '2024-11-11', '20:58:03', 'Budi', 3, 42000, 'Dalam proses', '3f93fa5a-89e4-4027-9152-b2428d93819c', NULL),
(110, '2024-11-12', '18:33:44', 'Budi', 1, 13000, 'Selesai', 'f0c83340-10db-4711-ae7d-2a6965c8a237', 'bank_transfer'),
(111, '2024-11-14', '15:30:28', 'Budi', 2, 1250000, 'Selesai', 'b704acbb-eb7a-4b61-9af5-4ada50f234a6', 'kasir'),
(112, '2024-11-14', '15:32:36', 'Budi', 2, 52000, 'Selesai', '82e7a818-d240-4eca-b962-02102d93c956', 'bank_transfer'),
(113, '2024-11-21', '12:22:54', 'Eko', 1, 16000, 'Selesai', 'b905dd43-24fa-48fb-8b4b-c53550f1b814', 'bank_transfer'),
(114, '2024-11-21', '12:32:26', 'Budi', 3, 14000, 'Dalam proses', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `password`, `level`) VALUES
(1, 'Bos', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1),
(2, 'Thomas', 'kasir', 'c7911af3adbd12a035b289556d96470a', 2),
(3, 'Guest', 'guest', '084e0343a0486ff05530df6c705c8bb4', 3),
(4, 'User', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 3),
(6, 'fa', 'fa', '89e6d2b383471fc370d828e552c19e65', 4),
(7, 'fathur', 'f', '800618943025315f869e4e1f09471012', 4),
(8, 'fas', 'fas', 'c5341e883d09ced169abfac23dc13abc', 1),
(9, 'a', 'a', '202cb962ac59075b964b07152d234b70', 1),
(10, 'b', 'b', '202cb962ac59075b964b07152d234b70', 5),
(11, 'k', 'k', '202cb962ac59075b964b07152d234b70', 2),
(12, 'm', 'm', '202cb962ac59075b964b07152d234b70', 4),
(13, 'g', 'g', '202cb962ac59075b964b07152d234b70', 3),
(14, 'c', 'c', '202cb962ac59075b964b07152d234b70', 5),
(15, 'ac', 'ac', '202cb962ac59075b964b07152d234b70', 4),
(16, 'ad', 'ad', '202cb962ac59075b964b07152d234b70', 3),
(17, 'customer', 'customer', '202cb962ac59075b964b07152d234b70', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `menu_bahan`
--
ALTER TABLE `menu_bahan`
  ADD PRIMARY KEY (`id_menu_bahan`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `menu_bahan_ibfk_2` (`id_bahan`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id_order_detail`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `order_pesanan`
--
ALTER TABLE `order_pesanan`
  ADD PRIMARY KEY (`id_order`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `menu_bahan`
--
ALTER TABLE `menu_bahan`
  MODIFY `id_menu_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id_order_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT for table `order_pesanan`
--
ALTER TABLE `order_pesanan`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_bahan`
--
ALTER TABLE `menu_bahan`
  ADD CONSTRAINT `menu_bahan_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`),
  ADD CONSTRAINT `menu_bahan_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `bahan` (`id_bahan`) ON DELETE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_order`) REFERENCES `order_pesanan` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
