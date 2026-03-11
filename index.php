<?php
// ... предыдущий код ...

// Подключение автозагрузчика
require_once __DIR__ . '/vendor/autoload.php';

// Подключение необходимых пространств имен
use Controllers\HomeController;

// Точка входа: создаем контроллер главной страницы и выводим результат
$controller = new HomeController();
echo $controller->get();