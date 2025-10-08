-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 11:06 AM
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
-- Database: `stud_org_gemini`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `document_id`, `user_id`, `comment_text`, `created_at`) VALUES
(34, 44, 16, 'Sample comment', '2025-09-30 08:50:15'),
(35, 44, 17, 'sample comment', '2025-09-30 08:55:55'),
(36, 44, 18, 'sample comment', '2025-09-30 08:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `status` enum('draft','submitted','pending','rejected','revision','endorsed','approved','approved_fssc') NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_html` longtext NOT NULL,
  `pdf_filename` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `document_type`, `organization_id`, `status`, `user_id`, `content_html`, `pdf_filename`, `created_at`, `updated_at`, `event_id`) VALUES
(44, 'Accreditation form', 1, 'approved_fssc', 1, '\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br><span style=\"background-color: rgb(81, 181, 171);\">Warmest\n												greetin</span>gs! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This even<span style=\"background-color: rgb(69, 106, 192);\">t is designed to provide\n												2n</span>d-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a val<span style=\"background-color: rgb(220, 41, 90);\">uable guide in preparing students for\n												their future projects.<br></span><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><font color=\"#000000\"><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br></font><strong>[FSSC_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font></font>\n											</div>\n\n									', 'futures.pdf', '2025-09-30 16:49:26', '2025-09-30 16:58:55', 11);

-- --------------------------------------------------------

--
-- Table structure for table `document_history`
--

CREATE TABLE `document_history` (
  `history_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `from_status` enum('draft','submitted','pending','rejected','revision','endorsed','approved','approved_fssc') NOT NULL,
  `to_status` enum('draft','submitted','pending','rejected','revision','endorsed','approved','approved_fssc') NOT NULL,
  `modified_by_user_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `old_content_html` longtext DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `e_signature_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_history`
--

INSERT INTO `document_history` (`history_id`, `document_id`, `from_status`, `to_status`, `modified_by_user_id`, `reason`, `old_content_html`, `timestamp`, `e_signature_code`) VALUES
(57, 44, 'draft', 'submitted', 1, NULL, NULL, '2025-09-30 16:49:48', NULL),
(58, 44, 'submitted', 'revision', 16, 'Commented for revision.', '\r\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\r\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\r\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\r\n												Information Technology Society formally request approval to host an event\r\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\r\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\r\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\r\n												2nd-year BSIT students with a comprehensive understanding of the capstone\r\n												project development process. It aims to equip students with the fundamental\r\n												knowledge necessary before embarking on their capstone journey. Through\r\n												discussions on research methodologies, project design, and the development\r\n												process, this event will serve as a valuable guide in preparing students for\r\n												their future projects.<br><br>Thank you for considering our request. We\r\n												look forward to your positive response and hope to make this event a\r\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><font color=\"#000000\"><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\r\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\r\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br></font><strong>[FSSC_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\r\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\r\n												Student Affairs and Services<br><br><br><br><br></font></font>\r\n											</div>\r\n\r\n									', '2025-09-30 16:50:18', NULL),
(59, 44, 'draft', 'submitted', 1, NULL, NULL, '2025-09-30 16:50:40', NULL),
(60, 44, 'submitted', 'endorsed', 16, NULL, NULL, '2025-09-30 16:50:50', 'ADVW92QR-8b31e4c806'),
(61, 44, 'submitted', 'revision', 17, 'Commented for revision.', '\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br><span style=\"background-color: rgb(81, 181, 171);\">Warmest\n												greetin</span>gs! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><font color=\"#000000\"><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br></font><strong>[FSSC_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font></font>\n											</div>\n\n									', '2025-09-30 16:55:57', NULL),
(62, 44, 'draft', 'submitted', 1, NULL, NULL, '2025-09-30 16:56:42', NULL),
(63, 44, 'submitted', 'endorsed', 16, NULL, NULL, '2025-09-30 16:56:59', 'ADVW92QR-962a48da6f'),
(64, 44, 'submitted', 'pending', 17, NULL, NULL, '2025-09-30 16:57:22', 'DEAPMOGN-273d7ef975'),
(65, 44, 'submitted', 'revision', 18, 'Commented for revision.', '\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br><span style=\"background-color: rgb(81, 181, 171);\">Warmest\n												greetin</span>gs! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This even<span style=\"background-color: rgb(69, 106, 192);\">t is designed to provide\n												2n</span>d-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><font color=\"#000000\"><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br></font><strong>[FSSC_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font></font>\n											</div>\n\n									', '2025-09-30 16:58:55', NULL),
(66, 44, 'draft', 'submitted', 1, NULL, NULL, '2025-09-30 16:59:04', NULL),
(67, 44, 'submitted', 'endorsed', 16, NULL, NULL, '2025-09-30 16:59:37', 'ADVW92QR-b84e5120ea'),
(68, 44, 'submitted', 'pending', 17, NULL, NULL, '2025-09-30 16:59:52', 'DEAPMOGN-7ed57d7da0'),
(69, 44, 'submitted', 'approved_fssc', 18, NULL, NULL, '2025-09-30 17:00:09', 'FSS62AP7-bee5fb8856');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `total_expenses` decimal(10,2) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `organization_id`, `title`, `description`, `start_date`, `end_date`, `location`, `total_expenses`, `created_at`) VALUES
(11, 1, 'Futures thinking Development', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi aut quo molestiae incidunt nam sapiente eos eaque aperiam. Explicabo, soluta.', '2025-10-01 09:00:00', '2025-10-01 17:00:00', 'FTC', 0.00, '2025-09-30 16:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `document_id`, `message`, `is_read`, `created_at`) VALUES
(70, 16, 44, 'A new document has been submitted for your approval.', 0, '2025-09-30 16:49:48'),
(71, 1, 44, 'A new comment has been added to your document by adviser.', 1, '2025-09-30 16:50:15'),
(72, 1, 44, 'adviser updated your document and marked it for revision.', 1, '2025-09-30 16:50:18'),
(73, 16, 44, 'A new document has been submitted for your approval.', 0, '2025-09-30 16:50:40'),
(74, 17, 44, 'A new document titled \'futures.pdf\' has been submitted for your review.', 0, '2025-09-30 16:50:50'),
(75, 1, 44, 'A new comment has been added to your document by dean.', 0, '2025-09-30 16:55:55'),
(76, 16, 44, 'A new document has been submitted for your approval.', 0, '2025-09-30 16:56:42'),
(77, 17, 44, 'A new document titled \'futures.pdf\' has been submitted for your review.', 0, '2025-09-30 16:56:59'),
(78, 18, 44, 'A new document titled \'futures.pdf\' has been submitted for your review.', 0, '2025-09-30 16:57:22'),
(79, 1, 44, 'A new comment has been added to your document by fssc.', 0, '2025-09-30 16:58:54'),
(80, 16, 44, 'A new document has been submitted for your approval.', 0, '2025-09-30 16:59:04'),
(81, 17, 44, 'A new document titled \'futures.pdf\' has been submitted for your review.', 0, '2025-09-30 16:59:37'),
(82, 18, 44, 'A new document titled \'futures.pdf\' has been submitted for your review.', 0, '2025-09-30 16:59:52');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `organization_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rank_id` int(11) NOT NULL DEFAULT 1,
  `type` enum('academic','non_academic') NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`organization_id`, `name`, `rank_id`, `type`, `logo`) VALUES
(1, 'BITS', 3, 'academic', 'img/logo/1_68daaf88b9f33.png');

-- --------------------------------------------------------

--
-- Table structure for table `organization_ranks`
--

CREATE TABLE `organization_ranks` (
  `rank_id` int(11) NOT NULL,
  `rank_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organization_ranks`
--

INSERT INTO `organization_ranks` (`rank_id`, `rank_name`) VALUES
(1, 'Member'),
(2, 'Treasurer'),
(3, 'Secretary'),
(4, 'Vice President'),
(5, 'President');

-- --------------------------------------------------------

--
-- Table structure for table `supporting_documents`
--

CREATE TABLE `supporting_documents` (
  `support_doc_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `template_id` int(11) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `content_html` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`template_id`, `template_name`, `content_html`, `created_at`) VALUES
(2, 'Accreditation form', '\r\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\r\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\r\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\r\n												Information Technology Society formally request approval to host an event\r\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\r\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\r\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\r\n												2nd-year BSIT students with a comprehensive understanding of the capstone\r\n												project development process. It aims to equip students with the fundamental\r\n												knowledge necessary before embarking on their capstone journey. Through\r\n												discussions on research methodologies, project design, and the development\r\n												process, this event will serve as a valuable guide in preparing students for\r\n												their future projects.<br><br>Thank you for considering our request. We\r\n												look forward to your positive response and hope to make this event a\r\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><font color=\"#000000\"><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\r\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\r\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br></font><strong>[FSSC_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\r\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\r\n												Student Affairs and Services<br><br><br><br><br></font></font>\r\n											</div>\r\n\r\n									', '2025-09-19 08:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `e_signature_path` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rank_id` int(11) DEFAULT NULL,
  `user_role` enum('academic_organization','non_academic_organization','adviser','dean','osas','fssc','vice_pres_academic_affairs') NOT NULL,
  `organization_id` int(11) DEFAULT NULL,
  `signature_base_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `e_signature_path`, `first_name`, `last_name`, `email`, `password_hash`, `rank_id`, `user_role`, `organization_id`, `signature_base_code`) VALUES
(1, NULL, 'acad', 'lead', 'acadlead@gmail.com', '$2y$10$rnufHBtG7.lXKzPKh6gAJO2OjfVY4X3k..69qv.gSolTHJyDsXTzS', 2, 'academic_organization', 1, NULL),
(8, NULL, 'sample', 'asdad', 'sample@gmail.com', '$2y$10$x8CkTfCkbJFKJhQdav5OyO0/waIwHE3R.HYorTyFgw21pP/2DITzW', 1, 'academic_organization', NULL, NULL),
(16, '../../../img/esig/signature_16.png', 'adviser', 'last', 'adviser@gmail.com', '$2y$10$rnufHBtG7.lXKzPKh6gAJO2OjfVY4X3k..69qv.gSolTHJyDsXTzS', NULL, 'adviser', 1, 'ADVW92QR'),
(17, '../../../img/esig/signature_17.png', 'dean', 'last', 'dean@gmail.com', '$2y$10$rnufHBtG7.lXKzPKh6gAJO2OjfVY4X3k..69qv.gSolTHJyDsXTzS', NULL, 'dean', NULL, 'DEAPMOGN'),
(18, '../../../img/esig/signature_18.png', 'fssc', 'last', 'fssc@gmail.com', '$2y$10$a5w1..gtGxpFO18b/cgEPu0m9YmwKmy3GX9ncZILZ74j0AIniw22C', NULL, 'fssc', NULL, 'FSS62AP7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `document_id` (`document_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `documents_ibfk_1` (`organization_id`),
  ADD KEY `documents_ibfk_2` (`event_id`),
  ADD KEY `documents_ibfk_3` (`user_id`);

--
-- Indexes for table `document_history`
--
ALTER TABLE `document_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `document_history_ibfk_1` (`document_id`),
  ADD KEY `document_history_ibfk_2` (`modified_by_user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `events_ibfk_1` (`organization_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `notifications_ibfk_1` (`user_id`),
  ADD KEY `notifications_ibfk_2` (`document_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`organization_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `organizations_ibfk_1` (`rank_id`);

--
-- Indexes for table `organization_ranks`
--
ALTER TABLE `organization_ranks`
  ADD PRIMARY KEY (`rank_id`);

--
-- Indexes for table `supporting_documents`
--
ALTER TABLE `supporting_documents`
  ADD PRIMARY KEY (`support_doc_id`),
  ADD KEY `supporting_documents_ibfk_1` (`document_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `signature_base_code` (`signature_base_code`),
  ADD KEY `users_ibfk_1` (`organization_id`),
  ADD KEY `fk_users_rank_id` (`rank_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `document_history`
--
ALTER TABLE `document_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `organization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organization_ranks`
--
ALTER TABLE `organization_ranks`
  MODIFY `rank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supporting_documents`
--
ALTER TABLE `supporting_documents`
  MODIFY `support_doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`organization_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `document_history`
--
ALTER TABLE `document_history`
  ADD CONSTRAINT `document_history_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_history_ibfk_2` FOREIGN KEY (`modified_by_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`organization_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_ibfk_1` FOREIGN KEY (`rank_id`) REFERENCES `organization_ranks` (`rank_id`);

--
-- Constraints for table `supporting_documents`
--
ALTER TABLE `supporting_documents`
  ADD CONSTRAINT `supporting_documents_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_rank_id` FOREIGN KEY (`rank_id`) REFERENCES `organization_ranks` (`rank_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`organization_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
