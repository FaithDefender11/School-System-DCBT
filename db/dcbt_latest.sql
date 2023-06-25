-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2023 at 11:53 AM
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
  `capacity` int(2) NOT NULL,
  `adviser_teacher_id` int(11) NOT NULL,
  `room` varchar(6) NOT NULL,
  `school_year_term` varchar(10) NOT NULL,
  `active` varchar(3) NOT NULL DEFAULT 'no',
  `is_tertiary` tinyint(1) NOT NULL DEFAULT 0,
  `is_full` varchar(3) NOT NULL DEFAULT 'no',
  `previous_course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `program_section`, `program_id`, `creationDate`, `course_level`, `capacity`, `adviser_teacher_id`, `room`, `school_year_term`, `active`, `is_tertiary`, `is_full`, `previous_course_id`) VALUES
(767, 'STEM11-A', 3, '2023-06-24 15:44:41', 11, 2, 1, '55', '2021-2022', 'no', 0, 'no', 0),
(768, 'ABE1-A', 2, '2023-06-24 15:50:31', 1, 2, 2, '45', '2021-2022', 'no', 1, 'no', 0),
(769, 'STEM12-A', 3, '2023-06-24 16:32:24', 12, 2, 0, '', '2022-2023', 'yes', 0, 'no', 767),
(770, 'ABE2-A', 2, '2023-06-24 16:32:24', 2, 2, 0, '', '2022-2023', 'no', 1, 'no', 768),
(771, 'ABE3-A', 2, '2023-06-24 16:41:14', 3, 2, 0, '', '2023-2024', 'yes', 1, 'no', 770);

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
(1, 'College of Engineering'),
(3, 'College of Technology'),
(4, 'Senior High School');

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
  `cashier_evaluated` varchar(3) NOT NULL DEFAULT 'no',
  `head_evaluated` varchar(3) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`enrollment_id`, `student_id`, `course_id`, `school_year_id`, `enrollment_form_id`, `enrollment_date`, `enrollment_approve`, `enrollment_status`, `is_tertiary`, `is_new_enrollee`, `is_transferee`, `is_returnee`, `registrar_evaluated`, `cashier_evaluated`, `head_evaluated`) VALUES
(851, 491, 767, 1, 'W4I5HR', '2023-06-24 16:17:08', '2023-06-24 16:19:46', 'enrolled', 0, 1, 0, 0, 'yes', 'yes', 'no'),
(852, 492, 768, 1, 'EVARN3', '2023-06-24 16:19:05', '2023-06-24 16:20:31', 'enrolled', 1, 1, 0, 0, 'yes', 'yes', 'no'),
(853, 491, 767, 2, '7QBIT8', '2023-06-24 16:28:44', '2023-06-24 16:30:05', 'enrolled', 0, 0, 0, 0, 'yes', 'yes', 'no'),
(854, 492, 768, 2, '4HMRQY', '2023-06-24 16:28:45', '2023-06-24 16:30:17', 'enrolled', 1, 0, 0, 0, 'yes', 'yes', 'no'),
(855, 491, 769, 3, 'TE74FJ', '2023-06-24 16:32:35', '2023-06-24 16:33:22', 'enrolled', 0, 0, 0, 0, 'yes', 'yes', 'no'),
(856, 492, 770, 3, '52R0WN', '2023-06-24 16:32:35', '2023-06-24 16:33:24', 'enrolled', 1, 0, 0, 0, 'yes', 'yes', 'no'),
(857, 491, 769, 4, 'K2J5PA', '2023-06-24 16:37:54', '2023-06-24 16:38:59', 'enrolled', 0, 0, 0, 0, 'yes', 'yes', 'no'),
(858, 492, 770, 4, 'DPIYBN', '2023-06-24 16:37:54', '2023-06-24 16:39:06', 'enrolled', 1, 0, 0, 0, 'yes', 'yes', 'no'),
(859, 492, 771, 5, 'HNTRZ6', '2023-06-24 16:41:28', '2023-06-24 16:41:45', 'enrolled', 1, 0, 0, 0, 'yes', 'yes', 'no'),
(860, 492, 771, 6, 'DHIOG8', '2023-06-24 16:42:24', '2023-06-24 16:42:38', 'enrolled', 1, 0, 0, 0, 'yes', 'yes', 'no'),
(861, 493, 769, 6, 'D98YK5', '2023-06-24 16:52:38', NULL, 'tentative', 0, 1, 1, 0, 'yes', 'yes', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `parent_id` int(11) NOT NULL,
  `pending_enrollees_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `contact_number` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `suffix` varchar(50) NOT NULL,
  `occupation` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`parent_id`, `pending_enrollees_id`, `student_id`, `firstname`, `middle_name`, `lastname`, `contact_number`, `email`, `date_creation`, `active`, `suffix`, `occupation`) VALUES
(67, 158, 491, 'Parent', 'Sirios', 'A', '91515123', 'hyper15@gmail.com', '2023-06-24 15:58:32', 1, '', ''),
(68, 160, 492, 'TerParent', 'Surname', 'Z', '0915151515123', 'parent@gmail.com', '2023-06-24 16:11:08', 1, '', ''),
(69, 161, 493, 'ParentTransferee ', 'Surname', 'Z', '0915151515123', 'parent@gmail.com', '2023-06-24 16:52:14', 1, '', 'Loyal');

-- --------------------------------------------------------

--
-- Table structure for table `pending_enrollees`
--

CREATE TABLE `pending_enrollees` (
  `pending_enrollees_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `token` varchar(350) NOT NULL,
  `expiration_time` datetime DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL DEFAULT 0,
  `activated` tinyint(4) NOT NULL,
  `password` varchar(255) NOT NULL,
  `program_id` int(11) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `nationality` varchar(50) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `birthday` date NOT NULL,
  `age` int(11) NOT NULL,
  `guardian_name` varchar(100) NOT NULL,
  `guardian_contact_number` varchar(20) NOT NULL,
  `sex` varchar(8) NOT NULL,
  `student_status` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `lrn` varchar(15) NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `type` varchar(8) DEFAULT NULL,
  `religion` varchar(50) NOT NULL,
  `birthplace` varchar(250) NOT NULL,
  `suffix` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pending_enrollees`
--

INSERT INTO `pending_enrollees` (`pending_enrollees_id`, `firstname`, `lastname`, `middle_name`, `email`, `token`, `expiration_time`, `is_finished`, `activated`, `password`, `program_id`, `civil_status`, `date_creation`, `nationality`, `contact_number`, `birthday`, `age`, `guardian_name`, `guardian_contact_number`, `sex`, `student_status`, `address`, `lrn`, `date_approved`, `type`, `religion`, `birthplace`, `suffix`) VALUES
(158, 'Hyper', 'Sirios', 'A', 'hypersirios15@gmail.com', '0572ec3abbb90d4fe4462bdbefffb81b', '2023-06-24 15:57:23', 1, 1, '$2y$10$JWPyOztzcmElFZcehnZgXeDxRCO2pj8DCPvEqY.3di3pJZ2B7hX0m', 3, 'Single', '2023-06-24 15:52:23', 'Filipino', '09151515123', '2000-06-15', 23, '', '', 'Male', 'APPROVED', 'None', '555', '2023-06-24 16:17:08', 'SHS', 'None', 'Taguigarao', 'Jr'),
(160, 'ter1', 'Sirios', 'A', 'hypersirios15@gmail.com', 'f4ea395952325fa519b08286300ecf8e', '2023-06-24 16:15:21', 1, 1, '$2y$10$4cj4Qb56T3OITuuEA.kYSu2sSgv107vFcLFA3ZBq8CjigfG79opCu', 2, 'Single', '2023-06-24 16:10:21', 'Filipino', '09151515123', '2000-06-15', 23, '', '', 'Male', 'APPROVED', 'None', '555', '2023-06-24 16:19:05', 'Tertiary', 'None', 'Taguigarao', ''),
(161, 'shstrans', 'Sirios', 'A', 'hypersirios15@gmail.com', 'e493155fade4e8b83db1edeb0f8fbbd0', '2023-06-24 16:56:03', 1, 1, '$2y$10$eGjadXb4p9sLSHb/E49MuuW2AIwz/W2eSKRWzNx.c74xnzD1AK2a2', 3, 'Single', '2023-06-24 16:51:03', 'Filipino', '09151515123', '2000-06-07', 23, '', '', 'Male', 'APPROVED', 'None', '555', NULL, 'SHS', 'None', 'Taguigarao', ''),
(162, 'shstrans', 'Sirios', 'A', 'hypersirios15@gmail.com', '5a8644648c917e63b3ad48776861d3d5', '2023-06-24 16:56:06', 0, 1, '$2y$10$aBA3we5GVi9fwwAnQ.hoUelIfgUkHLcPRUNvUP1BeTohpQJt7r11e', 0, '', '2023-06-24 16:51:06', '', '', '0000-00-00', 0, '', '', '', '', '', '', NULL, NULL, '', '', '');

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
  `acronym` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `program_name`, `department_id`, `dean`, `track`, `acronym`) VALUES
(1, 'Bachelor of Science in Computer Science', 2, 'Head of CS', 'Technology', 'CS'),
(2, 'Bachelor of Arts In English', 1, 'Head of ABE', 'Arts In English', 'ABE'),
(3, 'Science, Technology, Engineering, and Mathematics', 4, 'Head of STEM', 'Academic', 'STEM'),
(4, 'Humanities and Social Sciences', 4, 'Head of HUMMS', 'Academic', 'HUMMS'),
(7, 'Accountancy, Business, and Management', 4, 'Head of ABM', 'Academic', 'ABM'),
(8, 'Information Communication Technology', 4, 'Head of ICT', 'TVL', 'ICT');

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `school_year_id` int(11) NOT NULL,
  `term` varchar(10) NOT NULL,
  `period` varchar(15) NOT NULL,
  `statuses` varchar(25) NOT NULL DEFAULT 'InActive',
  `enrollment_status` tinyint(4) NOT NULL DEFAULT 0,
  `is_finished` tinyint(4) NOT NULL DEFAULT 0,
  `start_enrollment_date` datetime DEFAULT NULL,
  `end_enrollment_date` datetime DEFAULT NULL,
  `start_period` datetime DEFAULT NULL,
  `end_period` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`school_year_id`, `term`, `period`, `statuses`, `enrollment_status`, `is_finished`, `start_enrollment_date`, `end_enrollment_date`, `start_period`, `end_period`) VALUES
(1, '2021-2022', 'First', 'InActive', 0, 1, '2023-06-24 15:57:10', '2023-06-24 16:21:50', NULL, '2023-06-24 16:28:19'),
(2, '2021-2022', 'Second', 'InActive', 0, 1, '2023-06-24 16:28:44', '2023-06-24 16:30:29', NULL, '2023-06-24 16:32:24'),
(3, '2022-2023', 'First', 'InActive', 0, 1, '2023-06-24 16:32:35', '2023-06-24 16:36:18', NULL, '2023-06-24 16:36:45'),
(4, '2022-2023', 'Second', 'InActive', 1, 0, '2023-06-24 16:37:54', NULL, NULL, '2023-06-24 16:41:14'),
(5, '2023-2024', 'First', 'InActive', 0, 1, '2023-06-24 16:41:28', '2023-06-24 16:42:12', NULL, '2023-06-24 16:42:14'),
(6, '2023-2024', 'Second', 'Active', 1, 0, '2023-06-24 16:42:24', NULL, NULL, NULL),
(7, '2024-2025', 'First', 'InActive', 0, 0, NULL, NULL, NULL, NULL),
(8, '2024-2025', 'Second', 'InActive', 0, 0, NULL, NULL, NULL, NULL),
(9, '2025-2026', 'First', 'InActive', 0, 0, NULL, NULL, NULL, NULL),
(10, '2025-2026', 'Second', 'InActive', 0, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `middle_name` varchar(4) NOT NULL,
  `username` varchar(50) NOT NULL,
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
  `course_tertiary_id` int(11) NOT NULL,
  `student_unique_id` int(20) NOT NULL,
  `sex` varchar(6) NOT NULL,
  `birthday` date DEFAULT NULL,
  `birthplace` varchar(150) NOT NULL,
  `age` int(30) NOT NULL,
  `nationality` varchar(40) NOT NULL,
  `religion` varchar(40) NOT NULL,
  `contact_number` varchar(40) NOT NULL,
  `address` text NOT NULL,
  `guardian_name` varchar(100) NOT NULL,
  `guardian_contact_number` varchar(40) NOT NULL,
  `new_enrollee` tinyint(1) NOT NULL DEFAULT 1,
  `course_level` tinyint(2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `lrn` varchar(15) NOT NULL,
  `is_tertiary` tinyint(4) NOT NULL DEFAULT 0,
  `is_graduated` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `firstname`, `lastname`, `middle_name`, `username`, `email`, `profilePic`, `password`, `civil_status`, `student_status`, `student_statusv2`, `admission_status`, `citizenship`, `date_creation`, `course_id`, `course_tertiary_id`, `student_unique_id`, `sex`, `birthday`, `birthplace`, `age`, `nationality`, `religion`, `contact_number`, `address`, `guardian_name`, `guardian_contact_number`, `new_enrollee`, `course_level`, `active`, `lrn`, `is_tertiary`, `is_graduated`) VALUES
(491, 'Hyper', 'Sirios', 'A', 'sirios.100001@dcbt.ph', 'hypersirios15@gmail.com', '', '$2y$10$JWPyOztzcmElFZcehnZgXeDxRCO2pj8DCPvEqY.3di3pJZ2B7hX0m', 'Single', 'Regular', 'Regular', 'Standard', '', '2023-06-24 16:17:08', 769, 0, 100001, 'Male', '2000-06-15', 'Taguigarao', 23, 'Filipino', 'None', '09151515123', 'None', '', '', 0, 12, 0, '555', 0, 1),
(492, 'ter1', 'Sirios', 'A', 'sirios.100002@dcbt.ph', 'hypersirios15@gmail.com', '', '$2y$10$4cj4Qb56T3OITuuEA.kYSu2sSgv107vFcLFA3ZBq8CjigfG79opCu', 'Single', 'Regular', 'Regular', 'Standard', '', '2023-06-24 16:19:05', 771, 0, 100002, 'Male', '2000-06-15', 'Taguigarao', 23, 'Filipino', 'None', '09151515123', 'None', '', '', 0, 4, 1, '555', 1, 0),
(493, 'shstrans', 'Sirios', 'A', 'sirios.100003@dcbt.ph', 'hypersirios15@gmail.com', '', '$2y$10$eGjadXb4p9sLSHb/E49MuuW2AIwz/W2eSKRWzNx.c74xnzD1AK2a2', 'Single', 'Transferee', 'Regular', 'Transferee', '', '2023-06-24 16:52:38', 769, 0, 100003, 'Male', '2000-06-07', 'Taguigarao', 23, 'Filipino', 'None', '09151515123', 'None', '', '', 1, 12, 1, '555', 0, 0);

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
-- Table structure for table `student_inactive_reason`
--

CREATE TABLE `student_inactive_reason` (
  `student_inactive_reason_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `reason_title` varchar(100) NOT NULL,
  `description` varchar(150) NOT NULL,
  `student_status` varchar(10) NOT NULL,
  `current_course_id` int(11) NOT NULL,
  `current_course_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_inactive_reason`
--

INSERT INTO `student_inactive_reason` (`student_inactive_reason_id`, `student_id`, `date`, `reason_title`, `description`, `student_status`, `current_course_id`, `current_course_level`) VALUES
(13, 188, '2023-04-28 11:07:24', 'Student Had Reached the Enrollment Data', 'If you want to enroll, Please walk in to registrar.', '', 0, 0),
(14, 201, '2023-04-30 08:50:20', 'Student Had Reached the Enrollment Data', 'If you want to enroll, Please walk in to registrar.', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_payment`
--

CREATE TABLE `student_payment` (
  `student_payment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date_payment` datetime NOT NULL DEFAULT current_timestamp(),
  `course_id` int(11) NOT NULL,
  `course_level` int(11) NOT NULL,
  `total_units` int(11) NOT NULL,
  `amount_to_paid` double NOT NULL,
  `total_payment` double NOT NULL,
  `partial_payment` varchar(3) NOT NULL DEFAULT 'yes',
  `settled_payment` varchar(3) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `student_subject`
--

CREATE TABLE `student_subject` (
  `student_subject_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `subject_program_id` int(11) NOT NULL,
  `course_level` tinyint(2) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `is_transferee` varchar(3) NOT NULL DEFAULT 'no',
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `is_final` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_subject`
--

INSERT INTO `student_subject` (`student_subject_id`, `student_id`, `subject_id`, `enrollment_id`, `subject_program_id`, `course_level`, `school_year_id`, `is_transferee`, `date_creation`, `is_final`) VALUES
(1766, 491, 2907, 851, 122, 11, 1, 'no', '2023-06-24 16:19:46', 1),
(1767, 492, 2910, 852, 128, 1, 1, 'no', '2023-06-24 16:20:31', 1),
(1768, 492, 2913, 852, 140, 1, 1, 'no', '2023-06-24 16:20:31', 1),
(1769, 491, 2908, 853, 123, 11, 2, 'no', '2023-06-24 16:30:05', 1),
(1770, 491, 2909, 853, 124, 11, 2, 'no', '2023-06-24 16:30:05', 1),
(1771, 492, 2911, 854, 129, 1, 2, 'no', '2023-06-24 16:30:17', 1),
(1772, 492, 2912, 854, 130, 1, 2, 'no', '2023-06-24 16:30:17', 1),
(1773, 492, 2914, 854, 141, 1, 2, 'no', '2023-06-24 16:30:17', 1),
(1774, 491, 2915, 855, 125, 12, 3, 'no', '2023-06-24 16:33:22', 1),
(1775, 492, 2918, 856, 131, 2, 3, 'no', '2023-06-24 16:33:24', 1),
(1776, 491, 2916, 857, 126, 12, 4, 'no', '2023-06-24 16:38:59', 1),
(1777, 491, 2917, 857, 127, 12, 4, 'no', '2023-06-24 16:38:59', 1),
(1778, 492, 2919, 858, 132, 2, 4, 'no', '2023-06-24 16:39:06', 1),
(1779, 492, 2920, 858, 133, 2, 4, 'no', '2023-06-24 16:39:06', 1),
(1780, 492, 2921, 859, 134, 3, 5, 'no', '2023-06-24 16:41:45', 1),
(1781, 492, 2922, 860, 135, 3, 6, 'no', '2023-06-24 16:42:38', 1),
(1782, 492, 2923, 860, 136, 3, 6, 'no', '2023-06-24 16:42:38', 1),
(1783, 493, 2916, 861, 126, 12, 6, 'no', '2023-06-24 16:54:05', 0),
(1784, 493, 2917, 861, 127, 12, 6, 'no', '2023-06-24 16:54:05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_subject_grade`
--

CREATE TABLE `student_subject_grade` (
  `student_subject_grade_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_subject_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_title` varchar(40) NOT NULL,
  `first` int(11) NOT NULL,
  `second` int(11) NOT NULL,
  `third` int(11) NOT NULL,
  `fourth` int(11) NOT NULL,
  `average` float NOT NULL,
  `remarks` varchar(51) NOT NULL,
  `is_transferee` varchar(3) NOT NULL DEFAULT 'no',
  `comment` varchar(250) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_subject_grade`
--

INSERT INTO `student_subject_grade` (`student_subject_grade_id`, `student_id`, `student_subject_id`, `subject_id`, `course_id`, `subject_title`, `first`, `second`, `third`, `fourth`, `average`, `remarks`, `is_transferee`, `comment`, `date_creation`) VALUES
(1121, 491, 1766, 2907, 767, 'PE-1', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:27:30'),
(1122, 492, 1767, 2910, 768, 'Physical Fitness 1', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:27:41'),
(1123, 492, 1768, 2913, 768, 'RTC-1 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:27:44'),
(1124, 491, 1769, 2908, 2908, 'STEM-1 TITLE', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:30:46'),
(1125, 491, 1770, 2909, 2909, 'STEM-2 TITLE', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:30:55'),
(1126, 492, 1771, 2911, 2911, 'ABE-1 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:32:02'),
(1127, 492, 1772, 2912, 2912, 'ABE-2 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:32:03'),
(1128, 492, 1773, 2914, 2914, 'RTC-2 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:32:05'),
(1129, 491, 1774, 2915, 2915, 'PE-2', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:33:39'),
(1130, 492, 1775, 2918, 2918, 'Physical Fitness 2', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:33:49'),
(1131, 491, 1776, 2916, 2916, 'STEM-2 TITLE', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:39:27'),
(1132, 491, 1777, 2917, 2917, 'STEM-3 TITLE', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:39:34'),
(1133, 492, 1778, 2919, 770, 'ABE-3 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:40:04'),
(1134, 492, 1779, 2920, 770, 'ABE-4 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:40:06'),
(1135, 492, 1780, 2921, 771, 'NSTP-1 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:41:56'),
(1136, 492, 1781, 2922, 771, 'ABE-5 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:42:49'),
(1137, 492, 1782, 2923, 771, 'ABE-6 Title', 0, 0, 0, 0, 0, 'Passed', 'no', '', '2023-06-24 16:42:51'),
(1138, 493, 0, 0, 0, 'PE-1', 0, 0, 0, 0, 0, 'Passed', 'yes', '', '2023-06-24 16:52:48'),
(1139, 493, 0, 0, 0, 'STEM-1 TITLE', 0, 0, 0, 0, 0, 'Passed', 'yes', '', '2023-06-24 16:53:42'),
(1141, 493, 0, 0, 0, 'PE-2', 0, 0, 0, 0, 0, 'Passed', 'yes', '', '2023-06-24 16:53:50');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(100) NOT NULL,
  `subject_title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `subject_program_id` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `semester` varchar(100) NOT NULL,
  `pre_requisite` varchar(50) NOT NULL DEFAULT 'None',
  `program_id` int(11) NOT NULL,
  `course_level` int(1) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_type` enum('CORE SUBJECTS','APPLIED SUBJECTS','SPECIALIZED_SUBJECTS') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject_code`, `subject_title`, `description`, `subject_program_id`, `unit`, `semester`, `pre_requisite`, `program_id`, `course_level`, `course_id`, `subject_type`) VALUES
(2907, 'PE101-STEM11-A', 'PE-1', 'PE-1 Desc', 122, 3, 'First', 'None', 3, 11, 767, ''),
(2908, 'STEM-1-STEM11-A', 'STEM-1 TITLE', 'STEM-1 DESC ', 123, 3, 'Second', 'None', 3, 11, 767, ''),
(2909, 'STEM-2-STEM11-A', 'STEM-2 TITLE', 'STEM-2 DESC', 124, 3, 'Second', 'None', 3, 11, 767, ''),
(2910, 'PF-1-ABE1-A', 'Physical Fitness 1', 'Physical Fitness 1 Desc', 128, 3, 'First', 'None', 2, 1, 768, ''),
(2911, 'ABE-1-ABE1-A', 'ABE-1 Title', 'ABE-1 Desc', 129, 3, 'Second', 'None', 2, 1, 768, ''),
(2912, 'ABE-2-ABE1-A', 'ABE-2 Title', 'ABE-2 Desc', 130, 3, 'Second', 'None', 2, 1, 768, ''),
(2913, 'RTC101-ABE1-A', 'RTC-1 Title', 'RTC-1 DSC', 140, 3, 'First', 'None', 2, 1, 768, ''),
(2914, 'RTC201-ABE1-A', 'RTC-2 Title', 'RTC-2 Description', 141, 3, 'Second', 'None', 2, 1, 768, ''),
(2915, 'PE102-STEM12-A', 'PE-2', 'PE 2 DESC', 125, 3, 'First', 'None', 3, 12, 769, ''),
(2916, 'STEM-2-STEM12-A', 'STEM-2 TITLE', 'STEM-2 DESC', 126, 3, 'Second', 'None', 3, 12, 769, ''),
(2917, 'STEM-3-STEM12-A', 'STEM-3 TITLE', 'STEM-3 DESC', 127, 3, 'Second', 'None', 3, 12, 769, ''),
(2918, 'PF-2-ABE2-A', 'Physical Fitness 2', 'Physical Fitness 2 Desc', 131, 3, 'First', 'None', 2, 2, 770, ''),
(2919, 'ABE-3-ABE2-A', 'ABE-3 Title', 'ABE-3 Desc', 132, 3, 'Second', 'None', 2, 2, 770, ''),
(2920, 'ABE-4-ABE2-A', 'ABE-4 Title', 'ABE-4 Title Desc', 133, 3, 'Second', 'None', 2, 2, 770, ''),
(2921, 'NSTP-1-ABE3-A', 'NSTP-1 Title', 'NSTP-1 Desc', 134, 3, 'First', 'None', 2, 3, 771, ''),
(2922, 'ABE-5-ABE3-A', 'ABE-5 Title', 'ABE-5 Desc', 135, 3, 'Second', 'None', 2, 3, 771, ''),
(2923, 'ABE-6-ABE3-A', 'ABE-6 Title', 'ABE-6 Desc', 136, 3, 'Second', 'None', 2, 3, 771, '');

-- --------------------------------------------------------

--
-- Table structure for table `subject_period`
--

CREATE TABLE `subject_period` (
  `subject_period_id` int(11) NOT NULL,
  `term` varchar(10) NOT NULL,
  `title` varchar(350) NOT NULL,
  `description` varchar(350) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `thumbnail` varchar(250) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_period`
--

INSERT INTO `subject_period` (`subject_period_id`, `term`, `title`, `description`, `subject_id`, `school_year_id`, `thumbnail`, `date_creation`) VALUES
(1, 'Prelim', 'Topic Name for GM-1 (Prelim)', 'Requirements Analysis and Modeling (Prelim) Desc', 2529, 1, '', '2023-06-11 08:39:37'),
(2, 'Midterm', 'Design Principles (Midterm)', 'Design Principles (Midterm) Desc', 2529, 1, '', '2023-06-11 08:39:37'),
(5, 'Prelim', 'Topic Name for GM-2 (Prelim)', '', 2530, 1, '', '2023-06-12 11:09:14'),
(6, 'Midterm', 'Topic Name for GM-2 (Midterm)', '', 2530, 1, '', '2023-06-12 11:09:14'),
(7, 'Prelim', 'Topic Name for STEM11-1 TITLE (Prelim)', '', 2849, 1, '', '2023-06-17 07:37:19'),
(8, 'Midterm', 'Topic Name for STEM11-1 TITLE (Midterm)', '', 2849, 1, '', '2023-06-17 07:37:19'),
(9, 'Prelim', 'Topic Name for PE-1 (Prelim)', '', 2848, 1, '', '2023-06-17 07:41:21'),
(10, 'Midterm', 'Topic Name for PE-1 (Midterm)', '', 2848, 1, '', '2023-06-17 07:41:21'),
(11, 'Prelim', 'Topic Name for PE-1 (Prelim)', '', 2907, 1, '', '2023-06-24 15:45:53'),
(12, 'Midterm', 'Topic Name for PE-1 (Midterm)', '', 2907, 1, '', '2023-06-24 15:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `subject_period_assignment`
--

CREATE TABLE `subject_period_assignment` (
  `subject_period_assignment_id` int(11) NOT NULL,
  `assignment_name` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `subject_period_id` int(11) NOT NULL,
  `assignment_picture` varchar(250) NOT NULL,
  `student_viewed_id` int(11) DEFAULT NULL,
  `max_score` tinyint(3) NOT NULL,
  `max_attempt` tinyint(1) NOT NULL DEFAULT 2,
  `due_date` datetime DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_period_assignment`
--

INSERT INTO `subject_period_assignment` (`subject_period_assignment_id`, `assignment_name`, `description`, `subject_period_id`, `assignment_picture`, `student_viewed_id`, `max_score`, `max_attempt`, `due_date`, `date_creation`) VALUES
(1, 'First Assignment', 'Do this for yo sake.', 1, '', 0, 50, 2, '2023-06-21 08:55:34', '2023-06-11 08:39:58'),
(2, 'Samp', 'Samp Desc', 1, '', NULL, 50, 2, '2023-06-21 00:00:00', '2023-06-11 09:24:23'),
(3, 'Second', 'Second Desc', 1, '', NULL, 25, 2, '2023-06-21 00:00:00', '2023-06-11 09:25:23'),
(6, 'Test', 'Test', 1, 'assets/images/answers/tatay.jpg', NULL, 25, 2, '2023-06-22 00:00:00', '2023-06-11 15:33:25'),
(7, 'Algorithmic', 'Algorithmic Desc', 2, 'assets/images/answers/mypeace.png', NULL, 100, 2, '2023-06-15 00:00:00', '2023-06-12 08:39:10'),
(8, 'Samp', 'Design Principle Description Assignment', 7, 'assets/images/answers/mypeace.png', NULL, 50, 2, '2023-06-28 00:00:00', '2023-06-17 07:39:09'),
(9, 'PE', 'PE DESC', 9, 'assets/images/answers/game6.png', NULL, 25, 2, '2023-06-27 00:00:00', '2023-06-17 07:41:53');

-- --------------------------------------------------------

--
-- Table structure for table `subject_program`
--

CREATE TABLE `subject_program` (
  `subject_program_id` int(11) NOT NULL,
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

INSERT INTO `subject_program` (`subject_program_id`, `program_id`, `subject_template_id`, `subject_title`, `subject_code`, `pre_req_subject_title`, `unit`, `description`, `course_level`, `semester`, `subject_type`, `active`) VALUES
(122, 3, 75, 'PE-1', 'PE101', 'None', 3, 'PE-1 Desc', 11, 'First', 'Core', 'yes'),
(123, 3, 76, 'STEM-1 TITLE', 'STEM-1', 'None', 3, 'STEM-1 DESC ', 11, 'Second', 'Applied', 'yes'),
(124, 3, 77, 'STEM-2 TITLE', 'STEM-2', 'None', 3, 'STEM-2 DESC', 11, 'Second', 'Core', 'yes'),
(125, 3, 78, 'PE-2', 'PE102', 'None', 3, 'PE 2 DESC', 12, 'First', 'Core', 'yes'),
(126, 3, 77, 'STEM-2 TITLE', 'STEM-2', 'None', 3, 'STEM-2 DESC', 12, 'Second', 'Core', 'yes'),
(127, 3, 79, 'STEM-3 TITLE', 'STEM-3', 'None', 3, 'STEM-3 DESC', 12, 'Second', 'Core', 'yes'),
(128, 2, 87, 'Physical Fitness 1', 'PF-1', 'None', 3, 'Physical Fitness 1 Desc', 1, 'First', 'Core', 'yes'),
(129, 2, 89, 'ABE-1 Title', 'ABE-1', 'None', 3, 'ABE-1 Desc', 1, 'Second', 'Core', 'yes'),
(130, 2, 90, 'ABE-2 Title', 'ABE-2', 'None', 3, 'ABE-2 Desc', 1, 'Second', 'Specialized', 'yes'),
(131, 2, 88, 'Physical Fitness 2', 'PF-2', 'None', 3, 'Physical Fitness 2 Desc', 2, 'First', 'Core', 'yes'),
(132, 2, 91, 'ABE-3 Title', 'ABE-3', 'None', 3, 'ABE-3 Desc', 2, 'Second', 'Specialized', 'yes'),
(133, 2, 93, 'ABE-4 Title', 'ABE-4', 'None', 3, 'ABE-4 Title Desc', 2, 'Second', 'Specialized', 'yes'),
(134, 2, 94, 'NSTP-1 Title', 'NSTP-1', 'None', 3, 'NSTP-1 Desc', 3, 'First', 'Core', 'yes'),
(135, 2, 96, 'ABE-5 Title', 'ABE-5', 'None', 3, 'ABE-5 Desc', 3, 'Second', 'Specialized', 'yes'),
(136, 2, 97, 'ABE-6 Title', 'ABE-6', 'None', 3, 'ABE-6 Desc', 3, 'Second', 'Specialized', 'yes'),
(137, 2, 95, 'NSTP-2 Title', 'NSTP-2', 'None', 3, 'NSTP-2 Desc', 4, 'First', 'Core', 'yes'),
(138, 2, 98, 'ABE-7 Title', 'ABE-7', 'None', 3, 'ABE-7 Desc', 4, 'Second', 'Specialized', 'yes'),
(139, 2, 99, 'ABE-8 Title', 'ABE-8', 'None', 3, 'ABE-8 Desc', 4, 'Second', 'Specialized', 'yes'),
(140, 2, 100, 'RTC-1 Title', 'RTC101', 'None', 3, 'RTC-1 DSC', 1, 'First', 'Core', 'yes'),
(141, 2, 101, 'RTC-2 Title', 'RTC201', 'None', 3, 'RTC-2 Description', 1, 'Second', 'Core', 'yes');

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
  `room` int(11) NOT NULL,
  `section` varchar(15) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_schedule`
--

INSERT INTO `subject_schedule` (`subject_schedule_id`, `time_from`, `time_to`, `schedule_time`, `schedule_day`, `school_year_id`, `room`, `section`, `course_id`, `subject_id`, `teacher_id`, `date_creation`, `time`) VALUES
(107, '8:00', '9:30', '8:00 AM - 9:30 PM', 'M', 1, 55, '', 0, 2849, 1, '2023-06-17 07:37:19', '00:00:00'),
(108, '8:00', '9:30', '8:00 AM - 9:30 PM', 'M', 1, 55, '', 0, 2848, 1, '2023-06-17 07:41:21', '00:00:00'),
(109, '8:00', '9:30', '8:00 AM - 9:30 AM\"', 'M', 1, 55, '', 0, 2907, 1, '2023-06-24 15:45:53', '00:00:00');

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
  `course_level` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `subject_type` enum('Core','Applied','Specialized') NOT NULL,
  `program_id` int(11) NOT NULL,
  `template_type` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_template`
--

INSERT INTO `subject_template` (`subject_template_id`, `subject_title`, `subject_code`, `unit`, `description`, `pre_requisite_title`, `course_level`, `semester`, `subject_type`, `program_id`, `template_type`) VALUES
(75, 'Physical Education-1', 'PE101', 3, 'PE-1 Desc', 'None', 0, '', 'Core', 0, 0),
(76, 'STEM-1 TITLE', 'STEM-1', 3, 'STEM-1 DESC ', 'None', 0, '', 'Applied', 0, 0),
(77, 'STEM-2 TITLE', 'STEM-2', 3, 'STEM-2 DESC', 'None', 0, '', 'Core', 0, 0),
(78, 'Physical Education-2', 'PE102', 3, 'PE 2 DESC', 'None', 0, '', 'Core', 0, 0),
(79, 'STEM-3 TITLE', 'STEM-3', 3, 'STEM-3 DESC', 'None', 0, '', 'Core', 0, 0),
(80, 'STEM-4 TITLE', 'STEM-4', 3, 'STEM-4 DESC', 'None', 0, '', 'Core', 0, 0),
(87, 'Physical Fitness 1', 'PF-1', 3, 'Physical Fitness 1 Desc', 'None', 0, '', 'Core', 0, 0),
(88, 'Physical Fitness 2', 'PF-2', 3, 'Physical Fitness 2 Desc', 'None', 0, '', 'Core', 0, 0),
(89, 'ABE-1 Title', 'ABE-1', 3, 'ABE-1 Desc', 'None', 0, '', 'Core', 0, 0),
(90, 'ABE-2 Title', 'ABE-2', 3, 'ABE-2 Desc', 'None', 0, '', 'Specialized', 0, 0),
(91, 'ABE-3 Title', 'ABE-3', 3, 'ABE-3 Desc', 'None', 0, '', 'Specialized', 0, 0),
(93, 'ABE-4 Title', 'ABE-4', 3, 'ABE-4 Title Desc', 'None', 0, '', 'Specialized', 0, 0),
(94, 'NSTP-1 Title', 'NSTP-1', 3, 'NSTP-1 Desc', 'None', 0, '', 'Core', 0, 0),
(95, 'NSTP-2 Title', 'NSTP-2', 3, 'NSTP-2 Desc', 'None', 0, '', 'Core', 0, 0),
(96, 'ABE-5 Title', 'ABE-5', 3, 'ABE-5 Desc', 'None', 0, '', 'Specialized', 0, 0),
(97, 'ABE-6 Title', 'ABE-6', 3, 'ABE-6 Desc', 'None', 0, '', 'Specialized', 0, 0),
(98, 'ABE-7 Title', 'ABE-7', 3, 'ABE-7 Desc', 'None', 0, '', 'Specialized', 0, 0),
(99, 'ABE-8 Title', 'ABE-8', 3, 'ABE-8 Desc', 'None', 0, '', 'Specialized', 0, 0),
(100, 'RTC-1 Title', 'RTC101', 3, 'RTC-1 DSC', 'None', 0, '', 'Core', 0, 0),
(101, 'RTC-2 Title', 'RTC201', 3, 'RTC-2 Description', 'None', 0, '', 'Core', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `profilePic` varchar(250) NOT NULL,
  `teacher_status` varchar(10) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(7) NOT NULL,
  `email` varchar(50) NOT NULL,
  `teaching_load` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `username`, `password`, `firstname`, `lastname`, `department_id`, `profilePic`, `teacher_status`, `date_creation`, `gender`, `email`, `teaching_load`) VALUES
(1, '200', '123456', 'Albert', 'Einstein', 4, 'assets/images/profilePictures/default.png', 'active', '2023-04-30 08:35:19', '', '', 0),
(2, '201', '123456', 'Kick', 'Butowski', 4, 'assets/images/profilePictures/default-male.png', 'active', '2023-04-30 08:35:19', '', '', 0),
(3, '202', '123456', 'Juju', 'Coco', 4, 'assets/images/profilePictures/default.png', 'active', '2023-04-30 08:35:19', '', '', 0);

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
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(15) NOT NULL,
  `user_image` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `firstName`, `lastName`, `username`, `password`, `role`, `user_image`) VALUES
(1, 'Admin', 'Surname', 'admin', '123456', 'Administrator', ''),
(2, 'Cashier', 'Surname', 'cashier', '123456', 'Cashier', ''),
(3, 'Gerlie', 'Atienza', 'registrar', '123456', 'Registrar', '');

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
  ADD UNIQUE KEY `uc_enrollment` (`school_year_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`parent_id`);

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
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`school_year_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `active` (`active`),
  ADD KEY `course_tertiary_id` (`course_tertiary_id`);

--
-- Indexes for table `student_assignment_grade`
--
ALTER TABLE `student_assignment_grade`
  ADD PRIMARY KEY (`student_assignment_grade_id`),
  ADD KEY `subject_period_assignment_id` (`subject_period_assignment_id`);

--
-- Indexes for table `student_inactive_reason`
--
ALTER TABLE `student_inactive_reason`
  ADD PRIMARY KEY (`student_inactive_reason_id`);

--
-- Indexes for table `student_payment`
--
ALTER TABLE `student_payment`
  ADD PRIMARY KEY (`student_payment_id`);

--
-- Indexes for table `student_period_assignment`
--
ALTER TABLE `student_period_assignment`
  ADD PRIMARY KEY (`student_period_assignment_id`),
  ADD KEY `subject_period_assignment_id` (`subject_period_assignment_id`),
  ADD KEY `student_assignment_grade_id` (`student_assignment_grade_id`);

--
-- Indexes for table `student_subject`
--
ALTER TABLE `student_subject`
  ADD PRIMARY KEY (`student_subject_id`),
  ADD UNIQUE KEY `unique_student_subject` (`student_id`,`subject_id`),
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
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `subject_program_Id` (`subject_program_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `subject_period`
--
ALTER TABLE `subject_period`
  ADD PRIMARY KEY (`subject_period_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subject_period_assignment`
--
ALTER TABLE `subject_period_assignment`
  ADD PRIMARY KEY (`subject_period_assignment_id`);

--
-- Indexes for table `subject_program`
--
ALTER TABLE `subject_program`
  ADD PRIMARY KEY (`subject_program_id`);

--
-- Indexes for table `subject_schedule`
--
ALTER TABLE `subject_schedule`
  ADD PRIMARY KEY (`subject_schedule_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=772;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=862;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `pending_enrollees`
--
ALTER TABLE `pending_enrollees`
  MODIFY `pending_enrollees_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `school_year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=494;

--
-- AUTO_INCREMENT for table `student_assignment_grade`
--
ALTER TABLE `student_assignment_grade`
  MODIFY `student_assignment_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `student_inactive_reason`
--
ALTER TABLE `student_inactive_reason`
  MODIFY `student_inactive_reason_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `student_payment`
--
ALTER TABLE `student_payment`
  MODIFY `student_payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_period_assignment`
--
ALTER TABLE `student_period_assignment`
  MODIFY `student_period_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `student_subject`
--
ALTER TABLE `student_subject`
  MODIFY `student_subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1787;

--
-- AUTO_INCREMENT for table `student_subject_grade`
--
ALTER TABLE `student_subject_grade`
  MODIFY `student_subject_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1142;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2924;

--
-- AUTO_INCREMENT for table `subject_period`
--
ALTER TABLE `subject_period`
  MODIFY `subject_period_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subject_period_assignment`
--
ALTER TABLE `subject_period_assignment`
  MODIFY `subject_period_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subject_program`
--
ALTER TABLE `subject_program`
  MODIFY `subject_program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `subject_schedule`
--
ALTER TABLE `subject_schedule`
  MODIFY `subject_schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `subject_template`
--
ALTER TABLE `subject_template`
  MODIFY `subject_template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `track`
--
ALTER TABLE `track`
  MODIFY `track_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_subject_grade`
--
ALTER TABLE `student_subject_grade`
  ADD CONSTRAINT `student_subject_grade_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
