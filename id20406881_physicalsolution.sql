-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2023 at 04:22 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id20406881_physicalsolution`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_profile`
--

CREATE TABLE `company_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` text NOT NULL,
  `email_address` text NOT NULL,
  `mobile_number` text NOT NULL,
  `pan_number` text NOT NULL,
  `gst_number` text NOT NULL,
  `image_url` text DEFAULT NULL,
  `date_modify` datetime NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_profile`
--

INSERT INTO `company_profile` (`id`, `user_id`, `company_name`, `email_address`, `mobile_number`, `pan_number`, `gst_number`, `image_url`, `date_modify`, `date_created`) VALUES
(1, 4, 'Test', 'j18surana@gmail.com', '9057516113', 'null', 'null', 'http://localhost/apiPractice/v1/uploads/4_image.png', '2023-05-12 16:07:12', '2023-05-12 19:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_table`
--

CREATE TABLE `coupon_table` (
  `id` int(11) NOT NULL,
  `user_id` text NOT NULL,
  `coupon_code` text NOT NULL,
  `assign_to_id` text NOT NULL,
  `is_for_all` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_post`
--

CREATE TABLE `job_post` (
  `id` int(11) NOT NULL,
  `user_id` text DEFAULT NULL,
  `company_id` text DEFAULT NULL,
  `company_name` text DEFAULT NULL,
  `gst_number` text DEFAULT NULL,
  `contact_detail_name` text DEFAULT NULL,
  `contact_detail_mobile_number` text DEFAULT NULL,
  `location_pin_code` text DEFAULT NULL,
  `location_country` text DEFAULT NULL,
  `location_state` text DEFAULT NULL,
  `location_district` text DEFAULT NULL,
  `location_address_line_1` text DEFAULT NULL,
  `location_address_line_2` text DEFAULT NULL,
  `staff_ca_fresher` text DEFAULT NULL,
  `staff_ca_experience` text DEFAULT NULL,
  `staff_graduate_fresher` text DEFAULT NULL,
  `staff_graduate_experience` text DEFAULT NULL,
  `start_date` text DEFAULT NULL,
  `end_date` text DEFAULT NULL,
  `total_days` text DEFAULT NULL,
  `is_laptop_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_reimbursement_required` tinyint(1) NOT NULL DEFAULT 0,
  `reimbursement_amount` text DEFAULT NULL,
  `any_comment` text DEFAULT NULL,
  `coupon_code` text DEFAULT NULL,
  `discount_amount` text DEFAULT NULL,
  `profession_fees` text DEFAULT NULL,
  `convenience_fee` text DEFAULT NULL,
  `platform_fee` text DEFAULT NULL,
  `tax_percentage` text DEFAULT NULL,
  `tax_amount` text DEFAULT NULL,
  `total` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `date_modify` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profession_table`
--

CREATE TABLE `profession_table` (
  `id` int(11) NOT NULL,
  `user_id` text NOT NULL,
  `city_type` text NOT NULL,
  `experience` text NOT NULL,
  `fresher` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upload_images`
--

CREATE TABLE `upload_images` (
  `id` int(11) NOT NULL,
  `user_id` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `upload_images`
--

INSERT INTO `upload_images` (`id`, `user_id`, `image_url`, `date_created`) VALUES
(1, '2', 'http://localhost/apiPractice/v1/uploads/2_image.png', '2023-04-13 15:54:22'),
(2, '4', 'http://localhost/apiPractice/v1/uploads/4_image.png', '2023-05-12 12:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `mobile` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `otp` int(30) DEFAULT NULL,
  `status` text DEFAULT 'Active',
  `isORG` tinyint(1) DEFAULT 0,
  `isVerifiedMobileNumber` tinyint(1) NOT NULL DEFAULT 0,
  `isVerifiedEmail` tinyint(1) NOT NULL DEFAULT 0,
  `fcm_token` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `createdDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `name`, `email`, `mobile`, `password`, `otp`, `status`, `isORG`, `isVerifiedMobileNumber`, `isVerifiedEmail`, `fcm_token`, `image`, `createdDate`) VALUES
(1, 'TestJeetesh', 'testUser@gmail.com', '9057516113', 'Test@123$', 1235464, 'Active', 0, 0, 0, '', NULL, '2023-03-07 23:27:59'),
(2, 'Test', 'jeeteshsurana@gmail.com', NULL, 'Test', 809622, 'Active', 0, 0, 1, '', NULL, '2023-03-09 13:05:22'),
(4, NULL, 'j18surana@gmail.com', NULL, NULL, 509275, 'Active', 0, 0, 1, NULL, NULL, '2023-03-27 17:06:09'),
(5, NULL, 'test@gmail.com', NULL, NULL, 564127, 'Active', 0, 0, 0, NULL, NULL, '2023-04-02 11:21:11'),
(6, NULL, 'yedse@werw.chcg', NULL, NULL, 725645, 'Active', 0, 0, 0, NULL, NULL, '2023-04-11 01:38:00'),
(7, NULL, 'j18suana@gmail.com', NULL, NULL, 174249, 'Active', 0, 0, 0, NULL, NULL, '2023-04-12 09:15:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_profile`
--
ALTER TABLE `company_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_table`
--
ALTER TABLE `coupon_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_post`
--
ALTER TABLE `job_post`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profession_table`
--
ALTER TABLE `profession_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `upload_images`
--
ALTER TABLE `upload_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_profile`
--
ALTER TABLE `company_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupon_table`
--
ALTER TABLE `coupon_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_post`
--
ALTER TABLE `job_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profession_table`
--
ALTER TABLE `profession_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upload_images`
--
ALTER TABLE `upload_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
