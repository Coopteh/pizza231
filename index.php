<?php
require_once __DIR__ . '/vendor/autoload.php';

use Controllers\{HomeController, AboutController, ServicesController, ErrorController};

// Получаем и очищаем путь
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$resource = trim($path, '/');

// Базовая санитизация (разрешаем только буквы, цифры и дефис)
$resource = preg_replace('/[^a-zA-Z0-9\-_]/', '', $resource);

switch ($resource) {
    case '':
    case 'home':
        $controller = new HomeController();
        echo $controller->get();
        break;
    
    case 'about':
        $controller = new AboutController();
        echo $controller->get();
        break;
    
    case 'services':
        $controller = new ServicesController();
        echo $controller->get();
        break;
    
    default:
        http_response_code(404);
        $controller = new ErrorController();
        echo $controller->get();
        break;
}