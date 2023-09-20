-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2023 at 07:47 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dcbt`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `program_section` varchar(100) NOT NULL,
  `program_id` int(11) NOT NULL,
  `creationDate` datetime DEFAULT current_timestamp(),
  `course_level` int(1) NOT NULL,
  `min_student` tinyint(2) NOT NULL,
  `capacity` int(2) NOT NULL,
  `adviser_teacher_id` int(11) DEFAULT NULL,
  `first_period_room_id` int(5) DEFAULT NULL,
  `second_period_room_id` int(5) DEFAULT NULL,
  `room` varchar(6) NOT NULL,
  `school_year_term` varchar(10) NOT NULL,
  `active` varchar(3) NOT NULL DEFAULT 'no',
  `is_tertiary` tinyint(1) NOT NULL DEFAULT 0,
  `is_full` varchar(3) NOT NULL DEFAULT 'no',
  `previous_course_id` int(11) DEFAULT NULL,
  `is_remove` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `program_section`, `program_id`, `creationDate`, `course_level`, `min_student`, `capacity`, `adviser_teacher_id`, `first_period_room_id`, `second_period_room_id`, `room`, `school_year_term`, `active`, `is_tertiary`, `is_full`, `previous_course_id`, `is_remove`) VALUES
(1253, 'STEM11-A', 26, '2023-09-15 15:18:49', 11, 2, 3, NULL, NULL, NULL, '', '2023-2024', 'no', 1, 'yes', NULL, 0),
(1260, 'STEM12-A', 26, '2023-09-16 08:24:01', 12, 2, 3, NULL, NULL, NULL, '', '2024-2025', 'yes', 1, 'no', 1253, 0),
(1261, 'STEM11-A', 26, '2023-09-16 08:24:01', 11, 15, 30, NULL, NULL, NULL, '', '2024-2025', 'yes', 0, 'no', NULL, 0),
(1262, 'HUMSS11-A', 27, '2023-09-16 08:24:01', 11, 15, 30, NULL, NULL, NULL, '', '2024-2025', 'yes', 0, 'no', NULL, 0),
(1264, 'STEM12-B', 26, '2023-09-16 12:13:09', 12, 0, 30, NULL, NULL, NULL, '', '2024-2025', 'yes', 0, 'no', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`) VALUES
(8, 'Senior High School'),
(9, 'Tertiary');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `enrollment_form_id` varchar(15) NOT NULL,
  `enrollment_date` datetime DEFAULT current_timestamp(),
  `enrollment_approve` datetime DEFAULT NULL,
  `enrollment_status` varchar(15) NOT NULL DEFAULT 'tentative',
  `is_tertiary` tinyint(1) NOT NULL DEFAULT 0,
  `is_new_enrollee` tinyint(1) NOT NULL DEFAULT 0,
  `is_transferee` int(1) NOT NULL,
  `is_returnee` tinyint(4) NOT NULL DEFAULT 0,
  `registrar_evaluated` varchar(3) NOT NULL DEFAULT 'no',
  `registrar_confirmation_date` datetime DEFAULT NULL,
  `cashier_evaluated` varchar(3) NOT NULL DEFAULT 'no',
  `cashier_confirmation_date` datetime DEFAULT NULL,
  `head_evaluated` varchar(3) NOT NULL DEFAULT 'no',
  `student_status` varchar(25) NOT NULL DEFAULT 'Regular',
  `retake` tinyint(1) DEFAULT 0,
  `waiting_list` varchar(3) NOT NULL DEFAULT 'no',
  `enrollment_withdraw_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`enrollment_id`, `student_id`, `course_id`, `school_year_id`, `enrollment_form_id`, `enrollment_date`, `enrollment_approve`, `enrollment_status`, `is_tertiary`, `is_new_enrollee`, `is_transferee`, `is_returnee`, `registrar_evaluated`, `registrar_confirmation_date`, `cashier_evaluated`, `cashier_confirmation_date`, `head_evaluated`, `student_status`, `retake`, `waiting_list`, `enrollment_withdraw_date`) VALUES
(1295, 6971, 1253, 40, 'X2IQ06', '2023-09-15 15:53:13', '2023-09-15 15:57:20', 'enrolled', 0, 1, 0, 0, 'yes', '2023-09-15 15:57:00', 'yes', '2023-09-15 15:57:15', 'no', 'Regular', 0, 'no', NULL),
(1296, 6971, 1253, 41, 'HB7T1N', '2023-09-15 16:10:42', '2023-09-15 16:15:05', 'enrolled', 0, 0, 0, 0, 'yes', '2023-09-15 16:14:44', 'yes', '2023-09-15 16:14:57', 'no', 'Regular', 0, 'no', NULL),
(1298, 6971, 1260, 42, 'Z9CO20', '2023-09-16 10:18:41', '2023-09-16 11:07:13', 'enrolled', 0, 0, 0, 0, 'yes', '2023-09-16 10:19:07', 'yes', '2023-09-16 10:19:17', 'no', 'Regular', 0, 'no', NULL),
(1299, 6972, 1260, 42, 'P24E7Q', '2023-09-16 11:08:10', '2023-09-16 11:08:59', 'enrolled', 0, 1, 0, 0, 'yes', '2023-09-16 11:08:13', 'yes', '2023-09-16 10:19:17', 'no', 'Regular', 0, 'no', NULL),
(1300, 6973, 1260, 42, 'C27YW1', '2023-09-16 11:23:42', '2023-09-16 12:13:08', 'enrolled', 0, 1, 0, 0, 'yes', '2023-09-16 11:23:49', 'yes', NULL, 'no', 'Regular', 0, 'no', NULL),
(1301, 6971, 1260, 43, 'H1L0PX', '2023-09-16 13:08:09', '2023-09-16 13:45:58', 'enrolled', 0, 0, 0, 0, 'yes', '2023-09-16 13:08:37', 'yes', '2023-09-16 13:08:51', 'no', 'Regular', 0, 'no', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `parent_id` int(11) NOT NULL,
  `pending_enrollees_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `suffix` varchar(50) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `relationship` varchar(25) NOT NULL,
  `father_firstname` varchar(150) NOT NULL,
  `father_lastname` varchar(150) NOT NULL,
  `father_middle` varchar(25) NOT NULL,
  `father_suffix` varchar(10) NOT NULL,
  `father_contact_number` varchar(25) NOT NULL,
  `father_email` varchar(100) NOT NULL,
  `father_occupation` varchar(100) NOT NULL,
  `mother_firstname` varchar(150) NOT NULL,
  `mother_lastname` varchar(150) NOT NULL,
  `mother_middle` varchar(25) NOT NULL,
  `mother_suffix` varchar(10) NOT NULL,
  `mother_contact_number` varchar(150) NOT NULL,
  `mother_email` varchar(150) NOT NULL,
  `mother_occupation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`parent_id`, `pending_enrollees_id`, `student_id`, `firstname`, `middle_name`, `lastname`, `contact_number`, `email`, `date_creation`, `active`, `suffix`, `occupation`, `relationship`, `father_firstname`, `father_lastname`, `father_middle`, `father_suffix`, `father_contact_number`, `father_email`, `father_occupation`, `mother_firstname`, `mother_lastname`, `mother_middle`, `mother_suffix`, `mother_contact_number`, `mother_email`, `mother_occupation`) VALUES
(67, 158, 6923, 'Parent', 'B', 'Sirios', '91515123123', 'parenthyper15@gmail.com', '2023-06-24 15:58:32', 1, 'Jr', 'Hope to be the best of my self.', 'Auntie', 'test', 'Sirios', 'test', 'Jr', '09151515123', 'hypersirios15@gmail.com', 'FATHER', 'Hyper', 'Sirios', 'Hyper', 'Jr', '91515123', 'hyper15@gmail.com', 'mama'),
(69, 161, 6924, 'ParentTransferee ', 'Surname', 'Z', '0915151515123', 'parent@gmail.com', '2023-06-24 16:52:14', 1, '', 'Loyal', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(104, 220, 694, '', '', '', '', '', '2023-08-20 20:21:35', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', 'far@gmail.com', '', 'Mather', 'Mather', 'Mather', '', '09151515123', 'hypersirios15@gmail.com', ''),
(105, 221, 696, '', '', '', '', '', '2023-08-21 09:41:11', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', 'far@gmail.com', '', '', '', '', '', '', '', ''),
(106, NULL, 6908, '', '', '', '', '', '2023-08-22 16:56:25', 1, '', '', '', 'Father', 'Father', 'FATHER', '', '09151515123', 'far@gmail.com', '', 'Father', 'Father', 'FATHER', '', '09151515123', 'far@gmail.com', ''),
(107, NULL, 6910, '', '', '', '', '', '2023-08-23 14:49:18', 1, '', '', '', 'Father', 'Sirios', 'ABULENCIA', '', '09686033433', 'justinesirios15@gmail.com', '', 'Mather', 'Mather', 'MATHER', '', '09151515123', 'hypersirios15@gmail.com', ''),
(141, NULL, 6939, 'Father', 'Father', 'Father', '09151515123', '', '2023-08-30 14:53:27', 1, '', '', 'Auntie', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(142, NULL, 6940, 'Father', 'Father', 'Father', '09151515123', '', '2023-08-30 15:52:08', 1, 'Sr', '', 'Autniex', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(143, NULL, 6941, 'Father', 'Father', 'Father', '09151515123', '', '2023-08-31 10:16:09', 1, '', '', 'Relative', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(144, NULL, 6942, '', '', '', '', '', '2023-08-31 12:12:31', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(145, NULL, 6943, '', '', '', '', '', '2023-08-31 12:25:06', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(146, NULL, 6944, '', '', '', '', '', '2023-08-31 14:50:22', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(147, NULL, 6945, '', '', '', '', '', '2023-08-31 14:50:56', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(148, NULL, 6946, '', '', '', '', '', '2023-08-31 14:51:17', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(149, 227, 6950, '', '', '', '', '', '2023-08-31 15:04:21', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(150, NULL, 6947, '', '', '', '', '', '2023-08-31 15:29:43', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151516123', '', '', '', '', '', '', '', '', ''),
(151, NULL, 6948, '', '', '', '', '', '2023-08-31 15:36:27', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(153, NULL, 6955, '', '', '', '', '', '2023-09-02 17:34:04', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(154, NULL, 6956, '', '', '', '', '', '2023-09-03 07:50:55', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151616123', '', '', '', '', '', '', '', '', ''),
(155, NULL, 6957, '', '', '', '', '', '2023-09-03 08:23:08', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(156, NULL, 6958, '', '', '', '', '', '2023-09-03 08:27:51', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(157, NULL, 6959, '', '', '', '', '', '2023-09-03 08:29:07', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(158, NULL, 6960, '', '', '', '', '', '2023-09-03 15:21:20', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(159, NULL, 6962, '', '', '', '', '', '2023-09-05 11:38:14', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(160, NULL, 6963, '', '', '', '', '', '2023-09-05 11:47:38', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(161, NULL, 6964, '', '', '', '', '', '2023-09-05 11:48:27', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(162, NULL, 6966, '', '', '', '', '', '2023-09-13 18:45:25', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(163, 233, 6967, '', '', '', '', '', '2023-09-13 20:59:37', 1, '', '', '', 'Father', 'Fatherupdated', 'Father', 'Sr', '09151516123', '', 'Hardworker', 'Mather', 'Mather', 'Mather', '', '09151515123', '', 'Hardworker'),
(164, 234, 6968, '', '', '', '', '', '2023-09-14 08:22:27', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(165, NULL, 6969, '', '', '', '', '', '2023-09-14 08:32:56', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(166, NULL, 6970, '', '', '', '', '', '2023-09-15 10:26:35', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(167, 235, 6971, '', '', '', '', '', '2023-09-15 15:52:33', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(168, NULL, 6972, '', '', '', '', '', '2023-09-16 11:08:10', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151515123', '', '', '', '', '', '', '', '', ''),
(169, NULL, 6973, '', '', '', '', '', '2023-09-16 11:23:42', 1, '', '', '', 'Father', 'Father', 'Father', '', '09151516123', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pending_enrollees`
--

CREATE TABLE `pending_enrollees` (
  `pending_enrollees_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `activated` tinyint(4) NOT NULL,
  `is_finished` tinyint(1) NOT NULL DEFAULT 0,
  `expiration_time` datetime DEFAULT NULL,
  `token` varchar(600) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `type` varchar(8) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `course_level` tinyint(1) DEFAULT NULL,
  `student_status` varchar(15) NOT NULL,
  `admission_status` varchar(25) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `suffix` varchar(3) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `birthday` date NOT NULL,
  `birthplace` varchar(250) NOT NULL,
  `age` int(11) NOT NULL,
  `sex` varchar(8) NOT NULL,
  `address` varchar(255) NOT NULL,
  `lrn` varchar(15) NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `religion` varchar(50) NOT NULL,
  `guardian_name` varchar(100) NOT NULL,
  `guardian_contact_number` varchar(20) NOT NULL,
  `category` varchar(3) NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pending_enrollees`
--

INSERT INTO `pending_enrollees` (`pending_enrollees_id`, `school_year_id`, `activated`, `is_finished`, `expiration_time`, `token`, `email`, `password`, `date_creation`, `type`, `program_id`, `course_level`, `student_status`, `admission_status`, `firstname`, `lastname`, `middle_name`, `suffix`, `civil_status`, `nationality`, `contact_number`, `birthday`, `birthplace`, `age`, `sex`, `address`, `lrn`, `date_approved`, `religion`, `guardian_name`, `guardian_contact_number`, `category`) VALUES
(158, 3, 1, 1, '2023-06-24 15:57:23', '0572ec3abbb90d4fe4462bdbefffb81b', 'hypersirios1@gmail.com', '$2y$10$JWPyOztzcmElFZcehnZgXeDxRCO2pj8DCPvEqY.3di3pJZ2B7hX0m', '2023-06-24 15:52:23', 'SHS', 3, 11, 'APPROVED', 'Standard', 'hyper1', 'sirios', 'A', 'Jr', 'Single', 'Filipino', '09151515123', '2000-06-15', 'Taguigarao', 23, 'Male', 'None', '555', '2023-07-08 18:30:52', 'None', '', '', 'New'),
(160, 3, 1, 1, '2023-06-24 16:15:21', 'f4ea395952325fa519b08286300ecf8e', 'ter1@gmail.com', '$2y$10$4cj4Qb56T3OITuuEA.kYSu2sSgv107vFcLFA3ZBq8CjigfG79opCu', '2023-06-24 16:10:21', 'Tertiary', 2, 1, 'APPROVED', 'Standard', 'ter1', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-15', 'Taguigarao', 23, 'Male', 'None', '5123', '2023-07-08 17:04:34', 'None', '', '', 'New'),
(161, 3, 1, 1, '2023-06-24 16:56:03', 'e493155fade4e8b83db1edeb0f8fbbd0', 'hypersirios2@gmail.com', '$2y$10$eGjadXb4p9sLSHb/E49MuuW2AIwz/W2eSKRWzNx.c74xnzD1AK2a2', '2023-06-24 16:51:03', 'SHS', 3, 11, 'APPROVED', 'Standard', 'hyper2', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-07', 'Taguigarao', 23, 'Male', 'None', '51234', '2023-06-29 17:51:17', 'None', '', '', 'New'),
(163, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios3@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 3, 11, 'APPROVED', 'Standard', 'hyper3', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5551', NULL, 'None', '', '', 'New'),
(207, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios4@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 3, 11, 'APPROVED', 'Standard', 'hyper4', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5556', NULL, 'None', '', '', 'New'),
(208, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios5@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 3, 11, 'WITHDRAW', 'Standard', 'hyper5', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '555223', NULL, 'None', '', '', 'New'),
(209, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'jana1@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 4, 11, 'APPROVED', 'Standard', 'jana1', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5552', NULL, 'None', '', '', 'New'),
(213, 3, 1, 0, '2023-08-18 20:16:15', '89802040ebeba581232aa8475c61324d', 'justinesirios15@gmail.com', '$2y$10$s6jCPHV2RRyZ52WiHbbns.c8bBAh.yUEnK0hXJPnFaiVfyEpGjy1K', '2023-08-18 20:11:15', NULL, NULL, NULL, '', '', 'hope', 'Sirios', 'A', '', '', '', '', '0000-00-00', '', 0, '', '', '', NULL, '', '', '', 'New'),
(214, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios7@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 3, 12, 'APPROVED', 'Standard', 'hyper7', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5553', NULL, 'None', '', '', 'New'),
(215, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios8@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 14:54:18', 'SHS', 3, 12, 'APPROVED', 'Standard', 'hyper8', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5554', NULL, 'None', '', '', 'New'),
(216, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios9@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 3, 11, 'APPROVED', 'Standard', 'hyper9', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5555', NULL, 'None', '', '', 'New'),
(217, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios10@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 4, 11, 'APPROVED', 'Standard', 'hyper10', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5556', NULL, 'None', '', '', 'New'),
(218, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios11@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 3, 11, 'APPROVED', 'Standard', 'hyper11', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5557', NULL, 'None', '', '', 'New'),
(219, 3, 1, 1, '2023-06-28 13:59:18', '6f5992352a02adf187e3fdec1c83f58e', 'hypersirios12@gmail.com', '$2y$10$9.3iwaL5/mvm8N6xZLDgEu1VhGv9/LhazAq8mmVueqQ2AFJFcf0.y', '2023-06-28 13:54:18', 'SHS', 3, 11, 'WITHDRAW', 'Standard', 'hyper12', 'sirios', 'A', '', 'Single', 'Filipino', '09151515123', '2000-06-21', 'Taguigarao', 23, 'Male', 'None', '5558', NULL, 'None', '', '', 'New'),
(220, 3, 1, 1, '2023-08-20 20:18:42', '7af9d1327068e24e98c935ec27544e92', 'test@gmail.com', '$2y$10$m/xcaoiPTOY1alsOTF.31OPBgivGZYOGkPqQA/8EYPx7R.Kg3c7l6', '2023-08-20 20:13:42', 'SHS', 3, 11, 'APPROVED', 'Standard', 'Test', 'Sirios', 'Ab', '', 'Single', 'Filipino', '09151515123', '2000-08-22', 'Pasig', 22, 'Male', 'Pasig City', '123123', NULL, 'Catholic', '', '', 'New'),
(221, 3, 1, 1, '2023-08-21 09:45:13', '8f643a4374f69901702ef3aff04f1f87', 'xamp@gmail.com', '$2y$10$hnmayt7wvdVeLs3u3DVmP..TR6cPS/WY5c9lOHrWxZV51jv6M88Ke', '2023-08-21 09:40:13', 'SHS', 3, 11, 'APPROVED', 'Standard', 'Xamp', 'Sirios', 'Bababa', '', 'Single', 'Filipino', '09151516123', '2000-08-21', 'Pasig', 23, 'Male', 'Pasig', '', NULL, 'Catholic', '', '', 'New'),
(222, 3, 1, 1, '2023-08-21 09:45:13', '8f643a4374f69901702ef3aff04f1f87', 'first@gmail.com', '$2y$10$hnmayt7wvdVeLs3u3DVmP..TR6cPS/WY5c9lOHrWxZV51jv6M88Ke', '2023-08-21 09:40:13', 'SHS', 3, 11, 'APPROVED', 'Standard', 'first', 'Sirios', 'Bababa', '', 'Single', 'Filipino', '09151516123', '2000-08-21', 'Pasig', 23, 'Male', 'Pasig', '', NULL, 'Catholic', '', '', 'New'),
(223, 3, 1, 1, '2023-08-21 09:45:13', '8f643a4374f69901702ef3aff04f1f87', 'second@gmail.com', '$2y$10$hnmayt7wvdVeLs3u3DVmP..TR6cPS/WY5c9lOHrWxZV51jv6M88Ke', '2023-08-21 09:40:13', 'SHS', 3, 11, 'APPROVED', 'Standard', 'second', 'Sirios', 'Bababa', '', 'Single', 'Filipino', '09151516123', '2000-08-21', 'Pasig', 23, 'Male', 'Pasig', '', NULL, 'Catholic', '', '', 'New'),
(225, 3, 1, 1, '2023-08-28 15:52:06', '123,456,952f4a45b8b0031ea23c4eaffd19d1b0,64d799c2b37ea60c417a479bc845fad0,dc2687868aacd7d47335de9c58262ee4,23342e2e693be22d3e625f9a0758d3e7,13f4099b20a0837e692f0731b9029a8e', 'hypersirios15f@gmail.com', '$2y$10$NMUG6pyd6pY9T2Hj/zpM8u/jtnGIjCW0v4/nzZ7vqRkFu53dHc6BW', '2023-08-27 10:47:06', 'SHS', 3, 11, 'APPROVED', 'Standard', 'xa', 'Sirios', 'A', '', '', '', '', '0000-00-00', '', 0, '', '', '', NULL, '', '', '', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL,
  `dean` varchar(100) NOT NULL,
  `track` varchar(20) NOT NULL,
  `acronym` varchar(15) NOT NULL,
  `auto_create` varchar(3) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `program_name`, `department_id`, `dean`, `track`, `acronym`, `auto_create`) VALUES
(25, 'Bachelor of Arts In English', 9, 'ABE Head', 'English', 'ABE', 'no'),
(26, 'Science, Technology, Engineering, and Mathematics', 8, 'STEM Head', 'Academic', 'STEM', 'yes'),
(27, 'Humanities and Social Sciences', 8, 'HUMSS Head', 'Academic', 'HUMSS', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `room_capacity` int(11) NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `course_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_number`, `room_capacity`, `room_name`, `course_id`, `school_year_id`, `type`) VALUES
(9, '101', 30, 'SLOT 1', 0, 0, ''),
(10, '102', 30, 'SLOT 2', 0, 0, ''),
(11, '103', 30, 'SLOT 3', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `school_year_id` int(11) NOT NULL,
  `term` varchar(10) NOT NULL,
  `period` varchar(15) NOT NULL,
  `statuses` enum('Active','InActive') NOT NULL DEFAULT 'InActive',
  `enrollment_status` tinyint(4) NOT NULL DEFAULT 0,
  `is_finished` tinyint(4) NOT NULL DEFAULT 0,
  `start_enrollment_date` datetime DEFAULT NULL,
  `end_enrollment_date` datetime DEFAULT NULL,
  `start_period` datetime DEFAULT NULL,
  `end_period` datetime DEFAULT NULL,
  `class_startdate` datetime DEFAULT NULL,
  `class_enddate` datetime DEFAULT NULL,
  `prelim_exam_startdate` datetime DEFAULT NULL,
  `prelim_exam_enddate` datetime DEFAULT NULL,
  `midterm_exam_startdate` datetime DEFAULT NULL,
  `midterm_exam_enddate` datetime DEFAULT NULL,
  `prefinal_exam_startdate` datetime DEFAULT NULL,
  `prefinal_exam_enddate` datetime DEFAULT NULL,
  `final_exam_startdate` datetime DEFAULT NULL,
  `final_exam_enddate` datetime DEFAULT NULL,
  `final_exam_ended` tinyint(1) DEFAULT NULL,
  `break_startdate` datetime DEFAULT NULL,
  `break_enddate` datetime DEFAULT NULL,
  `break_ended` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`school_year_id`, `term`, `period`, `statuses`, `enrollment_status`, `is_finished`, `start_enrollment_date`, `end_enrollment_date`, `start_period`, `end_period`, `class_startdate`, `class_enddate`, `prelim_exam_startdate`, `prelim_exam_enddate`, `midterm_exam_startdate`, `midterm_exam_enddate`, `prefinal_exam_startdate`, `prefinal_exam_enddate`, `final_exam_startdate`, `final_exam_enddate`, `final_exam_ended`, `break_startdate`, `break_enddate`, `break_ended`) VALUES
(40, '2023-2024', 'First', 'InActive', 0, 1, '2023-09-16 07:30:14', '2023-09-15 16:07:10', NULL, NULL, NULL, NULL, '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-21 00:00:00', '2023-09-15 16:07:45', 1, '2023-09-15 00:00:00', '2023-09-15 00:00:00', 1),
(41, '2023-2024', 'Second', 'InActive', 0, 1, '2023-09-15 16:08:34', '2023-09-15 16:40:09', NULL, NULL, NULL, NULL, '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 00:00:00', '2023-09-15 19:14:32', NULL, '2023-09-15 00:00:00', '2023-09-15 00:00:00', 0),
(42, '2024-2025', 'First', 'InActive', 0, 0, '2023-09-16 07:31:00', '2023-09-17 09:54:00', NULL, NULL, NULL, NULL, '2023-09-15 00:01:00', '2023-09-15 00:02:00', '2023-09-15 00:03:00', '2023-09-15 00:04:00', '2023-09-15 00:05:00', '2023-09-15 00:06:00', '2023-09-15 00:07:00', '2023-09-15 00:08:00', NULL, '2023-09-15 00:09:00', '2023-09-15 00:10:00', 0),
(43, '2024-2025', 'Second', 'Active', 0, 0, '2023-09-16 00:00:00', '2023-09-18 17:00:00', NULL, NULL, NULL, NULL, '2023-09-16 00:00:00', '2023-09-16 00:00:00', '2023-09-16 00:00:00', '2023-09-16 00:00:00', '2023-09-16 00:00:00', '2023-09-16 00:00:00', '2023-09-16 00:00:00', '2023-09-16 00:00:00', NULL, '2023-09-16 00:00:00', '2023-09-16 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `student_unique_id` int(20) DEFAULT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `profilePic` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `civil_status` varchar(20) NOT NULL,
  `student_status` varchar(15) NOT NULL,
  `student_statusv2` varchar(15) DEFAULT 'Regular',
  `admission_status` varchar(15) DEFAULT NULL,
  `citizenship` varchar(100) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `course_id` int(11) NOT NULL,
  `course_level` tinyint(2) NOT NULL,
  `new_enrollee` tinyint(1) NOT NULL DEFAULT 1,
  `sex` varchar(6) NOT NULL,
  `birthday` date DEFAULT NULL,
  `birthplace` varchar(150) NOT NULL,
  `age` int(30) NOT NULL,
  `nationality` varchar(40) NOT NULL,
  `religion` varchar(40) NOT NULL,
  `contact_number` varchar(40) NOT NULL,
  `address` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `active_search` varchar(8) NOT NULL,
  `lrn` varchar(15) NOT NULL,
  `is_tertiary` tinyint(4) NOT NULL DEFAULT 0,
  `is_graduated` tinyint(1) NOT NULL DEFAULT 0,
  `suffix` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_unique_id`, `firstname`, `lastname`, `middle_name`, `username`, `email`, `profilePic`, `password`, `civil_status`, `student_status`, `student_statusv2`, `admission_status`, `citizenship`, `date_creation`, `course_id`, `course_level`, `new_enrollee`, `sex`, `birthday`, `birthplace`, `age`, `nationality`, `religion`, `contact_number`, `address`, `active`, `active_search`, `lrn`, `is_tertiary`, `is_graduated`, `suffix`) VALUES
(6971, 112185, 'Test', 'Sirios', 'Abulencia', 'sirios.112185@dcbt.ph', 'hypersirios15@gmail.com', '', '$2y$10$gKqZfqEBqNuYJ7Jtu0clVOsfX9bRlpNY4EibiAVo2SniulGRznC9S', 'Single', '', 'Regular', 'Old', '', '2023-09-15 15:53:13', 1260, 12, 0, 'Male', '2023-09-15', 'Pasig', 0, 'Filipino', 'Catholic', '09151516123', '49 Pechay St', 1, 'Active', '236-126-050-357', 0, 0, ''),
(6972, 129562, 'justine adrian', 'justine', 'Abulencia', 'justine.129562@dcbt.ph', 'hypersirioxs15@gmail.com', '', '$2y$10$kSRZGuQw55AStLLxk7dWYuSGCGy9Q.88.drtkoUe5NfnKkAo9qboO', 'Single', '', 'Regular', 'Old', '', '2023-09-16 11:08:10', 1260, 12, 0, 'Male', '2023-09-16', 'Pasig', 0, 'filipino', 'Catholic', '09686033433', '49 Pechay St Napico Manggahan Pasig City', 1, 'Active', '555-736-050-357', 0, 0, ''),
(6973, 136487, 'justine adrianx', 'justinex', 'Abulenciax', 'justinex.136487@dcbt.ph', 'hypersirios3315@gmail.com', '', '$2y$10$w0Kof8XQGI7twIYlpJ2zteUdKrUj091piUrzUQ7yKhmJE/YKkIKrK', 'Single', '', 'Regular', 'Old', '', '2023-09-16 11:23:42', 1260, 12, 0, 'Male', '2023-09-16', 'Pasig', 0, 'filipino', 'Catholic', '09686033433', '49 Pechay St Napico Manggahan Pasig City', 1, 'Active', '233-126-050-357', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `student_assignment_grade`
--

CREATE TABLE `student_assignment_grade` (
  `student_assignment_grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_period_assignment_id` int(11) NOT NULL,
  `is_final` tinyint(1) NOT NULL DEFAULT 1,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `grade` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_assignment_grade`
--

INSERT INTO `student_assignment_grade` (`student_assignment_grade_id`, `student_id`, `subject_period_assignment_id`, `is_final`, `date_creation`, `grade`) VALUES
(11, 449, 1, 0, '2023-06-11 20:01:34', 25),
(12, 449, 1, 1, '2023-06-11 20:13:18', 75),
(14, 449, 7, 1, '2023-06-12 10:02:04', 0),
(15, 450, 1, 1, '2023-06-12 12:32:32', 50),
(16, 481, 9, 1, '2023-06-17 07:42:18', 25);

-- --------------------------------------------------------

--
-- Table structure for table `student_period_assignment`
--

CREATE TABLE `student_period_assignment` (
  `student_period_assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_period_assignment_id` int(11) NOT NULL,
  `student_assignment_grade_id` int(11) NOT NULL,
  `file_name` varchar(250) NOT NULL,
  `file_path` varchar(250) NOT NULL,
  `passed_date` datetime NOT NULL DEFAULT current_timestamp(),
  `grade` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_period_assignment`
--

INSERT INTO `student_period_assignment` (`student_period_assignment_id`, `student_id`, `subject_period_assignment_id`, `student_assignment_grade_id`, `file_name`, `file_path`, `passed_date`, `grade`) VALUES
(15, 449, 1, 11, '', 'admin/assets/images/answers/game0.png', '2023-06-11 20:01:34', 0),
(16, 449, 1, 11, '', 'admin/assets/images/answers/game1.png', '2023-06-11 20:01:34', 0),
(17, 449, 1, 12, '', 'admin/assets/images/answers/game0.png', '2023-06-11 20:13:18', 0),
(20, 449, 7, 14, '', 'admin/assets/images/answers/Screenshot (473).png', '2023-06-12 10:02:04', 0),
(21, 449, 7, 14, '', 'admin/assets/images/answers/Screenshot (472).png', '2023-06-12 10:02:04', 0),
(22, 450, 1, 15, '', 'admin/assets/images/answers/game3.png', '2023-06-12 12:32:32', 0),
(23, 481, 9, 16, '', 'admin/assets/images/answers/mypeace.png', '2023-06-17 07:42:18', 0),
(24, 481, 9, 16, '', 'admin/assets/images/answers/Screenshot (443).png', '2023-06-17 07:42:19', 0),
(25, 481, 9, 16, '', 'admin/assets/images/answers/Screenshot (444).png', '2023-06-17 07:42:19', 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_requirement`
--

CREATE TABLE `student_requirement` (
  `student_requirement_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `pending_enrollees_id` int(11) NOT NULL,
  `form_137` varchar(255) DEFAULT NULL,
  `form_137_valid` tinyint(1) NOT NULL,
  `psa` varchar(255) DEFAULT NULL,
  `psa_valid` tinyint(1) NOT NULL,
  `good_moral` varchar(255) DEFAULT NULL,
  `good_moral_valid` tinyint(1) NOT NULL,
  `date_upload` datetime DEFAULT NULL,
  `student_type` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_requirement`
--

INSERT INTO `student_requirement` (`student_requirement_id`, `student_id`, `pending_enrollees_id`, `form_137`, `form_137_valid`, `psa`, `psa_valid`, `good_moral`, `good_moral_valid`, `date_upload`, `student_type`) VALUES
(46, 6909, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(47, 6911, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(48, 6912, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(49, 6913, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(50, 6914, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(51, 6915, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(52, 6916, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(53, 6917, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(54, 6923, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(55, 6924, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(56, 6925, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(57, 6926, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(58, 6928, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(65, 6959, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(66, 6960, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(67, 6961, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(76, 6971, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(77, 6972, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS'),
(78, 6973, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'SHS');

-- --------------------------------------------------------

--
-- Table structure for table `student_subject`
--

CREATE TABLE `student_subject` (
  `student_subject_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(150) NOT NULL,
  `program_code` varchar(50) DEFAULT NULL,
  `enrollment_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `subject_program_id` int(11) NOT NULL,
  `course_level` tinyint(2) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `is_transferee` tinyint(1) NOT NULL DEFAULT 0,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `is_final` tinyint(1) NOT NULL DEFAULT 1,
  `retake` tinyint(1) NOT NULL DEFAULT 0,
  `overlap` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_subject`
--

INSERT INTO `student_subject` (`student_subject_id`, `student_id`, `subject_id`, `subject_code`, `program_code`, `enrollment_id`, `course_id`, `subject_program_id`, `course_level`, `school_year_id`, `is_transferee`, `date_creation`, `is_final`, `retake`, `overlap`) VALUES
(2843, 6971, 0, 'STEM11-A-PE201', 'PE201', 1295, 1253, 199, 0, 40, 0, '2023-09-15 15:56:48', 1, 0, 0),
(2844, 6971, 0, 'STEM11-A-STEM101', 'STEM101', 1295, 1253, 200, 0, 40, 0, '2023-09-15 15:56:49', 1, 0, 0),
(2845, 6971, 0, 'STEM11-A-PE101', 'PE101', 1295, 1253, 198, 0, 40, 0, '2023-09-15 15:56:50', 1, 0, 0),
(2846, 6971, 0, 'STEM11-A-STEM201', 'STEM201', 1296, 1253, 201, 0, 41, 0, '2023-09-15 16:14:19', 1, 0, 0),
(2847, 6971, 0, 'STEM11-A-PE301', 'PE301', 1296, 1253, 202, 0, 41, 0, '2023-09-15 16:14:19', 1, 0, 0),
(2848, 6971, 0, 'STEM12-A-STEM301', 'STEM301', 1298, 1260, 203, 0, 42, 0, '2023-09-16 10:19:04', 1, 0, 0),
(2849, 6971, 0, 'STEM12-A-NSTP101', 'NSTP101', 1298, 1260, 204, 0, 42, 0, '2023-09-16 10:19:04', 1, 0, 0),
(2850, 6971, 0, 'STEM12-A-NSTP102', 'NSTP102', 1298, 1260, 205, 0, 42, 0, '2023-09-16 10:19:04', 1, 0, 0),
(2851, 6972, 0, 'STEM12-A-STEM301', 'STEM301', 1299, 1260, 203, 0, 42, 0, '2023-09-16 11:08:10', 1, 0, 0),
(2852, 6972, 0, 'STEM12-A-NSTP101', 'NSTP101', 1299, 1260, 204, 0, 42, 0, '2023-09-16 11:08:10', 1, 0, 0),
(2853, 6972, 0, 'STEM12-A-NSTP102', 'NSTP102', 1299, 1260, 205, 0, 42, 0, '2023-09-16 11:08:10', 1, 0, 0),
(2854, 6973, 0, 'STEM12-A-STEM301', 'STEM301', 1300, 1260, 203, 0, 42, 0, '2023-09-16 11:23:42', 1, 0, 0),
(2855, 6973, 0, 'STEM12-A-NSTP101', 'NSTP101', 1300, 1260, 204, 0, 42, 0, '2023-09-16 11:23:42', 1, 0, 0),
(2856, 6973, 0, 'STEM12-A-NSTP102', 'NSTP102', 1300, 1260, 205, 0, 42, 0, '2023-09-16 11:23:42', 1, 0, 0),
(2857, 6971, 0, 'STEM12-A-NSTP301', 'NSTP301', 1301, 1260, 206, 0, 43, 0, '2023-09-16 13:08:33', 1, 0, 0),
(2858, 6971, 0, 'STEM12-A-STEM401', 'STEM401', 1301, 1260, 207, 0, 43, 0, '2023-09-16 13:08:33', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_subject_grade`
--

CREATE TABLE `student_subject_grade` (
  `student_subject_grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_title` varchar(40) NOT NULL,
  `first` int(11) NOT NULL,
  `second` int(11) NOT NULL,
  `third` int(11) NOT NULL,
  `fourth` int(11) NOT NULL,
  `average` float NOT NULL,
  `remarks` varchar(51) NOT NULL,
  `is_transferee` tinyint(1) NOT NULL DEFAULT 0,
  `comment` varchar(250) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_subject_grade`
--

INSERT INTO `student_subject_grade` (`student_subject_grade_id`, `student_id`, `student_subject_id`, `teacher_id`, `subject_id`, `course_id`, `subject_title`, `first`, `second`, `third`, `fourth`, `average`, `remarks`, `is_transferee`, `comment`, `date_creation`) VALUES
(1334, 6971, 2843, 0, 0, 0, '', 0, 0, 0, 0, 0, 'Passed', 0, '', '2023-09-15 16:06:37'),
(1335, 6971, 2844, 0, 0, 0, '', 0, 0, 0, 0, 0, 'Passed', 0, '', '2023-09-15 16:06:39'),
(1336, 6971, 2845, 0, 0, 0, '', 0, 0, 0, 0, 0, 'Passed', 0, '', '2023-09-15 16:06:40'),
(1337, 6971, 2846, 0, 0, 0, '', 0, 0, 0, 0, 0, 'Passed', 0, '', '2023-09-15 16:37:14'),
(1338, 6971, 2847, 0, 0, 0, '', 0, 0, 0, 0, 0, 'Passed', 0, '', '2023-09-15 16:37:16');

-- --------------------------------------------------------

--
-- Table structure for table `subject_assignment_submission`
--

CREATE TABLE `subject_assignment_submission` (
  `subject_assignment_submission_id` int(11) NOT NULL,
  `subject_code_assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `subject_grade` int(3) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `date_graded` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_assignment_submission`
--

INSERT INTO `subject_assignment_submission` (`subject_assignment_submission_id`, `subject_code_assignment_id`, `student_id`, `school_year_id`, `subject_grade`, `date_creation`, `date_graded`) VALUES
(19, 19, 6962, 3, 99, '2023-09-11 13:53:27', '2023-09-12 11:57:16');

-- --------------------------------------------------------

--
-- Table structure for table `subject_assignment_submission_list`
--

CREATE TABLE `subject_assignment_submission_list` (
  `subject_assignment_submission_list_id` int(11) NOT NULL,
  `subject_assignment_submission_id` int(11) NOT NULL,
  `output_text` text DEFAULT NULL,
  `output_file` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_assignment_submission_list`
--

INSERT INTO `subject_assignment_submission_list` (`subject_assignment_submission_list_id`, `subject_assignment_submission_id`, `output_text`, `output_file`) VALUES
(14, 11, '<p>Sample first assignment</p>', ''),
(15, 12, '<p>Second Answer</p>', ''),
(19, 15, NULL, 'assets/images/student_assignment_images/64fb0a78a150f_1694173816_game0.png'),
(20, 15, NULL, 'assets/images/student_assignment_images/64fb0a78a2f74_1694173816_game1.png'),
(21, 18, NULL, 'assets/images/student_assignment_images/64fbb184133c3_1694216580_64fbb04d7110c_1694216269_AccomplishmentForm.docx'),
(23, 18, NULL, 'assets/images/student_assignment_images/64fbb18415588_1694216580_371755588_801689635077256_3163605306835615430_n.jpg'),
(28, 22, NULL, 'assets/images/student_assignment_images/64ffee83385fe_1694494339_game6.png'),
(29, 24, NULL, 'assets/images/student_assignment_images/64ffeea1e6387_1694494369_mypeace.png');

-- --------------------------------------------------------

--
-- Table structure for table `subject_code_assignment`
--

CREATE TABLE `subject_code_assignment` (
  `subject_code_assignment_id` int(11) NOT NULL,
  `subject_period_code_topic_id` int(11) NOT NULL,
  `subject_code_assignment_template_id` int(11) DEFAULT NULL,
  `assignment_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `assignment_image` varchar(150) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `max_score` int(3) NOT NULL,
  `allow_late_submission` varchar(3) NOT NULL DEFAULT 'no',
  `type` enum('text','upload') NOT NULL,
  `max_attempt` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_code_assignment`
--

INSERT INTO `subject_code_assignment` (`subject_code_assignment_id`, `subject_period_code_topic_id`, `subject_code_assignment_template_id`, `assignment_name`, `description`, `assignment_image`, `due_date`, `date_creation`, `max_score`, `allow_late_submission`, `type`, `max_attempt`) VALUES
(19, 32, 15, 'Assignment 1', 'Assignment 1 Description', NULL, '2023-09-20 13:51:00', '2023-09-11 13:52:08', 100, 'yes', 'text', 3),
(21, 32, 16, 'Assignment 2', 'Assignment 2 Description', NULL, '2023-09-14 00:00:00', '2023-09-12 07:35:54', 50, 'no', 'upload', 3),
(22, 32, NULL, 'Assignment 3', 'Assignment 3 Description', NULL, '2023-09-14 00:00:00', '2023-09-12 07:35:54', 100, 'no', 'upload', 3),
(23, 31, 18, 'Web Development Assignment 1', 'Web Development Assignment 1 Desc', NULL, '2023-09-14 17:59:00', '2023-09-13 18:00:02', 50, 'yes', 'upload', 3);

-- --------------------------------------------------------

--
-- Table structure for table `subject_code_assignment_list`
--

CREATE TABLE `subject_code_assignment_list` (
  `subject_code_assignment_list_id` int(11) NOT NULL,
  `subject_code_assignment_id` int(11) NOT NULL,
  `image` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subject_code_assignment_template`
--

CREATE TABLE `subject_code_assignment_template` (
  `subject_code_assignment_template_id` int(11) NOT NULL,
  `subject_period_code_topic_template_id` int(11) NOT NULL,
  `assignment_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `max_score` int(3) NOT NULL,
  `allow_late_submission` varchar(3) NOT NULL,
  `type` enum('text','upload') NOT NULL,
  `max_attempt` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_code_assignment_template`
--

INSERT INTO `subject_code_assignment_template` (`subject_code_assignment_template_id`, `subject_period_code_topic_template_id`, `assignment_name`, `description`, `due_date`, `date_creation`, `max_score`, `allow_late_submission`, `type`, `max_attempt`) VALUES
(15, 1, 'Assignment 1', 'Assignment 1 Description', NULL, '2023-09-11 13:50:19', 100, '', 'text', 5),
(16, 1, 'Assignment 2', 'Assignment 2 Description', NULL, '2023-09-11 13:50:51', 50, '', 'upload', 5),
(17, 1, 'Assignment 3', 'Assignment 3 Description', NULL, '2023-09-12 14:54:45', 100, '', 'upload', 3),
(18, 2, 'Web Development Assignment 1', 'Web Development Assignment 1 Desc', NULL, '2023-09-13 17:54:41', 50, '', 'upload', 2),
(19, 2, 'Web Development Assignment 2', 'Web Development Assignment 2 Desc', NULL, '2023-09-13 17:57:17', 100, '', 'text', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subject_code_assignment_template_list`
--

CREATE TABLE `subject_code_assignment_template_list` (
  `subject_code_assignment_template_list_id` int(11) NOT NULL,
  `subject_code_assignment_template_id` int(11) NOT NULL,
  `image` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_code_assignment_template_list`
--

INSERT INTO `subject_code_assignment_template_list` (`subject_code_assignment_template_list_id`, `subject_code_assignment_template_id`, `image`) VALUES
(25, 16, 'assets/images/assignments_images/64feaabb8cb1a_1694411451_SIRIOS, JUSTINE ADRIAN - ABULENCIA _ NEW S.Y. 23-24 (3).pdf'),
(26, 16, 'assets/images/assignments_images/64feaabb8e5b8_1694411451_371755588_801689635077256_3163605306835615430_n.jpg'),
(27, 17, 'assets/images/assignments_images/65000b3515dc9_1694501685_Database Diagram.png'),
(28, 18, 'assets/images/assignments_images/650186e1475b2_1694598881_65012d440ca8b_1694575940_network1.docx'),
(29, 19, 'assets/images/assignments_images/6501877dc58aa_1694599037_network1.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `subject_code_handout`
--

CREATE TABLE `subject_code_handout` (
  `subject_code_handout_id` int(11) NOT NULL,
  `subject_period_code_topic_id` int(11) NOT NULL,
  `subject_code_handout_template_id` int(11) DEFAULT NULL,
  `handout_name` varchar(100) NOT NULL,
  `file` varchar(300) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_code_handout`
--

INSERT INTO `subject_code_handout` (`subject_code_handout_id`, `subject_period_code_topic_id`, `subject_code_handout_template_id`, `handout_name`, `file`, `date_creation`) VALUES
(10, 32, 7, 'Handout 1', 'assets/images/handout/64feaacc54bbf_1694411468_01_Activity_2 (1).docx', '2023-09-11 13:52:21'),
(11, 31, 9, ' Web Development Handout1', 'assets/images/handout/650187a761c6e_1694599079_02_Activity_1(3).pdf', '2023-09-13 17:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `subject_code_handout_template`
--

CREATE TABLE `subject_code_handout_template` (
  `subject_code_handout_template_id` int(11) NOT NULL,
  `subject_period_code_topic_template_id` int(11) NOT NULL,
  `handout_name` varchar(150) NOT NULL,
  `file` varchar(300) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_code_handout_template`
--

INSERT INTO `subject_code_handout_template` (`subject_code_handout_template_id`, `subject_period_code_topic_template_id`, `handout_name`, `file`, `date_creation`) VALUES
(7, 1, 'Handout 1', 'assets/images/handout/64feaacc54bbf_1694411468_01_Activity_2 (1).docx', '2023-09-11 13:51:08'),
(8, 1, 'Handout 2', 'assets/images/handout/64feaad8a5ce7_1694411480_01_Activity_2.docx', '2023-09-11 13:51:20'),
(9, 2, ' Web Development Handout1', 'assets/images/handout/650187a761c6e_1694599079_02_Activity_1(3).pdf', '2023-09-13 17:57:59');

-- --------------------------------------------------------

--
-- Table structure for table `subject_code_handout_template_list`
--

CREATE TABLE `subject_code_handout_template_list` (
  `subject_code_handout_template_list_id` int(11) NOT NULL,
  `subject_code_handout_template_id` int(11) NOT NULL,
  `file` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_code_handout_template_list`
--

INSERT INTO `subject_code_handout_template_list` (`subject_code_handout_template_list_id`, `subject_code_handout_template_id`, `file`) VALUES
(1, 1, 'assets/images/assignments_images/64fd20c67a02b_1694310598_64fbb184133c3_1694216580_64fbb04d7110c_1694216269_AccomplishmentForm (1).docx');

-- --------------------------------------------------------

--
-- Table structure for table `subject_period`
--

CREATE TABLE `subject_period` (
  `subject_period_id` int(11) NOT NULL,
  `period_name` enum('Prelim','Midterm','Pre-final','Final') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_period`
--

INSERT INTO `subject_period` (`subject_period_id`, `period_name`) VALUES
(23, 'Prelim'),
(24, 'Midterm'),
(25, 'Pre-final'),
(26, 'Final');

-- --------------------------------------------------------

--
-- Table structure for table `subject_period_code`
--

CREATE TABLE `subject_period_code` (
  `subject_period_code_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_period_name` enum('Prelim','Midterm','Pre-final','Final') NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `subject_code` varchar(100) NOT NULL,
  `program_code` varchar(50) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_period_code`
--

INSERT INTO `subject_period_code` (`subject_period_code_id`, `teacher_id`, `subject_period_name`, `school_year_id`, `subject_code`, `program_code`, `date_creation`) VALUES
(5, 2, 'Prelim', 3, 'STEM11-A-STEM-1', 'STEM-1', '2023-09-05 13:25:34'),
(6, 2, 'Midterm', 3, 'STEM11-A-STEM-1', 'STEM-1', '2023-09-05 13:25:34'),
(7, 2, 'Pre-final', 3, 'STEM11-A-STEM-1', 'STEM-1', '2023-09-05 13:25:34'),
(8, 2, 'Final', 3, 'STEM11-A-STEM-1', 'STEM-1', '2023-09-05 13:25:34'),
(9, 2, 'Prelim', 3, 'STEM11-B-PE102', 'PE102', '2023-09-14 07:35:25'),
(10, 2, 'Midterm', 3, 'STEM11-B-PE102', 'PE102', '2023-09-14 07:35:25'),
(11, 2, 'Pre-final', 3, 'STEM11-B-PE102', 'PE102', '2023-09-14 07:35:25'),
(12, 2, 'Final', 3, 'STEM11-B-PE102', 'PE102', '2023-09-14 07:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `subject_period_code_topic`
--

CREATE TABLE `subject_period_code_topic` (
  `subject_period_code_topic_id` int(11) NOT NULL,
  `subject_period_code_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `school_year_id` int(11) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `image` varchar(300) DEFAULT NULL,
  `description` varchar(300) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `subject_period_name` enum('Prelim','Midterm','Pre-final','Final') NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `program_code` varchar(50) NOT NULL,
  `period_order` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_period_code_topic`
--

INSERT INTO `subject_period_code_topic` (`subject_period_code_topic_id`, `subject_period_code_id`, `course_id`, `teacher_id`, `school_year_id`, `topic`, `image`, `description`, `date_creation`, `subject_period_name`, `subject_code`, `program_code`, `period_order`) VALUES
(30, 0, 1249, 1, 3, 'Midterm Topic 1', NULL, 'Midterm Topic 1 Description', '2023-09-07 18:21:15', 'Midterm', 'STEM11-A-STEM-1', 'STEM-1', 3),
(31, 0, 1249, 1, 3, 'Web Development Frameworks', NULL, 'Web Development Frameworks Description', '2023-09-07 18:21:18', 'Prelim', 'STEM11-A-STEM-1', 'STEM-1', 2),
(32, 0, 1249, 1, 3, 'Introduction to ASP.NET', NULL, 'Introduction to ASP.NET Description', '2023-09-07 18:21:28', 'Prelim', 'STEM11-A-STEM-1', 'STEM-1', 1),
(33, 0, 1249, 1, 3, 'Pre-final Topic 1', NULL, 'Pre-final Topic 1 Description', '2023-09-07 18:21:35', 'Pre-final', 'STEM11-A-STEM-1', 'STEM-1', 4);

-- --------------------------------------------------------

--
-- Table structure for table `subject_period_code_topic_template`
--

CREATE TABLE `subject_period_code_topic_template` (
  `subject_period_code_topic_template_id` int(11) NOT NULL,
  `topic` varchar(50) NOT NULL,
  `description` varchar(300) NOT NULL,
  `subject_period_name` varchar(50) NOT NULL,
  `program_code` varchar(50) NOT NULL,
  `period_order` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_period_code_topic_template`
--

INSERT INTO `subject_period_code_topic_template` (`subject_period_code_topic_template_id`, `topic`, `description`, `subject_period_name`, `program_code`, `period_order`) VALUES
(1, 'Introduction to ASP.NET', 'Introduction to ASP.NET Description', 'Prelim', 'STEM-1', 1),
(2, 'Web Development Frameworks', 'Web Development Frameworks Description', 'Prelim', 'STEM-1', 2),
(3, 'Midterm Topic 1', 'Midterm Topic 1 Description', 'Midterm', 'STEM-1', 3),
(4, 'Pre-final Topic 1', 'Pre-final Topic 1 Description', 'Pre-final', 'STEM-1', 4);

-- --------------------------------------------------------

--
-- Table structure for table `subject_program`
--

CREATE TABLE `subject_program` (
  `subject_program_id` int(11) NOT NULL,
  `department_type` varchar(10) NOT NULL,
  `program_id` int(11) NOT NULL,
  `subject_template_id` int(11) NOT NULL,
  `subject_title` varchar(100) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `pre_req_subject_title` varchar(150) NOT NULL,
  `unit` int(3) NOT NULL,
  `description` varchar(150) NOT NULL,
  `course_level` int(11) NOT NULL,
  `semester` varchar(12) NOT NULL,
  `subject_type` varchar(75) NOT NULL,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_program`
--

INSERT INTO `subject_program` (`subject_program_id`, `department_type`, `program_id`, `subject_template_id`, `subject_title`, `subject_code`, `pre_req_subject_title`, `unit`, `description`, `course_level`, `semester`, `subject_type`, `active`) VALUES
(198, 'SHS', 26, 145, 'Physical Education 1', 'PE101', 'None', 3, 'Physical Education 1 Description', 11, 'First', 'Core', 'yes'),
(199, 'SHS', 26, 146, 'Physical Education 2', 'PE201', 'None', 3, 'Physical Education 2 Description', 11, 'First', 'Core', 'yes'),
(200, 'SHS', 26, 149, 'STEM-1', 'STEM101', 'None', 3, 'STEM-1 Description', 11, 'First', 'Specialized', 'yes'),
(201, 'SHS', 26, 150, 'STEM-2', 'STEM201', 'STEM101', 3, 'STEM-2 Description', 11, 'Second', 'Applied', 'yes'),
(202, 'SHS', 26, 165, 'Physical Education 3', 'PE301', 'None', 3, 'Physical Education 3 Description', 11, 'Second', 'Core', 'yes'),
(203, 'SHS', 26, 151, 'STEM-3', 'STEM301', 'STEM201', 3, 'STEM-3 Description', 12, 'First', 'Specialized', 'yes'),
(204, 'SHS', 26, 147, 'NSTP-1', 'NSTP101', 'None', 3, 'NSTP-1 Desc', 12, 'First', 'Core', 'yes'),
(205, 'SHS', 26, 148, 'NSTP-2', 'NSTP102', 'None', 3, 'NSTP-2 Desc', 12, 'First', 'Core', 'yes'),
(206, 'SHS', 26, 166, 'NSTP-3', 'NSTP301', 'None', 3, 'NSTP-3 Desc', 12, 'Second', 'Core', 'yes'),
(207, 'SHS', 26, 152, 'STEM-4', 'STEM401', 'STEM301', 3, 'STEM-4 Description', 12, 'Second', 'Specialized', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `subject_schedule`
--

CREATE TABLE `subject_schedule` (
  `subject_schedule_id` int(11) NOT NULL,
  `time_from` varchar(30) NOT NULL,
  `time_to` varchar(30) NOT NULL,
  `schedule_time` varchar(30) NOT NULL,
  `schedule_day` varchar(15) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `subject_program_id` int(11) NOT NULL,
  `subject_code` varchar(150) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `time` time NOT NULL,
  `day_count` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_schedule`
--

INSERT INTO `subject_schedule` (`subject_schedule_id`, `time_from`, `time_to`, `schedule_time`, `schedule_day`, `school_year_id`, `room_id`, `course_id`, `subject_id`, `subject_program_id`, `subject_code`, `teacher_id`, `date_creation`, `time`, `day_count`) VALUES
(204, '08:00', '09:30', '08:00 AM - 09:30 AM', 'M', 40, 9, 1253, 0, 198, 'STEM11-A-PE101', NULL, '2023-09-15 15:33:03', '00:00:00', 1),
(205, '08:00', '09:30', '08:00 AM - 09:30 AM', 'T', 40, 9, 1253, 0, 199, 'STEM11-A-PE201', NULL, '2023-09-15 15:33:24', '00:00:00', 2),
(206, '08:00', '09:30', '08:00 AM - 09:30 AM', 'W', 40, NULL, 1253, 0, 200, 'STEM11-A-STEM101', NULL, '2023-09-15 15:33:45', '00:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `subject_template`
--

CREATE TABLE `subject_template` (
  `subject_template_id` int(11) NOT NULL,
  `subject_title` varchar(150) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `unit` int(3) NOT NULL,
  `description` varchar(250) NOT NULL,
  `pre_requisite_title` varchar(150) NOT NULL,
  `subject_type` enum('Core','Applied','Specialized') NOT NULL,
  `program_type` tinyint(1) NOT NULL DEFAULT 0,
  `program_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_template`
--

INSERT INTO `subject_template` (`subject_template_id`, `subject_title`, `subject_code`, `unit`, `description`, `pre_requisite_title`, `subject_type`, `program_type`, `program_id`) VALUES
(145, 'Physical Education 1', 'PE101', 3, 'Physical Education 1 Description', 'None', 'Core', 0, 0),
(146, 'Physical Education 2', 'PE201', 3, 'Physical Education 2 Description', 'None', 'Core', 0, 0),
(147, 'NSTP-1', 'NSTP101', 3, 'NSTP-1 Desc', 'None', 'Core', 0, 0),
(148, 'NSTP-2', 'NSTP201', 3, 'NSTP-2 Desc', 'None', 'Core', 0, 0),
(149, 'STEM-1', 'STEM101', 3, 'STEM-1 Description', 'None', 'Specialized', 0, 26),
(150, 'STEM-2', 'STEM201', 3, 'STEM-2 Description', 'STEM101', 'Applied', 0, 26),
(151, 'STEM-3', 'STEM301', 3, 'STEM-3 Description', 'STEM201', 'Specialized', 0, 26),
(152, 'STEM-4', 'STEM401', 3, 'STEM-4 Description', 'STEM301', 'Specialized', 0, 26),
(153, 'HUMSS-1', 'HUMSS101', 3, 'HUMSS-1 Description', 'None', 'Applied', 0, 27),
(154, 'HUMSS-2', 'HUMSS201', 3, 'HUMMS-1 Description', 'HUMSS101', 'Specialized', 0, 27),
(155, 'HUMSS-3', 'HUMSS301', 3, 'HUMSS-3 Description', 'HUMSS201', 'Specialized', 0, 27),
(156, 'HUMMS-4', 'HUMSS401', 3, 'HUMSS-4 Description', 'HUMSS301', 'Specialized', 0, 27),
(157, 'Physical Fitness 1', 'PF-1', 3, 'Physical Fitness 1 Description', 'None', 'Core', 1, 0),
(158, 'Physical Fitness 2', 'PF-2', 3, 'Physical Fitness 2 Description', 'None', 'Core', 1, 0),
(159, 'RTC-1', 'RTC101', 3, 'RTC-1 Description', 'None', 'Core', 1, 0),
(160, 'RTC-2', 'RTC201', 3, 'RTC-2 Description', 'None', 'Core', 1, 0),
(161, 'ABE-1', 'ABE101', 3, 'ABE-1 Description', 'None', 'Specialized', 1, 25),
(162, 'ABE-2', 'ABE201', 3, 'ABE-2 Description', 'ABE101', 'Specialized', 1, 25),
(163, 'ABE-3', 'ABE301', 3, 'ABE-3 Description', 'ABE201', 'Specialized', 1, 25),
(164, 'ABE-4', 'ABE401', 3, 'ABE-4 Description', 'ABE301', 'Specialized', 1, 25),
(165, 'Physical Education 3', 'PE301', 3, 'Physical Education 3 Description', 'None', 'Core', 0, 0),
(166, 'NSTP-3', 'NSTP301', 3, 'NSTP-3 Desc', 'None', 'Core', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL,
  `school_teacher_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middle_name` varchar(20) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `suffix` varchar(20) NOT NULL,
  `department_id` int(11) NOT NULL,
  `profilePic` varchar(250) NOT NULL,
  `teacher_status` varchar(10) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(7) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `citizenship` varchar(20) NOT NULL,
  `birthplace` varchar(100) NOT NULL,
  `birthday` varchar(25) NOT NULL,
  `religion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `school_teacher_id`, `username`, `password`, `firstname`, `middle_name`, `lastname`, `suffix`, `department_id`, `profilePic`, `teacher_status`, `date_creation`, `gender`, `civil_status`, `email`, `contact_number`, `address`, `citizenship`, `birthplace`, `birthday`, `religion`) VALUES
(23, 52552, '', '$2y$10$JnzAJfV9pej999SZN2ZKXetGuvAitXi6ed.FCdr3sYKMbsFk/mzga', 'Albert', 'Great', 'Einstein', '', 8, 'assets/images/teacher_profiles/650405f279803_1694762482_368688907_308473881565761_384765816943796138_n.jpg', 'active', '2023-09-15 15:21:22', 'Male', '', 'einstein15@gmail.com', '09151515123', '4123 Brain St Pasig City', 'Filipino', 'Pasig', '2023-09-15', 'None'),
(24, 0, '', '$2y$10$RAyn/ocvAqaQfCZWcwXuee6WO4twkagnsm2yGfWc4A/D7wMFg7.Fy', 'Kick', 'Great', 'Butowski', '', 8, 'assets/images/teacher_profiles/6504065307ec2_1694762579_pig-game-flowchart.png', 'active', '2023-09-15 15:22:59', 'Male', '', 'butowski15@gmail.com', '09151515123', '4123 Kick St Pasig City', 'Filipino', 'Pasig', '2023-09-15', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `track_id` int(11) NOT NULL,
  `track_name` varchar(100) NOT NULL,
  `program_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `track`
--

INSERT INTO `track` (`track_id`, `track_name`, `program_id`) VALUES
(1, 'Science, Technology, Engineering, and Mathematics', 3),
(2, 'Accountancy, Business, and Management', 3),
(3, 'Artificial Intelligence', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role` varchar(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `remember_me_token` varchar(255) DEFAULT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `photo` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role`, `username`, `email`, `remember_me_token`, `firstName`, `lastName`, `password`, `photo`) VALUES
(1, 'Administrator', 'admin', 'hypersirios15@gmail.com', NULL, 'Admin', 'Surname', '$2y$10$3UEKxgpR2Z3BCFHy973Bj.0VTzwgk72pHaEN.dgD4dZXgVZ0TORZi', ''),
(2, 'Cashier', 'cashier', 'hypersirios15@gmail.com', NULL, 'Cashier', 'Surname', '$2y$10$3UEKxgpR2Z3BCFHy973Bj.0VTzwgk72pHaEN.dgD4dZXgVZ0TORZi', ''),
(3, 'Registrar', 'registrar', 'hypersirios15@gmail.com', NULL, 'Gerlie', 'Atienza', '$2y$10$3UEKxgpR2Z3BCFHy973Bj.0VTzwgk72pHaEN.dgD4dZXgVZ0TORZi', ''),
(4, 'Super Administrator', 'superadmin', 'hypersirios15@gmail.com', NULL, 'Korean', 'Taekwando', '$2y$10$3UEKxgpR2Z3BCFHy973Bj.0VTzwgk72pHaEN.dgD4dZXgVZ0TORZi', ''),
(5, 'Registrar', '', 'samp11235@gmail.com', NULL, 'Samp TanX', 'Sampx', '$2y$10$L2O7UGBL6PquvPBOaZOjsOScM213kJEWRuoQTic.Uijtp3d6a92IC', 'assets/images/users/64f8094d2b81a_1693976909_mypeace.png');

-- --------------------------------------------------------

--
-- Table structure for table `waiting_list`
--

CREATE TABLE `waiting_list` (
  `waiting_list_id` int(11) NOT NULL,
  `registrar_evaluated` varchar(3) NOT NULL DEFAULT 'no',
  `registrar_evaluated_date` datetime DEFAULT NULL,
  `cashier_evaluated` varchar(3) DEFAULT 'no',
  `cashier_evaluated_date` datetime DEFAULT NULL,
  `pending_enrollees_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `course_level` tinyint(2) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'Waiting',
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `waiting_list`
--

INSERT INTO `waiting_list` (`waiting_list_id`, `registrar_evaluated`, `registrar_evaluated_date`, `cashier_evaluated`, `cashier_evaluated_date`, `pending_enrollees_id`, `student_id`, `school_year_id`, `program_id`, `course_level`, `status`, `date_creation`) VALUES
(4, 'yes', '2023-08-17 16:57:32', 'yes', '2023-08-17 17:09:17', 0, 675, 3, 3, 11, 'Approved', '2023-08-17 11:52:26'),
(5, 'yes', '2023-08-19 10:52:48', 'no', NULL, 0, 676, 3, 3, 11, 'Waiting', '2023-08-17 11:52:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD UNIQUE KEY `uc_enrollment` (`school_year_id`,`student_id`,`course_id`,`enrollment_status`) USING BTREE,
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`parent_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `pending_enrollees`
--
ALTER TABLE `pending_enrollees`
  ADD PRIMARY KEY (`pending_enrollees_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `school_year_id` (`school_year_id`);

--
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`school_year_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `student_assignment_grade`
--
ALTER TABLE `student_assignment_grade`
  ADD PRIMARY KEY (`student_assignment_grade_id`),
  ADD KEY `subject_period_assignment_id` (`subject_period_assignment_id`);

--
-- Indexes for table `student_period_assignment`
--
ALTER TABLE `student_period_assignment`
  ADD PRIMARY KEY (`student_period_assignment_id`),
  ADD KEY `subject_period_assignment_id` (`subject_period_assignment_id`),
  ADD KEY `student_assignment_grade_id` (`student_assignment_grade_id`);

--
-- Indexes for table `student_requirement`
--
ALTER TABLE `student_requirement`
  ADD PRIMARY KEY (`student_requirement_id`),
  ADD KEY `fk_student_id` (`student_id`);

--
-- Indexes for table `student_subject`
--
ALTER TABLE `student_subject`
  ADD PRIMARY KEY (`student_subject_id`),
  ADD KEY `school_year_id` (`school_year_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `enrollment_id` (`enrollment_id`),
  ADD KEY `subject_program_id` (`subject_program_id`);

--
-- Indexes for table `student_subject_grade`
--
ALTER TABLE `student_subject_grade`
  ADD PRIMARY KEY (`student_subject_grade_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `student_subject_id` (`student_subject_id`);

--
-- Indexes for table `subject_assignment_submission`
--
ALTER TABLE `subject_assignment_submission`
  ADD PRIMARY KEY (`subject_assignment_submission_id`),
  ADD KEY `subject_code_assignment_id` (`subject_code_assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `subject_assignment_submission_list`
--
ALTER TABLE `subject_assignment_submission_list`
  ADD PRIMARY KEY (`subject_assignment_submission_list_id`);

--
-- Indexes for table `subject_code_assignment`
--
ALTER TABLE `subject_code_assignment`
  ADD PRIMARY KEY (`subject_code_assignment_id`),
  ADD KEY `subject_period_code_topic_id` (`subject_period_code_topic_id`);

--
-- Indexes for table `subject_code_assignment_list`
--
ALTER TABLE `subject_code_assignment_list`
  ADD PRIMARY KEY (`subject_code_assignment_list_id`),
  ADD KEY `subject_code_assignment_id` (`subject_code_assignment_id`);

--
-- Indexes for table `subject_code_assignment_template`
--
ALTER TABLE `subject_code_assignment_template`
  ADD PRIMARY KEY (`subject_code_assignment_template_id`);

--
-- Indexes for table `subject_code_assignment_template_list`
--
ALTER TABLE `subject_code_assignment_template_list`
  ADD PRIMARY KEY (`subject_code_assignment_template_list_id`);

--
-- Indexes for table `subject_code_handout`
--
ALTER TABLE `subject_code_handout`
  ADD PRIMARY KEY (`subject_code_handout_id`),
  ADD KEY `subject_period_code_topic_id` (`subject_period_code_topic_id`);

--
-- Indexes for table `subject_code_handout_template`
--
ALTER TABLE `subject_code_handout_template`
  ADD PRIMARY KEY (`subject_code_handout_template_id`);

--
-- Indexes for table `subject_code_handout_template_list`
--
ALTER TABLE `subject_code_handout_template_list`
  ADD PRIMARY KEY (`subject_code_handout_template_list_id`),
  ADD UNIQUE KEY `subject_code_handout_template_id` (`subject_code_handout_template_id`);

--
-- Indexes for table `subject_period`
--
ALTER TABLE `subject_period`
  ADD PRIMARY KEY (`subject_period_id`);

--
-- Indexes for table `subject_period_code`
--
ALTER TABLE `subject_period_code`
  ADD PRIMARY KEY (`subject_period_code_id`),
  ADD KEY `school_year_id` (`school_year_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `subject_period_code_topic`
--
ALTER TABLE `subject_period_code_topic`
  ADD PRIMARY KEY (`subject_period_code_topic_id`),
  ADD KEY `subject_period_code_id` (`subject_period_code_id`),
  ADD KEY `school_year_id` (`school_year_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `subject_period_code_topic_template`
--
ALTER TABLE `subject_period_code_topic_template`
  ADD PRIMARY KEY (`subject_period_code_topic_template_id`);

--
-- Indexes for table `subject_program`
--
ALTER TABLE `subject_program`
  ADD PRIMARY KEY (`subject_program_id`);

--
-- Indexes for table `subject_schedule`
--
ALTER TABLE `subject_schedule`
  ADD PRIMARY KEY (`subject_schedule_id`),
  ADD KEY `student_subject_id` (`subject_program_id`) USING BTREE;

--
-- Indexes for table `subject_template`
--
ALTER TABLE `subject_template`
  ADD PRIMARY KEY (`subject_template_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indexes for table `track`
--
ALTER TABLE `track`
  ADD PRIMARY KEY (`track_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `waiting_list`
--
ALTER TABLE `waiting_list`
  ADD PRIMARY KEY (`waiting_list_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1265;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1302;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `pending_enrollees`
--
ALTER TABLE `pending_enrollees`
  MODIFY `pending_enrollees_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `school_year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6974;

--
-- AUTO_INCREMENT for table `student_assignment_grade`
--
ALTER TABLE `student_assignment_grade`
  MODIFY `student_assignment_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student_period_assignment`
--
ALTER TABLE `student_period_assignment`
  MODIFY `student_period_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `student_requirement`
--
ALTER TABLE `student_requirement`
  MODIFY `student_requirement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `student_subject`
--
ALTER TABLE `student_subject`
  MODIFY `student_subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2859;

--
-- AUTO_INCREMENT for table `student_subject_grade`
--
ALTER TABLE `student_subject_grade`
  MODIFY `student_subject_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1342;

--
-- AUTO_INCREMENT for table `subject_assignment_submission`
--
ALTER TABLE `subject_assignment_submission`
  MODIFY `subject_assignment_submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `subject_assignment_submission_list`
--
ALTER TABLE `subject_assignment_submission_list`
  MODIFY `subject_assignment_submission_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `subject_code_assignment`
--
ALTER TABLE `subject_code_assignment`
  MODIFY `subject_code_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `subject_code_assignment_list`
--
ALTER TABLE `subject_code_assignment_list`
  MODIFY `subject_code_assignment_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subject_code_assignment_template`
--
ALTER TABLE `subject_code_assignment_template`
  MODIFY `subject_code_assignment_template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `subject_code_assignment_template_list`
--
ALTER TABLE `subject_code_assignment_template_list`
  MODIFY `subject_code_assignment_template_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `subject_code_handout`
--
ALTER TABLE `subject_code_handout`
  MODIFY `subject_code_handout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subject_code_handout_template`
--
ALTER TABLE `subject_code_handout_template`
  MODIFY `subject_code_handout_template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subject_code_handout_template_list`
--
ALTER TABLE `subject_code_handout_template_list`
  MODIFY `subject_code_handout_template_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subject_period`
--
ALTER TABLE `subject_period`
  MODIFY `subject_period_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `subject_period_code`
--
ALTER TABLE `subject_period_code`
  MODIFY `subject_period_code_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subject_period_code_topic`
--
ALTER TABLE `subject_period_code_topic`
  MODIFY `subject_period_code_topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `subject_period_code_topic_template`
--
ALTER TABLE `subject_period_code_topic_template`
  MODIFY `subject_period_code_topic_template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subject_program`
--
ALTER TABLE `subject_program`
  MODIFY `subject_program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `subject_schedule`
--
ALTER TABLE `subject_schedule`
  MODIFY `subject_schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `subject_template`
--
ALTER TABLE `subject_template`
  MODIFY `subject_template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `track`
--
ALTER TABLE `track`
  MODIFY `track_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `waiting_list`
--
ALTER TABLE `waiting_list`
  MODIFY `waiting_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_requirement`
--
ALTER TABLE `student_requirement`
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_subject`
--
ALTER TABLE `student_subject`
  ADD CONSTRAINT `fk_enrollment_id` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollment` (`enrollment_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_subject_grade`
--
ALTER TABLE `student_subject_grade`
  ADD CONSTRAINT `student_subject_grade_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `subject_code_assignment_list`
--
ALTER TABLE `subject_code_assignment_list`
  ADD CONSTRAINT `subject_code_assignment_list_ibfk_1` FOREIGN KEY (`subject_code_assignment_id`) REFERENCES `subject_code_assignment` (`subject_code_assignment_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
