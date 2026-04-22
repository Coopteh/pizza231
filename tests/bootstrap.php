<?php

// tests/bootstrap.php

// Автозагрузка
spl_autoload_register(function ($class) {
    $prefix = 'Tests\\';
    $baseDir = __DIR__ . '/';

    if (strpos($class, $prefix) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }

    // Для классов приложения
    $appPrefixes = ['App\\'];
    foreach ($appPrefixes as $appPrefix) {
        if (strpos($class, $appPrefix) === 0) {
            $relativeClass = substr($class, strlen($appPrefix));
            $file = __DIR__ . '/../src/' . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

// Подключение интерфейсов и моков
require_once __DIR__ . '/Mocks/MockStorage.php';

// Глобальные константы для тестов
if (!defined('TEST_MODE')) {
    define('TEST_MODE', true);
}
