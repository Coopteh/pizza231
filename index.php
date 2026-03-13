<?php
require_once __DIR__ . '/vendor/autoload.php';

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$resource = trim($path, '/');

if ($resource === '') {
    $resource = 'home';
}

switch ($resource) {
    case 'about':
        require_once __DIR__ . '/Controllers/AboutController.php';
        $controller = new Controllers\AboutController();
        echo $controller->get();
        break;

    case 'services':
        // Подключаем новый контроллер услуг
        require_once __DIR__ . '/Controllers/ServicesController.php';
        $controller = new Controllers\ServicesController();
        echo $controller->get();
        break;

    case 'home':
    case 'index':
    default:
        require_once __DIR__ . '/Controllers/HomeController.php';
        $controller = new Controllers\HomeController();
        echo $controller->get();
        break;
}