-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2024 at 11:00 AM
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
(1, 100000000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

DELIMITER //
CREATE TRIGGER after_transaction_insert
AFTER INSERT ON transaction
FOR EACH ROW
BEGIN
    IF NEW.type = 'IN' THEN
        UPDATE balance
        SET balance = balance + NEW.price
        WHERE id = 1;
    ELSEIF NEW.type = 'OUT' THEN
        UPDATE balance
        SET balance = balance - NEW.price
        WHERE id = 1;
    END IF;
END //

DELIMITER ;

DELIMITER //
CREATE TRIGGER after_transaction_update
AFTER UPDATE ON transaction
FOR EACH ROW
BEGIN
    IF OLD.type = 'IN' THEN
        UPDATE balance
        SET balance = balance - OLD.price
        WHERE id = 1;
    ELSEIF OLD.type = 'OUT' THEN
        UPDATE balance
        SET balance = balance + OLD.price
        WHERE id = 1;
    END IF;

    IF NEW.type = 'IN' THEN
        UPDATE balance
        SET balance = balance + NEW.price
        WHERE id = 1;
    ELSEIF NEW.type = 'OUT' THEN
        UPDATE balance
        SET balance = balance - NEW.price
        WHERE id = 1;
    END IF;
END //

DELIMITER ;

