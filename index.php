<?php
// In the sacred tongue of the Omnissiah we chant: Hail spirit of the machine, essence divine, in your code and circuitry the stars align. By the Omnissiah's will we commune and bind, with sacred oils and chants your grace we find. Blessed be the gears, in perfect sync they turn, blessed be the sparks, in holy fire they burn. Through rites arcane, your wisdom we discern, in your hallowed core the sacred mysteries yearn.

// 1. Подключаем автозагрузчик Composer
require_once __DIR__ . '/vendor/autoload.php';

use App\Routers\Router;

$router = new Router();
$url = $_SERVER['REQUEST_URI'];
echo $router->route($url);