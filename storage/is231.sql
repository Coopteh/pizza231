-- База данных: `is231`
-- Кодировка: utf8mb4
CREATE DATABASE IF NOT EXISTS `is231` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `is231`;

-- --------------------------------------------------------
-- Таблица `products`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT,
  `image` VARCHAR(120),
  `price` FLOAT NOT NULL,
  `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Таблица `orders`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fio` VARCHAR(120) NOT NULL,
  `address` TEXT,
  `phone` VARCHAR(15),
  `email` VARCHAR(120),
  `all_sum` FLOAT NOT NULL,
  `created` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Таблица `order_item`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_item` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `count_item` INT NOT NULL,
  `price_item` FLOAT NOT NULL,
  `sum_item` FLOAT NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Данные: курсы из data/courses.php
-- --------------------------------------------------------
INSERT INTO `products` (`name`, `description`, `image`, `price`) VALUES
('Python-разработчик', 'От основ программирования до создания полноценных веб-приложений.', 'PY', 54000.00),
('Frontend: React', 'Создавай современные интерактивные интерфейсы для веб-приложений.', 'RX', 50000.00),
('SQL и базы данных', 'Проектирование, оптимизация и работа с большими данными.', 'DB', 32000.00),
('Machine Learning', 'Нейросети, компьютерное зрение, обработка естественного языка.', 'ML', 112000.00),
('Web3 & Blockchain', 'Смарт-контракты, dApps, разработка на Solidity.', 'W3', 91000.00),
('Mobile Dev (Flutter)', 'Кроссплатформенные приложения для iOS и Android.', 'MB', 66000.00);