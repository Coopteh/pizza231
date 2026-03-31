<?php
// 👇 Подключаем автозагрузку или вручную нужные файлы
require_once __DIR__ . '/src/Router/Router.php';

// Контроллеры
require_once __DIR__ . '/src/Controllers/HomeController.php';
require_once __DIR__ . '/src/Controllers/AboutController.php';
require_once __DIR__ . '/src/Controllers/ProductController.php';
require_once __DIR__ . '/src/Controllers/CatalogController.php';
require_once __DIR__ . '/src/Controllers/CartController.php'; // 👈 Новый

// Модели
require_once __DIR__ . '/src/Models/Cart.php'; // 👈 Новая модель
require_once __DIR__ . '/src/Models/Logger.php'; // 👈 Логирование ошибок

// === Инициализация PDO (перед использованием моделей) ===
require_once __DIR__ . '/src/Config/Config.php';
require_once __DIR__ . '/src/Database/Database.php';

use App\Config\Config;

// Инициализируем подключение к БД (безопасно - если БД недоступна, будет fallback на JSON)
Config::initDatabase();

// Шаблоны
require_once __DIR__ . '/src/Views/BaseTemplate.php';
require_once __DIR__ . '/src/Views/HomeTemplate.php';
require_once __DIR__ . '/src/Views/AboutTemplate.php';
require_once __DIR__ . '/src/Views/CartTemplate.php'; // 👈 Новый шаблон

use App\Router\Router;
use App\Models\Logger;

// Регистрация обработчиков ошибок и исключений
set_error_handler(function(int $errno, string $errstr, string $errfile, int $errline) {
    Logger::error($errstr, [
        'file' => $errfile,
        'line' => $errline,
        'errno' => $errno
    ]);
    
    // Продолжаем стандартную обработку ошибок
    return false;
});

set_exception_handler(function(Throwable $exception) {
    Logger::error($exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    
    // Показываем пользователю дружелюбное сообщение об ошибке
    if (str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/api/')) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode(['error' => 'Внутренняя ошибка сервера'], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo '<h1>Внутренняя ошибка сервера</h1>';
        echo '<p>Приносим извинения за временные неудобства. Мы уже работаем над решением проблемы.</p>';
    }
});

$router = new Router();
$url = $_SERVER['REQUEST_URI'];

// 👇 Для API возвращаем JSON-заголовки
if (str_starts_with($url, '/api/')) {
    header('Content-Type: application/json; charset=utf-8');
}

echo $router->route($url);