-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 02 يونيو 2025 الساعة 00:41
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_system`
--

-- --------------------------------------------------------

--
-- بنية الجدول `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 4, 37, 'gcg', '2025-05-15 14:09:44'),
(2, 3, 37, 'hhh', '2025-05-15 14:09:51'),
(3, 3, 37, 'jjh', '2025-05-15 14:09:57'),
(4, 4, 37, 'fhgf', '2025-05-15 14:10:03'),
(5, 6, 37, 'gcg', '2025-05-15 14:30:21'),
(6, 7, 37, 'hhh', '2025-05-15 14:32:17'),
(8, 9, 37, 'hhh', '2025-05-15 16:55:10'),
(9, 9, 37, 'hhh', '2025-05-15 17:13:33'),
(10, 7, 37, 'hhh', '2025-05-15 17:24:07'),
(11, 9, 39, 'Ok', '2025-05-15 18:48:53'),
(12, 10, 39, 'Hello', '2025-05-15 18:49:15'),
(13, 9, 37, 'hhh', '2025-05-15 20:16:07'),
(14, 10, 37, 'dwcqc', '2025-05-16 15:37:27'),
(15, 11, 37, 'dwcqc', '2025-05-17 18:04:36');

-- --------------------------------------------------------

--
-- بنية الجدول `comment_replies`
--

CREATE TABLE `comment_replies` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`) VALUES
(6, 0, 37),
(21, 6, 39),
(23, 11, 37);

-- --------------------------------------------------------

--
-- بنية الجدول `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `message_type` enum('text','image','file','audio','sticker') NOT NULL,
  `message` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `audio_path` varchar(255) DEFAULT NULL,
  `sticker` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `messages`
--

INSERT INTO `messages` (`id`, `room_id`, `sender`, `message_type`, `message`, `file_path`, `image_path`, `audio_path`, `sticker`, `timestamp`) VALUES
(1, 2, 8, 'text', 'ytdjcjy', NULL, 'uploads/1747116705_ui.png', NULL, '', '2025-05-13 06:11:45'),
(2, 2, 8, 'text', 'thrdhd', NULL, NULL, NULL, '', '2025-05-13 06:12:02'),
(3, 2, 8, 'text', 'kuyfkuyu', NULL, NULL, NULL, '', '2025-05-13 06:33:58'),
(4, 2, 8, 'text', 'hghgc', NULL, NULL, NULL, '', '2025-05-13 06:34:02'),
(5, 2, 8, 'text', '', NULL, NULL, NULL, 'smile.png', '2025-05-13 06:34:07'),
(6, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 06:44:22'),
(7, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 06:45:13'),
(8, 2, 8, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-13 06:46:43'),
(9, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 06:46:44'),
(10, 2, 8, 'text', 'jgvhhhv;jv', NULL, NULL, NULL, NULL, '2025-05-13 06:48:03'),
(11, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 06:48:16'),
(12, 2, 8, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-13 07:34:27'),
(13, 2, 8, 'text', '', NULL, 'uploads/images/6822f68aa324e.jpeg', NULL, NULL, '2025-05-13 07:36:42'),
(14, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 08:04:24'),
(15, 2, 8, 'text', '', NULL, NULL, 'uploads/audio/6822fd4c2d57e.webm', NULL, '2025-05-13 08:05:32'),
(16, 2, 8, 'text', '', NULL, NULL, 'uploads/audio/6822ffd125049.webm', NULL, '2025-05-13 08:16:17'),
(17, 2, 8, 'text', '', NULL, NULL, 'uploads/audio/6823000ec06bd.webm', NULL, '2025-05-13 08:17:18'),
(18, 2, 8, 'text', '', NULL, NULL, 'uploads/audio/6823015f3a1b2.webm', NULL, '2025-05-13 08:22:55'),
(19, 2, 8, 'text', 'y', NULL, NULL, NULL, NULL, '2025-05-13 08:28:07'),
(20, 2, 8, 'text', 'yyt,h', NULL, NULL, NULL, NULL, '2025-05-13 08:28:31'),
(21, 2, 8, 'text', 'yfj', NULL, NULL, 'uploads/audio/682302badaab1.webm', NULL, '2025-05-13 08:28:42'),
(22, 2, 37, 'text', 'byybuubygbgyuyug', NULL, NULL, NULL, NULL, '2025-05-13 11:24:12'),
(23, 3, 8, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-13 12:04:35'),
(24, 2, 8, 'text', 'ueflisfhsflfs', NULL, NULL, NULL, NULL, '2025-05-13 12:49:28'),
(25, 2, 8, 'text', 'dsqvsdwvdsvdsvv', NULL, NULL, NULL, NULL, '2025-05-13 12:49:33'),
(26, 2, 8, 'text', 'vdscsdv', NULL, NULL, NULL, NULL, '2025-05-13 12:50:23'),
(27, 2, 8, 'text', '<div class=\"content\"><?= nl2br(htmlspecialchars($msg[\'message\'])) ?></div>', NULL, NULL, NULL, NULL, '2025-05-13 12:51:01'),
(28, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:14:40'),
(29, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:14:41'),
(30, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:14:42'),
(31, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:12'),
(32, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:16'),
(33, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:17'),
(34, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:18'),
(35, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:21'),
(36, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:21'),
(37, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:21'),
(38, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:22'),
(39, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:22'),
(40, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:22'),
(41, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:23'),
(42, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:16:23'),
(43, 2, 8, 'text', ';hj;vhj', NULL, NULL, NULL, NULL, '2025-05-13 13:21:11'),
(44, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:21:12'),
(45, 2, 8, 'text', '', NULL, NULL, NULL, NULL, '2025-05-13 13:21:17'),
(46, 2, 39, 'text', 'Hhjh', NULL, NULL, NULL, NULL, '2025-05-13 13:50:18'),
(47, 2, 39, 'text', '', NULL, 'uploads/images/68234e44f1fe5.png', NULL, NULL, '2025-05-13 13:51:01'),
(48, 3, 39, 'text', '', NULL, 'uploads/images/6823518821f9d.jpeg', NULL, NULL, '2025-05-13 14:04:56'),
(49, 3, 39, 'text', '🚶🏻🚶🏻', NULL, NULL, NULL, NULL, '2025-05-13 14:05:14'),
(50, 3, 8, 'text', 'hello', NULL, NULL, NULL, NULL, '2025-05-13 20:42:30'),
(51, 3, 40, 'text', 'CC', NULL, NULL, NULL, NULL, '2025-05-13 20:42:45'),
(52, 6, 8, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-13 20:45:49'),
(53, 6, 8, 'text', '', NULL, 'uploads/images/6823afa47e9b7.jpg', NULL, NULL, '2025-05-13 20:46:28'),
(54, 3, 8, 'text', 'hghgc', NULL, NULL, NULL, NULL, '2025-05-14 17:06:32'),
(55, 3, 8, 'text', '-uykyfu', NULL, NULL, NULL, NULL, '2025-05-14 17:08:22'),
(56, 3, 8, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-14 17:17:04'),
(57, 3, 8, 'text', 'jgvhhhv;jv', NULL, NULL, NULL, NULL, '2025-05-14 17:21:48'),
(58, 3, 8, 'text', 'jgvhhhv;jv', NULL, NULL, NULL, NULL, '2025-05-14 17:24:28'),
(59, 3, 8, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 17:27:16'),
(60, 3, 8, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 17:27:19'),
(61, 3, 8, 'text', 'hghgc', NULL, NULL, NULL, NULL, '2025-05-14 17:32:47'),
(62, 3, 8, 'text', 'jgvhhhv;jv', NULL, NULL, NULL, NULL, '2025-05-14 17:33:37'),
(63, 3, 37, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-14 17:37:06'),
(65, 3, 37, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-14 17:40:08'),
(66, 3, 37, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 17:42:08'),
(67, 3, 37, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 17:44:55'),
(68, 3, 37, 'text', '', NULL, NULL, NULL, NULL, '2025-05-14 17:45:02'),
(69, 3, 37, 'text', 'jgvhhhv;jv', NULL, NULL, NULL, NULL, '2025-05-14 17:46:11'),
(70, 3, 37, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 17:46:15'),
(71, 3, 39, 'text', 'عع', NULL, NULL, NULL, NULL, '2025-05-14 17:49:31'),
(72, 3, 39, 'text', 'ههه', NULL, NULL, NULL, NULL, '2025-05-14 17:52:47'),
(74, 3, 39, 'text', 'ووو', NULL, NULL, NULL, NULL, '2025-05-14 17:53:52'),
(75, 3, 37, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 17:54:12'),
(76, 3, 37, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-14 18:01:30'),
(77, 3, 37, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-14 18:01:31'),
(78, 3, 37, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-14 18:04:14'),
(79, 3, 37, 'text', 'jgvhhhv;jv', NULL, NULL, NULL, NULL, '2025-05-14 18:05:21'),
(82, 3, 39, 'text', 'هههه', NULL, NULL, NULL, NULL, '2025-05-14 18:10:55'),
(84, 3, 39, 'text', 'هههه', NULL, NULL, NULL, NULL, '2025-05-14 18:17:11'),
(85, 3, 39, 'text', 'هبهبهده', NULL, NULL, NULL, NULL, '2025-05-14 18:26:22'),
(86, 5, 39, 'text', 'ععع', NULL, NULL, NULL, NULL, '2025-05-14 18:31:41'),
(87, 5, 39, 'text', '', NULL, NULL, NULL, NULL, '2025-05-14 18:31:58'),
(88, 5, 39, 'text', '', NULL, NULL, NULL, NULL, '2025-05-14 18:32:03'),
(89, 5, 39, 'text', '', NULL, NULL, 'uploads/audio/6824e1cad1334.webm', NULL, '2025-05-14 18:32:44'),
(111, 3, 37, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 22:05:02'),
(113, 3, 37, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 22:06:46'),
(123, 4, 37, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-14 22:23:24'),
(126, 4, 37, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-15 01:11:07'),
(129, 3, 37, 'text', 'hghgc', NULL, NULL, NULL, NULL, '2025-05-17 18:12:05'),
(130, 3, 37, 'text', '', NULL, 'uploads/images/6828d18f87b97.png', NULL, NULL, '2025-05-17 18:12:31'),
(131, 4, 47, 'text', 'kuyfkuyu', NULL, NULL, NULL, NULL, '2025-05-31 21:04:41'),
(132, 4, 47, 'text', 'hjvjvhhjv', NULL, NULL, NULL, NULL, '2025-05-31 21:04:46'),
(133, 13, 47, 'text', 'hghgc', NULL, NULL, NULL, NULL, '2025-05-31 22:52:43');

-- --------------------------------------------------------

--
-- بنية الجدول `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image_path`, `created_at`) VALUES
(1, 'hcggc', 'jb,n', NULL, '2025-05-15 18:42:45'),
(2, 'hh', 'hh', '68262833d2be7.jpg', '2025-05-15 18:45:23');

-- --------------------------------------------------------

--
-- بنية الجدول `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `created_at`) VALUES
(3, 37, 'hi', '2025-05-15 13:53:54'),
(4, 37, 'hello', '2025-05-15 14:00:54'),
(5, 37, 'hi', '2025-05-15 14:24:47'),
(6, 37, '.\r\n;jhjgh', '2025-05-15 14:28:23'),
(7, 37, 'hello world', '2025-05-15 14:32:11'),
(9, 37, 'go', '2025-05-15 16:55:04'),
(10, 39, 'Hi', '2025-05-15 18:49:07'),
(11, 37, 'hi', '2025-05-17 18:04:29'),
(12, 45, '?TGDC??GC', '2025-05-31 20:31:54');

-- --------------------------------------------------------

--
-- بنية الجدول `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `major` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`, `created_by`, `created_at`, `major`) VALUES
(3, 'AI', '982985954', '2025-05-13 13:04:22', 'AI'),
(4, ' Sécurité g1', '982985954', '2025-05-13 15:10:30', 'Sécurité'),
(5, 'Réseaux', '982985954', '2025-05-13 15:10:38', 'Réseaux'),
(11, 'Sécurité g2', '51651654', '2025-05-31 22:23:05', 'Sécurité'),
(13, 'Sécurité g3', '51651654', '2025-05-31 23:04:15', 'Sécurité'),
(14, 'Sécurité g4', '51651654', '2025-05-31 23:52:11', 'Sécurité');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 0,
  `activation_code` varchar(10) DEFAULT NULL,
  `reset_code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profil_img` longblob NOT NULL,
  `major` enum('Sécurité','AI','Réseaux','info','JDID') DEFAULT NULL,
  `g_admin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `registration_number`, `email`, `password`, `first_name`, `last_name`, `age`, `birthdate`, `is_admin`, `is_active`, `activation_code`, `reset_code`, `created_at`, `profil_img`, `major`, `g_admin`) VALUES
(2, '78556245555', 'jex.car000@gmail.com', '$2y$10$kN/ih9mFSkOhOQX7zgFlAu7bQJ8vMJEr8C75mweTpQ6fI9DzxLAJm', NULL, NULL, 25, NULL, 0, 1, '699250', '738959', '2025-05-12 22:19:43', '', NULL, NULL),
(43, 'SQDFSDFCS', '25', '$2y$10$mqtqIyQPF7pCef.98T.icOKfH0u/4sFxehI17koVa4vJ4q9Tm33yq', NULL, NULL, 0, NULL, 0, 0, '505116', NULL, '2025-05-31 20:16:29', 0x353035313136, '', NULL),
(46, '65454554', 'Sécurité', '$2y$10$KUawElj3eXYClVM3PjNQsOw8RmqRyqWmBy4a5.Qn/hwUf2Vdt6OZi', NULL, NULL, 25, NULL, 0, 0, '380948', NULL, '2025-05-31 20:42:58', 0x333830393438, '', NULL),
(47, '51651654', 'didindz500dz@gmail.com', '$2y$10$JbDor.qnG1IBjxRKKQOuCu/z92FjrVR9lSzH6IKAlxR2BMorNizAu', 'dj', 'teh', 25, NULL, 1, 1, '869461', NULL, '2025-05-31 20:48:46', 0x43497968446e6c55454141673562502e6a7067, 'Sécurité', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`friend_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `comment_replies`
--
ALTER TABLE `comment_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD CONSTRAINT `comment_replies_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
