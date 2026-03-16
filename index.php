<?php
require_once __DIR__ . '/src/Router/Router.php';
require_once __DIR__ . '/src/Controllers/HomeController.php';
require_once __DIR__ . '/src/Controllers/AboutController.php';
require_once __DIR__ . '/src/Views/BaseTemplate.php';
require_once __DIR__ . '/src/Views/HomeTemplate.php';
require_once __DIR__ . '/src/Views/AboutTemplate.php';

use App\Router\Router;

$router = new Router();
$url = $_SERVER['REQUEST_URI'];
echo $router->route($url);