<?php
require_once __DIR__ . '/vendor/autoload.php';

use Controllers\{HomeController, AboutController, ServicesController, ErrorController, ProductController};

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$resource = trim($path, '/');

// Базовая санитизация
$resource = preg_replace('/[^a-zA-Z0-9\-_\/]/', '', $resource);

// 🆕 Обработка маршрута /product/{id}
if (preg_match('/^product\/(\d+)$/', $resource, $matches)) {
    $productId = (int)$matches[1];
    // Валидация: допустимые ID 1-6
    if ($productId >= 1 && $productId <= 6) {
        $controller = new ProductController($productId);
        echo $controller->get();
    } else {
        http_response_code(404);
        $controller = new ErrorController();
        echo $controller->get();
    }
    exit;
}

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