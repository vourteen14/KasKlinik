-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2024 at 12:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasklinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE `action` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` varchar(20) NOT NULL,
  `notes` text NOT NULL,
  `diagnosis` text NOT NULL,
  `medicine` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `action`
--

INSERT INTO `action` (`id`, `patient_id`, `notes`, `diagnosis`, `medicine`, `created_at`) VALUES
(18, '25', 'dfsa', 'fdaf', 'fdfd', '2024-07-29 01:03:02'),
(19, '23', 'hfdfg anjay mabar', 'dsgsdgs', 'fsgdsg', '2024-07-31 14:20:52'),
(20, '26', 'dfasf jdafbdcb  dfakjsdfsac', 'fdasd', 'fsadf', '2024-08-01 01:52:33'),
(26, '25', 'yufytf', 'oiuu', 'kjhj', '2024-08-01 07:54:38'),
(28, '25', 'kkkk', 'llll', 'lll', '2024-08-01 08:02:51'),
(29, '28', 'pasien puyeng setelah telat makan selama 3 jam', 'Maag', 'promaag, paracetamol', '2024-08-01 09:17:58'),
(30, '29', 'pasien perlu minum obat HRIG 3x sehari', 'pasien terkena rabies', 'HRIG', '2024-08-01 09:52:47'),
(31, '30', 'pasien terkena leukimia', 'leukimia', 'parasetamol, bodrex', '2024-08-01 09:57:25');

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `id` int(11) NOT NULL,
  `balance` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `balance`
--

INSERT INTO `balance` (`id`, `balance`) VALUES
(1, 157397678);

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` varchar(20) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `assurance` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `patient_id`, `fullname`, `address`, `phone`, `category`, `assurance`, `created_at`) VALUES
(28, 'bp1-06', 'Asep Sareupna', 'Jambu, Caringin', '082217829965', 'BPJS', 'BPJS-332199204153', '2024-08-01 09:11:58'),
(29, 'as1-02', 'Salma Fadilah', 'Buahdua, Cigalagah', '082217822222', 'Asuransi', 'ALLIAZ-33334258725', '2024-08-01 09:51:01'),
(30, 'um1-03', 'Usep Barja', 'Buahdua, Bojongloa', '082217825553', 'Umum', '', '2024-08-01 09:56:47');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_in_id` varchar(255) DEFAULT NULL,
  `transaction_out_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `suppliers` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `price` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `transaction_in_id`, `transaction_out_id`, `type`, `suppliers`, `comment`, `price`, `created_at`) VALUES
(63073096, '00000-29211-0022265', NULL, 'IN', '', '', 100000, '2024-08-01 09:42:37'),
(63073097, NULL, '00000-38917-0011110', 'OUT', 'kimia farma', 'Membeli obat cacar air', 2500000, '2024-08-01 09:44:40'),
(63073098, '00000-57551-0005454', NULL, 'IN', '', 'asuransi Alliaz', 2000000, '2024-08-01 09:53:25'),
(63073099, '00000-30118-0034354', NULL, 'IN', '', 'pembayaran umum', 15000, '2024-08-01 09:57:51');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_in`
--

CREATE TABLE `transaction_in` (
  `id` varchar(255) NOT NULL,
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `doctor` varchar(255) NOT NULL,
  `total_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_in`
--

INSERT INTO `transaction_in` (`id`, `action_id`, `created_at`, `doctor`, `total_price`) VALUES
('00000-29211-0022265', 29, '2024-08-01 09:42:37', 'dr.Achmad Irawan', 100000),
('00000-30118-0034354', 31, '2024-08-01 09:57:51', 'dr.Achmad Irawan', 15000),
('00000-57551-0005454', 30, '2024-08-01 09:53:25', 'dr.Achmad Irawan', 2000000);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_out`
--

CREATE TABLE `transaction_out` (
  `id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `information` text NOT NULL,
  `total_price` double NOT NULL,
  `suppliers` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_out`
--

INSERT INTO `transaction_out` (`id`, `created_at`, `information`, `total_price`, `suppliers`) VALUES
('00000-38917-0011110', '2024-08-01 09:44:40', 'Membeli obat cacar air', 2500000, 'kimia farma');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `fullname`, `password`) VALUES
(1, 'admin', 'admin', '$2y$10$gtfIGqLpyzLH1Ed9LWgEr.dSVTMUOS/6llUQ9DZaKMO38DlaonfkG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_in_id`),
  ADD KEY `fk_transaction_transaction_out_id` (`transaction_out_id`);

--
-- Indexes for table `transaction_in`
--
ALTER TABLE `transaction_in`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `transaction_in_action_id_foreign` (`action_id`);

--
-- Indexes for table `transaction_out`
--
ALTER TABLE `transaction_out`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action`
--
ALTER TABLE `action`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63073100;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_transaction_transaction_out_id` FOREIGN KEY (`transaction_out_id`) REFERENCES `transaction_out` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`transaction_in_id`) REFERENCES `transaction_in` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_transaction_id_foreign_in` FOREIGN KEY (`transaction_in_id`) REFERENCES `transaction_in` (`id`);

--
-- Constraints for table `transaction_in`
--
ALTER TABLE `transaction_in`
  ADD CONSTRAINT `transaction_in_action_id_foreign` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
