-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2025 at 05:59 PM
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
(30, 32, 16, 'noe', '2025-09-22 15:33:20'),
(31, 16, 17, 'make it bold text', '2025-09-22 15:56:12');

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
(16, '', 1, 'revision', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px; background-color: rgb(255, 255, 255);\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\n												greetings! I hope this letter finds you well.<br><br>The Bu<span style=\"background-color: rgb(255, 128, 0);\">ilders of\n												Information Tech</span>nology Society formally request approval to host an event&nbsp;titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone<b>\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before e</b>mbarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', 'page-export.pdf', '2025-09-17 22:34:25', '2025-09-22 23:56:14', NULL),
(17, '', 1, 'revision', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px;\"><span style=\"background-color: rgb(255, 255, 255);\"><font color=\"#08090d\">28 February 2025<br><br><b style=\"\">DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br><b style=\"\">Warmest\n												greetings! I hope this letter finds you </b>well.<br><br>The Builders of\n												Information <b style=\"\">Technology Society formally request approval to host </b>an event\n												titled <b style=\"\">\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b style=\"\">March 27, 2025</b>, from <b style=\"\">7:00 AM to 12:00 PM</b> at the\n												<b style=\"\">Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the <b style=\"\">capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on </b>their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b style=\"\">ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b style=\"\">MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b style=\"\">MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b style=\"\">VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b style=\"\"><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br></font></span><br></font>\n											</div>\n\n									', 'page-export.pdf', '2025-09-17 22:40:01', '2025-09-18 15:58:29', NULL),
(24, '', 1, 'draft', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px; background-color: rgb(255, 255, 255);\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br><b>Warmest\n												greetings! I hope this letter finds you </b>well.<br><br>The Builders of\n												Information <b>Technology Society formally request approval to host </b>an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the <b>capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on </b>their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', 'file.pdf', '2025-09-18 13:46:00', '0000-00-00 00:00:00', NULL),
(25, '', 1, 'revision', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br></font><div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><b>Warmest\n												greetings! I hope th</b>is letter finds you <b>well.</b></font><br></div><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This <span style=\"background-color: rgb(52, 209, 205);\">event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamenta</span>l\n												know<span style=\"background-color: rgb(128, 128, 255);\">ledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projec</span>ts.<br><br>Thank y<span style=\"background-color: rgb(0, 255, 0);\">ou for considering our </span>request.<span style=\"background-color: rgb(158, 121, 10);\"> We\n												look forward to your positive respons</span>e and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br></font>\n											</div>\n\n									', 'newdocfromTemplate.pdf', '2025-09-19 16:23:59', '2025-09-22 08:58:44', NULL),
(26, 'off_campus_activity', 1, 'draft', 1, '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#8000ff\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', 'bago.pdf', '2025-09-19 16:32:32', '0000-00-00 00:00:00', NULL),
(31, '-- Select Template --', 1, 'pending', 1, '\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', 'esigtry.pdf', '2025-09-22 22:27:14', '2025-09-22 23:21:40', NULL),
(32, 'bago', 1, 'pending', 1, '\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT stude<span style=\"background-color: rgb(0, 128, 64);\">nts with a comprehensive understanding of the capstone\n												project development </span>process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><font color=\"#000000\"><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br></font><strong>[FSSC_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font></font>\n											</div>\n\n									', 'complete.pdf', '2025-09-22 23:31:20', '2025-09-22 23:33:34', NULL);

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
  `timestamp` datetime NOT NULL,
  `e_signature_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_history`
--

INSERT INTO `document_history` (`history_id`, `document_id`, `from_status`, `to_status`, `modified_by_user_id`, `reason`, `old_content_html`, `timestamp`, `e_signature_code`) VALUES
(16, 14, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 09:45:32', NULL),
(17, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 09:54:54', NULL),
(18, 17, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 09:56:11', NULL),
(19, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 13:07:17', NULL),
(20, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 14:03:04', NULL),
(21, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 14:29:03', NULL),
(22, 17, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 15:20:41', NULL),
(24, 14, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 15:58:49', NULL),
(25, 16, 'draft', 'submitted', 1, NULL, NULL, '2025-09-18 15:58:56', NULL),
(28, 14, 'draft', 'submitted', 1, NULL, NULL, '2025-09-19 11:24:26', NULL),
(30, 25, 'draft', 'submitted', 1, NULL, NULL, '2025-09-19 16:24:19', NULL),
(31, 25, 'draft', 'submitted', 1, NULL, NULL, '2025-09-21 10:05:37', NULL),
(33, 25, 'draft', 'submitted', 1, NULL, NULL, '2025-09-21 15:42:49', NULL),
(35, 31, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:00:17', NULL),
(36, 31, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:00:31', NULL),
(37, 31, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:00:37', NULL),
(38, 31, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:00:41', NULL),
(39, 31, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:00:45', NULL),
(40, 31, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:00:51', NULL),
(41, 31, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:13:59', NULL),
(42, 31, 'submitted', 'endorsed', 16, NULL, NULL, '0000-00-00 00:00:00', 'ADVW92QR-65b6f5bf61'),
(43, 31, 'submitted', 'pending', 17, NULL, NULL, '0000-00-00 00:00:00', 'DEAPMOGN-4af3a34efe'),
(44, 32, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:33:03', NULL),
(45, 32, 'submitted', 'revision', 16, 'Commented for revision.', '\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\n												Information Technology Society formally request approval to host an event\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\n												2nd-year BSIT students with a comprehensive understanding of the capstone\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before embarking on their capstone journey. Through\n												discussions on research methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><font color=\"#000000\"><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br></font><strong>[FSSC_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font></font>\n											</div>\n\n									', '2025-09-22 23:33:34', NULL),
(46, 32, 'draft', 'submitted', 1, NULL, NULL, '2025-09-22 23:33:57', NULL),
(47, 32, 'submitted', 'endorsed', 16, NULL, NULL, '0000-00-00 00:00:00', 'ADVW92QR-2876b5e2c1'),
(49, 32, 'submitted', 'pending', 17, NULL, NULL, '0000-00-00 00:00:00', 'DEAPMOGN-5dae6fbe96'),
(50, 16, 'submitted', 'revision', 17, 'Commented for revision.', '\n										<div align=\"justify\">\n											<font face=\"Arial\" style=\"letter-spacing: 0px\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warm<span style=\"background-color: rgb(10, 251, 255);\">est\n												greetings! I hope this letter finds you wel</span>l.<br><br>The Builders of\n												Information T<span style=\"background-color: rgb(5, 251, 255);\">echnology Society formally request approval to host an event</span>\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\n												<b>Farmers Training Center</b>.<br><br>This event is designe<span style=\"background-color: rgb(0, 255, 0);\">d to provide\n												2nd-ye</span>ar BSIT students with a comprehensive understanding of the capstone<b>\n												project development process. It aims to equip students with the fundamental\n												knowledge necessary before e</b>mbarking on their capstone journey. Through\n												discussions on res<span style=\"background-color: rgb(0, 0, 255);\">earch methodologies, project design, and the development\n												process, this event will serve as a valuable guide in preparing students for\n												their future</span> projects.<br><br>Thank you for considering our request. We\n												look forward to your positive response and hope to make this event a\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br><br><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\n												Builders of Information Technology Society<br><br>Noted by:<br><br><br><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br><br><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\n												Student Affairs and Services<br><br><br><br><br></font>\n											</div>\n\n									', '2025-09-22 23:56:14', NULL);

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
(13, 1, 16, 'A new comment has been added to your document by adviser .', 1, '2025-09-18 11:48:14'),
(15, 1, 16, 'A new comment has been added to your document by adviser .', 0, '2025-09-18 14:01:11'),
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
(29, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:37:53'),
(30, 1, 16, 'A new comment has been added to your document by adviser.', 0, '2025-09-18 14:38:08'),
(31, 1, 16, 'A new comment has been added to your document by adviser.', 1, '2025-09-18 14:40:13'),
(32, 1, 16, 'A new comment has been added to your document by adviser.', 1, '2025-09-18 14:53:52'),
(33, 1, 17, 'A new comment has been added to your document by dean.', 1, '2025-09-18 15:17:10'),
(43, 1, 25, 'A new comment has been added to your document by adviser.', 0, '2025-09-21 10:03:15'),
(46, 1, 25, 'A new comment has been added to your document by adviser.', 0, '2025-09-21 15:43:26'),
(47, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:28:55'),
(48, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:37:53'),
(49, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:38:28'),
(50, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:41:35'),
(51, 1, 16, 'A new comment has been added to your document by dean.', 0, '2025-09-22 09:46:35'),
(52, 1, 16, 'A new comment has been added to your document by dean.', 1, '2025-09-22 09:47:41'),
(53, 16, 31, 'A new document has been submitted for your approval.', 0, '2025-09-22 23:13:59'),
(55, 17, 31, 'A new document titled \'esigtry.pdf\' has been submitted for your review.', 0, '2025-09-22 23:27:20'),
(56, 16, 32, 'A new document has been submitted for your approval.', 0, '2025-09-22 23:33:03'),
(57, 1, 32, 'A new comment has been added to your document by adviser.', 0, '2025-09-22 23:33:20'),
(58, 16, 32, 'A new document has been submitted for your approval.', 0, '2025-09-22 23:33:57'),
(59, 17, 32, 'A new document titled \'complete.pdf\' has been submitted for your review.', 0, '2025-09-22 23:35:15'),
(60, 17, 32, 'A new document titled \'complete.pdf\' has been submitted for your review.', 0, '2025-09-22 23:37:17'),
(61, 17, 32, 'A new document titled \'complete.pdf\' has been submitted for your review.', 1, '2025-09-22 23:39:57'),
(62, 1, 16, 'A new comment has been added to your document by dean.', 1, '2025-09-22 23:56:12');

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
(2, 'bagotemplate', '\r\n										<div align=\"justify\"><font face=\"Arial\" style=\"letter-spacing: 0px\" color=\"#000000\">28 February 2025<br><br><b>DR. CECILIA S. SANTIAGO</b><br>Vice\r\n												President, Academic Affairs<br>This College<br><br><br>Madame,<br><br>Warmest\r\n												greetings! I hope this letter finds you <b>well.</b><br><br>The Builders of\r\n												Information Technology Society formally request approval to host an event\r\n												titled <b>\"From Vision to Reality: The Capstone Adventure Begins”</b>,\r\n												scheduled for <b>March 27, 2025</b>, from <b>7:00 AM to 12:00 PM</b> at the\r\n												<b>Farmers Training Center</b>.<br><br>This event is designed to provide\r\n												2nd-year BSIT students with a comprehensive understanding of the capstone\r\n												project development process. It aims to equip students with the fundamental\r\n												knowledge necessary before embarking on their capstone journey. Through\r\n												discussions on research methodologies, project design, and the development\r\n												process, this event will serve as a valuable guide in preparing students for\r\n												their future projects.<br><br>Thank you for considering our request. We\r\n												look forward to your positive response and hope to make this event a\r\n												memorable occasion for our IT community.<br><br>Respectfully yours:<br><br><b>ANGELO LAURENTE</b><br>OIC President, Builders of Information Technology Society<br></font><font color=\"#000000\"><strong>[ADVISER_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MA. MELANIE ABLAZA-CRUZ, DIT</b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\r\n												&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>Adviser,\r\n												Builders of Information Technology Society<br><br>Noted by:<br><br></font><strong>[DEAN_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>MICHELLE M. CORTEZ, MIT</b><br>Dean, Institute of Engineering and Applied Technology<br></font><strong>[FSSC_SIGNATURE]</strong><br><font face=\"Arial\" style=\"letter-spacing: 0px\"><b>VLADIMIR C. SEMPIO, RN, MSN</b><br>Head, Student Development Programs Unit<br><br>Recommending\r\n												Approval:<br><b><br>JENNIFER P. ADRIANO, Ph.D.</b><br>Director,\r\n												Student Affairs and Services<br><br><br><br><br></font></font>\r\n											</div>\r\n\r\n									', '2025-09-19 08:31:59');

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
  `organization_id` int(11) DEFAULT NULL,
  `signature_base_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password_hash`, `user_role`, `organization_id`, `signature_base_code`) VALUES
(1, 'acad', 'lead', 'acadlead@gmail.com', '$2y$10$rnufHBtG7.lXKzPKh6gAJO2OjfVY4X3k..69qv.gSolTHJyDsXTzS', 'academic_organization', 1, NULL),
(6, 'fssc', 'ssss', 'fssc@gmail.com', '$2y$10$sfxMLP.ciJKd/Ksi7XlwMenR7QQWCK3.dpP7KPr6FldIcukYKaDMu', 'fssc', NULL, NULL),
(8, 'sample', 'asdad', 'sample@gmail.com', '$2y$10$x8CkTfCkbJFKJhQdav5OyO0/waIwHE3R.HYorTyFgw21pP/2DITzW', 'academic_organization', 1, NULL),
(16, 'adviser', 'last', 'adviser@gmail.com', '$2y$10$HOZv8PvDlOl8SBl0ecv81uzXzkZ.x/TQFkMfQJguoNn6b020czR0e', 'adviser', 1, 'ADVW92QR'),
(17, 'dean', 'last', 'dean@gmail.com', '$2y$10$xHOscN405v5f6bSfy0zqV.j7JJtWa14GxiExtsgRVVQYiXjc/z0Hu', 'dean', NULL, 'DEAPMOGN');

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
  ADD UNIQUE KEY `signature_base_code` (`signature_base_code`),
  ADD KEY `users_ibfk_1` (`organization_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `document_history`
--
ALTER TABLE `document_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
