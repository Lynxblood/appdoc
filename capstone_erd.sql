-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 03:25 AM
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
  `event_Time` time NOT NULL,
  `venue_ID` int(11) NOT NULL,
  `file_Type` varchar(123) NOT NULL,
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
  `file_ID` int(11) NOT NULL,
  `file_Name` varchar(255) NOT NULL,
  `file_Type` varchar(100) DEFAULT NULL,
  `file_Size` int(11) DEFAULT NULL,
  `folder_ID` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `folder_ID` int(11) NOT NULL,
  `folder_name` varchar(100) NOT NULL,
  `parent_ID` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`folder_ID`, `folder_name`, `parent_ID`, `created_at`) VALUES
(24, 'another sample', NULL, '2025-04-30 01:05:14'),
(25, 'sample inside', 24, '2025-04-30 01:05:32'),
(26, 'sample inside inside', 25, '2025-04-30 01:05:41'),
(27, 'last na pls', NULL, '2025-04-30 01:18:37'),
(28, 'sample inside inside hjdxcjuyterwASSFGHJK', 26, '2025-04-30 11:45:48'),
(29, 'sample folder', NULL, '2025-05-04 22:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `org_ID` int(12) NOT NULL,
  `userID` int(12) NOT NULL,
  `logo` varchar(12) NOT NULL,
  `org_name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `org_Type` varchar(50) NOT NULL,
  `established_Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `org_accounts`
--

CREATE TABLE `org_accounts` (
  `orgAccount_ID` int(11) NOT NULL,
  `org_Name` varchar(100) NOT NULL,
  `org_Email` varchar(100) NOT NULL,
  `org_Acronym` varchar(100) NOT NULL,
  `org_Type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `org_type`
--

CREATE TABLE `org_type` (
  `orgType_ID` int(11) NOT NULL,
  `orgType_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `org_type`
--

INSERT INTO `org_type` (`orgType_ID`, `orgType_name`) VALUES
(1, 'Academic Organization'),
(2, 'Non-Academic Organization');

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
  `file_ID` int(12) NOT NULL,
  `template_Name` varchar(123) NOT NULL,
  `date_Uploaded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `user_ID` int(11) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contactNo` int(11) NOT NULL,
  `org_accounts` int(11) NOT NULL,
  `position` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `userType_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `userType_ID` int(12) NOT NULL,
  `user_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`userType_ID`, `user_type`) VALUES
(1, 'OSAS'),
(2, 'USC'),
(3, 'Dean'),
(4, 'Adviser'),
(5, 'Officer');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venue_ID` int(11) NOT NULL,
  `venue_Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

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
  ADD KEY `org_ID` (`org_ID`),
  ADD KEY `venue_ID` (`venue_ID`);

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
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`folder_ID`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`org_ID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `org_accounts`
--
ALTER TABLE `org_accounts`
  ADD PRIMARY KEY (`orgAccount_ID`),
  ADD KEY `org_Type` (`org_Type`);

--
-- Indexes for table `org_type`
--
ALTER TABLE `org_type`
  ADD PRIMARY KEY (`orgType_ID`);

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
  ADD KEY `file_ID` (`file_ID`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`user_ID`),
  ADD KEY `userType_ID` (`userType_ID`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`userType_ID`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venue_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `file_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `folder_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `org_ID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `org_accounts`
--
ALTER TABLE `org_accounts`
  MODIFY `orgAccount_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `org_type`
--
ALTER TABLE `org_type`
  MODIFY `orgType_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `userType_ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `venue_ID` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`org_ID`) REFERENCES `organization` (`org_ID`),
  ADD CONSTRAINT `event_ibfk_3` FOREIGN KEY (`venue_ID`) REFERENCES `venue` (`venue_ID`);

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
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`folder_ID`) REFERENCES `folders` (`folder_ID`) ON DELETE CASCADE;

--
-- Constraints for table `organization`
--
ALTER TABLE `organization`
  ADD CONSTRAINT `organization_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `account` (`user_ID`);

--
-- Constraints for table `org_accounts`
--
ALTER TABLE `org_accounts`
  ADD CONSTRAINT `org_accounts_ibfk_1` FOREIGN KEY (`org_Type`) REFERENCES `org_type` (`orgType_ID`);

--
-- Constraints for table `post_evaluation`
--
ALTER TABLE `post_evaluation`
  ADD CONSTRAINT `post_evaluation_ibfk_1` FOREIGN KEY (`event_ID`) REFERENCES `event` (`event_ID`);

--
-- Constraints for table `push_notif`
--
ALTER TABLE `push_notif`
  ADD CONSTRAINT `push_notif_ibfk_1` FOREIGN KEY (`event_ID`) REFERENCES `event` (`event_ID`);

--
-- Constraints for table `submission`
--
ALTER TABLE `submission`
  ADD CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`file_ID`) REFERENCES `files` (`file_ID`),
  ADD CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`org_ID`) REFERENCES `organization` (`org_ID`);

--
-- Constraints for table `templates`
--
ALTER TABLE `templates`
  ADD CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`file_ID`) REFERENCES `files` (`file_ID`);

--
-- Constraints for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD CONSTRAINT `user_accounts_ibfk_1` FOREIGN KEY (`userType_ID`) REFERENCES `user_type` (`userType_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
