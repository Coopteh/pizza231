<?php
require_once __DIR__ . '/vendor/autoload.php';

use Controllers\{HomeController, AboutController, ServicesController, CatalogController, ProductController, BasketController, AuthController, ProfileController, CheckoutController, ErrorController};

// Запускаем сессию в самом начале
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Получаем и очищаем путь
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$resource = trim($path, '/');

// Базовая санитизация (разрешаем буквы, цифры, дефис, слэш)
$resource = preg_replace('/[^a-zA-Z0-9\-_\/]/', '', $resource);

// === 🔐 Маршруты авторизации ===
if ($resource === 'register') {
    echo (new AuthController())->register();
    exit;
}
if ($resource === 'register/process') {
    (new AuthController())->processRegister();
    exit;
}
if ($resource === 'login') {
    echo (new AuthController())->login();
    exit;
}
if ($resource === 'login/process') {
    (new AuthController())->processLogin();
    exit;
}
if ($resource === 'logout') {
    (new AuthController())->logout();
    exit;
}

// === 👤 Маршруты профиля ===
if ($resource === 'profile') {
    echo (new ProfileController())->get();
    exit;
}
if ($resource === 'profile/phone') {
    (new ProfileController())->updatePhone();
    exit;
}
if ($resource === 'profile/password') {
    (new ProfileController())->updatePassword();
    exit;
}
if ($resource === 'profile/card') {
    (new ProfileController())->updateCard();
    exit;
}

// === 🛒 Маршруты корзины ===
if ($resource === 'cart') {
    echo (new BasketController())->get();
    exit;
}
if ($resource === 'cart/add') {
    (new BasketController())->add();
    exit;
}
if ($resource === 'cart/remove') {
    (new BasketController())->remove();
    exit;
}
if ($resource === 'cart/clear') {
    (new BasketController())->clear();
    exit;
}
if ($resource === 'cart/update') {
    (new BasketController())->update();
    exit;
}
if ($resource === 'cart/setStorage') {
    (new BasketController())->setStorage();
    exit;
}

// === 💳 Маршруты оплаты ===
if ($resource === 'checkout') {
    echo (new CheckoutController())->get();
    exit;
}
if ($resource === 'checkout/process') {
    (new CheckoutController())->process();
    exit;
}
if ($resource === 'checkout/success') {
    echo (new CheckoutController())->success();
    exit;
}

// === 📦 Маршрут /product/{id} ===
if (preg_match('/^product\/(\d+)$/', $resource, $matches)) {
    echo (new ProductController())->get((int)$matches[1]);
    exit;
}

// === 📋 Маршрут /products ===
if ($resource === 'products') {
    echo (new CatalogController())->get();
    exit;
}

// === 🏠 Остальные маршруты ===
switch ($resource) {
    case '':
    case 'home':
        echo (new HomeController())->get();
        break;
    
    case 'about':
        echo (new AboutController())->get();
        break;
    
    case 'services':
        echo (new ServicesController())->get();
        break;
    
    default:
        http_response_code(404);
        echo (new ErrorController())->get();
        break;
}