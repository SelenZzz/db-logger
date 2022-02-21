-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 03, 2021 at 11:38 PM
-- Server version: 10.5.8-MariaDB
-- PHP Version: 7.1.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `logger`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(2) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`) VALUES
(1, 'BackupStart'),
(2, 'BackupEnd'),
(3, 'UploadStart'),
(4, 'UploadEnd');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `server` varchar(32) NOT NULL,
  `base` varchar(32) NOT NULL,
  `event` int(2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `guid` varchar(32) NOT NULL,
  `user` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `date`, `server`, `base`, `event`, `status`, `guid`, `user`) VALUES
(1, '2021-02-09 19:07:25', 'gbi31csrv', 'gbi3', 1, 0, '1f95a158b355412196ddc52b5638487b', NULL),
(2, '2021-02-22 23:43:52', 'gbi31csrv', 'gbi3', 2, 1, '74bc587fdf6d492ea4ce10230a35b3fd', 'user'),
(3, '2021-03-17 09:26:39', 'gbi31csrv', 'gbi3', 3, 1, '1f95a158b355412196ddc52b5638487b', 'admin'),
(4, '2021-03-18 09:33:03', 'gbi31csrv', 'gbi3', 4, 1, '74bc587fdf6d492ea4ce10230a35b3fd', 'user'),
(5, '2021-03-17 16:00:28', 'gbi31csrv', 'gbi3', 1, 1, '1f95a158b355412196ddc52b5638487b', 'admin'),
(6, '2021-03-17 09:57:03', 'gbi31csrv', 'gbi3', 2, 1, '74bc587fdf6d492ea4ce10230a35b3fd', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `full_access` int(1) NOT NULL DEFAULT 0,
  `email` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_access`, `email`) VALUES
(0, 'admin', 'da66b25bd582b26e1c31faeea9cd0a78', 1, ''),
(1, 'user', 'da66b25bd582b26e1c31faeea9cd0a78', 0, 'heroinsadness@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event` (`event`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
