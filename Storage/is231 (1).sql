-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Апр 20 2026 г., 09:28
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
  `address` text DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `all_sum` float NOT NULL,
  `created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(120) DEFAULT NULL,
  `price` float NOT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `created`, `updated`) VALUES
(1, 'Лесные Просторы', 'Погрузитесь в атмосферу первозданной природы! Тур «Лесные Просторы» — это идеальное сочетание спокойствия и приключений.</p><p>Вас ждут живописные тропы среди вековых сосен, кристально чистые озёра, ароматы хвои и пение птиц.</p><h5>📌 Программа тура включает:</h5><ul><li>Пешие прогулки с опытным гидом</li><li>Мастер-класс по выживанию в лесу</li><li>Вечерние посиделки у костра с ухой и гитарой</li><li>Посещение заповедной зоны с редкими растениями</li></ul><h5>🏠 Размещение:</h5><p>Уютные эко-домики с камином</p><h5>⏱️ Длительность:</h5><p>5 дней / 4 ночи</p><h5>✅ В стоимость входит:</h5><p>Трансфер, проживание, питание, услуги гида, страховка</p><p><em>Подходит для семей с детьми, любителей фотографии и тех, кто хочет отдохнуть от городской суеты.</em></p>', './assets/img/image1.png', 10550, '2026-04-13 11:43:08', '2026-04-13 11:43:08'),
(2, 'Горные Вершины', 'Для тех, кто стремится к высоте! Тур «Горные Вершины» — это вызов себе и незабываемые панорамные виды. Маршрут пролегает через живописные ущелья, альпийские луга и скалистые гряды с захватывающими дух пейзажами.</p><h5>📌 Программа тура включает:</h5><ul><li>Трекинг разной сложности</li><li>Восхождение на смотровую площадку</li><li>Обучение основам альпинизма</li><li>Фотосессия на фоне горных хребтов</li></ul><h5>🏠 Размещение:</h5><p>Комфортабельные домики с панорамными окнами</p><h5>⏱️ Длительность:</h5><p>6 дней / 5 ночей</p><h5>✅ В стоимость входит:</h5><p>Трансфер, проживание, трёхразовое питание, снаряжение, услуги инструктора, медицинская страховка</p><p><em>Тур подходит для активных путешественников с базовой физической подготовкой.</em></p>', './assets/img/image2.png', 20450, '2026-04-13 11:43:08', '2026-04-13 11:43:08'),
(3, 'Озеро', 'Для тех, кто ищет гармонию с природой! Тур «Озёрная гладь» — это возможность отдохнуть душой и насладиться чистейшим воздухом у воды.</p><p>Маршрут проходит по живописным берегам, лесным тропам и смотровым площадкам с потрясающими видами на озеро и окрестности.</p><h5>📌 Программа тура включает:</h5><ul><li>Пешие прогулки вдоль озера</li><li>Вечерние костры и песни под гитару</li><li>Фотосессия на закате</li><li>Посещение бани на берегу</li></ul><h5>🏠 Размещение:</h5><p>Комфортабельные палатки с видом на озеро</p><h5>⏱️ Длительность:</h5><p>7 дней / 6 ночей</p><h5>✅ В стоимость входит:</h5><p>Снаряжение для водных активностей, услуги гида, медицинская страховка</p><p><em>Тур подходит для путешественников любого возраста и уровня подготовки.</em></p>', './assets/img/image3.png', 15350, '2026-04-13 11:43:08', '2026-04-13 11:43:08');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_token` varchar(64) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `fio` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
