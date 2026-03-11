<?php
// Объявляем пространство имен, соответствующее папке src/Controllers
namespace App\Controllers;

// Подключаем класс шаблона из корня src/
use App\Views\HomeTemplate;

class HomeController
{
    public function index()
    {
        $content = "
            <div class='jumbotron'>
                <h1 class='display-4'>Добро пожаловать в Лучший Магазин Бытовой Техники!</h1>
                <p class='lead'>Я коммандер Шепард и это мой любимый магазин бытовой техники на Цитадели.</p>
                <a class='btn btn-primary btn-lg' href='/catalogue' role='button'>Перейти в каталог</a>
            </div>
        ";
        
        // Вызываем статический метод класса BaseTemplate
        echo HomeTemplate::getTemplate("Главная - Магазин 231", $content);
    }
}