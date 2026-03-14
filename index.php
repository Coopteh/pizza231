<?php
// index.php

// 1. Подключаем автозагрузчик Composer
require_once __DIR__ . '/vendor/autoload.php';
// require_once __DIR__ . '/Config/Config.php';

use App\Routers\Router;

$router = new Router();
$url = $_SERVER['REQUEST_URI'];
echo $router->route($url);

// OLD ROUTING. MAY IT REST WELL IN THE WORLD OF NEW LOGIC

// 3. Получаем URI запроса
// $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 4. Простая маршрутизация
// switch ($requestUri) {
//     case '/':
//     case '/home':
//         $controller = new App\Controllers\HomeController();
//         $controller->index();
//         break;
        
//     case '/catalogue':
//         $controller = new App\Controllers\CatalogueController();
//         $controller->index();
//         break;
        
//     case '/order':
//         $controller = new App\Controllers\OrderController();
//         $controller->index();
//         break;
        
//     default:
//         http_response_code(404);
//         // Используем класс шаблона через неймспейс
//         echo App\BaseTemplate::getTemplate("Страница не найдена", "<h1>404 - Страница не найдена</h1>");
//         break;
// }