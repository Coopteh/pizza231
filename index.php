<?php
require_once("./vendor/autoload.php");
use App\Router\Router;

$url = $_SERVER['REQUEST_URI'];
$controller = new Router();
echo $controller->route($url);