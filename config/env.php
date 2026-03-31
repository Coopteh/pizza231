<?php
// Загрузка переменных окружения из .env файла
function loadEnv($path)
{
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
    
    return true;
}

// Загружаем .env файл
loadEnv(__DIR__ . '/../.env');

// Функция для получения переменных окружения
function env($key, $default = null)
{
    $value = $_ENV[$key] ?? getenv($key);
    return $value === false ? $default : $value;
}