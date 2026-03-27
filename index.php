<?php
// 🌐 LANG: Добавлена поддержка сессии для языка
session_start();

// 🌐 LANG: 1. СНАЧАЛА регистрируем автозагрузчик
spl_autoload_register(function ($class) {
    // Преобразуем пространство имен в путь к файлу
    $class = ltrim($class, '\\');
    
    // 🌐 LANG: Проверка для Lib namespace
    if (strpos($class, 'Lib\\') === 0) {
        $fileName = __DIR__ . '/Lib/' . substr($class, 4) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
            return;
        }
    }
    
    // Проверка для Controllers namespace
    if (strpos($class, 'Controllers\\') === 0) {
        $fileName = __DIR__ . '/Controllers/' . substr($class, 12) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
            return;
        }
    }
    
    // Проверка для Views namespace
    if (strpos($class, 'Views\\') === 0) {
        $fileName = __DIR__ . '/Views/' . substr($class, 6) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
            return;
        }
    }
    
    // Общий путь
    $fileName = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($fileName)) {
        require $fileName;
    }
});

// 🌐 LANG: 2. ТЕПЕРЬ инициализируем язык (после автозагрузчика!)
use Lib\Language;
Language::init();

// 🌐 LANG: 3. Обработка переключения языка
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ru', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    header('Location: ' . $uri);
    exit;
}

// Простая маршрутизация
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
    case '/home':
        $controller = new Controllers\HomeController();
        echo $controller->get();
        break;
    case '/courses':
        $controller = new Controllers\CoursesController();
        echo $controller->get();
        break;
    case '/about':
        $controller = new Controllers\AboutController();
        echo $controller->get();
        break;
    case '/cart':
        $controller = new Controllers\CartController();
        echo $controller->view();
        break;
    case '/cart/add':
        $controller = new Controllers\CartController();
        $controller->add();
        break;
    case '/cart/remove':
        $controller = new Controllers\CartController();
        $controller->remove();
        break;
    case '/cart/clear':
        $controller = new Controllers\CartController();
        $controller->clear();
        break;
    case '/cart/count':
        $controller = new Controllers\CartController();
        $controller->getCountJson();
        break;
    case '/cart/checkout':
        $controller = new Controllers\CartController();
        $controller->checkout();
        break;
    case '/cart/order':
        $controller = new Controllers\CartController();
        $controller->order();
        break;
    case '/cart/success':
        $controller = new Controllers\CartController();
        $controller->success();
        break;
    default:
        if (preg_match('#^/course/(\d+)$#', $uri, $matches)) {
            $controller = new Controllers\CourseController((int)$matches[1]);
            echo $controller->get();
        } else {
            $controller = new Controllers\ErrorController();
            http_response_code(404);
            echo $controller->get();
        }
        break;
}