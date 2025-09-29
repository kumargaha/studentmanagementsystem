-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 03:51 AM
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
-- Database: `attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `att`
--

CREATE TABLE `att` (
  `id` int(255) NOT NULL,
  `stud_id` int(255) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `stud_name` varchar(255) NOT NULL,
  `class_date` date NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `att`
--

INSERT INTO `att` (`id`, `stud_id`, `subject_name`, `stud_name`, `class_date`, `status`) VALUES
(31, 7100200, 'Advanced Java Programming', 'Gaha', '2025-05-25', 'Present'),
(32, 7100200, 'Distributed System', 'Gaha', '2025-05-25', 'Present'),
(33, 23, 'Distributed System', 'Sases', '2025-05-25', 'Absent'),
(34, 7100200, 'Applied Economics', 'Gaha', '2025-05-25', 'Present'),
(35, 23, 'Applied Economics', 'Sases', '2025-05-25', 'Present'),
(36, 2345, 'Applied Economics', 'Yaksha', '2025-05-25', 'Present'),
(37, 7100200, 'Network Programming', 'Gaha', '2025-05-25', 'Present'),
(38, 553, 'Network Programming', 'Niraj Chaudhary', '2025-05-25', 'Present'),
(39, 23, 'Network Programming', 'Sases', '2025-05-25', 'Present'),
(40, 2345, 'Network Programming', 'Yaksha', '2025-05-25', 'Present'),
(41, 7100200, 'Advanced Java Programming', 'Gaha', '2025-05-26', 'Present'),
(42, 553, 'Advanced Java Programming', 'Niraj Chaudhary', '2025-05-26', 'Absent'),
(43, 227462, 'Advanced Java Programming', 'Sahil Kunwar', '2025-05-26', 'Present'),
(44, 23, 'Advanced Java Programming', 'Sases', '2025-05-26', 'Present'),
(45, 2345, 'Advanced Java Programming', 'Yaksha', '2025-05-26', 'Absent'),
(46, 512, 'Advanced Java Programming', 'Yubraj Shrestha', '2025-05-26', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stud_id` varchar(255) NOT NULL,
  `stud_name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stud_id`, `stud_name`) VALUES
('227462', 'Sahil Kunwar'),
('23', 'Sases'),
('2345', 'Yaksha'),
('512', 'Yubraj Shrestha'),
('553', 'Niraj Chaudhary'),
('7100200', 'Gaha');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(255) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_code` varchar(255) NOT NULL,
  `teacher_name` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `subject_name`, `subject_code`, `teacher_name`) VALUES
(8, 'Mobile Programming', 'CACS351', 'RamBahadur'),
(9, 'Distributed System', 'CACS352', 'Bam Bahadur'),
(10, 'Applied Economics', 'CACS353', 'Suur Bahadur'),
(11, 'Advanced Java Programming', 'CACS354', 'Gam Bahadur'),
(12, 'Network Programming', 'CACS355', 'Hem Bahadur');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirmpassword` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `confirmpassword`, `user_type`) VALUES
(1, 'yaksha', 'Yaksha1', 'Yaksha1', '2'),
(3, 'student', 'Student1', 'Student1', '1'),
(5, 'studentt', 'Student1', 'Student1', '1'),
(6, 'gaha', 'Gaha1', 'Gaha1', '1'),
(7, 'admin', 'Admin1', 'Admin1', '2'),
(8, 'chano', 'Chano1', 'Chano1', '1'),
(9, 'green', 'Green1', 'Green1', '1'),
(10, 'iphone', 'Iphone1', 'Iphone1', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `att`
--
ALTER TABLE `att`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `att`
--
ALTER TABLE `att`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
