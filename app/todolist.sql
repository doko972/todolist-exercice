-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jun 27, 2024 at 03:01 PM
-- Server version: 8.0.37
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todolist`
--

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `Id_task` int NOT NULL,
  `priority` int NOT NULL,
  `description` varchar(150) NOT NULL,
  `creation_date` date NOT NULL,
  `done` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`Id_task`, `priority`, `description`, `creation_date`, `done`) VALUES
(2, 2, 'Tâche moyenne priorité', '2023-06-27', 1),
(4, 3, 'action', '2024-06-27', 1),
(8, 2, 'manger', '2024-06-27', 0),
(9, 2, 'dormir', '2024-06-27', 0),
(10, 1, 'Conduire mamie', '2024-06-27', 0),
(11, 3, 'tester', '2024-06-27', 0),
(13, 1, 'conduire la voiture', '2024-06-27', 1),
(14, 2, 'telephoner impots', '2024-06-27', 0),
(15, 1, 'ajouter', '2024-06-27', 1),
(17, 3, 'faire le menage', '2024-06-27', 0),
(18, 3, 'absober', '2024-06-27', 0),
(19, 3, 'voir beber', '2024-06-27', 0),
(20, 2, 'se moucher', '2024-06-27', 0),
(21, 1, 'ajouter encore', '2024-06-27', 0),
(22, 2, 'ajouter encore une tache', '2024-06-27', 0),
(23, 2, 'tester mon php', '2024-06-27', 0),
(24, 3, 'ajouter encore et encore', '2024-06-27', 0),
(25, 1, 'travailler', '2024-06-27', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`Id_task`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `Id_task` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
