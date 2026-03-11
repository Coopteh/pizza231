<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Controllers\HomeControllers;

$controller = new HomeControllers();
echo $controller->get();