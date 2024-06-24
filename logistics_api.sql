-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2023 at 09:44 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `logistics_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text DEFAULT NULL,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 0, '000111222', 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_pricing`
--

CREATE TABLE `delivery_pricing` (
  `id` int(11) NOT NULL,
  `min_distance` varchar(100) DEFAULT NULL,
  `max_distance` varchar(100) DEFAULT NULL,
  `price` varchar(100) DEFAULT NULL,
  `inserted_dt` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_pricing`
--

INSERT INTO `delivery_pricing` (`id`, `min_distance`, `max_distance`, `price`, `inserted_dt`) VALUES
(1, '0 km', '20 km', '1,000.00', '01-06-2023, 04:42:00'),
(2, '21 km', '30 km', '1,500.00', '01-06-2023, 04:42:11'),
(4, '31 km', '40 km', '2,500.00', '02-06-2023, 01:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_service`
--

CREATE TABLE `delivery_service` (
  `id` int(11) NOT NULL,
  `service` varchar(100) DEFAULT NULL,
  `inserted_dt` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_service`
--

INSERT INTO `delivery_service` (`id`, `service`, `inserted_dt`) VALUES
(1, 'Premium Service', '13-04-2023, 12:07:49'),
(2, 'Regular Service', '13-04-2023, 12:08:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_pricing`
--
ALTER TABLE `delivery_pricing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_service`
--
ALTER TABLE `delivery_service`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_pricing`
--
ALTER TABLE `delivery_pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `delivery_service`
--
ALTER TABLE `delivery_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
