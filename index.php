<?php
require_once("./vendor/autoload.php");
use App\Routes\Router;

$user_id= 0;
$username= "";
// Обновляем глобальные переменные - данными из сессии
session_start();
if (isset($_SESSION['user_id']))
    $user_id = $_SESSION['user_id'];
if (isset($_SESSION['username']))
    $username = $_SESSION['username'];

$url = $_SERVER['REQUEST_URI'];
$controller = new Router();
echo $controller->route($url);