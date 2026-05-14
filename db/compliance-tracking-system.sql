-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2026 at 08:31 PM
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
-- Database: `compliance-tracking-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `severity` enum('info','warning','critical') DEFAULT 'info',
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `task_id`, `log_time`, `ip_address`, `user_agent`, `module`, `severity`, `status`) VALUES
(1, 3, 'Created a task', 1, '2026-05-10 20:00:49', NULL, NULL, NULL, 'info', NULL),
(2, 3, 'Updated task status to Not Started', 1, '2026-05-10 20:07:18', NULL, NULL, NULL, 'info', NULL),
(3, 3, 'Updated task status to ', 0, '2026-05-10 20:59:47', NULL, NULL, NULL, 'info', NULL),
(4, 3, 'Updated task status to In Progress (Task ID: 1)', 1, '2026-05-10 21:09:42', NULL, NULL, NULL, 'info', NULL),
(5, 3, 'Submitted incident report', NULL, '2026-05-11 18:08:55', NULL, NULL, NULL, 'info', NULL),
(6, 3, 'Submitted incident report', NULL, '2026-05-11 18:09:43', NULL, NULL, NULL, 'info', NULL),
(7, 3, 'SUCCESS LOGIN from IP: ::1', NULL, '2026-05-13 12:43:57', NULL, NULL, NULL, 'info', NULL),
(8, 2, 'SUCCESS LOGIN from IP: ::1', NULL, '2026-05-13 12:44:12', NULL, NULL, NULL, 'info', NULL),
(9, 1, 'SUCCESS LOGIN from IP: ::1', NULL, '2026-05-13 12:44:54', NULL, NULL, NULL, 'info', NULL),
(10, 3, 'FAILED LOGIN (1/3) from IP: ::1', NULL, '2026-05-13 12:53:56', NULL, NULL, NULL, 'info', NULL),
(11, 3, 'FAILED LOGIN (2/3) from IP: ::1', NULL, '2026-05-13 12:54:07', NULL, NULL, NULL, 'info', NULL),
(12, 3, 'FAILED LOGIN (3/3) from IP: ::1', NULL, '2026-05-13 12:56:06', NULL, NULL, NULL, 'info', NULL),
(13, 3, 'ACCOUNT LOCKED + EMAIL SENT', NULL, '2026-05-13 12:56:08', NULL, NULL, NULL, 'info', NULL),
(14, 1, 'FAILED LOGIN (1/3) from IP: ::1', NULL, '2026-05-13 13:02:06', NULL, NULL, NULL, 'info', NULL),
(15, 1, 'FAILED LOGIN (2/3) from IP: ::1', NULL, '2026-05-13 13:02:36', NULL, NULL, NULL, 'info', NULL),
(16, 1, 'FAILED LOGIN (3/3) from IP: ::1', NULL, '2026-05-13 13:04:47', NULL, NULL, NULL, 'info', NULL),
(17, 3, 'Updated task status to Not Started', 1, '2026-05-14 18:26:10', NULL, NULL, NULL, 'info', NULL),
(18, 3, 'Updated task status to In Progress', 1, '2026-05-14 18:26:13', NULL, NULL, NULL, 'info', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `compliance_records`
--

CREATE TABLE `compliance_records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `policy_id` int(11) DEFAULT NULL,
  `compliance_status` enum('Compliant','Non-Compliant','Pending') DEFAULT 'Pending',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compliance_records`
--

INSERT INTO `compliance_records` (`id`, `user_id`, `policy_id`, `compliance_status`, `updated_at`) VALUES
(1, 3, 1, 'Compliant', '2026-05-10 18:32:37'),
(2, 3, 2, 'Pending', '2026-05-10 18:32:37'),
(3, 3, 3, 'Non-Compliant', '2026-05-10 18:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `employee_compliance`
--

CREATE TABLE `employee_compliance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `severity` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `date_reported` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_reports`
--

INSERT INTO `incident_reports` (`id`, `user_id`, `title`, `description`, `severity`, `status`, `proof_image`, `date_reported`) VALUES
(1, 3, '', '', '', 'Pending', '', '2026-05-11 18:08:55'),
(2, 3, '', '', '', 'Pending', '', '2026-05-11 18:09:43');

-- --------------------------------------------------------

--
-- Table structure for table `policies`
--

CREATE TABLE `policies` (
  `id` int(11) NOT NULL,
  `policy_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `policies`
--

INSERT INTO `policies` (`id`, `policy_name`, `description`, `status`) VALUES
(1, 'Password Policy', 'Users must update passwords every 90 days', 'Active'),
(2, 'Data Privacy Policy', 'Users must protect sensitive information', 'Active'),
(3, 'Device Security Policy', 'Devices must use antivirus software', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `security_events`
--

CREATE TABLE `security_events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('Not Started','In Progress','Completed') DEFAULT 'Not Started',
  `department` varchar(100) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `priority`, `deadline`, `status`, `department`, `assigned_to`, `created_at`) VALUES
(1, 'nyek', 'Medium', '2026-05-13', 'In Progress', 'IT', 3, '2026-05-10 20:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) DEFAULT NULL,
  `username` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','security','employee') NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `violation_count` int(11) DEFAULT 0,
  `is_locked` tinyint(1) DEFAULT 0,
  `login_attempts` int(11) DEFAULT 0,
  `last_login_ip` varchar(50) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `must_change_password` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `email`, `contact_number`, `password`, `role`, `department`, `violation_count`, `is_locked`, `login_attempts`, `last_login_ip`, `last_login_at`, `must_change_password`) VALUES
(3, NULL, 'jade', 'employee@gmail.com', '', 'Password123', 'employee', NULL, 0, 0, 0, NULL, NULL, 1),
(5, NULL, 'admin', 'admin@gmail.com', '', 'admin123', 'admin', NULL, 0, 0, 0, NULL, NULL, 1),
(6, 'IT-101', 'Fiona Jade Villanueva', 'jadee.fiona4@gmail.com', '09166314087', 'employee123', 'employee', 'IT', 0, 0, 0, NULL, NULL, 1),
(12, 'IT-EMP-00012', 'John Doe', 'piyoacadnotes@gmail.com', '09123456789', 'password123', 'employee', 'IT', 0, 0, 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_policies`
--

CREATE TABLE `user_policies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_id` varchar(20) DEFAULT NULL,
  `violation_type` varchar(255) DEFAULT NULL,
  `severity` enum('Low','Medium','High') DEFAULT NULL,
  `status` enum('Open','Resolved') DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `user_id`, `employee_id`, `violation_type`, `severity`, `status`, `created_at`) VALUES
(1, 3, NULL, 'Unauthorized File Access', 'High', 'Open', '2026-05-10 18:32:37'),
(2, 3, NULL, 'Password Expired', 'Medium', 'Resolved', '2026-05-10 18:32:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `compliance_records`
--
ALTER TABLE `compliance_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `policy_id` (`policy_id`);

--
-- Indexes for table `employee_compliance`
--
ALTER TABLE `employee_compliance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `policies`
--
ALTER TABLE `policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_events`
--
ALTER TABLE `security_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- Indexes for table `user_policies`
--
ALTER TABLE `user_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `compliance_records`
--
ALTER TABLE `compliance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_compliance`
--
ALTER TABLE `employee_compliance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `policies`
--
ALTER TABLE `policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `security_events`
--
ALTER TABLE `security_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_policies`
--
ALTER TABLE `user_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `compliance_records`
--
ALTER TABLE `compliance_records`
  ADD CONSTRAINT `compliance_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `compliance_records_ibfk_2` FOREIGN KEY (`policy_id`) REFERENCES `policies` (`id`);

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
