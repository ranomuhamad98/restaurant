-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 23, 2023 at 09:37 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restoran`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE `auth` (
  `id` int(11) NOT NULL,
  `tipe_user` enum('MSORDER','MSKITCHEN') DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  `client_secret` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `rano` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `session_time` datetime DEFAULT NULL,
  `tipe` enum('callback','request') DEFAULT NULL,
  `input_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth`
--

INSERT INTO `auth` (`id`, `tipe_user`, `client_id`, `client_secret`, `username`, `password`, `rano`, `token`, `session_time`, `tipe`, `input_time`) VALUES
(1, 'MSORDER', 'femch01', 'MACHINE-1', 'msorderm01', '6c23670f050af5f443f407437c199894', 'pass_msorderm01', 'NAkARz1Kaa7RUTK1Rbg0g_ng_DkWyc7a2zEoMGxzjAE3ETz13pLJ73qCpOOsfI8xlggmWu2S2UDg8ioRNUHibirPWTG_C47YL9xokgeFhM5pFJrvkdMFHSl92Myt6O6opfaEoQVTlLSWi2O__6o0rHtBrvsF5y9OHTm2.G', '2023-02-24 08:35:45', 'request', '2023-02-17 08:35:29'),
(2, 'MSKITCHEN', 'bekpc01', 'PC-1', 'mskitchenp01', 'd0ea679d88a73d23c9119af2f5b55528', 'pass_mskitchenp01', '9lecFBbda_QJMarCKIINCU5hS3OiNOxEJiOROsaDTfiVjmawSowQRrOBGoH70xr3CE_DzAnXWOaNINkuUl4q_dM6T04lDMqdqXHuhXoC4NcXs4jYzl5CKe0DIXFHylhYYeJfMwYin5KJrRZ5WjsGwkQ867hSlrovKYfYZU', '2023-02-24 08:35:51', 'request', '2023-02-17 08:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `nama_depan` varchar(50) NOT NULL,
  `nama_belakang` varchar(50) NOT NULL,
  `jabatan` enum('dapur','kasir') NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '1=aktif,0=nonaktif',
  `input_time` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `del` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `nama_depan`, `nama_belakang`, `jabatan`, `email`, `password`, `status`, `input_time`, `del`) VALUES
(1, 'John', 'Doe', 'kasir', 'johndoe@gmail.com', 'de28f8f7998f23ab4194b51a6029416f', 1, '2023-02-17 08:24:57', 0),
(2, 'Rano', 'Muhamad', 'dapur', 'ranomuhamad98@gmail.com', 'e0ef6defcf8b8a0869df7e068cd100f0', 1, '2023-02-17 16:11:55', 0);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `status` int(1) NOT NULL COMMENT '1=available,0=out of stock',
  `jenis` enum('drink','food') NOT NULL,
  `del` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama`, `harga`, `status`, `jenis`, `del`) VALUES
(1, 'NASI GORENG GILA', 20000, 0, 'food', 0),
(2, 'NASI GORENG BIASA', 15000, 1, 'food', 0),
(3, 'MIE AYAM SEAFOOD', 18000, 1, 'food', 0),
(4, 'MIE AYAM BIASA', 32000, 1, 'food', 0),
(5, 'ES TEH', 20000, 1, 'drink', 0),
(6, 'ES JERUK', 20000, 1, 'drink', 0),
(7, 'AIR MINERAL', 15000, 1, 'drink', 0),
(8, 'TEH HANGAT', 18000, 1, 'drink', 0),
(9, 'JERUK HANGAT', 18000, 1, 'drink', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `nomor_meja` int(11) DEFAULT NULL,
  `nomor_pesanan` varchar(10) DEFAULT NULL,
  `status_pesanan` enum('pending','process','ready') DEFAULT 'pending',
  `status_bayar` int(1) DEFAULT 0 COMMENT '1=paid,0=unpaid',
  `metode_bayar` enum('cash','debit','credit') DEFAULT NULL,
  `input_time` datetime DEFAULT NULL,
  `del` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `id_employee`, `nomor_meja`, `nomor_pesanan`, `status_pesanan`, `status_bayar`, `metode_bayar`, `input_time`, `del`) VALUES
(1, NULL, 1, 'P0001', 'ready', 0, 'cash', '2023-02-23 12:50:14', 0),
(2, NULL, 2, 'P0002', 'process', 0, 'debit', '2023-02-23 13:09:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_detail`
--

CREATE TABLE `transaction_detail` (
  `id_transaction` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT 1 COMMENT '2=ready,1=pending,0=reject'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_detail`
--

INSERT INTO `transaction_detail` (`id_transaction`, `id_menu`, `quantity`, `total`, `status`) VALUES
(1, 1, 3, 60000, 2),
(1, 4, 1, 32000, 2),
(1, 6, 3, 60000, 2),
(1, 7, 1, 15000, 2),
(2, 3, 1, 18000, 1),
(2, 4, 2, 64000, 1),
(2, 6, 1, 20000, 1),
(2, 8, 2, 36000, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
