-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2025 at 05:29 AM
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
(2, 17, 3, 'wrong format', '2025-09-18 03:35:25'),
(3, 17, 3, 'wrong text color', '2025-09-18 03:44:16'),
(16, 16, 3, 'hello world', '2025-09-18 06:21:10'),
(21, 17, 5, 'wrong text color', '2025-09-18 07:17:10'),
(22, 25, 3, 'no nono', '2025-09-21 02:03:15'),
(23, 25, 3, 'nsnsnsnsns', '2025-09-21 07:43:26'),
(24, 16, 5, 'hello', '2025-09-22 01:28:55'),
(26, 16, 5, 'eee', '2025-09-22 01:38:28');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `status` enum('draft','submitted','pending','rejected','revision','endorsed','approved') NOT NULL,
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
(14, '', 1, 'endorsed', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#8000ff\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', 'page-export.pdf', '2025-09-17 15:31:50', '2025-09-18 15:58:20', NULL),
(16, '', 1, 'endorsed', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warm<span style=\"background-color: rgb(10, 251, 255);\">est\n												greetings! I hope this letter finds you wel</span>l.<br><br>The Builders of\n												Information T<span style=\"background-color: rgb(5, 251, 255);\">echnology Society formally request approval to host an event</span>\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designe<span style=\"background-color: rgb(0, 255, 0);\">d to provide\n												2nd-ye</span>ar BSIT students with a comprehensive understanding of the capstone<b>\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before e</b>mbarking on their capstone journey. Through\n												discussions on res<span style=\"background-color: rgb(0, 0, 255);\">earch methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future</span> projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', 'page-export.pdf', '2025-09-17 22:34:25', '2025-09-18 15:58:24', NULL),
(17, '', 1, 'revision', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px;\"><span style=\"background-color: rgb(255, 255, 255);\"><font color=\"#08090d\">28 February 2025<br><br><b style=\"\">DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br><b style=\"\">Warmest\n												greetings! I hope this letter finds you </b>well.<br><br>The Builders of\n												Information <b style=\"\">Technology Society formally request approval to host </b>an event\n												titled <b style=\"\">\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b style=\"\">March 27, 2025</b>, from <b style=\"\">7:00 AM to 12:00 PM</b> at the\n												<b style=\"\">Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the <b style=\"\">capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on </b>their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b style=\"\">ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b style=\"\">MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b style=\"\">MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b style=\"\">VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b style=\"\"><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br></font></span><br></font>\n											</div>\n\n									', 'page-export.pdf', '2025-09-17 22:40:01', '2025-09-18 15:58:29', NULL),
(24, '', 1, 'draft', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px; background-color: rgb(255, 255, 255);\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br><b>Warmest\n												greetings! I hope this letter finds you </b>well.<br><br>The Builders of\n												Information <b>Technology Society formally request approval to host </b>an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the <b>capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on </b>their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', 'file.pdf', '2025-09-18 13:46:00', '0000-00-00 00:00:00', NULL),
(25, '', 1, 'revision', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br></font><div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><b>Warmest\n												greetings! I hope th</b>is letter finds you <b>well.</b></font><br></div><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This <span style=\"background-color: rgb(52, 209, 205);\">event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamenta</span>l\n												know<span style=\"background-color: rgb(128, 128, 255);\">ledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projec</span>ts.<br><br>Thank y<span style=\"background-color: rgb(0, 255, 0);\">ou for considering our </span>request.<span style=\"background-color: rgb(158, 121, 10);\"> We\n												look forward to your positive respons</span>e and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br></font>\n											</div>\n\n									', 'newdocfromTemplate.pdf', '2025-09-19 16:23:59', '2025-09-22 08:58:44', NULL),
(26, 'off_campus_activity', 1, 'draft', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#8000ff\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', 'bago.pdf', '2025-09-19 16:32:32', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document_history`
--

CREATE TABLE `document_history` (
  `history_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `from_status` enum('draft','submitted','pending','rejected','revision','endorsed','approved') NOT NULL,
  `to_status` enum('draft','submitted','pending','rejected','revision','endorsed','approved') NOT NULL,
  `modified_by_user_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `old_content_html` longtext DEFAULT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_history`
--

INSERT INTO `document_history` (`history_id`, `document_id`, `from_status`, `to_status`, `modified_by_user_id`, `reason`, `old_content_html`, `timestamp`) VALUES
(16, 14, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 09:45:32'),
(17, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 09:54:54'),
(18, 17, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 09:56:11'),
(19, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 13:07:17'),
(20, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 14:03:04'),
(21, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 14:29:03'),
(22, 17, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 15:20:41'),
(23, 16, 'submitted', 'endorsed', 3, NULL, NULL, '0000-00-00 00:00:00'),
(24, 14, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 15:58:49'),
(25, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 15:58:56'),
(26, 14, 'submitted', 'endorsed', 3, NULL, NULL, '0000-00-00 00:00:00'),
(27, 16, 'submitted', 'endorsed', 3, NULL, NULL, '0000-00-00 00:00:00'),
(28, 14, 'draft', 'submitted', 1, NULL, NULL, '2025-09-19 11:24:26'),
(29, 14, 'submitted', 'endorsed', 3, NULL, NULL, '0000-00-00 00:00:00'),
(30, 25, 'draft', 'submitted', 1, NULL, NULL, '2025-09-19 16:24:19'),
(31, 25, 'draft', 'submitted', 1, NULL, NULL, '2025-09-21 10:05:37'),
(32, 25, '', '', 3, 'Content updated by creator.', '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br></font><div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><b>Warmest\n												greetings! I hope th</b>is letter finds you <b>well.</b></font><br></div><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This <span style=\"background-color: rgb(52, 209, 205);\">event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamenta</span>l\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank y<span style=\"background-color: rgb(0, 255, 0);\">ou for considering our </span>request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br></font>\n											</div>\n\n									', '2025-09-21 10:05:51'),
(33, 25, 'draft', 'submitted', 1, NULL, NULL, '2025-09-21 15:42:49'),
(34, 25, 'submitted', 'revision', 3, 'Content updated by creator.', '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br></font><div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><b>Warmest\n												greetings! I hope th</b>is letter finds you <b>well.</b></font><br></div><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This <span style=\"background-color: rgb(52, 209, 205);\">event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamenta</span>l\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank y<span style=\"background-color: rgb(0, 255, 0);\">ou for considering our </span>request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br></font>\n											</div>\n\n									', '2025-09-21 15:43:28');

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
(10, 3, 14, 'A new document has been submitted for your approval.', 1, '2025-09-18 09:45:32'),
(11, 3, 16, 'A new document has been submitted for your approval.', 1, '2025-09-18 09:54:54'),
(12, 3, 17, 'A new document has been submitted for your approval.', 1, '2025-09-18 09:56:11'),
(13, 1, 16, 'A new comment has been added to your document by adviser .', 1, '2025-09-18 11:48:14'),
(14, 3, 16, 'A new document has been submitted for your approval.', 1, '2025-09-18 13:07:17'),
(15, 1, 16, 'A new comment has been added to your document by adviser .', 0, '2025-09-18 14:01:11'),
(16, 3, 16, 'A new document has been submitted for your approval.', 1, '2025-09-18 14:03:04'),
(17, 1, 16, 'A new comment has been added to your document by adviser .', 0, '2025-09-18 14:03:26'),
(18, 1, 16, 'A new comment has been added to your document by adviser .', 0, '2025-09-18 14:03:28'),
(19, 1, 16, 'A new comment has been added to your document by adviser .', 0, '2025-09-18 14:04:11'),
(20, 1, 16, 'A new comment has been added to your document by adviser .', 0, '2025-09-18 14:04:31'),
(21, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:05:20'),
(22, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:06:37'),
(23, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:08:41'),
(24, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:08:58'),
(25, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:11:19'),
(26, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:11:49'),
(27, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:21:10'),
(28, 3, 16, 'A new document has been submitted for your approval.', 1, '2025-09-18 14:29:03'),
(29, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:37:53'),
(30, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:38:08'),
(31, 1, 16, 'A new comment has been added to your document by adviser.', 1, '2025-09-18 14:40:13'),
(32, 1, 16, 'A new comment has been added to your document by adviser.', 1, '2025-09-18 14:53:52'),
(33, 1, 17, 'A new comment has been added to your document by dean.', 1, '2025-09-18 15:17:10'),
(34, 3, 17, 'A new document has been submitted for your approval.', 1, '2025-09-18 15:20:41'),
(35, 5, 16, 'A new document titled \'page-export.pdf\' has been submitted for your review.', 1, '0000-00-00 00:00:00'),
(36, 3, 14, 'A new document has been submitted for your approval.', 1, '2025-09-18 15:58:49'),
(37, 3, 16, 'A new document has been submitted for your approval.', 1, '2025-09-18 15:58:56'),
(38, 5, 14, 'A new document titled \'page-export.pdf\' has been submitted for your review.', 1, '0000-00-00 00:00:00'),
(39, 5, 16, 'A new document titled \'page-export.pdf\' has been submitted for your review.', 1, '2025-09-18 16:03:29'),
(40, 3, 14, 'A new document has been submitted for your approval.', 1, '2025-09-19 11:24:26'),
(41, 5, 14, 'A new document titled \'page-export.pdf\' has been submitted for your review.', 1, '2025-09-19 11:25:34'),
(42, 3, 25, 'A new document has been submitted for your approval.', 1, '2025-09-19 16:24:19'),
(43, 1, 25, 'A new comment has been added to your document by adviser.', 0, '2025-09-21 10:03:15'),
(44, 3, 25, 'A new document has been submitted for your approval.', 1, '2025-09-21 10:05:37'),
(45, 3, 25, 'A new document has been submitted for your approval.', 1, '2025-09-21 15:42:49'),
(46, 1, 25, 'A new comment has been added to your document by adviser.', 0, '2025-09-21 15:43:26'),
(47, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:28:55'),
(48, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:37:53'),
(49, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:38:28'),
(50, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:41:35'),
(51, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:46:35'),
(52, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `organization_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('academic','non_academic') NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`organization_id`, `name`, `type`, `logo`) VALUES
(1, 'BITS', 'academic', 'img/logo/1_68ccccc68ae8f.png');

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
(1, 'template test', '\r\n										<div align=\"justify\">\r\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\r\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br></font><div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><b>Warmest\r\n												greetings! I hope th</b>is letter finds you <b>well.</b></font><br></div><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><br>The Builders of\r\n												Information Technology Society formally request approval to host an event\r\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\r\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\r\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\r\n												2nd-year BSIT students with a comprehensive understanding of the capstone\r\n												project development process. It aims to equip students with the fundamental\r\n												knowledge necessary before embarking on their capstone journey. Through\r\n												discussions on research methodologies, project design, and the development\r\n												process, this event will serve as a valuable guide in preparing students for\r\n												their future projects.<br><br>Thank y<span style=\"background-color: rgb(0, 255, 0);\">ou for considering our </span>request. We\r\n												look forward to your positive response and hope to make this event a\r\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\r\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\r\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\r\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\r\n												Student Affairs and Services<br><br><br></font>\r\n											</div>\r\n\r\n									', '2025-09-19 08:05:03'),
(2, 'bago', '\r\n										<div align=\"justify\">\r\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#8000ff\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\r\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\r\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\r\n												Information Technology Society formally request approval to host an event\r\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\r\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\r\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\r\n												2nd-year BSIT students with a comprehensive understanding of the capstone\r\n												project development process. It aims to equip students with the fundamental\r\n												knowledge necessary before embarking on their capstone journey. Through\r\n												discussions on research methodologies, project design, and the development\r\n												process, this event will serve as a valuable guide in preparing students for\r\n												their future projects.<br><br>Thank you for considering our request. We\r\n												look forward to your positive response and hope to make this event a\r\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\r\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\r\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\r\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\r\n												Student Affairs and Services<br><br><br><br><br></font>\r\n											</div>\r\n\r\n									', '2025-09-19 08:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `user_role` enum('academic_organization','non_academic_organization','adviser','dean','osas','fssc','vice_pres_academic_affairs') NOT NULL,
  `organization_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password_hash`, `user_role`, `organization_id`) VALUES
(1, 'acad', 'lead', 'acadlead@gmail.com', '$2y$10$rnufHBtG7.lXKzPKh6gAJO2OjfVY4X3k..69qv.gSolTHJyDsXTzS', 'academic_organization', 1),
(3, 'adviser', 'asdas', 'adviser@gmail.com', '$2y$10$RpeYpwueeVfaSKBsTR/he.uLicjcDX7VMU9uP3UiLZ9xIPe7GuMqm', 'adviser', 1),
(5, 'dean', 'asda', 'dean@gmail.com', '$2y$10$aAvS6XtOZN1J3VFU6PQF7O5GLKNF2QAOeYW9pCjtFOOMBkdM6BSOm', 'dean', NULL),
(6, 'fssc', 'ssss', 'fssc@gmail.com', '$2y$10$sfxMLP.ciJKd/Ksi7XlwMenR7QQWCK3.dpP7KPr6FldIcukYKaDMu', 'fssc', NULL),
(8, 'sample', 'asdad', 'sample@gmail.com', '$2y$10$x8CkTfCkbJFKJhQdav5OyO0/waIwHE3R.HYorTyFgw21pP/2DITzW', 'academic_organization', 1);

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
  ADD UNIQUE KEY `name` (`name`);

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
  ADD KEY `users_ibfk_1` (`organization_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `document_history`
--
ALTER TABLE `document_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `organization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`organization_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
