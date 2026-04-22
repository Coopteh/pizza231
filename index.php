<?php
<<<<<<< HEAD
require_once("./vendor/autoload.php");
use App\Views\BaseTemplate;

$template = BaseTemplate::getTemplate();
$resultTemplate =  sprintf($template, 
    "Основная страница", 
    "<p>Пиццерия ИС-231 - это вкусная пицца, которую вам доставят прямо на занятия в 409 кабинет!</p>");

echo $resultTemplate;
=======

session_start();

// 📝 LOGGER: Подключение логгера
require_once __DIR__ . '/Lib/Logger.php';

// 📝 LOGGER: Глобальный обработчик ошибок
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $errorTypes = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSE',
        E_NOTICE => 'NOTICE',
        E_CORE_ERROR => 'CORE_ERROR',
        E_CORE_WARNING => 'CORE_WARNING',
        E_COMPILE_ERROR => 'COMPILE_ERROR',
        E_COMPILE_WARNING => 'COMPILE_WARNING',
        E_USER_ERROR => 'USER_ERROR',
        E_USER_WARNING => 'USER_WARNING',
        E_USER_NOTICE => 'USER_NOTICE',
        E_STRICT => 'STRICT',
        E_RECOVERABLE_ERROR => 'RECOVERABLE_ERROR',
        E_DEPRECATED => 'DEPRECATED',
        E_USER_DEPRECATED => 'USER_DEPRECATED'
    ];

    $type = $errorTypes[$errno] ?? 'UNKNOWN';
    \Lib\Logger::error("PHP Error: {$errstr}", [
        'type' => $type,
        'file' => $errfile,
        'line' => $errline,
        'errno' => $errno
    ]);

    return false;
});

// 📝 LOGGER: Глобальный обработчик исключений
set_exception_handler(function ($exception) {
    \Lib\Logger::critical("Uncaught Exception: " . $exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString(),
        'code' => $exception->getCode()
    ]);

    http_response_code(500);
    echo '<div class="container text-center my-5">
        <h1 class="display-1 text-danger">500</h1>
        <h2>Внутренняя ошибка сервера</h2>
        <p class="lead">Ошибка была залогирована. Обратитесь к администратору.</p>
        <a href="/" class="btn btn-primary mt-3">Вернуться на главную</a>
    </div>';
});

// 📝 LOGGER: Shutdown функция для фатальных ошибок
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        \Lib\Logger::critical("Fatal Error: " . $error['message'], [
            'file' => $error['file'],
            'line' => $error['line'],
            'type' => $error['type']
        ]);
    }
});

use Lib\Language;

spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');
    if (strpos($class, 'Lib\\') === 0) {
        $fileName = __DIR__ . '/Lib/' . substr($class, 4) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
            return;
        }
    }
    if (strpos($class, 'Controllers\\') === 0) {
        $fileName = __DIR__ . '/Controllers/' . substr($class, 12) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
            return;
        }
    }
    if (strpos($class, 'Views\\') === 0) {
        $fileName = __DIR__ . '/Views/' . substr($class, 6) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
            return;
        }
    }
});

Language::init();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['ru', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    header('Location: ' . $uri);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 📝 LOGGER: Логирование запросов в debug режиме
\Lib\Logger::debug("Request", ['uri' => $uri, 'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET']);

switch ($uri) {
    // 🔐 ДОБАВЛЕНО: Маршруты для подтверждения кодом
    case '/auth/verify-code':
        $controller = new Controllers\AuthController();
        echo $controller->verifyCode();
        break;
    case '/auth/resend-code':
        $controller = new Controllers\AuthController();
        $controller->resendCode();
        break;
        // === СУЩЕСТВУЮЩИЕ МАРШРУТЫ ===
    case '/auth/register':
        $controller = new Controllers\AuthController();
        echo $controller->register();
        break;
    case '/auth/login':
        $controller = new Controllers\AuthController();
        echo $controller->login();
        break;
    case '/auth/logout':
        $controller = new Controllers\AuthController();
        $controller->logout();
        break;
    case '/auth/forgot-password':
        $controller = new Controllers\AuthController();
        echo $controller->forgotPassword();
        break;
    case '/auth/reset-password':
        $controller = new Controllers\AuthController();
        echo $controller->resetPassword();
        break;
    case '/profile':
        $controller = new Controllers\ProfileController();
        echo $controller->index();
        break;
    case '/profile/edit':
        $controller = new Controllers\ProfileController();
        echo $controller->edit();
        break;
    case '/profile/orders':
        $controller = new Controllers\ProfileController();
        echo $controller->orders();
        break;
    case '/admin':
        $controller = new Controllers\AdminController();
        echo $controller->index();
        break;
    case '/admin/orders':
        $controller = new Controllers\AdminController();
        echo $controller->orders();
        break;
    case '/admin/order':
        $controller = new Controllers\AdminController();
        echo $controller->orderDetail();
        break;
    case '/admin/order/update':
        $controller = new Controllers\AdminController();
        $controller->updateOrderStatus();
        break;
    case '/admin/users':
        $controller = new Controllers\AdminController();
        echo $controller->users();
        break;
    case '/admin/user/delete':
        $controller = new Controllers\AdminController();
        $controller->deleteUser();
        break;
        // 📝 LOGGER: Новый маршрут для просмотра логов
    case '/admin/logs':
        $controller = new Controllers\AdminController();
        echo $controller->logs();
        break;
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
>>>>>>> 66a47bb (upd)
