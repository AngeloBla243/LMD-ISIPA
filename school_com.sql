-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2024 at 02:34 AM
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
-- Database: `school.com`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_class_teacher`
--

CREATE TABLE `assign_class_teacher` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `is_delete` tinyint(4) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assign_class_teacher`
--

INSERT INTO `assign_class_teacher` (`id`, `class_id`, `teacher_id`, `subject_id`, `status`, `is_delete`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 3, 3, NULL, 0, 0, 1, '2024-10-12 22:35:18', '2024-10-12 22:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:not read, 1: read',
  `created_date` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `sender_id`, `receiver_id`, `message`, `file`, `status`, `created_date`, `created_at`, `updated_at`) VALUES
(1, 1, 21, 'hi i am testing', NULL, 1, 1689134771, '2023-07-12 04:06:11', '2023-07-30 03:56:42'),
(2, 1, 14, 'hi', NULL, 1, 1689304468, '2023-07-14 03:14:28', '2023-07-19 06:56:25'),
(3, 1, 14, 'hi', NULL, 1, 1689304549, '2023-07-14 03:15:49', '2023-07-19 06:56:25'),
(4, 21, 1, 'hi i am', NULL, 1, 1689134771, '2023-07-12 04:06:11', '2023-07-19 06:55:02'),
(5, 1, 21, 'hi', NULL, 1, 1689305781, '2023-07-14 03:36:21', '2023-07-30 03:56:42'),
(6, 1, 21, 'hi', NULL, 1, 1689306123, '2023-07-14 03:42:03', '2023-07-30 03:56:42'),
(7, 1, 21, 'hi', NULL, 1, 1689306229, '2023-07-14 03:43:49', '2023-07-30 03:56:42'),
(8, 1, 21, 'hello', NULL, 1, 1689306285, '2023-07-14 03:44:45', '2023-07-30 03:56:42'),
(9, 1, 21, 'hey', NULL, 1, 1689306306, '2023-07-14 03:45:06', '2023-07-30 03:56:42'),
(10, 1, 21, 'hi', NULL, 1, 1689306440, '2023-07-14 03:47:20', '2023-07-30 03:56:42'),
(11, 1, 21, 'hi', NULL, 1, 1689306472, '2023-07-14 03:47:52', '2023-07-30 03:56:42'),
(12, 1, 21, 'hioo', NULL, 1, 1689306477, '2023-07-14 03:47:57', '2023-07-30 03:56:42'),
(13, 1, 21, 'hey', NULL, 1, 1689306482, '2023-07-14 03:48:02', '2023-07-30 03:56:42'),
(14, 1, 21, 'hey', NULL, 1, 1689306496, '2023-07-14 03:48:16', '2023-07-30 03:56:42'),
(15, 1, 21, 'heyl', NULL, 1, 1689306542, '2023-07-14 03:49:02', '2023-07-30 03:56:42'),
(16, 1, 21, 'hello', NULL, 1, 1689306564, '2023-07-14 03:49:24', '2023-07-30 03:56:42'),
(17, 1, 21, 'hey', NULL, 1, 1689306589, '2023-07-14 03:49:49', '2023-07-30 03:56:42'),
(18, 9, 1, 'hi', NULL, 1, 1689306602, '2023-07-14 03:50:02', '2023-07-19 06:54:57'),
(19, 9, 1, 'welcoem', NULL, 1, 1689306608, '2023-07-14 03:50:08', '2023-07-19 06:54:57'),
(20, 21, 1, 'heyllo', NULL, 1, 1689392529, '2023-07-15 03:42:09', '2023-07-19 06:55:02'),
(21, 1, 21, 'heyy', NULL, 1, 1689392544, '2023-07-15 03:42:24', '2023-07-30 03:56:42'),
(22, 1, 21, 'hey', NULL, 1, 1689392898, '2023-07-15 03:48:18', '2023-07-30 03:56:42'),
(23, 1, 9, 'hi', NULL, 0, 1689478114, '2023-07-16 03:28:34', '2023-07-16 03:28:34'),
(24, 1, 9, 'wefdosdfds', NULL, 0, 1689478119, '2023-07-16 03:28:39', '2023-07-16 03:28:39'),
(25, 1, 21, 'hi', NULL, 1, 1689565190, '2023-07-17 03:39:50', '2023-07-30 03:56:42'),
(26, 16, 14, 'Hi', NULL, 1, 1689565691, '2023-07-17 03:48:11', '2023-07-19 06:56:33'),
(27, 14, 16, 'hi', NULL, 1, 1689565764, '2023-07-17 03:49:24', '2023-07-30 04:04:57'),
(28, 14, 1, 'yes', NULL, 1, 1689565787, '2023-07-17 03:49:47', '2023-07-19 06:55:17'),
(29, 1, 14, 'hey ia m test', '20230718024251vt1alr8vzqclg5fhla3x.PNG', 1, 1689648171, '2023-07-18 02:42:51', '2023-07-19 06:56:25'),
(30, 1, 14, 'hey', '20230718024339cklmuaji1kvr14brxp4k.PNG', 1, 1689648219, '2023-07-18 02:43:39', '2023-07-19 06:56:25'),
(31, 14, 1, 'okay i got its submit work', '20230718025017a7db7lupsq0z1tnkurqm.png', 1, 1689648617, '2023-07-18 02:50:17', '2023-07-19 06:55:17'),
(32, 14, 1, 'hi', NULL, 1, 1689648675, '2023-07-18 02:51:15', '2023-07-19 06:55:17'),
(33, 1, 14, '🙂fdsffds😝fdsf\r\n\r\nfdsfds\r\n😉\r\nfdsfds\r\n😌', NULL, 1, 1689909628, '2023-07-21 03:20:28', '2023-07-21 07:35:34'),
(34, 1, 21, '🤪 dfdsfdfds fdsfdsfds', NULL, 1, 1689909705, '2023-07-21 03:21:45', '2023-07-30 03:56:42'),
(35, 1, 21, 'fdsfdsf dfds😄', NULL, 1, 1689909754, '2023-07-21 03:22:34', '2023-07-30 03:56:42'),
(36, 1, 21, 'fdsfds fsdfds', NULL, 1, 1689909757, '2023-07-21 03:22:37', '2023-07-30 03:56:42'),
(37, 1, 21, '😌sd fdsfdsf 🤪 fdsdfd😙 fdsfds', NULL, 1, 1689924549, '2023-07-21 07:29:09', '2023-07-30 03:56:42'),
(38, 1, 21, 'dsf', NULL, 1, 1689924613, '2023-07-21 07:30:13', '2023-07-30 03:56:42'),
(39, 1, 21, '😆dsf', NULL, 1, 1689924625, '2023-07-21 07:30:25', '2023-07-30 03:56:42'),
(40, 1, 21, 'fdsfds', NULL, 1, 1689924665, '2023-07-21 07:31:05', '2023-07-30 03:56:42'),
(41, 1, 21, '😃 hi am tesing working or not 😋', NULL, 1, 1689924681, '2023-07-21 07:31:21', '2023-07-30 03:56:42'),
(42, 1, 14, '🙃 hi working o no', NULL, 1, 1689924742, '2023-07-21 07:32:22', '2023-07-21 07:35:34'),
(43, 1, 9, 'Hi \r\n\r\niam tesonmg 😚', NULL, 0, 1689924776, '2023-07-21 07:32:56', '2023-07-21 07:32:56'),
(44, 1, 9, 'helli am tesing \r\n\r\nfdsfdfdsf', NULL, 0, 1689924848, '2023-07-21 07:34:08', '2023-07-21 07:34:08'),
(45, 14, 1, '😋', NULL, 1, 1689924943, '2023-07-21 07:35:43', '2023-07-21 07:35:56'),
(46, 14, 1, 'hi Alex 😄\r\n\r\nWhat are you doiung', NULL, 1, 1689924982, '2023-07-21 07:36:22', '2023-07-30 03:38:41'),
(47, 1, 9, 'hi', NULL, 0, 1690688375, '2023-07-30 03:39:35', '2023-07-30 03:39:35'),
(48, 1, 16, 'bonjour', NULL, 1, 1728696512, '2024-10-12 01:28:32', '2024-10-12 01:28:49');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:active, 1:inactive',
  `is_delete` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:not, 1: yes',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`, `amount`, `status`, `is_delete`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SS1', 500, 0, 0, 1, '2023-03-28 03:13:46', '2023-06-14 04:27:41'),
(2, 'SS2', 300, 0, 0, 1, '2023-03-29 03:18:21', '2023-06-14 04:27:35'),
(3, 'PART TIME', 150, 0, 0, 1, '2023-03-29 04:08:25', '2023-06-14 04:27:30'),
(4, 'SS2 (ARTS and SCIENCE)', 300, 0, 0, 1, '2023-03-29 04:08:43', '2023-06-14 04:27:24'),
(5, 'SS3', 200, 0, 0, 1, '2023-06-14 04:27:13', '2023-06-14 04:27:13');

-- --------------------------------------------------------

--
-- Table structure for table `class_subject`
--

CREATE TABLE `class_subject` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_subject`
--

INSERT INTO `class_subject` (`id`, `class_id`, `subject_id`, `created_by`, `is_delete`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 1, 0, 0, '2023-03-30 14:17:57', '2023-03-30 14:17:57'),
(2, 1, 4, 1, 0, 0, '2023-03-30 14:17:58', '2023-03-30 14:17:58'),
(3, 1, 6, 1, 0, 0, '2023-03-30 14:17:58', '2023-03-30 14:17:58'),
(4, 1, 2, 1, 0, 0, '2023-03-30 14:17:58', '2023-03-30 14:17:58'),
(5, 4, 6, 1, 0, 0, '2023-03-30 14:18:12', '2023-03-30 14:18:12'),
(6, 4, 2, 1, 0, 0, '2023-03-30 14:18:12', '2023-03-30 14:18:12'),
(17, 3, 7, 1, 0, 0, '2023-03-30 14:43:34', '2023-03-30 14:43:34'),
(18, 3, 4, 1, 0, 0, '2023-03-30 14:43:34', '2023-03-30 14:43:34'),
(19, 3, 6, 1, 0, 0, '2023-03-30 14:43:34', '2023-03-30 14:43:34'),
(20, 3, 1, 1, 0, 0, '2023-03-30 14:43:34', '2023-03-31 03:00:37'),
(21, 3, 2, 1, 0, 0, '2023-04-14 03:50:26', '2023-04-14 03:50:26'),
(22, 3, 5, 1, 0, 0, '2023-04-14 03:50:27', '2023-04-14 03:50:27'),
(23, 2, 1, 1, 0, 0, '2023-04-14 04:00:20', '2023-04-14 04:00:20'),
(24, 2, 3, 1, 0, 0, '2023-04-14 04:00:21', '2023-04-14 04:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `class_subject_timetable`
--

CREATE TABLE `class_subject_timetable` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `week_id` int(11) DEFAULT NULL,
  `start_time` varchar(25) DEFAULT NULL,
  `end_time` varchar(25) DEFAULT NULL,
  `room_number` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_subject_timetable`
--

INSERT INTO `class_subject_timetable` (`id`, `class_id`, `subject_id`, `week_id`, `start_time`, `end_time`, `room_number`, `created_at`, `updated_at`) VALUES
(12, 3, 2, 1, '11:00', '12:00', '1', '2023-04-21 04:51:01', '2023-04-21 04:51:01'),
(13, 3, 2, 2, '11:00', '12:00', '1', '2023-04-21 04:51:01', '2023-04-21 04:51:01'),
(14, 3, 2, 3, '10:00', '11:00', '2', '2023-04-21 04:51:01', '2023-04-21 04:51:01'),
(15, 3, 2, 4, '10:00', '11:00', '3', '2023-04-21 04:51:01', '2023-04-21 04:51:01'),
(16, 3, 2, 5, '09:00', '11:00', '4', '2023-04-21 04:51:01', '2023-04-21 04:51:01'),
(17, 3, 2, 6, '11:00', '12:00', '1', '2023-04-21 04:51:01', '2023-04-21 04:51:01'),
(18, 3, 2, 7, '08:00', '12:00', '1', '2023-04-21 04:51:01', '2023-04-21 04:51:01'),
(19, 1, 2, 1, '09:00', '10:00', '1', '2023-04-22 03:18:28', '2023-04-22 03:18:28'),
(29, 3, 1, 1, '09:00', '10:00', '1', '2023-04-26 13:47:04', '2023-04-26 13:47:04'),
(35, 3, 5, 1, '09:00', '10:00', '1', '2023-07-30 03:46:08', '2023-07-30 03:46:08'),
(36, 3, 5, 2, '21:00', '10:00', '1', '2023-07-30 03:46:08', '2023-07-30 03:46:08'),
(37, 3, 5, 3, '21:00', '10:00', '2', '2023-07-30 03:46:08', '2023-07-30 03:46:08'),
(38, 3, 5, 4, '09:00', '10:00', '2', '2023-07-30 03:46:08', '2023-07-30 03:46:08'),
(39, 3, 5, 7, '09:00', '11:00', '5', '2023-07-30 03:46:08', '2023-07-30 03:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `note` varchar(2000) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`id`, `name`, `note`, `created_by`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'FIRST TERM 2022/2023', '', 1, 0, '2023-04-29 09:33:24', '2023-04-29 09:44:44'),
(2, 'FIRST TERM 2023/2024', '', 1, 0, '2023-04-29 09:33:44', '2023-04-29 09:44:58'),
(3, 'FIRST TERM 2024/2025', '', 1, 0, '2023-04-29 09:45:06', '2023-04-29 09:45:06'),
(4, 'FIRST TERM 2024/2026', '', 1, 0, '2023-05-13 03:19:10', '2023-05-13 03:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `exam_schedule`
--

CREATE TABLE `exam_schedule` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `start_time` varchar(25) DEFAULT NULL,
  `end_time` varchar(25) DEFAULT NULL,
  `room_number` varchar(25) DEFAULT NULL,
  `full_marks` varchar(25) DEFAULT NULL,
  `passing_mark` varchar(25) DEFAULT NULL,
  `ponde` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_schedule`
--

INSERT INTO `exam_schedule` (`id`, `exam_id`, `class_id`, `subject_id`, `exam_date`, `start_time`, `end_time`, `room_number`, `full_marks`, `passing_mark`, `ponde`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 5, '2024-10-02', '01:11', '01:11', '1', '20', '10', '2', 1, '2024-10-09 23:12:46', '2024-10-09 23:12:46'),
(2, 1, 3, 2, '2024-10-03', '00:11', '01:11', '1', '20', '10', '3', 1, '2024-10-09 23:12:46', '2024-10-09 23:12:46'),
(3, 1, 3, 1, '2024-10-03', '00:11', '01:11', '1', '20', '10', '2', 1, '2024-10-09 23:12:46', '2024-10-09 23:12:46'),
(4, 1, 3, 6, '2024-10-09', '00:11', '02:11', '1', '20', '10', '4', 1, '2024-10-09 23:12:46', '2024-10-09 23:12:46'),
(5, 1, 3, 4, '2024-10-03', '00:11', '02:11', '1', '20', '10', '5', 1, '2024-10-09 23:12:46', '2024-10-09 23:12:46'),
(6, 1, 3, 7, '2024-10-03', '01:11', '02:11', '1', '20', '10', '2', 1, '2024-10-09 23:12:46', '2024-10-09 23:12:46'),
(7, 1, 2, 1, '2024-10-01', '13:29', '13:29', '1', '20', '10', '2', 1, '2024-10-10 11:29:37', '2024-10-10 11:29:37');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `homework`
--

CREATE TABLE `homework` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `homework_date` date DEFAULT NULL,
  `submission_date` date DEFAULT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_delete` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homework`
--

INSERT INTO `homework` (`id`, `class_id`, `subject_id`, `homework_date`, `submission_date`, `document_file`, `description`, `is_delete`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 3, 5, '2023-06-12', '2023-06-22', '20230605044813yrc91gr875diohromzzn.pdf', '<p>Hi</p><p>I need to understand how to work home work<br></p>', 1, 1, '2023-06-05 04:48:13', '2023-06-07 03:59:36'),
(2, 1, 2, '2023-06-06', '2023-06-07', '20230606035119wdtqafgonuyzv1wowrlf.pdf', '<p>Hi i just testing<br></p>', 0, 1, '2023-06-06 03:51:00', '2023-06-06 03:51:19'),
(3, 3, 2, '2023-06-07', '2023-06-07', '202306070353506icz1pmi5xi1vrajafyh.pdf', '<p>hello<br></p>', 0, 21, '2023-06-07 03:53:50', '2023-06-07 04:02:26'),
(4, 4, 6, '2023-06-07', '2023-06-07', NULL, '<p>dsffsdf fsdfdsf<br></p>', 0, 21, '2023-06-07 03:57:47', '2023-06-07 04:03:50'),
(5, 3, 5, '2023-06-09', '2023-06-10', '20230608032534xfsb8vps29vv36rg9fqk.jpg', '<p>Hi</p><p>please complete the assignment<br></p>', 0, 1, '2023-06-08 03:25:07', '2023-06-08 03:25:34'),
(6, 3, 5, '2023-06-12', '2023-06-13', '2023061204512843qxgri6l6ehbyz45bbp.jpg', '<p>Pls check document<br></p>', 0, 1, '2023-06-12 04:51:28', '2023-06-12 04:51:28');

-- --------------------------------------------------------

--
-- Table structure for table `homework_submit`
--

CREATE TABLE `homework_submit` (
  `id` int(11) NOT NULL,
  `homework_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homework_submit`
--

INSERT INTO `homework_submit` (`id`, `homework_id`, `student_id`, `description`, `document_file`, `created_at`, `updated_at`) VALUES
(1, 5, 14, '<p>I have attached my home work<br></p>', '20230610041631envwnzvaoduofiywpzzn.jpg', '2023-06-10 04:16:31', '2023-06-10 04:16:31'),
(2, 3, 14, '<p>I have attached </p><p>Thank you :)<br></p>', '202306100416584fpbqjyl29s6om9p6voe.jpg', '2023-06-10 04:16:58', '2023-06-10 04:16:58'),
(3, 2, 13, '<p>I have attached my homework<br></p>', '20230613031035mrgcvqi20fjq6fue858h.jpg', '2023-06-13 03:10:35', '2023-06-13 03:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `marks_grade`
--

CREATE TABLE `marks_grade` (
  `id` int(11) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `percent_from` int(11) NOT NULL DEFAULT 0,
  `percent_to` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks_grade`
--

INSERT INTO `marks_grade` (`id`, `name`, `percent_from`, `percent_to`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'A', 90, 100, 1, '2023-05-16 03:33:48', '2023-05-16 03:41:30'),
(2, 'B', 80, 89, 1, '2023-05-16 03:41:39', '2023-05-16 03:41:39'),
(3, 'C', 70, 79, 1, '2023-05-16 03:41:47', '2023-05-16 03:41:47'),
(4, 'D', 60, 69, 1, '2023-05-16 03:41:56', '2023-05-16 03:41:56'),
(5, 'E', 50, 59, 1, '2023-05-16 03:42:04', '2023-05-16 03:42:04'),
(7, 'F', 0, 58, 1, '2023-05-16 03:43:38', '2023-05-16 03:43:38');

-- --------------------------------------------------------

--
-- Table structure for table `marks_register`
--

CREATE TABLE `marks_register` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `class_work` int(11) NOT NULL DEFAULT 0,
  `home_work` int(11) NOT NULL DEFAULT 0,
  `test_work` int(11) NOT NULL DEFAULT 0,
  `exam` int(11) NOT NULL DEFAULT 0,
  `full_marks` int(11) NOT NULL DEFAULT 0,
  `passing_mark` int(11) DEFAULT 0,
  `ponde` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks_register`
--

INSERT INTO `marks_register` (`id`, `student_id`, `exam_id`, `class_id`, `subject_id`, `class_work`, `home_work`, `test_work`, `exam`, `full_marks`, `passing_mark`, `ponde`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 14, 1, 3, 5, 10, 0, 0, 0, 20, 10, 2, 1, '2024-10-09 23:26:55', '2024-10-09 23:59:45'),
(2, 14, 1, 3, 2, 10, 0, 0, 0, 20, 10, 3, 1, '2024-10-09 23:26:55', '2024-10-10 00:04:15'),
(3, 14, 1, 3, 1, 12, 0, 0, 0, 20, 10, 2, 1, '2024-10-09 23:26:55', '2024-10-10 00:57:14'),
(4, 14, 1, 3, 6, 13, 0, 0, 0, 20, 10, 4, 1, '2024-10-09 23:26:55', '2024-10-10 00:24:18'),
(5, 14, 1, 3, 4, 10, 0, 0, 0, 20, 10, 5, 1, '2024-10-09 23:26:55', '2024-10-10 00:57:14'),
(6, 14, 1, 3, 7, 15, 0, 0, 0, 20, 10, 2, 1, '2024-10-09 23:26:55', '2024-10-10 00:24:18');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2014_04_02_193005_create_translations_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notice_board`
--

CREATE TABLE `notice_board` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `notice_date` date DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice_board`
--

INSERT INTO `notice_board` (`id`, `title`, `notice_date`, `publish_date`, `message`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Issue about Group1', '2023-05-27', '2023-05-29', '<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain\r\n                        was born and I will give you a complete account of the system, and expound the actual teachings\r\n                        of the great explorer of the truth, the master-builder of human happiness. No one rejects,\r\n                        dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know\r\n                        how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again\r\n                        is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain,\r\n                        but because occasionally circumstances occur in which toil and pain can procure him some great\r\n                        pleasure. </p><p><br></p><p>To take a trivial example, which of us ever undertakes laborious physical exercise,\r\n                        except to obtain some advantage from it? But who has any right to find fault with a man who\r\n                        chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that\r\n                        produces no resultant pleasure? On the other hand, we denounce with righteous indignation and\r\n                        dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so\r\n                        blinded by desire, that they cannot foresee</p>', 1, '2023-05-27 03:42:32', '2023-05-29 03:32:25'),
(3, 'welcome', '2023-05-27', '2023-05-30', '<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain\r\n                        was born and I will give you a complete account of the system, and expound the actual teachings\r\n                        of the great explorer of the truth, the master-builder of human happiness. No one rejects,\r\n                        dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know\r\n                        how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again\r\n                        is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain,\r\n                        but because occasionally circumstances occur in which toil and pain can procure him some great\r\n                        pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise,\r\n                        except to obtain some advantage from it? But who has any right to find fault with a man who\r\n                        chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that\r\n                        produces no resultant pleasure? On the other hand, we denounce with righteous indignation and\r\n                        dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so\r\n                        blinded by desire, that they cannot foresee</p>', 1, '2023-05-27 03:52:11', '2023-05-27 03:52:11'),
(4, 'Welcome Aha 1', '2023-05-26', '2023-05-28', '<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain\r\n                        was born and I will give you a complete account of the system, and expound the actual teachings\r\n                        of the great explorer of the truth, the master-builder of human happiness. No one rejects,\r\n                        dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know\r\n                        how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again\r\n                        is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain,\r\n                        but because occasionally circumstances occur in which toil and pain can procure him some great\r\n                        pleasure. </p><p>To take a trivial example, which of us ever undertakes laborious physical exercise,\r\n                        except to obtain some advantage from it? But who has any right to find fault with a man who\r\n                        chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that\r\n                        produces no resultant pleasure? </p><p>On the other hand, we denounce with righteous indignation and\r\n                        dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so\r\n                        blinded by desire, that they cannot foresee</p>', 1, '2023-05-27 03:57:14', '2023-05-29 03:31:39');

-- --------------------------------------------------------

--
-- Table structure for table `notice_board_message`
--

CREATE TABLE `notice_board_message` (
  `id` int(11) NOT NULL,
  `notice_board_id` int(11) DEFAULT NULL,
  `message_to` int(11) DEFAULT NULL COMMENT 'user type',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice_board_message`
--

INSERT INTO `notice_board_message` (`id`, `notice_board_id`, `message_to`, `created_at`, `updated_at`) VALUES
(1, 3, 3, '2023-05-27 03:52:11', '2023-05-27 03:52:11'),
(2, 3, 4, '2023-05-27 03:52:11', '2023-05-27 03:52:11'),
(22, 4, 3, '2023-05-29 03:31:39', '2023-05-29 03:31:39'),
(23, 4, 4, '2023-05-29 03:31:39', '2023-05-29 03:31:39'),
(24, 4, 2, '2023-05-29 03:31:39', '2023-05-29 03:31:39'),
(27, 2, 3, '2023-05-29 03:32:25', '2023-05-29 03:32:25'),
(28, 2, 2, '2023-05-29 03:32:25', '2023-05-29 03:32:25');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recours`
--

CREATE TABLE `recours` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `objet` varchar(255) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `session_year` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recours`
--

INSERT INTO `recours` (`id`, `class_id`, `student_id`, `subject_id`, `objet`, `numero`, `session_year`, `created_at`, `updated_at`) VALUES
(1, 3, 14, 5, 'non-transmission', 1, 'October 2024', '2024-10-15 00:31:07', '2024-10-15 00:31:07'),
(2, 3, 14, 2, 'id-confuse', 2, 'October 2024', '2024-10-15 00:35:21', '2024-10-15 00:35:21'),
(3, 2, 4, 1, 'omission-nom', 3, 'October 2024', '2024-10-15 01:51:46', '2024-10-15 01:51:46'),
(4, 3, 14, 7, 'non-transmission', 4, 'October 2024', '2024-10-15 22:00:58', '2024-10-15 22:00:58'),
(5, 3, 14, 7, 'transcription-erronne', 5, 'October 2024', '2024-10-15 22:02:10', '2024-10-15 22:02:10');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `exam_description` text DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `stripe_key` varchar(500) DEFAULT NULL,
  `stripe_secret` varchar(500) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `fevicon_icon` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `school_name`, `exam_description`, `paypal_email`, `stripe_key`, `stripe_secret`, `logo`, `fevicon_icon`, `created_at`, `updated_at`) VALUES
(1, 'SCHOOL MODEL <br /> INTERNATIONAL SCHOOL', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'test@test.com', '', '', '20241010011316lbar9mbdx8.jpg', '20241010011343eyuxhrsmos.jpg', '2023-06-21 05:07:48', '2024-10-10 01:13:43');

-- --------------------------------------------------------

--
-- Table structure for table `student_add_fees`
--

CREATE TABLE `student_add_fees` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `total_amount` int(11) DEFAULT 0,
  `paid_amount` int(11) DEFAULT 0,
  `remaning_amount` int(11) DEFAULT 0,
  `payment_type` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `is_payment` tinyint(4) NOT NULL DEFAULT 0,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `payment_data` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_add_fees`
--

INSERT INTO `student_add_fees` (`id`, `student_id`, `class_id`, `total_amount`, `paid_amount`, `remaning_amount`, `payment_type`, `remark`, `is_payment`, `stripe_session_id`, `payment_data`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 500, 100, 400, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-17 03:45:38', '2023-06-17 03:45:38'),
(2, 10, 1, 400, 100, 300, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-17 03:45:51', '2023-06-17 03:45:51'),
(3, 10, 1, 300, 200, 100, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-17 03:46:04', '2023-06-17 03:46:04'),
(4, 10, 1, 100, 100, 0, 'Cheque', NULL, 1, NULL, NULL, 1, '2023-06-17 03:46:18', '2023-06-17 03:46:18'),
(5, 10, 1, 0, 0, 0, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-17 03:46:30', '2023-06-17 03:46:30'),
(6, 11, 1, 500, 200, 300, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-17 03:47:58', '2023-06-17 03:47:58'),
(7, 11, 1, 300, 300, 0, 'Cheque', NULL, 1, NULL, NULL, 1, '2023-06-17 03:48:05', '2023-06-17 03:48:05'),
(8, 13, 1, 500, 150, 350, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-17 03:52:52', '2023-06-17 03:52:52'),
(9, 13, 1, 350, 100, 250, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-17 03:53:08', '2023-06-17 03:53:08'),
(10, 13, 1, 250, 250, 0, 'Cash', 'welccome', 1, NULL, NULL, 1, '2023-06-17 03:53:37', '2023-06-17 03:53:37'),
(11, 14, 3, 150, 20, 130, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-17 03:54:22', '2023-06-17 03:54:22'),
(12, 14, 3, 130, 50, 80, 'Cash', NULL, 1, NULL, NULL, 1, '2023-06-20 04:05:40', '2023-06-20 04:05:40'),
(18, 14, 3, 80, 20, 60, 'Paypal', 'test', 0, NULL, NULL, 14, '2023-06-22 03:29:42', '2023-06-22 03:29:42'),
(19, 14, 3, 80, 23, 57, 'Paypal', NULL, 0, NULL, NULL, 14, '2023-06-22 03:31:32', '2023-06-22 03:31:32'),
(20, 14, 3, 80, 20, 60, 'Paypal', NULL, 0, NULL, NULL, 14, '2023-06-22 03:34:00', '2023-06-22 03:34:00'),
(21, 14, 3, 80, 20, 60, 'Paypal', NULL, 0, NULL, NULL, 14, '2023-06-22 03:39:51', '2023-06-22 03:39:51'),
(24, 14, 3, 80, 20, 60, 'Stripe', NULL, 0, NULL, NULL, 14, '2023-06-23 03:49:56', '2023-06-23 03:49:56'),
(25, 14, 3, 80, 20, 60, 'Stripe', NULL, 0, 'cs_test_a1rt4besAIxCiSOfQb9TRmJiiZqG1JvJKvUW4i9iad5NVvFfkYuMw5gn9C', NULL, 14, '2023-06-23 03:50:48', '2023-06-23 03:50:49'),
(30, 14, 3, 80, 10, 70, 'Paypal', 'welcome', 0, NULL, NULL, 16, '2023-06-26 04:30:42', '2023-06-26 04:30:42'),
(31, 14, 3, 80, 10, 70, 'Paypal', 'wlecome to tet', 1, NULL, '{\"PayerID\":\"8SBPAFGGPK66J\",\"st\":\"Completed\",\"tx\":\"3VB341245G206521U\",\"cc\":\"USD\",\"amt\":\"10.00\",\"payer_email\":\"hardikuser111@gmail.com\",\"payer_id\":\"8SBPAFGGPK66J\",\"payer_status\":\"VERIFIED\",\"first_name\":\"hardik\",\"last_name\":\"RAMESHBHAI\",\"txn_id\":\"3VB341245G206521U\",\"mc_currency\":\"USD\",\"mc_fee\":\"0.81\",\"mc_gross\":\"10.00\",\"protection_eligibility\":\"ELIGIBLE\",\"payment_fee\":\"0.81\",\"payment_gross\":\"10.00\",\"payment_status\":\"Completed\",\"payment_type\":\"instant\",\"handling_amount\":\"0.00\",\"shipping\":\"0.00\",\"item_name\":\"Student Fees\",\"item_number\":\"31\",\"quantity\":\"1\",\"txn_type\":\"web_accept\",\"payment_date\":\"2023-06-26T04:31:31Z\",\"receiver_id\":\"7D6XEZUUQVDGG\",\"notify_version\":\"UNVERSIONED\",\"verify_sign\":\"AgONxoMs0eFLQjVu8DAdQCrJCJC6A8.XMTWEReCx5UE19WH-EO2qYZ8M\"}', 16, '2023-06-30 00:00:00', '2023-06-26 04:32:21'),
(32, 14, 3, 70, 20, 50, 'Stripe', NULL, 1, 'cs_test_a1Z5uSS04RVkkjmdJlz2hHochexvkiqYFo5BfPbgr0oGm9uCu5stSc3NV3', NULL, 16, '2023-06-30 04:37:27', '2023-06-26 04:37:33'),
(33, 14, 3, 50, 20, 30, 'Cash', NULL, 1, NULL, NULL, 1, '2023-07-01 06:14:05', '2023-07-01 06:14:05'),
(34, 14, 3, 30, 30, 0, 'Cash', NULL, 1, NULL, NULL, 1, '2023-07-01 06:14:26', '2023-07-01 06:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `attendance_type` int(11) DEFAULT NULL COMMENT '1=Present, 2=Late, 3=Absent, 4=Half Day',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_attendance`
--

INSERT INTO `student_attendance` (`id`, `class_id`, `attendance_date`, `student_id`, `attendance_type`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-05-19', 13, 1, 1, '2023-05-19 02:56:37', '2023-05-19 03:03:08'),
(2, 1, '2023-05-19', 11, 3, 1, '2023-05-19 02:56:58', '2023-05-19 02:57:15'),
(3, 1, '2023-05-19', 10, 2, 1, '2023-05-19 02:57:07', '2023-05-19 02:57:09'),
(4, 1, '2023-05-20', 13, 1, 1, '2023-05-19 03:02:39', '2023-05-19 03:02:39'),
(5, 1, '2023-05-20', 11, 3, 1, '2023-05-19 03:02:40', '2023-05-19 03:02:47'),
(6, 1, '2023-05-20', 10, 1, 1, '2023-05-19 03:02:41', '2023-05-19 03:02:41'),
(7, 3, '2023-05-19', 14, 1, 1, '2023-05-19 03:03:40', '2023-05-19 03:03:40'),
(8, 3, '2023-05-20', 14, 1, 1, '2023-05-20 03:10:27', '2023-05-20 03:10:27'),
(9, 3, '2023-05-22', 14, 4, 1, '2023-05-22 03:18:57', '2023-05-22 03:19:01'),
(10, 3, '2023-05-23', 14, 2, 21, '2023-05-22 03:32:48', '2023-05-22 03:32:48'),
(11, 3, '2023-05-24', 14, 1, 21, '2023-05-22 03:33:30', '2023-05-22 03:33:39'),
(12, 3, '2023-07-30', 14, 3, 1, '2023-07-30 03:51:44', '2023-07-30 03:59:22'),
(13, 3, '2024-10-17', 14, 1, 20, '2024-10-10 14:04:19', '2024-10-10 14:14:26'),
(14, 3, '2024-10-10', 14, 3, 1, '2024-10-10 14:18:07', '2024-10-10 14:19:31');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:active, 1:inactive',
  `is_delete` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:not, 1:yes',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `name`, `type`, `created_by`, `status`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'MATHEMATICS', 'Practical', 1, 0, 0, '2023-03-30 03:17:19', '2023-03-30 03:55:57'),
(2, 'ENGLISH LANGUAGE', 'Theory', 1, 0, 0, '2023-03-30 03:18:07', '2023-03-30 03:56:03'),
(3, 'SOCIAL STUDIES', 'Theory', 1, 0, 0, '2023-03-30 03:28:25', '2023-03-30 03:56:09'),
(4, 'BASIC SCIENCE AND TECHNOLOGY', 'Theory', 1, 0, 0, '2023-03-30 03:56:19', '2023-03-30 03:56:19'),
(5, 'HOME ECONOMICS', 'Theory', 1, 0, 0, '2023-03-30 03:56:29', '2023-03-30 03:56:29'),
(6, 'BASIC TECHNOLOGY', 'Theory', 1, 0, 0, '2023-03-30 03:56:37', '2023-03-30 03:56:37'),
(7, 'AGRIC SCIENCE', 'Theory', 1, 0, 0, '2023-03-30 03:56:44', '2023-03-30 03:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `admission_number` varchar(50) DEFAULT NULL,
  `roll_number` varchar(50) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `caste` varchar(50) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `profile_pic` varchar(100) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `qualification` varchar(1000) DEFAULT NULL,
  `work_experience` varchar(1000) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT 3 COMMENT '1:admin, 2:teacher, 3: student, 4: parent',
  `is_delete` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:not deleted, 1 : deleted',
  `status` tinyint(4) DEFAULT 0 COMMENT '0:active, 1:inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `parent_id`, `name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `admission_number`, `roll_number`, `class_id`, `gender`, `date_of_birth`, `caste`, `religion`, `mobile_number`, `admission_date`, `profile_pic`, `blood_group`, `height`, `weight`, `occupation`, `marital_status`, `address`, `permanent_address`, `qualification`, `work_experience`, `note`, `user_type`, `is_delete`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Admin', NULL, 'admin@gmail.com', NULL, '$2y$10$NVyTxYXIPnXeYi4GckzmPeHwipDph/OQArrAF.HbeVnmosG0m1yV.', 'dI0NH3FIt1mjWO9h5MaZ5m11N8lpbMmqzviRXvWrchsBJsCCLpoGkb0QtYq6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20230707030514ogeefhmwghlna3rown0n.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, '2023-03-23 02:46:09', '2024-10-15 22:55:43'),
(3, NULL, 'Teacher', NULL, 'teacher@gmail.com', NULL, '$2y$10$hbU2a9ziRFjQyomwypBbKuSlGoQYKWihaZikcOy838wDPJvMo6iim', NULL, NULL, NULL, NULL, 'Male', '2011-01-01', NULL, NULL, '1234567891', '2020-01-01', NULL, NULL, NULL, NULL, NULL, 'test', 'test', 'test', 'test', 'test', 'test', 2, 0, 0, '2023-03-23 02:46:09', '2024-10-15 22:53:55'),
(4, NULL, 'Student', NULL, 'student@gmail.com', NULL, '$2y$10$XBPChms8IYS8RenF9.CpH.Si2uJ9hfrt0suyFfA4hgYdEliDHml1e', 'hrtqEwPld3BD6PsFGQ9lagwIoI19PPUUgZIXLzr7yaiy4q7FqIoPPiPmKJRK', NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 0, '2023-03-23 02:46:09', '2024-10-15 01:04:57'),
(5, NULL, 'Parent', NULL, 'parent@gmail.com', NULL, '$2y$10$hbU2a9ziRFjQyomwypBbKuSlGoQYKWihaZikcOy838wDPJvMo6iim', NULL, NULL, NULL, NULL, 'Female', NULL, NULL, NULL, '123456781', NULL, '20230419110559ibwfsbvq8usfuhfzd2y7.jpg', NULL, NULL, NULL, '', NULL, 'test', NULL, NULL, NULL, NULL, 4, 0, 0, '2023-03-23 02:46:09', '2024-10-11 22:04:25'),
(6, NULL, 'Lareina Colon', NULL, 'rybywa@mailinator.com', NULL, '$2y$10$DQLqTDb5EGpl9SPkUuklMObx0wjyAO3i5e1ADoYBlDuaxB8J5aTkO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, '2023-03-26 21:39:46', '2023-03-26 21:56:15'),
(7, NULL, 'Admin', NULL, 'rihewiz1@mailinator.com', NULL, '$2y$10$KYhgcB/QbiWeFMV5X4Wb3.9.IHcDuHm/9lwH4jQeaRVZGSE7JT.vK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, '2023-03-26 21:40:47', '2023-03-27 22:40:01'),
(8, NULL, 'Admin', NULL, 'admin12@gmail.com', NULL, '$2y$10$.2lyJswIb9TgFu5uLQ33aeFHrzw3aXpjGSQk/oMkTZ3M8PemfOFYO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, '2023-03-27 21:55:20', '2023-03-27 22:40:06'),
(9, NULL, 'TESTADMIN', NULL, 'TESTADMIN@TESTADMIN.com', NULL, '$2y$10$CU3GqrNkSuoA8cBNqKt0D..xDf3tnCQtqn1EBhbEvTsy4pBTVPS76', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20230707030157uxht2yrfjn65ybzhvhw7.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, '2023-03-27 22:40:17', '2023-07-06 21:31:57'),
(10, NULL, 'Student', '4', 'student4@test.com', NULL, '$2y$10$tOjOl9JIZfu4UjhOWuvP0eDfETLF.QnL86b1DgcUWxzRjZGkrgAgO', NULL, '913', '989', 1, 'Other', '1992-12-23', 'test', 'test', '1234567891', '1990-08-26', NULL, 'test', '10', '20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 1, '2023-04-01 00:09:19', '2024-10-11 14:45:41'),
(11, 16, 'Student', '3', 'student3@test.com', NULL, '$2y$10$Wl3VwvyrS7w7MkEQOpNgb.hh0uUuruX.BRpVusK9dRZY9DZ40vNfK', NULL, '92', '7', 1, 'Other', '1994-12-26', 'test', 'test', '1234567891', '1991-07-23', '20230419111044tbrd3jc0bvb9ohv8omrp.jpg', 'test', '20', '10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 0, '2023-04-01 00:16:32', '2024-10-13 00:26:22'),
(12, NULL, 'Keith Estrada', 'Kirkland', 'lapefuta@mailinator.com', NULL, '$2y$10$uRoHZlTThVDrL39mo2qFNO2hJDcAPdtk/ekDy.DiNil4ieTudYz6C', NULL, '105', '667', 2, 'Male', '2011-06-26', 'Aspe', 'Itaque', '695', '2005-09-21', NULL, 'o+', '20', '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 0, '2023-04-01 00:17:19', '2023-04-03 22:24:31'),
(13, 16, 'Student', '2', 'student2@test.com', NULL, '$2y$10$MZ4OEh1nGYI790UgyyC.IOetfn1taOXc1gUuBwunQz3ae1gz/7S9m', NULL, '325', '205', 1, 'Female', '2016-01-13', 'test', 'test', '1234567891', '2014-02-19', '20230419111054eg7whtor32uhwmsu2nsb.jpg', 'test', '20', '30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 0, '2023-04-01 00:17:51', '2024-10-14 12:52:15'),
(14, 16, 'Student', '1', 'student1@test.com', NULL, '$2y$10$53t5Ln9N66IGr8joTVJ01.1WvoyOFUre2765pvJgr25E4knbt0QQK', NULL, '194', '148', 3, 'Male', '2018-11-28', 'test', 'test', '1234567891', '2019-10-05', '20230725030959rub7cojcr9pk31mwa1sx.jpg', 'test', '10', '20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 0, 0, '2023-04-02 21:49:59', '2024-10-15 21:02:12'),
(15, NULL, 'Parent', '2', 'parent1@test.com', NULL, '$2y$10$Ooge1rKExWL4HH.X4lL1QuPF0CXCKH3k2htJpL89pmK0034yb26DK', NULL, NULL, NULL, NULL, 'Female', NULL, NULL, NULL, '123456781', NULL, '20230419110552qzao5latzryoo8aba5is.jpg', NULL, NULL, NULL, 'test', NULL, 'test', NULL, NULL, NULL, NULL, 4, 0, 0, '2023-04-05 06:14:50', '2023-04-19 05:37:07'),
(16, NULL, 'Parent', '1', 'parent2@test.com', NULL, '$2y$10$pzzP3a61ji8bwa2GeZ/ZGuFoBTkfnahPfprDGJlPcKT7mDUOeOMbC', NULL, NULL, NULL, NULL, 'Other', NULL, NULL, NULL, '123456789', NULL, '20230419110546x4w1au94wpih5r7bbmcz.jpg', NULL, NULL, NULL, 'test', NULL, 'test', NULL, NULL, NULL, NULL, 4, 0, 0, '2023-04-05 06:26:00', '2024-10-12 00:42:01'),
(17, NULL, 'Teacher', '1', 'teacher1@test.com', NULL, '$2y$10$DCqGG0ATuQotHtDPvBwsJeBaPJ1zDfbldleM2ungANgKXWFvYPRcG', NULL, NULL, NULL, NULL, 'Male', '2011-06-01', NULL, NULL, '1234567891', '1982-09-29', NULL, NULL, NULL, NULL, NULL, 'test', 'test', 'test', 'test', 'test', 'test', 2, 0, 0, '2023-04-07 22:57:43', '2024-10-11 23:41:01'),
(18, NULL, 'Jessica Daugherty', 'Schwartz', 'wyhuzufis@mailinator.com', NULL, '$2y$10$mIgx.VTdB4jn5gV.DfAxlOLM1Xz187wYKbGfTfnzdXWkjJZ4TZG9u', NULL, NULL, NULL, NULL, 'Other', '2012-03-21', NULL, NULL, '39123564541', '2016-11-15', NULL, NULL, NULL, NULL, NULL, 'Cillum qui quibusdam', 'Qui eaque aut suscip', 'Perferendis in cumqu', 'Qui pariatur Ipsa', 'Sed laborum rerum ni', 'Qui iste est possimu', 2, 1, 0, '2023-04-07 23:00:44', '2023-04-07 23:10:22'),
(19, NULL, 'Teacher', '2', 'teacher2@test.com', NULL, '$2y$10$WyUgrSPkf02A4Z8JooMF4eGp7Az5Vs5Z/GV3gOkO81E4K4nFHzOGO', NULL, NULL, NULL, NULL, 'Male', '1995-01-25', NULL, NULL, '1234567891', '2022-10-19', '20230419111323ymdgkcaba6gb0thgbocn.jpg', NULL, NULL, NULL, NULL, 'test', 'test', 'test', 'test', 'test', 'test', 2, 0, 0, '2023-04-07 23:01:05', '2023-04-19 05:43:23'),
(20, NULL, 'Teacher', '3', 'teacher3@test.com', NULL, '$2y$10$eixA.28g9ZtFdVG.ntuTg.Pslh1fwq.X/wBzXdxOqpiFI9mcow2em', NULL, NULL, NULL, NULL, 'Female', '1989-09-24', NULL, NULL, '1234567891', '1977-04-25', '20230419111314cmv3cgl33htyk0xh7o1w.jpg', NULL, NULL, NULL, NULL, 'test', 'test', 'test', 'test', 'test', 'test', 2, 0, 1, '2023-04-07 23:11:23', '2024-10-10 13:15:26'),
(21, NULL, 'Teacher', '4', 'teacher4@test.com', NULL, '$2y$10$X0kHfYx82wNRKhQbB5I8BO1UpRKTuDm.bjjqZ2WDjZgxcQvCcVbMy', NULL, NULL, NULL, NULL, 'Other', '1982-11-24', NULL, NULL, '1234567891', '1990-05-21', '20230419111424ua6pgzh2mrqw6egmereq.jpg', NULL, NULL, NULL, NULL, 'test', 'test', 'test', 'test', 'test', 'test', 2, 0, 0, '2023-04-07 23:51:00', '2023-07-29 22:30:05'),
(22, NULL, 'teacher', '6', 'teacher8@test.com', NULL, '$2y$10$IIY8pKVsLRUGclC9y5bYY.UKMCNnGqZBQXVRq2zlRZWlM2Qgo.0rq', NULL, NULL, NULL, NULL, 'Male', '1993-12-10', NULL, NULL, '1234567891', '2023-02-20', '20230419112612otvwo3wchrquohsfzuyc.jpg', NULL, NULL, NULL, NULL, 'test', 'test', 'test', 'test', 'test', 'test', 2, 1, 0, '2023-04-19 05:56:12', '2023-04-19 06:01:16');

-- --------------------------------------------------------

--
-- Table structure for table `week`
--

CREATE TABLE `week` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `fullcalendar_day` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `week`
--

INSERT INTO `week` (`id`, `name`, `fullcalendar_day`, `created_at`, `updated_at`) VALUES
(1, 'Monday', 1, NULL, NULL),
(2, 'Tuesday', 2, NULL, NULL),
(3, 'Wednesday', 3, NULL, NULL),
(4, 'Thursday', 4, NULL, NULL),
(5, 'Friday', 5, NULL, NULL),
(6, 'Saturday', 6, NULL, NULL),
(7, 'Sunday', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_class_teacher`
--
ALTER TABLE `assign_class_teacher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_subject`
--
ALTER TABLE `class_subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_subject_timetable`
--
ALTER TABLE `class_subject_timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_schedule`
--
ALTER TABLE `exam_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homework_submit`
--
ALTER TABLE `homework_submit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marks_grade`
--
ALTER TABLE `marks_grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marks_register`
--
ALTER TABLE `marks_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_board`
--
ALTER TABLE `notice_board`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_board_message`
--
ALTER TABLE `notice_board_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `recours`
--
ALTER TABLE `recours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_add_fees`
--
ALTER TABLE `student_add_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `week`
--
ALTER TABLE `week`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_class_teacher`
--
ALTER TABLE `assign_class_teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `class_subject`
--
ALTER TABLE `class_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `class_subject_timetable`
--
ALTER TABLE `class_subject_timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `exam_schedule`
--
ALTER TABLE `exam_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `homework`
--
ALTER TABLE `homework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `homework_submit`
--
ALTER TABLE `homework_submit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `marks_grade`
--
ALTER TABLE `marks_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `marks_register`
--
ALTER TABLE `marks_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notice_board`
--
ALTER TABLE `notice_board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notice_board_message`
--
ALTER TABLE `notice_board_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recours`
--
ALTER TABLE `recours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_add_fees`
--
ALTER TABLE `student_add_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `week`
--
ALTER TABLE `week`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
