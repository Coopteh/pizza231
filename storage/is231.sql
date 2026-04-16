-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 16 2026 г., 13:56
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `is231`
--

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `fio` varchar(120) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(120) NOT NULL,
  `all_sum` float NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `fio`, `address`, `phone`, `email`, `all_sum`, `created`) VALUES
(1, 'Петрова М.И.', 'г. Кемерово, ул. Тухачевского, 32', '89059000088', 'petrova@yahoo.com', 0, '2025-04-08 10:52:01'),
(2, 'Петрова М.И.', 'г. Кемерово, ул. Тухачевского, 32', '89059000088', 'jorkazomli@gufum.com', 550, '2025-04-08 10:53:11'),
(3, 'Иванов И.И.', 'г. Кемерово, ул. Ленинский 132', '89059000080', 'ivanov@yahoo.com', 1200, '2025-04-08 11:12:11'),
(4, 'Петрова М.И.', 'г. Кемерово, ул. Тухачевского, 32', '89059000088', 'petrova@yahoo.com', 3050, '2025-04-08 11:13:44'),
(5, 'Bafysdfsdf', 'г. Кемерово, ул. Тухачевского, 32', '89059000088', 'jorkazomli@gufum.com', 1200, '2025-04-11 12:13:05'),
(6, 'Милевский Владислав Анатольевич', 'Кемерово, ул.Тухачевского 32 (оставить на вахте Коляну)', '+79059000687', 'iylzepttcdtfczw-12159@binitrax.com', 1750, '2026-04-13 11:14:16');

-- --------------------------------------------------------

--
-- Структура таблицы `order_item`
--

CREATE TABLE `order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `count_item` int(11) NOT NULL,
  `price_item` float NOT NULL,
  `sum_item` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `order_item`
--

INSERT INTO `order_item` (`id`, `order_id`, `product_id`, `count_item`, `price_item`, `sum_item`) VALUES
(1, 3, 1, 1, 550, 550),
(2, 3, 2, 1, 650, 650),
(3, 4, 1, 2, 550, 1100),
(4, 4, 2, 3, 650, 1950),
(5, 5, 1, 1, 550, 550),
(6, 5, 2, 1, 650, 650),
(7, 6, 1, 2, 550, 1100),
(8, 6, 2, 1, 650, 650);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(120) NOT NULL,
  `price` float NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `created`, `updated`) VALUES
(1, 'Пицца Маргарита', 'Это наша фирменная пицца, она состоит из куриного мяса, сосисок, грибов и 2 сортов сыра (пармезана и моцареллы), декорирована веточкой петрушки!', 'https://localhost/asserts/img/pizza01.jpg', 550, '2025-04-07 13:00:54', '2025-04-07 13:00:54'),
(2, 'Пицца Пипперони', 'Это знаменитая пицца с колбасой и сыром', 'https://localhost/asserts/img/pizza02.jpg', 650, '2025-04-07 13:00:54', '2025-04-07 13:00:54');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `is_verified` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `token`, `is_verified`, `created_at`) VALUES
(1, 'Петров', 'petrov@mail.ru', '$2y$10$XXTBpBCTj8oPoaA4nPUsne9qI1FGsgDtUdxK.KPiKR89KV13zUAbm', '448d3a0327b90b1bf583370a491ec5dde9cab0e2c9bb54623d01428531336b4b', 0, '2025-04-15 10:01:31'),
(2, 'Сидорова', 'sidorova@mail.ru', '$2y$10$A17hjhtd7S1/bZ1dNS2ZseDnhMkAFcQM25iQY3hH4j29IYFBEAs6.', '359b71ac6c3137a13d55791222aba24a', 0, '2025-04-15 10:04:53'),
(3, 'Иван', 'iamjptnkculjmmm-19966@ke.furvionx.com', '$2y$10$RJQPjk8YOsWYvx/9pxqVX.Uz.G6PCL3y22iS8RuttmRZAoDIl13oO', '4aac10d7475a851cf348ae4ef16c28d16947fd17e258a0ef2e6580a255c327de', 0, '2026-04-14 17:22:14'),
(4, 'Иван Иванов', 'sriptmmrarhcnfs-92953@up.tomicorp.com', '$2y$10$HPuPsLuz.6W9xOAwcA2Mw.UZqsQ3jHkAZ13DBqrVJFHFIzJOoKNs2', '', 1, '2026-04-14 18:02:42'),
(6, '+79009800-33-55', 'iamjptnkculjmmm-1996sefwefw6@ke.furvionx.com', '$2y$10$KcbnaG5/JcKZzXlsfzc3nOdOJlzkFTqfWQ642wZICy8o5BKMmCZpO', '63c97853464856bbcb892b94e740ff92bc20b67ab3a60acbcb97a606fa720ee7', 0, '2026-04-15 13:59:20'),
(7, '+790009204455', 'iamjptnkculjmmm-199aaaa66@ke.furvionx.com', '$2y$10$7UtKdS47bcEfYdFaB5/zBeWQPYYWxBFX2gAt/x3KPJyTllZ6kjD/a', 'b1f6da767ee197ae125afdd2354fae6ce77212838cb10ee83dba16bb0d86b0cf', 0, '2026-04-15 14:02:24'),
(8, '+790023', 'iylzepttcdtfczw-12dds159@binitrax.com', '$2y$10$DLUrxflllmt2gD/z/DbTbOWRVb9guCSUvWgqP.CTBJ15WniqGLycy', 'c4727eb4b2ddc9859166ac025e118773d8417f62bb71a4e9b951876ef9693d4f', 0, '2026-04-15 14:10:29'),
(9, ' <p>\"Ivan\"<br></p>', 'iylzepttcdtfczw-1246545159@binitrax.com', '$2y$10$vp0j0KdUCd/KC1adTT7Qi.ZNtcCwJwhOqigkMGPnaveR/xA.6keri', 'ad302b5ac6cbea0c8d0b6145be150ad2bb896effbd9c46ba15e942052776fcc4', 0, '2026-04-15 15:50:14');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
