-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 03:07 PM
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
-- Database: `erd`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user_ID` int(12) NOT NULL,
  `image` varchar(123) NOT NULL,
  `username` varchar(123) NOT NULL,
  `f_Name` varchar(123) NOT NULL,
  `m_Name` varchar(123) NOT NULL,
  `l_Name` varchar(123) NOT NULL,
  `contact_Number` int(12) NOT NULL,
  `email` varchar(123) NOT NULL,
  `password` varchar(123) NOT NULL,
  `user_Type` varchar(123) NOT NULL,
  `status` varchar(123) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `endorsement`
--

CREATE TABLE `endorsement` (
  `endorsement_ID` int(12) NOT NULL,
  `org_ID` int(12) NOT NULL,
  `event_ID` int(12) NOT NULL,
  `issued_By` varchar(123) NOT NULL,
  `issued_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_ID` int(12) NOT NULL,
  `org_ID` int(12) NOT NULL,
  `file_ID` int(12) NOT NULL,
  `event_Title` varchar(123) NOT NULL,
  `description` varchar(213) NOT NULL,
  `event_Date` date NOT NULL,
  `file_Type` varchar(123) NOT NULL,
  `venue` varchar(123) NOT NULL,
  `created_By` varchar(123) NOT NULL,
  `status` varchar(123) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_approval`
--

CREATE TABLE `event_approval` (
  `approval_ID` int(12) NOT NULL,
  `event_ID` int(12) NOT NULL,
  `reviewed_By` varchar(123) NOT NULL,
  `role` varchar(123) NOT NULL,
  `status` varchar(213) NOT NULL,
  `remarks` varchar(123) NOT NULL,
  `date_Reviewed` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expenses_ID` int(12) NOT NULL,
  `event_ID` int(12) NOT NULL,
  `product_Name` varchar(123) NOT NULL,
  `quantity` int(12) NOT NULL,
  `amount` int(12) NOT NULL,
  `submitted_By` varchar(123) NOT NULL,
  `submitted_Date` date NOT NULL,
  `status` varchar(123) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `file_ID` int(12) NOT NULL,
  `folder_ID` int(11) NOT NULL,
  `file_Name` varchar(123) NOT NULL,
  `file_Type` varchar(123) NOT NULL,
  `file_Size` varchar(123) NOT NULL,
  `uploaded_By` varchar(123) NOT NULL,
  `upload_Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE `folder` (
  `folder_ID` int(11) NOT NULL,
  `folder_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folder`
--

INSERT INTO `folder` (`folder_ID`, `folder_name`) VALUES
(2, 'hh'),
(3, 'osef'),
(4, 'zcxc');

-- --------------------------------------------------------

--
-- Table structure for table `ord_member`
--

CREATE TABLE `ord_member` (
  `member_ID` int(12) NOT NULL,
  `org_ID` int(12) NOT NULL,
  `image` varchar(123) NOT NULL,
  `f_Name` varchar(123) NOT NULL,
  `m_Name` varchar(123) NOT NULL,
  `l_Name` varchar(123) NOT NULL,
  `role` varchar(123) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `org_ID` int(12) NOT NULL,
  `user_ID` int(12) NOT NULL,
  `logo` varchar(12) NOT NULL,
  `org_name` varchar(123) NOT NULL,
  `description` varchar(12) NOT NULL,
  `classification` varchar(12) NOT NULL,
  `established_Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_evaluation`
--

CREATE TABLE `post_evaluation` (
  `evaluation_ID` int(12) NOT NULL,
  `event_ID` int(12) NOT NULL,
  `feedback` varchar(123) NOT NULL,
  `ratings` varchar(123) NOT NULL,
  `submitted_By` varchar(213) NOT NULL,
  `submitted_Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `push_notif`
--

CREATE TABLE `push_notif` (
  `notif_ID` int(12) NOT NULL,
  `user_ID` int(12) NOT NULL,
  `event_ID` int(12) NOT NULL,
  `message` varchar(123) NOT NULL,
  `date_Sent` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

CREATE TABLE `submission` (
  `submission_ID` int(12) NOT NULL,
  `org_ID` int(12) NOT NULL,
  `file_ID` int(12) NOT NULL,
  `file_Name` varchar(123) NOT NULL,
  `reviewed_By` varchar(213) NOT NULL,
  `reviewed_Date` date NOT NULL,
  `status` varchar(123) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `template_ID` int(12) NOT NULL,
  `user_ID` int(12) NOT NULL,
  `file_ID` int(12) NOT NULL,
  `template_Name` varchar(123) NOT NULL,
  `date_Uploaded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `endorsement`
--
ALTER TABLE `endorsement`
  ADD PRIMARY KEY (`endorsement_ID`),
  ADD KEY `event_ID` (`event_ID`),
  ADD KEY `org_ID` (`org_ID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_ID`),
  ADD KEY `file_ID` (`file_ID`),
  ADD KEY `org_ID` (`org_ID`);

--
-- Indexes for table `event_approval`
--
ALTER TABLE `event_approval`
  ADD PRIMARY KEY (`approval_ID`),
  ADD KEY `event_ID` (`event_ID`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenses_ID`),
  ADD KEY `event_ID` (`event_ID`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_ID`),
  ADD KEY `folder_ID` (`folder_ID`);

--
-- Indexes for table `folder`
--
ALTER TABLE `folder`
  ADD PRIMARY KEY (`folder_ID`);

--
-- Indexes for table `ord_member`
--
ALTER TABLE `ord_member`
  ADD PRIMARY KEY (`member_ID`),
  ADD KEY `org_ID` (`org_ID`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`org_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `post_evaluation`
--
ALTER TABLE `post_evaluation`
  ADD PRIMARY KEY (`evaluation_ID`),
  ADD KEY `event_ID` (`event_ID`);

--
-- Indexes for table `push_notif`
--
ALTER TABLE `push_notif`
  ADD PRIMARY KEY (`notif_ID`),
  ADD KEY `event_ID` (`event_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `submission`
--
ALTER TABLE `submission`
  ADD PRIMARY KEY (`submission_ID`),
  ADD KEY `org_ID` (`org_ID`),
  ADD KEY `file_ID` (`file_ID`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`template_ID`),
  ADD KEY `file_ID` (`file_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `user_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `endorsement`
--
ALTER TABLE `endorsement`
  MODIFY `endorsement_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_approval`
--
ALTER TABLE `event_approval`
  MODIFY `approval_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenses_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `file_ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `folder`
--
ALTER TABLE `folder`
  MODIFY `folder_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ord_member`
--
ALTER TABLE `ord_member`
  MODIFY `member_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `org_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_evaluation`
--
ALTER TABLE `post_evaluation`
  MODIFY `evaluation_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `push_notif`
--
ALTER TABLE `push_notif`
  MODIFY `notif_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submission`
--
ALTER TABLE `submission`
  MODIFY `submission_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `endorsement`
--
ALTER TABLE `endorsement`
  ADD CONSTRAINT `endorsement_ibfk_1` FOREIGN KEY (`event_ID`) REFERENCES `event` (`event_ID`),
  ADD CONSTRAINT `endorsement_ibfk_2` FOREIGN KEY (`org_ID`) REFERENCES `organization` (`org_ID`);

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`file_ID`) REFERENCES `files` (`file_ID`),
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`org_ID`) REFERENCES `organization` (`org_ID`);

--
-- Constraints for table `event_approval`
--
ALTER TABLE `event_approval`
  ADD CONSTRAINT `event_approval_ibfk_1` FOREIGN KEY (`event_ID`) REFERENCES `event` (`event_ID`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`event_ID`) REFERENCES `event` (`event_ID`);

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`folder_ID`) REFERENCES `folder` (`folder_ID`);

--
-- Constraints for table `ord_member`
--
ALTER TABLE `ord_member`
  ADD CONSTRAINT `ord_member_ibfk_1` FOREIGN KEY (`org_ID`) REFERENCES `organization` (`org_ID`);

--
-- Constraints for table `organization`
--
ALTER TABLE `organization`
  ADD CONSTRAINT `organization_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `accounts` (`user_ID`);

--
-- Constraints for table `post_evaluation`
--
ALTER TABLE `post_evaluation`
  ADD CONSTRAINT `post_evaluation_ibfk_1` FOREIGN KEY (`event_ID`) REFERENCES `event` (`event_ID`);

--
-- Constraints for table `push_notif`
--
ALTER TABLE `push_notif`
  ADD CONSTRAINT `push_notif_ibfk_1` FOREIGN KEY (`event_ID`) REFERENCES `event` (`event_ID`),
  ADD CONSTRAINT `push_notif_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `accounts` (`user_ID`);

--
-- Constraints for table `submission`
--
ALTER TABLE `submission`
  ADD CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`org_ID`) REFERENCES `organization` (`org_ID`),
  ADD CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`file_ID`) REFERENCES `files` (`file_ID`);

--
-- Constraints for table `templates`
--
ALTER TABLE `templates`
  ADD CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`file_ID`) REFERENCES `files` (`file_ID`),
  ADD CONSTRAINT `templates_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `accounts` (`user_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
