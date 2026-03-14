<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Routes\Router;
$router = new Router();
$url = $_SERVER['REQUEST_URI'];
echo $router->route($url);