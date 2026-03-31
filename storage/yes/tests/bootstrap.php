<?php
/**
 * Bootstrap файл для PHPUnit тестов
 */

// Подключаем автолоader
require_once __DIR__ . '/../vendor/autoload.php';

// Определяем константы, если они не определены (для тестов без веб-сервера)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Отключаем вывод ошибок в консоль при тестах для чистого вывода
error_reporting(E_ALL);

// Запускаем сессию до любого вывода
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Мокаем суперглобальные переменные для тестов
if (!isset($_SESSION)) {
    $_SESSION = [];
}

if (!isset($_COOKIE)) {
    $_COOKIE = [];
}

if (!isset($_SERVER)) {
    $_SERVER = [];
}
