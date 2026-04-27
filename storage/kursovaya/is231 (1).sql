-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 12:28 PM
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
-- Database: `is231`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT '',
  `address` text DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `email`, `name`, `phone`, `address`, `total`, `status`, `items`, `created_at`, `updated_at`) VALUES
(1, NULL, 'goida@mail.com', '', '+7 999 999 99 99', 'улица пушкина дом калатушкина', 1596.00, '', '[{\"id\":3,\"name\":\"\\u041c\\u044f\\u0441\\u043d\\u0430\\u044f\",\"price\":449,\"image\":\"\\/..\\/..\\/assets\\/img\\/pizza003.png\",\"quantity\":1,\"added_at\":1776053361},{\"id\":2,\"name\":\"\\u0413\\u043e\\u0432\\u044f\\u0434\\u0438\\u043d\\u0430 \\u0441 \\u0445\\u0440\\u0435\\u043d\\u043e\\u043c\",\"price\":339,\"image\":\"\\/..\\/..\\/assets\\/img\\/pizza002.png\",\"quantity\":1,\"added_at\":1776053361},{\"id\":4,\"name\":\"\\u041f\\u0435\\u043f\\u043f\\u0435\\u0440\\u043e\\u043d\\u0438 \\u0444\\u0440\\u0435\\u0448\",\"price\":259,\"image\":\"\\/..\\/..\\/assets\\/img\\/pizza004.png\",\"quantity\":1,\"added_at\":1776053363},{\"id\":5,\"name\":\"\\u041c\\u044f\\u0441\\u043d\\u043e\\u0439 \\u043c\\u0438\\u043a\\u0441\",\"price\":549,\"image\":\"\\/..\\/..\\/assets\\/img\\/pizza005.png\",\"quantity\":1,\"added_at\":1776053363}]', '2026-04-13 13:10:00', '2026-04-25 10:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(500) DEFAULT '/assets/img/no-image.jpg',
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT 'Без категории',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `description`, `price`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Гриль', '/assets/img/card1.png', 'Хороший гриль', 20000.00, 'Без категории', '2026-04-25 10:03:33', '2026-04-25 10:03:33'),
(2, 'Стиральная машина', '/assets/img/card2.png', 'Стиральная машина', 12000.00, 'Без категории', '2026-04-25 10:03:33', '2026-04-25 10:03:33'),
(3, 'Чайник', '/assets/img/card3.png', 'Чайник, российского производства, импортировано из Вьетнама', 5000.00, 'Без категории', '2026-04-25 10:03:33', '2026-04-25 10:03:33'),
(4, 'Холодильник', '/assets/img/card4.png', 'Холодильник корейский, встроенная ферма криптовалюты, система видеонаблюдения', 10000.00, 'Без категории', '2026-04-25 10:03:33', '2026-04-25 10:03:33'),
(5, 'Аэрогриль', '/assets/img/card5.png', 'Как гриль, но аэро', 3400255.00, 'Без категории', '2026-04-25 10:03:33', '2026-04-25 10:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT '',
  `address` text DEFAULT '',
  `avatar` varchar(500) DEFAULT '',
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(10) DEFAULT NULL,
  `verification_expires` datetime DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `phone`, `address`, `avatar`, `is_verified`, `verification_code`, `verification_expires`, `verified_at`, `is_admin`, `created_at`, `updated_at`) VALUES
(1, '', '', '', '', '', '', 0, NULL, NULL, NULL, 0, '2026-04-25 10:03:33', '2026-04-25 10:03:33'),
(2, 'heslessscum@gmail.com', '$2y$10$QJzgPGzx3/ui5XIGuSbs.OyHH6iUgSsswWSRw7UDlhfPP0tT95EiS', 'Могучий Йа', '', '', '', 0, '880564', '2026-04-25 13:18:16', NULL, 0, '2026-04-25 10:16:07', '2026-04-25 10:18:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_price` (`price`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_is_verified` (`is_verified`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
