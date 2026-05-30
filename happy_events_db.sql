-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2026 at 03:09 AM
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
-- Database: `happy_events_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_ref` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_type_id` int(11) DEFAULT NULL,
  `event_title` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `venue_address` text DEFAULT NULL,
  `venue_location` varchar(100) DEFAULT NULL,
  `number_of_guests` int(11) DEFAULT NULL,
  `budget_range` varchar(50) DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `venue_fee` decimal(10,2) DEFAULT NULL,
  `admin_remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_ref`, `user_id`, `event_type_id`, `event_title`, `event_date`, `event_time`, `venue_address`, `venue_location`, `number_of_guests`, `budget_range`, `additional_notes`, `status`, `total_amount`, `venue_fee`, `admin_remarks`, `created_at`) VALUES
(1, 'HE202605252848', 1, 2, 'kasalang bayan', '2026-05-27', '01:47:00', 'dadaduodhuhdoha', 'Laguna Area', 50, '₱500,000+', 'addawdwadawda', 'approved', NULL, 2000.00, 'Booking accepted by admin.', '2026-05-25 15:47:14'),
(2, 'HE202605253167', 1, 2, 'kasal ', '2026-05-26', '23:49:00', 'wdwdwdadad', 'Laguna Area', 100, '₱500,000+', 'adwaddwada', 'approved', NULL, 2000.00, 'Booking accepted by admin.', '2026-05-25 15:49:24'),
(3, 'HE202605269607', 53, 1, 'birthday ', '2026-04-30', '12:25:00', 'sadawdawda', 'Laguna Area', 100, 'PHP 10,000 - PHP 50,000', 'DADAWDWADWAD', 'approved', NULL, 2000.00, 'Booking accepted by admin.', '2026-05-25 16:26:20'),
(4, 'HE202605299533', 1, 1, 'birthday ', '2026-05-30', '23:59:00', 'sa pslsp', 'Quezon Province', 100, 'PHP 10,000 - PHP 50,000', 'ajhhuahkaa', 'approved', NULL, 5000.00, 'Booking accepted by admin.', '2026-05-29 14:59:45'),
(5, 'HE202605304666', 56, 3, 'Reunion sa PLSP', '2026-06-01', '00:00:00', 'plsp', 'Within San Pablo', 500, 'PHP 500,000+', 'hehe\r\n', 'approved', NULL, 0.00, 'Booking accepted by admin.', '2026-05-29 17:00:20'),
(6, 'HE202605302280', 56, 5, 'Baptism ', '2026-05-31', '07:00:00', 'dadihwhoduhawld', 'Within San Pablo', 20, 'PHP 200,000 - PHP 500,000', '', 'approved', NULL, 0.00, 'Booking accepted by admin.', '2026-05-29 18:55:51'),
(7, 'HE202605301315', 57, 1, 'birthday ', '2026-05-31', '08:33:00', 'hdjasgdgsajd', 'Laguna Area', 1000, 'PHP 50,000 - PHP 100,000', 'hdahsahdsdskajdsa', 'pending', NULL, 2000.00, NULL, '2026-05-30 00:28:27'),
(8, 'HE202605304729', 58, 3, 'Reunion sa PLSP', '2026-07-16', '08:56:00', 'jkhfgfddfghjklhgf', 'Laguna Area', 366, 'PHP 200,000 - PHP 500,000', 'jhghjhj', 'approved', NULL, 2000.00, 'Booking accepted by admin.', '2026-05-30 00:57:35');

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price_at_booking` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_services`
--

INSERT INTO `booking_services` (`booking_id`, `service_id`, `quantity`, `price_at_booking`) VALUES
(1, 1, 1, 5000.00),
(1, 2, 1, 7000.00),
(1, 3, 1, 15000.00),
(1, 4, 1, 10000.00),
(1, 5, 1, 8000.00),
(1, 6, 1, 15000.00),
(1, 7, 1, 12000.00),
(1, 8, 1, 25000.00),
(1, 9, 1, 8000.00),
(1, 10, 1, 10000.00),
(1, 11, 1, 20000.00),
(1, 12, 1, 45000.00),
(1, 13, 1, 7000.00),
(1, 14, 1, 10000.00),
(2, 1, 1, 5000.00),
(2, 2, 1, 7000.00),
(2, 3, 1, 15000.00),
(2, 4, 1, 10000.00),
(2, 5, 1, 8000.00),
(2, 6, 1, 15000.00),
(2, 7, 1, 12000.00),
(2, 8, 1, 25000.00),
(2, 9, 1, 8000.00),
(2, 10, 1, 10000.00),
(2, 11, 1, 20000.00),
(2, 12, 1, 45000.00),
(2, 13, 1, 7000.00),
(2, 14, 1, 10000.00),
(3, 1, 1, 5000.00),
(3, 4, 1, 10000.00),
(3, 9, 1, 8000.00),
(3, 11, 1, 20000.00),
(4, 1, 1, 5000.00),
(4, 3, 1, 15000.00),
(4, 4, 1, 10000.00),
(4, 11, 1, 20000.00),
(5, 1, 1, 5000.00),
(5, 2, 1, 7000.00),
(5, 3, 1, 15000.00),
(5, 4, 1, 10000.00),
(5, 5, 1, 8000.00),
(5, 6, 1, 15000.00),
(5, 7, 1, 12000.00),
(5, 8, 1, 25000.00),
(5, 9, 1, 8000.00),
(5, 10, 1, 10000.00),
(5, 11, 1, 20000.00),
(5, 12, 1, 45000.00),
(5, 13, 1, 7000.00),
(5, 14, 1, 10000.00),
(5, 15, 1, 50000.00),
(6, 9, 1, 8000.00),
(6, 10, 1, 10000.00),
(6, 12, 1, 45000.00),
(6, 14, 1, 10000.00),
(6, 15, 1, 50000.00),
(7, 10, 1, 10000.00),
(7, 15, 1, 50000.00),
(8, 2, 1, 7000.00),
(8, 3, 1, 15000.00),
(8, 5, 1, 8000.00),
(8, 10, 1, 10000.00),
(8, 12, 1, 45000.00),
(8, 15, 1, 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE `event_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_types`
--

INSERT INTO `event_types` (`id`, `name`, `description`, `is_active`) VALUES
(1, 'Birthday / Debut', 'Celebrate your special day', 1),
(2, 'Wedding / Anniversary', 'Love and commitment celebrations', 1),
(3, 'Reunion', 'Family and friends gathering', 1),
(4, 'Corporate Event', 'Business and professional events', 1),
(5, 'Baptism', 'Spiritual celebration', 1),
(6, 'Graduation Party', 'Academic achievement celebration', 1),
(7, 'Other Events', 'Custom events', 1);

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `meeting_date` date DEFAULT NULL,
  `meeting_time` time DEFAULT NULL,
  `meeting_venue` varchar(255) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `admin_remarks` text DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `notification_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `booking_id`, `title`, `message`, `is_read`, `notification_type`, `created_at`) VALUES
(1, 1, 2, 'Booking Submitted', 'Your booking request has been submitted for approval.', 1, 'booking', '2026-05-25 15:49:24'),
(2, 53, 3, 'Booking Submitted', 'Your booking request has been submitted for approval.', 0, 'booking', '2026-05-25 16:26:20'),
(3, 1, 4, 'Booking Submitted', 'Your booking request has been submitted for approval.', 1, 'booking', '2026-05-29 14:59:45'),
(4, 56, 5, 'Booking Submitted', 'Your booking request has been submitted for approval.', 0, 'booking', '2026-05-29 17:00:20'),
(5, 56, 6, 'Booking Submitted', 'Your booking request has been submitted for approval.', 0, 'booking', '2026-05-29 18:55:51'),
(6, 57, 7, 'Booking Submitted', 'Your booking request has been submitted for approval.', 1, 'booking', '2026-05-30 00:28:27'),
(7, 58, 8, 'Booking Submitted', 'Your booking request has been submitted for approval.', 0, 'booking', '2026-05-30 00:57:35');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `quotation_number` varchar(20) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `venue_fee` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `category`, `is_active`) VALUES
(1, 'Emcee Only', 'Professional event host', 5000.00, 'Hosting', 1),
(2, 'Emcee with Singing', 'Host with singing performance', 7000.00, 'Hosting', 1),
(3, 'Live Band', 'Full band performance', 15000.00, 'Entertainment', 1),
(4, 'Acoustic Band', 'Acoustic music session', 10000.00, 'Entertainment', 1),
(5, 'Sound System Basic', 'Small event audio setup', 8000.00, 'Audio', 1),
(6, 'Sound System Premium', 'Large event audio setup', 15000.00, 'Audio', 1),
(7, 'LED Wall Small', 'Basic LED display', 12000.00, 'Visual', 1),
(8, 'LED Wall Large', 'Full LED wall setup', 25000.00, 'Visual', 1),
(9, 'Photography', 'Professional photo coverage', 8000.00, 'Media', 1),
(10, 'Videography', 'Professional video coverage', 10000.00, 'Media', 1),
(11, 'Catering Basic', 'Food for 50 persons', 20000.00, 'Catering', 1),
(12, 'Catering Premium', 'Food for 100 persons', 45000.00, 'Catering', 1),
(13, 'Lights and Effects', 'Event lighting setup', 7000.00, 'Production', 1),
(14, 'Venue Decoration', 'Basic event decoration', 10000.00, 'Decoration', 1),
(15, 'PhotoBooth', 'test', 50000.00, 'Wedding', 1),
(17, 'chloe massage', 'trtyuguihiuvuhigu', 1000.00, 'Wedding', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'default.jpg',
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','disabled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `address`, `contact_number`, `email`, `username`, `password`, `profile_picture`, `role`, `status`, `created_at`) VALUES
(1, 'chloemaranan', 'san pablo city', '09304524378', 'Chloemaranan222@gmail.com', 'chloemaranan', '$2y$10$MumNP8ymX7gSOA0KvCUKNOR/Eg6gSzl3/nITZi67yD20aaDCyX9Lm', 'default.jpg', 'user', 'active', '2026-05-25 14:48:59'),
(2, 'Test Admin', 'Happy Events Office', '09190000000', 'admin@happy-events.test', 'admin', '$2y$10$ccBJ7L32jBZZxhdUhLlFZOriYc0KSEQGso5McBr8k6aSKS86C3gRW', 'default.jpg', 'admin', 'active', '2026-05-25 15:40:11'),
(3, 'Dummy User 001', 'Sample Address 001, San Pablo City', '09180000001', 'dummyuser001@happy-events.test', 'dummyuser001', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-06 16:58:28'),
(4, 'Dummy User 002', 'Sample Address 002, San Pablo City', '09180000002', 'dummyuser002@happy-events.test', 'dummyuser002', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-07 17:35:28'),
(5, 'Dummy User 003', 'Sample Address 003, San Pablo City', '09180000003', 'dummyuser003@happy-events.test', 'dummyuser003', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-08 18:12:28'),
(6, 'Dummy User 004', 'Sample Address 004, San Pablo City', '09180000004', 'dummyuser004@happy-events.test', 'dummyuser004', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-09 18:49:28'),
(7, 'Dummy User 005', 'Sample Address 005, San Pablo City', '09180000005', 'dummyuser005@happy-events.test', 'dummyuser005', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-10 19:26:28'),
(8, 'Dummy User 006', 'Sample Address 006, San Pablo City', '09180000006', 'dummyuser006@happy-events.test', 'dummyuser006', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-11 20:03:28'),
(9, 'Dummy User 007', 'Sample Address 007, San Pablo City', '09180000007', 'dummyuser007@happy-events.test', 'dummyuser007', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-12 20:40:28'),
(10, 'Dummy User 008', 'Sample Address 008, San Pablo City', '09180000008', 'dummyuser008@happy-events.test', 'dummyuser008', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-13 21:17:28'),
(11, 'Dummy User 009', 'Sample Address 009, San Pablo City', '09180000009', 'dummyuser009@happy-events.test', 'dummyuser009', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-14 21:54:28'),
(12, 'Dummy User 010', 'Sample Address 010, San Pablo City', '09180000010', 'dummyuser010@happy-events.test', 'dummyuser010', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-15 22:31:28'),
(13, 'Dummy User 011', 'Sample Address 011, San Pablo City', '09180000011', 'dummyuser011@happy-events.test', 'dummyuser011', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-16 23:08:28'),
(14, 'Dummy User 012', 'Sample Address 012, San Pablo City', '09180000012', 'dummyuser012@happy-events.test', 'dummyuser012', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-17 23:45:28'),
(15, 'Dummy User 013', 'Sample Address 013, San Pablo City', '09180000013', 'dummyuser013@happy-events.test', 'dummyuser013', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-19 00:22:28'),
(16, 'Dummy User 014', 'Sample Address 014, San Pablo City', '09180000014', 'dummyuser014@happy-events.test', 'dummyuser014', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-20 00:59:28'),
(17, 'Dummy User 015', 'Sample Address 015, San Pablo City', '09180000015', 'dummyuser015@happy-events.test', 'dummyuser015', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-21 01:36:28'),
(18, 'Dummy User 016', 'Sample Address 016, San Pablo City', '09180000016', 'dummyuser016@happy-events.test', 'dummyuser016', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-22 02:13:28'),
(19, 'Dummy User 017', 'Sample Address 017, San Pablo City', '09180000017', 'dummyuser017@happy-events.test', 'dummyuser017', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-23 02:50:28'),
(20, 'Dummy User 018', 'Sample Address 018, San Pablo City', '09180000018', 'dummyuser018@happy-events.test', 'dummyuser018', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-24 03:27:28'),
(21, 'Dummy User 019', 'Sample Address 019, San Pablo City', '09180000019', 'dummyuser019@happy-events.test', 'dummyuser019', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-25 04:04:28'),
(22, 'Dummy User 020', 'Sample Address 020, San Pablo City', '09180000020', 'dummyuser020@happy-events.test', 'dummyuser020', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-26 04:41:28'),
(23, 'Dummy User 021', 'Sample Address 021, San Pablo City', '09180000021', 'dummyuser021@happy-events.test', 'dummyuser021', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-27 05:18:28'),
(24, 'Dummy User 022', 'Sample Address 022, San Pablo City', '09180000022', 'dummyuser022@happy-events.test', 'dummyuser022', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-28 05:55:28'),
(25, 'Dummy User 023', 'Sample Address 023, San Pablo City', '09180000023', 'dummyuser023@happy-events.test', 'dummyuser023', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-29 06:32:28'),
(26, 'Dummy User 024', 'Sample Address 024, San Pablo City', '09180000024', 'dummyuser024@happy-events.test', 'dummyuser024', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-04-30 07:09:28'),
(27, 'Dummy User 025', 'Sample Address 025, San Pablo City', '09180000025', 'dummyuser025@happy-events.test', 'dummyuser025', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-01 07:46:28'),
(28, 'Dummy User 026', 'Sample Address 026, San Pablo City', '09180000026', 'dummyuser026@happy-events.test', 'dummyuser026', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-02 08:23:28'),
(29, 'Dummy User 027', 'Sample Address 027, San Pablo City', '09180000027', 'dummyuser027@happy-events.test', 'dummyuser027', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-03 09:00:28'),
(30, 'Dummy User 028', 'Sample Address 028, San Pablo City', '09180000028', 'dummyuser028@happy-events.test', 'dummyuser028', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-04 09:37:28'),
(31, 'Dummy User 029', 'Sample Address 029, San Pablo City', '09180000029', 'dummyuser029@happy-events.test', 'dummyuser029', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-05 10:14:28'),
(32, 'Dummy User 030', 'Sample Address 030, San Pablo City', '09180000030', 'dummyuser030@happy-events.test', 'dummyuser030', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-06 10:51:28'),
(33, 'Dummy User 031', 'Sample Address 031, San Pablo City', '09180000031', 'dummyuser031@happy-events.test', 'dummyuser031', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-07 11:28:28'),
(34, 'Dummy User 032', 'Sample Address 032, San Pablo City', '09180000032', 'dummyuser032@happy-events.test', 'dummyuser032', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-08 12:05:28'),
(35, 'Dummy User 033', 'Sample Address 033, San Pablo City', '09180000033', 'dummyuser033@happy-events.test', 'dummyuser033', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-09 12:42:28'),
(36, 'Dummy User 034', 'Sample Address 034, San Pablo City', '09180000034', 'dummyuser034@happy-events.test', 'dummyuser034', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-10 13:19:28'),
(37, 'Dummy User 035', 'Sample Address 035, San Pablo City', '09180000035', 'dummyuser035@happy-events.test', 'dummyuser035', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-11 13:56:28'),
(38, 'Dummy User 036', 'Sample Address 036, San Pablo City', '09180000036', 'dummyuser036@happy-events.test', 'dummyuser036', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-12 14:33:28'),
(39, 'Dummy User 037', 'Sample Address 037, San Pablo City', '09180000037', 'dummyuser037@happy-events.test', 'dummyuser037', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-13 15:10:28'),
(40, 'Dummy User 038', 'Sample Address 038, San Pablo City', '09180000038', 'dummyuser038@happy-events.test', 'dummyuser038', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-14 15:47:28'),
(41, 'Dummy User 039', 'Sample Address 039, San Pablo City', '09180000039', 'dummyuser039@happy-events.test', 'dummyuser039', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-14 16:24:28'),
(42, 'Dummy User 040', 'Sample Address 040, San Pablo City', '09180000040', 'dummyuser040@happy-events.test', 'dummyuser040', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-15 17:01:28'),
(43, 'Dummy User 041', 'Sample Address 041, San Pablo City', '09180000041', 'dummyuser041@happy-events.test', 'dummyuser041', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-16 17:38:28'),
(44, 'Dummy User 042', 'Sample Address 042, San Pablo City', '09180000042', 'dummyuser042@happy-events.test', 'dummyuser042', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-17 18:15:28'),
(45, 'Dummy User 043', 'Sample Address 043, San Pablo City', '09180000043', 'dummyuser043@happy-events.test', 'dummyuser043', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-18 18:52:28'),
(46, 'Dummy User 044', 'Sample Address 044, San Pablo City', '09180000044', 'dummyuser044@happy-events.test', 'dummyuser044', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-19 19:29:28'),
(47, 'Dummy User 045', 'Sample Address 045, San Pablo City', '09180000045', 'dummyuser045@happy-events.test', 'dummyuser045', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-20 20:06:28'),
(48, 'Dummy User 046', 'Sample Address 046, San Pablo City', '09180000046', 'dummyuser046@happy-events.test', 'dummyuser046', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-21 20:43:28'),
(49, 'Dummy User 047', 'Sample Address 047, San Pablo City', '09180000047', 'dummyuser047@happy-events.test', 'dummyuser047', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-22 21:20:28'),
(50, 'Dummy User 048', 'Sample Address 048, San Pablo City', '09180000048', 'dummyuser048@happy-events.test', 'dummyuser048', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-23 21:57:28'),
(51, 'Dummy User 049', 'Sample Address 049, San Pablo City', '09180000049', 'dummyuser049@happy-events.test', 'dummyuser049', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-24 22:34:28'),
(52, 'Dummy User 050', 'Sample Address 050, San Pablo City', '09180000050', 'dummyuser050@happy-events.test', 'dummyuser050', '$2y$10$UhlFZx6Jie98x16JtQIhpuMIgGEbSg4nUh3NDjDrtDOBQluptlEDy', 'default.jpg', 'user', 'active', '2026-05-25 23:11:28'),
(53, 'geoffmaranan', 'dwadadwad', '0989638236782', 'Chloemaranan123@gmail.com', 'geoff', '$2y$10$OS4MtDwPBcOSCW7aBwaw9ufCUc0my3AzEXNo/1wRNuFpjbn1Jrlee', 'default.jpg', 'user', 'active', '2026-05-25 16:24:33'),
(54, 'Janice Recto', 'Sta Veronica', '1234567890', 'janis@gmail.com', 'janis', '$2y$10$Hj68DpK4LG0dmfd3sUIUjetabGcGTa1V8cUWsztw8gkLKtGCNKiSe', 'default.jpg', 'user', 'active', '2026-05-26 10:10:25'),
(56, 'chloe geoff', 'san pablo city', '1234567890', 'Chloemaranan0@gmail.com', 'chloe123', '$2y$10$yJKkvmZ/m3noSBvGVbWteeSbW.WaGVgN02Ka62YmvFUkY0YFx/JV6', 'default.jpg', 'user', 'active', '2026-05-29 16:57:19'),
(57, 'Bone John Andrei R.', 'daskldhaldlkahdkldhad', '09123567812', 'andrei@gmail.com', 'drei29', '$2y$10$r2eaD3aWrrzfnT1lcWf4a.pxjrxtzi0gP3KBqZSyYWKh/JovL9nV6', 'default.jpg', 'user', 'active', '2026-05-30 00:26:10'),
(58, 'jm labag', 'feihjiejfijeil', '0989638236789', 'jmlabag123@gmail.com', 'jmlabag', '$2y$10$6CYBol199SgeosgX/zggf.X5dNcysHaXpAXieDArbXST1hE7G.5Ii', 'default.jpg', 'user', 'active', '2026-05-30 00:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `venue_fees`
--

CREATE TABLE `venue_fees` (
  `id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `fee` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_fees`
--

INSERT INTO `venue_fees` (`id`, `location`, `fee`) VALUES
(1, 'Within San Pablo', 0.00),
(2, 'Laguna Area', 2000.00),
(3, 'Batangas Area', 4000.00),
(4, 'Metro Manila', 6000.00),
(5, 'Quezon Province', 5000.00),
(6, 'Other Provinces', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_ref` (`booking_ref`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_type_id` (`event_type_id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`booking_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotation_number` (`quotation_number`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `venue_fees`
--
ALTER TABLE `venue_fees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `venue_fees`
--
ALTER TABLE `venue_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`id`);

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
