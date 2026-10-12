<?php
// 🔍 ВКЛЮЧАЕМ ОТОБРАЖЕНИЕ ОШИБОК (УДАЛИТЕ ЭТИ СТРОКИ ПЕРЕД ПУБЛИКАЦИЕЙ НА ХОСТИНГЕ)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

require_once __DIR__ . "/vendor/autoload.php";

use App\Router\Router;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$url = $_SERVER['REQUEST_URI'];
$router = new Router();
echo $router->route($url);