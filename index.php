<?php
// Файл: index.php

// Подключаем автозагрузчик Composer
require_once __DIR__ . "/vendor/autoload.php";

use App\BaseTemplate;

// Получаем шаблон
$template = BaseTemplate::getTemplate();

// Подставляем: заголовок страницы + контент
$resultTemplate = sprintf($template, "Главная — Турестическая фирма", "
    <div class='text-center py-5'>
        <h1 class='display-4'>Добро пожаловать!</h1>
        <p class='lead'>Лучшие варианты отдыха в Кемерово — дешево, весело, увлекательно!</p>
        <a href='/menu' class='btn btn-primary btn-lg'>Варианты отдыха</a>
    </div>
");

// Выводим готовую страницу
echo $resultTemplate;