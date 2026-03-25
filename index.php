<?php
require_once __DIR__ . '/vendor/autoload.php';

use Controllers\{HomeController, AboutController, CoursesController, CourseController, CartController, ProductController, ProductsController};

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$resource = trim($path, '/');
$resource = preg_replace('/[^a-zA-Z0-9\-_\/]/', '', $resource);

// Маршруты корзины
if ($resource === 'cart/count' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new CartController();
    $controller->getCountJson();
    exit;
}

if ($resource === 'cart/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CartController();
    $controller->add();
    exit;
}

if ($resource === 'cart/remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CartController();
    $controller->remove();
    exit;
}

if ($resource === 'cart/clear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CartController();
    $controller->clear();
    exit;
}

// Маршрут товара
if (preg_match('/^course\/(\d+)$/', $resource, $matches)) {
    $courseId = (int)$matches[1];
    if ($courseId >= 1 && $courseId <= 6) {
        $controller = new ProductController($courseId);
        echo $controller->get();
    } else {
        http_response_code(404);
        echo '<h1>Товар не найден</h1>';
    }
    exit;
}

// Основные маршруты
switch ($resource) {
    case '':
    case 'home':
        $controller = new HomeController();
        echo $controller->get();
        break;
    case 'products':
    case 'course':
        $controller = new ProductsController();
        echo $controller->get();
        break;
    case 'about':
        $controller = new AboutController();
        echo $controller->get();
        break;
    case 'cart':
        $controller = new CartController();
        echo $controller->view();
        break;
    default:
        http_response_code(404);
        $controller = new \Controllers\ErrorController();
        echo $controller->get();
        break;
}