<?php

require_once __DIR__ . '/vendor/autoload.php';
use App\BaseTemplate;
use App\Views\HomeTemplate;

// $controller = new HomeController();
// echo $controller->get();
echo HomeTemplate:: getTemplate();