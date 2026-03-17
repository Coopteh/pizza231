<?php
require_once __DIR__ . '/vendor/autoload.php';

use Controllers\{HomeController, AboutController, ServicesController, ProductController, ErrorController};

// Получаем и очищаем путь
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$resource = trim($path, '/');

// Базовая санитизация (разрешаем буквы, цифры, дефис, слэш)
$resource = preg_replace('/[^a-zA-Z0-9\-_\/]/', '', $resource);

// === Обработка маршрута /products и /products/{id} ===
if (str_starts_with($resource, 'products')) {
    $pieces = explode('/', $resource);
    $productId = isset($pieces[1]) ? intval($pieces[1]) : null;
    
    $controller = new ProductController();
    echo $controller->get($productId);
    exit;
}

// === Остальные маршруты ===
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