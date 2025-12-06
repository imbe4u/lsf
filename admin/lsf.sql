-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 08:41 PM
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
-- Database: `lsf`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `external_link` varchar(255) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `helped_with` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `disease` varchar(100) DEFAULT NULL,
  `money_required` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `donor_name` varchar(100) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `units_provided` int(11) DEFAULT NULL,
  `donation_date` date DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `hospital_name` varchar(255) DEFAULT NULL,
  `verified_by_member_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `money_helped` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `Id` int(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(14) NOT NULL,
  `Address` varchar(5000) NOT NULL,
  `password` varchar(50) NOT NULL,
  `profile_pic` varchar(5000) NOT NULL,
  `Approved_By` varchar(255) NOT NULL,
  `role` enum('admin','member','volunteer') NOT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`Id`, `name`, `email`, `phone`, `Address`, `password`, `profile_pic`, `Approved_By`, `role`, `Created_At`) VALUES
(2, 'Kumar Anshu Raj', 'anshuonwork@gmail.com', '06375634668', 'Sharda Nagar Purnea\r\nBehind Bus stand', '$2y$10$fAANySYZZrcWgOPltp1yde6Z1cLceTzg/5o6infPSxr', 'uploads/Ridhima-Pandit-Indian-Actress.jpg', 'Manish', 'admin', '2025-05-22 16:36:30'),
(18, 'roshni singh', 'anshuonwork@gmail.com', '6375634668', '01\r\nGM road', '$2y$10$vM9cjB73fAWxcst4q7VvBeSOT1Py14KYuMpVYNQsi9h', '', 'Manish', 'admin', '2025-05-23 09:27:46');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `doctor_prescription_doc` varchar(255) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `any_other_help` text DEFAULT NULL,
  `raised_by_user_id` int(11) DEFAULT NULL,
  `relationship_with_patient` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `approved_by_admin_id` int(11) DEFAULT NULL,
  `hospital_name` varchar(255) DEFAULT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `amount_needed` decimal(10,2) DEFAULT NULL,
  `amount_provided` decimal(10,2) DEFAULT NULL,
  `blood_provided_units` int(11) DEFAULT NULL,
  `images_after_treatment` text DEFAULT NULL,
  `message_from_patient` text DEFAULT NULL,
  `campaign_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `blood_group_proof` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `user_type` enum('donor','admin','patient','member') NOT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `blood_group`, `blood_group_proof`, `profile_image`, `user_type`, `gender`, `age`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, '2025-05-22 09:50:52', '2025-05-22 09:50:52'),
(2, 'ravi', 'rajes@gmail.com', '23424', NULL, 'b', 'sldkjf', 'donor', 'male', 34, '2025-05-22 10:30:21', '2025-05-22 10:30:21'),
(3, 'raj', 'asdfl@asdfljk.com', '23', NULL, '6833-2048x1360-desktop-hd-hummer-wallpaper-image.jpg', '1699637-3840x2160-desktop-4k-dodge-challenger-background-image.jpg', 'donor', NULL, NULL, '2025-05-22 14:21:41', '2025-05-22 14:21:41'),
(5, 'asd', 'sd@sldf.com', '232', NULL, '6833-2048x1360-desktop-hd-hummer-wallpaper-image.jpg', '1699637-3840x2160-desktop-4k-dodge-challenger-background-image.jpg', 'donor', NULL, NULL, '2025-05-22 14:24:37', '2025-05-22 14:24:37'),
(6, 'yul', 'asdlfj@gmail.com', '1122', NULL, '6833-2048x1360-desktop-hd-hummer-wallpaper-image.jpg', '1699637-3840x2160-desktop-4k-dodge-challenger-background-image.jpg', 'donor', NULL, NULL, '2025-05-22 14:27:03', '2025-05-22 14:27:03'),
(8, 'rahul', 'ra@glasdj.com', '2323', NULL, '6833-2048x1360-desktop-hd-hummer-wallpaper-image.jpg', '1699637-3840x2160-desktop-4k-dodge-challenger-background-image.jpg', 'donor', NULL, NULL, '2025-05-22 14:53:50', '2025-05-22 14:53:50'),
(9, 'Manish Kumar Singh', 'manish147.418@rediffmail.com', '4554545454', NULL, 'about.php', '486857578_715139690853619_1909385821314526481_n.jpg', 'donor', NULL, NULL, '2025-05-22 15:09:48', '2025-05-22 15:09:48'),
(10, 'Kumar Anshu Raj', 'anshuonwork@gmail.com', '06375634668', NULL, '6833-2048x1360-desktop-hd-hummer-wallpaper-image.jpg', '1699637-3840x2160-desktop-4k-dodge-challenger-background-image.jpg', 'donor', NULL, NULL, '2025-05-23 07:17:07', '2025-05-23 07:17:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verified_by_member_id` (`verified_by_member_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_patient` (`patient_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raised_by_user_id` (`raised_by_user_id`),
  ADD KEY `approved_by_admin_id` (`approved_by_admin_id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `Id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `donors_ibfk_1` FOREIGN KEY (`verified_by_member_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `donors_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`raised_by_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `patients_ibfk_2` FOREIGN KEY (`approved_by_admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `patients_ibfk_3` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
