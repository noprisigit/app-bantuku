-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2020 at 09:12 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bantuku`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(11) NOT NULL,
  `AdminName` varchar(100) NOT NULL,
  `AdminUsername` varchar(100) NOT NULL,
  `AdminPassword` varchar(256) NOT NULL,
  `AdminStatus` enum('Super Admin','Admin') NOT NULL,
  `AdminStatusAccount` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `login_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminID`, `AdminName`, `AdminUsername`, `AdminPassword`, `AdminStatus`, `AdminStatusAccount`, `date_created`, `login_time`) VALUES
(1, 'Sigit Prasetyo Noprianto', 'admin', '$2y$10$b0Z2RWP9rQ3DFlTrCq9rAO8Jy2EIHaYBtOL4QdNqhZYs/wQBc.mVS', 'Super Admin', 1, '2020-05-05 02:35:36', '2020-05-06 13:46:02');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL,
  `CategoryDescription` varchar(256) NOT NULL,
  `CategoryIcon` varchar(100) NOT NULL,
  `CategoryStatus` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `CategoryDescription`, `CategoryIcon`, `CategoryStatus`, `date_created`, `date_updated`) VALUES
(6, 'Restaurant Cepat Saji', 'find ur junk food', 'b138122312bcaa2fd9b7203c84125a47.png', 1, '2020-05-05 20:43:12', '2020-05-05 20:43:12'),
(7, 'McDonald Kacang Pedang', 'McDonald Tractors', '80b56ccfecdf873e849bf8ba8406f396.png', 0, '2020-05-05 20:45:41', '2020-05-05 20:45:41'),
(8, 'Minuman', 'Minuman hangat menjadi pilihan', '8fa1badebce3de334b16a70ed4331205.png', 1, '2020-05-05 20:47:50', '2020-05-05 20:47:50'),
(9, 'Coffee Shop', 'find best coffee in town', 'eac11adc06562b8c2f1feab11b23e7e0.png', 1, '2020-05-05 20:56:22', '2020-05-05 20:56:22'),
(10, 'UMKM', 'find ur best snack here', '751693ca9644e5d14334d93944c4c571.png', 0, '2020-05-05 20:58:05', '2020-05-05 20:58:05');

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `PartnerID` int(11) NOT NULL,
  `PartnerUniqueID` varchar(250) NOT NULL,
  `CompanyName` varchar(250) NOT NULL,
  `PartnerName` varchar(250) NOT NULL,
  `Address` varchar(500) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`PartnerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `PartnerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
