-- --------------------------------------------------------
-- Database: `avtomobil_ijarasi`
-- --------------------------------------------------------

DROP DATABASE IF EXISTS `avtomobil_ijarasi`;
CREATE DATABASE `avtomobil_ijarasi`;
USE `avtomobil_ijarasi`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ----------------------------
-- Table structure for `car_types`
-- ----------------------------
CREATE TABLE `car_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `car_types` (`id`, `type_name`) VALUES
(1, 'Sedan'),
(2, 'SUV'),
(3, 'Hatchback'),
(4, 'Pickup'),
(5, 'Van'),
(6, 'Electric'),
(7, 'X5'),
(8, 'Crossover');

-- ----------------------------
-- Table structure for `cars`
-- ----------------------------
CREATE TABLE `cars` (
  `id` int NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` int NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `status` enum('available','rented','maintenance') DEFAULT 'available',
  `car_type_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image_url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `car_type_id` (`car_type_id`),
  CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`car_type_id`) REFERENCES `car_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `price_per_day`, `status`, `car_type_id`, `created_at`, `updated_at`, `image_url`, `image`) VALUES
(1, 'Toyota', 'Corolla', 2020, '2000000.00', 'available', NULL, '2025-05-17 11:50:00', '2025-05-17 11:50:00', 'Toyota.webp', NULL),
(2, 'BMW', 'X5', 2022, '500000.00', 'available', 2, '2025-05-21 21:18:26', '2025-05-21 21:18:26', 'X5.avif', NULL),
(3, 'Chevrolet', 'Cobalt', 2021, '250000.00', 'available', 1, '2025-05-21 22:41:00', '2025-05-21 22:41:00', 'car_682e567c189d92.75362627.webp', NULL),
(4, 'Mercedes', 'E-Class', 2023, '700000.00', 'available', 1, '2025-05-21 22:44:34', '2025-05-21 22:44:34', 'car_682e5752692ea7.71122684.jfif', NULL),
(5, 'Chevrolet', 'Captiva', 2022, '400000.00', 'available', 2, '2025-05-21 22:48:03', '2025-05-21 22:48:03', 'car_682e5823ab6163.72966744.jpg', NULL),
(6, 'Chevrolet', 'Tracker', 2022, '300000.00', 'available', 8, '2025-05-22 06:43:46', '2025-05-22 06:43:46', 'car_682ec7a2767ef6.02966172.png', NULL),
(7, 'Chevrolet', 'Gentra', 2021, '220000.00', 'available', 1, '2025-05-22 06:45:22', '2025-05-22 06:45:22', 'car_682ec802167d89.52556569.webp', NULL);

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Gulayda', 'gulayda@gmail.com', '$2y$10$3mZOuKhh33kI2/IhoAld1.WRH9pGnE3Jvq49J3cMzYRsKggxBn5e.', 'admin', '2025-05-13 15:36:32', '2025-05-13 15:37:15'),
(2, 'Qudiyar', 'qudiyar@gmail.com', '$2y$10$L6thj5qwbqX9u81Kt4HOYes3U6QGdg1.Txp4QPgLBKJuI7cznli3e', 'user', '2025-05-17 12:07:17', '2025-05-17 12:07:17');

-- ----------------------------
-- Table structure for `rentals`
-- ----------------------------
CREATE TABLE `rentals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `car_id` int DEFAULT NULL,
  `rental_start` date NOT NULL,
  `rental_end` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('active','completed','cancelled') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `car_id` (`car_id`),
  CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `rentals` (`id`, `user_id`, `car_id`, `rental_start`, `rental_end`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 1, '2025-05-01', '2025-05-30', '58000000.00', 'active', '2025-05-22 07:18:30', '2025-05-22 07:18:30');

-- ----------------------------
-- Table structure for `payments`
-- ----------------------------
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rental_id` int DEFAULT NULL,
  `payment_amount` decimal(10,0) NOT NULL,
  `payment_method` enum('credit_card','paypal','cash') NOT NULL,
  `payment_status` enum('pending','completed') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `rental_id` (`rental_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Table structure for `notifications`
-- ----------------------------
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
