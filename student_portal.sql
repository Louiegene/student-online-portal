-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 16, 2025 at 08:37 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE `Categories` (
  `category_id` int(11) NOT NULL,
  `category_code` varchar(10) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`category_id`, `category_code`, `category_name`) VALUES
(1, 'ABM', 'Accountancy, Business, and Management'),
(2, 'HUMSS', 'Humanities and Social Sciences'),
(3, 'STEM', 'Science, Technology, Engineering, and Mathematics'),
(4, 'GAS', 'General Academic Strand'),
(5, 'PBM', 'Pre-Baccalaureate Maritime'),
(6, 'AFA', 'Agri-Fishery Arts'),
(7, 'HE', 'Home Economics'),
(8, 'ICT', 'Information and Communications Technology'),
(9, 'IA', 'Industrial Arts'),
(10, 'ALR', 'Applied Learning and Research');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lrn` varchar(12) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `grade` decimal(5,2) NOT NULL,
  `school_year` varchar(9) NOT NULL,
  `semester` enum('1st','2nd') NOT NULL,
  `quarter` enum('1st','2nd','3rd','4th') NOT NULL,
  `grade_level` varchar(10) NOT NULL,
  `upload_date` datetime DEFAULT current_timestamp(),
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `user_id`, `lrn`, `subject_id`, `grade`, `school_year`, `semester`, `quarter`, `grade_level`, `upload_date`, `last_modified`) VALUES
(18, 5, '123456789012', 22, 98.00, '2023-2024', '1st', '1st', '11', '2023-09-15 10:00:00', '2025-04-23 09:54:47'),
(19, 5, '123456789012', 20, 88.75, '2023-2024', '1st', '1st', '11', '2023-09-30 10:00:00', '2025-04-23 09:54:47'),
(20, 5, '123456789012', 15, 92.00, '2023-2024', '1st', '1st', '11', '2023-10-15 10:00:00', '2025-04-23 09:54:47'),
(21, 6, '987654321098', 1, 78.00, '2023-2024', '1st', '1st', '12', '2023-09-01 11:00:00', '2025-04-23 07:19:27'),
(22, 6, '987654321098', 2, 85.00, '2023-2024', '1st', '2nd', '12', '2023-09-15 11:00:00', '2025-04-23 07:19:27'),
(23, 6, '987654321098', 3, 80.50, '2023-2024', '1st', '3rd', '12', '2023-09-30 11:00:00', '2025-04-23 07:19:27'),
(24, 6, '987654321098', 4, 82.25, '2023-2024', '1st', '4th', '12', '2023-10-15 11:00:00', '2025-04-23 07:19:27'),
(27, 7, '111113445566', 3, 91.50, '2023-2024', '2nd', '3rd', '11', '2024-01-30 09:00:00', '2025-04-23 07:19:27'),
(28, 7, '111113445566', 4, 89.75, '2023-2024', '2nd', '4th', '11', '2024-02-10 09:00:00', '2025-04-23 07:19:27'),
(29, 8, '223344556677', 1, 76.50, '2023-2024', '2nd', '1st', '12', '2024-01-10 10:30:00', '2025-04-23 07:19:27'),
(30, 8, '223344556677', 2, 80.00, '2023-2024', '2nd', '2nd', '12', '2024-01-20 10:30:00', '2025-04-23 07:19:27'),
(31, 8, '223344556677', 3, 78.25, '2023-2024', '2nd', '3rd', '12', '2024-01-30 10:30:00', '2025-04-23 07:19:27'),
(32, 8, '223344556677', 4, 81.00, '2023-2024', '2nd', '4th', '12', '2024-02-10 10:30:00', '2025-04-23 07:19:27'),
(37, 26, '104689998655', 14, 90.00, '2025-2026', '1st', '1st', '11', '2025-04-22 23:22:43', '2025-04-23 07:19:27'),
(38, 26, '104689998655', 73, 91.00, '2025-2026', '1st', '1st', '11', '2025-04-22 23:40:11', '2025-04-23 07:19:27'),
(39, 26, '104689998655', 65, 90.00, '2025-2026', '1st', '1st', '11', '2025-04-22 23:48:13', '2025-04-23 07:19:27'),
(40, 26, '104689998655', 66, 88.00, '2025-2026', '1st', '1st', '11', '2025-04-23 00:12:26', '2025-04-23 07:19:27'),
(44, 8, '456789012345', 4, 90.00, '2025-2026', '1st', '1st', '12', '2025-04-23 00:18:14', '2025-04-23 07:19:27'),
(45, 26, '104689998655', 74, 98.00, '2025-2026', '1st', '1st', '11', '2025-04-23 00:48:22', '2025-04-23 07:19:27'),
(49, 22, '104656789876', 73, 97.00, '2025-2026', '1st', '1st', '11', '2025-04-23 14:22:18', '2025-04-23 07:19:27'),
(50, 22, '104656789876', 72, 91.00, '2025-2026', '1st', '1st', '11', '2025-04-23 14:22:18', '2025-04-23 07:19:27'),
(51, 22, '104656789876', 58, 90.00, '2025-2026', '1st', '1st', '11', '2025-04-23 14:22:18', '2025-04-23 07:19:27'),
(52, 22, '104656789876', 74, 95.00, '2025-2026', '1st', '1st', '11', '2025-04-23 14:22:18', '2025-04-23 07:19:27'),
(53, 22, '104656789876', 31, 98.00, '2025-2026', '1st', '1st', '11', '2025-04-23 14:22:18', '2025-04-23 07:19:27'),
(54, 23, '897686678908', 15, 90.00, '2025-2026', '1st', '1st', '11', '2025-04-23 14:40:14', '2025-04-24 13:01:24'),
(55, 23, '897686678908', 17, 95.00, '2025-2026', '1st', '1st', '11', '2025-04-23 14:40:14', '2025-04-24 13:01:24'),
(56, 23, '897686678908', 20, 90.00, '2025-2026', '1st', '1st', '11', '2025-04-23 14:40:14', '2025-04-24 13:01:24'),
(57, 88, '104634589000', 31, 95.00, '2025-2026', '1st', '1st', '11', '2025-04-23 17:56:20', '2025-04-23 09:56:20'),
(58, 26, '104689998655', 47, 97.00, '2025-2026', '1st', '2nd', '11', '2025-04-24 03:12:12', '2025-04-23 19:12:12'),
(59, 26, '104689998655', 59, 95.00, '2025-2026', '1st', '2nd', '11', '2025-04-24 03:12:12', '2025-04-23 19:12:12'),
(60, 5, '123456789012', 21, 90.00, '2025-2026', '1st', '2nd', '11', '2025-04-24 22:13:18', '2025-04-29 09:22:04'),
(61, 7, '104613445566', 58, 90.00, '2023-2024', '1st', '1st', '11', '2025-04-24 23:31:53', '2025-04-29 15:24:15'),
(62, 7, '104613445566', 66, 87.00, '2023-2024', '1st', '1st', '11', '2025-04-24 23:31:53', '2025-04-29 15:24:15'),
(63, 26, '104689998655', 73, 90.00, '2025-2026', '1st', '2nd', '11', '2025-04-29 22:05:48', '2025-04-29 14:05:48'),
(64, 26, '104689998655', 42, 98.00, '2025-2026', '1st', '2nd', '11', '2025-04-29 22:05:48', '2025-04-29 14:05:48'),
(65, 86, '104666755657', 67, 80.00, '2025-2026', '1st', '2nd', '11', '2025-04-29 23:22:57', '2025-04-29 15:22:57'),
(66, 86, '104666755657', 66, 90.00, '2025-2026', '1st', '2nd', '11', '2025-04-29 23:22:57', '2025-04-29 15:22:57'),
(70, 26, '104689998655', 92, 90.00, '2025-2026', '2nd', '3rd', '11', '2025-04-30 00:15:43', '2025-04-29 16:15:43'),
(71, 86, '104666755657', 1, 90.00, '2024-2025', '1st', '1st', '12', '2025-04-30 00:31:43', '2025-04-29 16:31:43'),
(72, 86, '104666755657', 13, 90.00, '2024-2025', '1st', '1st', '11', '2025-04-30 00:33:41', '2025-04-29 16:33:41'),
(73, 90, '104600001234', 47, 99.00, '2025-2026', '1st', '1st', '11', '2025-05-15 20:03:07', '2025-05-15 12:05:17'),
(75, 90, '104600001234', 58, 95.00, '2025-2026', '1st', '2nd', '11', '2025-05-15 20:13:38', '2025-05-15 12:13:38'),
(76, 90, '104600001234', 65, 96.00, '2025-2026', '1st', '2nd', '11', '2025-05-15 20:13:38', '2025-05-15 12:13:38');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Strand`
--

CREATE TABLE `Strand` (
  `strand_id` int(11) NOT NULL,
  `strandname` varchar(100) NOT NULL,
  `track_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Strand`
--

INSERT INTO `Strand` (`strand_id`, `strandname`, `track_id`, `created_at`, `updated_at`) VALUES
(1, 'Accountancy, Business, and Management (ABM)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Science, Technology, Engineering, and Mathematics (STEM)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Humanities and Social Sciences (HUMSS)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'General Academic Strand (GAS)', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Agri-Fishery Arts', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Home Economics', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Industrial Arts', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Information and Communication Technology (ICT)', 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

CREATE TABLE `student_info` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `enrollment_date` date NOT NULL,
  `school_year` varchar(50) NOT NULL,
  `lrn` varchar(12) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT '-',
  `last_name` varchar(50) NOT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `grade_level` varchar(20) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `track_id` int(11) NOT NULL,
  `strand_id` int(11) NOT NULL,
  `specific_strand` varchar(255) DEFAULT 'N/A',
  `enrollment_status` enum('Enrolled','Dropped','Transferred Out','No Longer Participating (NLP)') DEFAULT 'Enrolled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_info`
--

INSERT INTO `student_info` (`student_id`, `user_id`, `enrollment_date`, `school_year`, `lrn`, `first_name`, `middle_name`, `last_name`, `gender`, `grade_level`, `section`, `birthdate`, `track_id`, `strand_id`, `specific_strand`, `enrollment_status`, `created_at`, `updated_at`) VALUES
(11, 5, '2024-06-01', '2023-2024', '123456789012', 'Mikhaela Janna', 'Jimenea', 'Lim', 'Female', '11', 'A', '2006-03-24', 1, 1, 'N/A', 'Enrolled', '2025-03-28 14:22:04', '2025-04-29 15:20:32'),
(12, 6, '2024-06-01', '2023-2024', '987654321098', 'Maria', 'Santos', 'Lopez', 'Female', '12', 'B', '2006-08-12', 1, 2, 'N/A', 'Enrolled', '2025-03-28 14:22:04', '2025-04-18 17:02:40'),
(13, 7, '2024-06-01', '2023-2024', '104613445566', 'Pedro', 'Reyes', 'Perez', 'Male', '11', 'A', '2006-11-28', 2, 8, 'Computer Systems Servicing NC II', 'Enrolled', '2025-03-28 14:22:04', '2025-04-18 17:02:43'),
(14, 8, '2024-06-01', '2023-2024', '456789012345', 'Ana', 'Rivera', 'Joseph', 'Female', '12', 'B', '2006-09-02', 2, 6, 'Cookery NC II', 'Enrolled', '2025-03-28 14:22:04', '2025-04-22 16:17:51'),
(17, 22, '2025-06-12', '2023-2024', '104656789876', 'Albert', 'Aguilar', 'Francisco', 'Male', '11', 'A', '1996-01-24', 1, 2, 'N/A', 'Enrolled', '2025-04-17 07:38:44', '2025-04-20 12:15:27'),
(18, 23, '2025-06-15', '2023-2024', '897686678908', 'Joan Lou', 'Tinao', 'Donato', 'Female', '11', 'A', '1996-10-17', 1, 1, 'N/A', 'Enrolled', '2025-04-17 07:40:21', '2025-04-18 17:02:47'),
(19, 24, '2025-06-25', '2023-2024', '888775564564', 'Shaendy', 'Tinao', 'Donato', 'Female', '11', 'A', '2001-06-21', 1, 3, 'N/A', 'Enrolled', '2025-04-17 07:41:35', '2025-04-18 17:02:50'),
(20, 25, '2025-06-15', '2023-2024', '104699211526', 'Paulo Joseph', 'Tinao', 'Santos', 'Male', '11', 'A', '1994-10-22', 1, 3, 'N/A', 'Enrolled', '2025-04-17 07:42:34', '2025-04-18 17:02:51'),
(21, 26, '2025-06-15', '2023-2024', '104689998655', 'Louiegene', 'Tinao', 'Donato', 'Male', '11', 'A', '1995-11-28', 2, 8, 'Computer Programming Java', 'Enrolled', '2025-04-17 08:22:47', '2025-04-29 07:37:50'),
(23, 28, '2025-06-15', '2023-2024', '104678433445', 'Arshe Rina', 'Omaguing', 'Donato', 'Female', '11', 'A', '1994-08-12', 1, 2, 'N/A', 'Enrolled', '2025-04-17 08:40:02', '2025-04-19 01:41:20'),
(24, 29, '2025-06-15', '2023-2024', '104656238764', 'Aldrin', 'Isidro', 'Eperida', 'Male', '11', 'A', '1991-08-15', 2, 3, 'N/A', 'Enrolled', '2025-04-17 09:12:07', '2025-04-18 17:02:58'),
(26, 59, '2025-04-21', '2023-2024', '104677889900', 'Leonard', 'Mariano', 'Tinao', 'Male', '11', 'A', '2002-04-20', 1, 2, NULL, 'Enrolled', '2025-04-21 15:50:02', '2025-04-21 15:50:02'),
(28, 61, '2025-04-22', '2023-2024', '104621897644', 'Vice', '', 'Ganda', 'Male', '11', 'B', '1996-02-28', 2, 8, '', 'No Longer Participating (NLP)', '2025-04-21 16:15:17', '2025-04-29 10:53:08'),
(44, 86, '2025-04-22', '2023-2024', '104666755657', 'Nikkie', NULL, 'Minaj', 'Female', '12', 'C', '2005-11-11', 2, 6, 'Hairdressing', 'Enrolled', '2025-04-21 17:36:33', '2025-04-21 17:36:33'),
(46, 88, '2025-04-23', '2025-2026', '104634589000', 'Cheyser Charrese', 'Cuevas', 'Gatchula', 'Female', '11', 'A', '1989-06-18', 1, 2, '', 'Enrolled', '2025-04-22 17:19:47', '2025-04-22 17:21:52'),
(48, 90, '2025-05-15', '2025-2026', '104600001234', 'Andie', NULL, 'Ramos', 'Male', '11', 'C', '2000-10-22', 2, 8, 'Computer Programming Java', 'Enrolled', '2025-05-15 11:57:14', '2025-05-15 11:57:14');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(10) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_type` enum('Core Subject','Applied Subject','Specialized Subject','') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_code`, `subject_name`, `subject_type`, `created_at`, `updated_at`) VALUES
(1, 'HOME104', 'Beauty/Nail Care (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:23:18'),
(2, 'HOME105', 'Bread and Pastry Production (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:23:28'),
(3, 'HOME106', 'Caregiving (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:23:38'),
(4, 'HOME108', 'Cookery (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:23:51'),
(5, 'HOME109', 'Dressmaking (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:30:41'),
(6, 'HOME112', 'Food and Beverage Services (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:30:46'),
(7, 'HOME113', 'Front Office Services (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:30:52'),
(8, 'HOME114', 'Hairdressing (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:30:59'),
(9, 'HOME115', 'Hairdressing (NC III)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:42:47'),
(10, 'HOME116', 'Housekeeping (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:45:27'),
(11, 'HOME118', 'Tailoring (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:42:37'),
(12, 'HOME121', 'Wellness Massage (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:42:31'),
(13, 'ICT101', 'Animation (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:31:13'),
(14, 'ICT103', 'Computer Programming (.NET Technology) (NC III)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:31:07'),
(15, 'ABM101', 'Applied Economics', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:43:27'),
(16, 'ABM102', 'Business Ethics and Social Responsibility', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:43:36'),
(17, 'ABM201', 'Fundamentals of Accountancy Business and Management 1', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:43:53'),
(18, 'ABM202', 'Fundamentals of Accountancy Business and Management 2', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:43:59'),
(19, 'ABM203', 'Business Math', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:44:09'),
(20, 'ABM204', 'Business Finance', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:44:14'),
(21, 'ABM205', 'Organization and Management', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:44:26'),
(22, 'ABM206', 'Principles of Marketing', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:44:31'),
(23, 'HUMSS101', 'Creative Writing / Malikhaing Pagsulat', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:47:57'),
(24, 'HUMSS102', 'Introduction to World Religions and Belief Systems', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:48:31'),
(25, 'HUMSS103', 'Creative Nonfiction', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:48:14'),
(26, 'HUMSS104', 'Trends, Networks, and Critical Thinking in the 21st Century Culture', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:49:37'),
(27, 'HUMSS105', 'Philippine Politics and Governance', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:49:25'),
(28, 'HUMSS106', 'Community Engagement, Solidarity, and Citizenship', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:48:47'),
(29, 'HUMSS107', 'Disciplines and Ideas in the Social Sciences', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:49:50'),
(30, 'HUMSS108', 'Disciplines and Ideas in the Applied Social Sciences', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:50:03'),
(31, 'STEM101', 'Pre-Calculus', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:47:29'),
(32, 'STEM102', 'Basic Calculus', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:47:25'),
(33, 'STEM201', 'General Biology 1', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:47:21'),
(34, 'STEM202', 'General Biology 2', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:47:16'),
(35, 'STEM301', 'General Physics 1', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:47:11'),
(36, 'STEM302', 'General Physics 2', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:47:05'),
(37, 'STEM401', 'General Chemistry 1', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:47:01'),
(38, 'STEM402', 'General Chemistry 2', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:46:56'),
(39, 'GAS202', 'Applied Economics', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:46:50'),
(40, 'GAS203', 'Organization and Management', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:50:12'),
(41, 'GAS204', 'Disaster Readiness and Risk Reduction', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:51:22'),
(42, 'WI101', 'Work Immersion', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:44:55'),
(45, 'IM101', 'Inquiries, Investigations, and Immersion', 'Applied Subject', '2025-04-04 20:29:44', '2025-04-18 00:42:25'),
(46, 'CA101', 'Culminating Activity', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:45:08'),
(47, 'ICT104', 'Computer Programming (Java) (NC III)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:25:29'),
(48, 'ICT105', 'Computer Programming (Oracle Database) (NC III)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:24:37'),
(49, 'ICT106', 'Computer Systems Servicing (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:24:30'),
(50, 'ICT107', 'Contact Center Services (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:24:24'),
(51, 'ICT108', 'Illustration (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-18 00:24:17'),
(52, 'ICT109', 'Medical Transcription (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:50:37'),
(53, 'ICT110', 'Technical Drafting (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:50:30'),
(54, 'IND107', 'Electrical Installation and Maintenance (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:49:38'),
(55, 'IND108', 'Electric Power Distribution Line Construction (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:49:43'),
(56, 'IND109', 'Electronic Products Assembly and Servicing (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:49:48'),
(57, 'IND120', 'Shielded Metal Arc Welding (NC II)', 'Specialized Subject', '2025-04-04 20:29:44', '2025-04-17 23:50:24'),
(58, 'ICT210', 'Empowerment Technologies', 'Applied Subject', '2025-04-04 20:29:44', '2025-04-17 23:49:57'),
(59, 'MIL102', 'Media and Information Literacy', 'Core Subject', '2025-04-04 20:29:44', '2025-04-17 23:49:28'),
(62, 'ENG201', 'Reading and Writing', 'Applied Subject', '2025-04-17 23:17:50', '2025-04-17 23:50:16'),
(63, 'SOC101', 'Personal Development', 'Core Subject', '2025-04-17 23:49:02', '2025-04-17 23:49:02'),
(64, 'MATH102', 'Statistics and Probability', 'Core Subject', '2025-04-18 00:26:04', '2025-04-18 00:26:04'),
(65, 'MATH101', 'General Mathematics', 'Core Subject', '2025-04-18 00:32:00', '2025-04-18 00:32:00'),
(66, 'SCI101', 'Earth and Life Science', 'Core Subject', '2025-04-18 00:32:32', '2025-04-18 00:32:32'),
(67, 'SCI102', 'Physical Science', 'Core Subject', '2025-04-18 00:32:53', '2025-04-18 00:32:53'),
(68, 'PHILO101', 'Introduction to Philosophy of the Human Person', 'Core Subject', '2025-04-18 00:33:28', '2025-04-18 00:34:16'),
(69, 'HUMSS109', 'Contemporary Philippine Arts from the Regions', 'Core Subject', '2025-04-18 00:35:11', '2025-04-18 00:35:11'),
(70, 'HUMSS110', '21st-century Literature from the Philippines and the World', 'Core Subject', '2025-04-18 00:35:44', '2025-04-18 00:35:44'),
(71, 'FIL102', 'Pagbasa at Pagsusuri ng Iba’t-ibang Teksto Tungo sa Pananaliksik', 'Core Subject', '2025-04-18 00:36:20', '2025-04-18 00:36:20'),
(72, 'FIL101', 'Komunikasyon at Pananaliksik sa Wika at Kulturang Filipino', 'Core Subject', '2025-04-18 00:36:41', '2025-04-18 00:36:41'),
(73, 'ENG101', 'Oral Communication', 'Core Subject', '2025-04-18 00:37:00', '2025-04-18 00:37:00'),
(74, 'PEH101', 'Physical Education and Health - Q1 Exercise for Fitness', 'Core Subject', '2025-04-18 00:37:54', '2025-04-18 00:37:54'),
(75, 'PEH102', 'Physical Education and Health - Q2 Individual, Dual, and Team Sports', 'Core Subject', '2025-04-18 00:38:09', '2025-04-18 00:38:09'),
(76, 'PEH103', 'Physical Education and Health - Q3 Philippine Dances', 'Core Subject', '2025-04-18 00:38:51', '2025-04-18 00:38:51'),
(77, 'PEH104', 'Physical Education and Health - Q4 Recreational Activities', 'Core Subject', '2025-04-18 00:39:20', '2025-04-18 00:39:20'),
(78, 'ENG202', 'English for Academic and Professional Purposes', 'Applied Subject', '2025-04-18 00:39:57', '2025-04-18 00:39:57'),
(79, 'PRACR1', 'Practical Research 1', 'Applied Subject', '2025-04-18 00:40:28', '2025-04-18 00:40:28'),
(80, 'PRACR2', 'Practical Research 2', 'Applied Subject', '2025-04-18 00:40:43', '2025-04-18 00:40:43'),
(81, 'FIL103', 'Filipino sa Piling Larangan (Akademik, Isports, Sining, at Tech-Voc)', 'Applied Subject', '2025-04-18 00:41:24', '2025-04-18 00:41:24'),
(82, 'ENTREP101', 'Entrepreneurships', 'Applied Subject', '2025-04-18 00:41:58', '2025-04-29 23:27:49'),
(83, 'ELEC101', 'Elective 1', 'Specialized Subject', '2025-04-18 00:51:00', '2025-04-18 00:51:00'),
(84, 'ELEC102', 'Elective 2', 'Specialized Subject', '2025-04-18 00:51:12', '2025-04-18 00:51:12'),
(85, 'MATH103', 'Finite Mathematics', 'Core Subject', '2025-04-18 19:50:12', '2025-04-18 19:50:12'),
(86, 'RCS', 'Recess', 'Applied Subject', '2025-04-20 20:23:42', '2025-04-20 20:23:42'),
(87, 'UWI', 'Uwian na', 'Core Subject', '2025-04-20 20:24:06', '2025-04-20 20:24:06'),
(88, 'SWF', 'Spelling with Feelings', 'Specialized Subject', '2025-04-20 20:24:21', '2025-04-20 20:24:21'),
(90, 'BRK1', 'Barking 101 (NC2)', 'Applied Subject', '2025-04-20 20:25:23', '2025-04-20 20:25:23'),
(91, 'ICT211', 'Robotics', 'Specialized Subject', '2025-04-22 00:56:38', '2025-04-22 00:56:38'),
(92, 'ICT112', 'Basic Computer', 'Applied Subject', '2025-04-23 17:58:44', '2025-04-23 17:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `Track`
--

CREATE TABLE `Track` (
  `track_id` int(11) NOT NULL,
  `trackname` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Track`
--

INSERT INTO `Track` (`track_id`, `trackname`, `created_at`, `updated_at`) VALUES
(1, 'Academic', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Technical-Vocational-Livelihood', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_picture` text NOT NULL DEFAULT 'user_profile.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `PASSWORD`, `role`, `email`, `created_at`, `updated_at`, `profile_picture`) VALUES
(4, 'limayshs_admin', '$2y$10$.lAbFcD6.2JvDKWIOc3MG.hKzcZ1J2U5DSXjoajPylB0eZMpYDP0q', 'admin', 'admin@example.com', '2025-03-26 23:50:51', '2025-04-23 21:47:26', 'profile_6808ef6e2bfc2.png'),
(5, 'student_user1', '$2a$10$UTvlQUkRdLC2PZUx6c0kxuHXK1Ql08zurygryioCWGAk2DkBhVO9a', 'student', 'john.delacruz@example.com', '2025-03-26 23:50:51', '2025-04-29 17:31:14', 'profile_68109c625cc71.jpg'),
(6, 'student_user2', '$2a$10$UTvlQUkRdLC2PZUx6c0kxuHXK1Ql08zurygryioCWGAk2DkBhVO9a', 'student', 'maria.santos@example.com', '2025-03-26 23:50:51', '2025-04-17 02:47:46', 'user_profile.png'),
(7, 'student_user3', '$2a$10$UTvlQUkRdLC2PZUx6c0kxuHXK1Ql08zurygryioCWGAk2DkBhVO9a', 'student', 'pedro.perez@example.com', '2025-03-26 23:53:16', '2025-04-17 02:47:50', 'user_profile.png'),
(8, 'student_user4', '$2a$10$UTvlQUkRdLC2PZUx6c0kxuHXK1Ql08zurygryioCWGAk2DkBhVO9a', 'student', 'ana.joseph@example.com', '2025-03-26 23:53:16', '2025-04-17 02:47:55', 'user_profile.png'),
(22, 'albertfrancisco00', '$2y$10$k5B11bRq5cHUblE3ZY7VvOBJSRfJgXKifikSW2/7lCa7bJeuJJndC', 'student', 'albertfrancisco@gmail.com', '2025-04-17 15:38:44', '2025-04-17 15:38:44', 'user_profile.png'),
(23, 'joandonato00', '$2y$10$alk/dNPR4.fjaZ1YHEjGM.MQ8IuSyRuzkYxgj8H0xSgDbvHlrrBJy', 'student', 'joandonato@gmail.com', '2025-04-17 15:40:21', '2025-04-17 15:40:21', 'user_profile.png'),
(24, 'shaendydonato00', '$2y$10$t5Ht0Lw0COhokGjgJLjjFOMakMtRWJCrK/88LRE.vypAfrZ0qzPBy', 'student', 'shaendydonato@gmail.com', '2025-04-17 15:41:35', '2025-04-17 15:41:35', 'user_profile.png'),
(25, 'paulosantos00', '$2y$10$zT78UyhHgGxKZ1iWvoqUW.SRfC74XUe81aU/VG08Z.So2VoCu0vQa', 'student', 'paulosantos@gmail.com', '2025-04-17 15:42:34', '2025-04-17 15:42:34', 'user_profile.png'),
(26, 'louiegenedonato00', '$2y$12$1UFj0NMXffksU5asw3l79e8birpdSTunO0Z2PLBq.BvuGAiS.cSqm', 'student', 'louiegenedonato@gmail.com', '2025-04-17 16:22:47', '2025-04-29 23:17:11', 'profile_6810ed41a5635.jpg'),
(28, 'arsheomaguing00', '$2y$10$NYltC3nbyf.IwLxx/rFPN.80.X.xOF7drx0FMo18zJhnov8pyfrIK', 'student', 'arsherina12@gmail.com', '2025-04-17 16:40:02', '2025-04-17 16:40:02', 'user_profile.png'),
(29, 'aldrinesperida00', '$2y$10$jzL68o0cQ/LlpVVbHXDcUOdDyMM.Cayhg5RexxTdDt67x3/F6mb76', 'student', 'aldrinesperida@gmail.com', '2025-04-17 17:12:07', '2025-04-18 01:04:22', 'user_profile.png'),
(59, 'leonard00', '$2y$10$K.5XW4ZJ8rjh8o//knMM3OswZHPyiSGS9KPiIgWmJ7SHtqN4m.B/W', 'student', 'leonard@gmail.com', '2025-04-21 23:50:02', '2025-04-21 23:50:02', 'user_profile.png'),
(61, 'viceganda00', '$2y$10$6grRGl/ZopxLmmOXD1S6Rejr9YTP8acmARyr3bZYCWfLjDnIpJYqu', 'student', 'viceganda@gmail.com', '2025-04-22 00:15:17', '2025-04-22 00:15:17', 'user_profile.png'),
(86, 'nikkieminaj001', '$2y$10$6uitVOa5guJoTtKWTnksquxBT2x/mRULO0KxSCQCTrXZNaZTwLYni', 'student', 'nikkieminaj001@gmail.com', '2025-04-22 01:36:33', '2025-04-22 01:36:33', 'user_profile.png'),
(88, 'chagatchula00', '$2y$10$tec51QWYyBQ6wQwE/ORuYO7dIOBK5M84DY/1LVLX/8Zv84ojp.i3e', 'student', 'gatchula@gmail.com', '2025-04-23 01:19:47', '2025-04-23 01:19:47', 'user_profile.png'),
(90, 'andieramos00', '$2y$10$6zzuEGX1VFP.eR8yCTVVVe5SqlmA7wpYQu0TOtSBDefX6TGqdj8Sm', 'student', 'andie@example.com', '2025-05-15 19:57:14', '2025-05-15 19:59:07', 'profile_6825d70b5d64f.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_code` (`category_code`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `grades_ibfk_2` (`subject_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Strand`
--
ALTER TABLE `Strand`
  ADD PRIMARY KEY (`strand_id`),
  ADD KEY `track_id` (`track_id`);

--
-- Indexes for table `student_info`
--
ALTER TABLE `student_info`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `track_id` (`track_id`),
  ADD KEY `strand_id` (`strand_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `Track`
--
ALTER TABLE `Track`
  ADD PRIMARY KEY (`track_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Strand`
--
ALTER TABLE `Strand`
  MODIFY `strand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student_info`
--
ALTER TABLE `student_info`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `Track`
--
ALTER TABLE `Track`
  MODIFY `track_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Strand`
--
ALTER TABLE `Strand`
  ADD CONSTRAINT `strand_ibfk_1` FOREIGN KEY (`track_id`) REFERENCES `Track` (`track_id`);

--
-- Constraints for table `student_info`
--
ALTER TABLE `student_info`
  ADD CONSTRAINT `student_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_info_ibfk_2` FOREIGN KEY (`track_id`) REFERENCES `Track` (`track_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_info_ibfk_3` FOREIGN KEY (`strand_id`) REFERENCES `Strand` (`strand_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
