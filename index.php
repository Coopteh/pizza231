<?php
// Включаем отображение ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключаем автозагрузку Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    die('❌ Ошибка: Не найден vendor/autoload.php. Выполните: composer install');
}

// Импортируем классы
use Controllers\{
    HomeController,
    AboutController,
    ProductsController,
    ProductController,
    CartController,
    OrderController,
    ErrorController
};

// Получаем текущий URI
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$resource = trim($path, '/');
$resource = preg_replace('/[^a-zA-Z0-9\-_\/]/', '', $resource);

// ==========================================
// 🛒 МАРШРУТЫ КОРЗИНЫ
// ==========================================
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

if ($resource === 'cart/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CartController();
    $controller->update();
    exit;
}

if ($resource === 'cart' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new CartController();
    echo $controller->view();
    exit;
}

// ==========================================
// 📦 МАРШРУТЫ ЗАКАЗОВ
// ==========================================
if ($resource === 'order/checkout' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new OrderController();
    echo $controller->checkout();
    exit;
}

if ($resource === 'order/submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new OrderController();
    $controller->submit();
    exit;
}

if ($resource === 'order/success' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new OrderController();
    echo $controller->success();
    exit;
}

// ==========================================
// 🔐 МАРШРУТЫ АДМИНКИ
// ==========================================
if ($resource === 'admin/orders') {
    $controller = new OrderController();
    echo $controller->admin();
    exit;
}

if ($resource === 'admin/update-status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new OrderController();
    $controller->updateStatus();
    exit;
}

// ==========================================
// 🍎 МАРШРУТЫ ТОВАРОВ
// ==========================================
if (preg_match('/^course\/(\d+)$/', $resource, $matches)) {
    $productId = (int)$matches[1];
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

// ==========================================
// 🏠 ОСНОВНЫЕ МАРШРУТЫ
// ==========================================
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

    default:
        http_response_code(404);
        $controller = new ErrorController();
        echo $controller->get();
        break;
}