<?php
// Автозагрузчик Composer
require_once __DIR__ . '/vendor/autoload.php';

// Конфигурация
require_once __DIR__ . '/Config/Config.php';

// Маршруты
require_once __DIR__ . '/src/Routes/web.php';

// Получение и нормализация URL
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
if (empty($url)) {
    $url = '/';
}

// Обработка маршрута
if (array_key_exists($url, $routes)) {
    $controllerClass = $routes[$url]['controller'];
    $action = $routes[$url]['action'];
    
    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        if (method_exists($controller, $action)) {
            $controller->$action();
            exit;
        }
    }
}

// 404
http_response_code(404);
$controller = new \App\Controllers\HomeController();
$controller->notFound();