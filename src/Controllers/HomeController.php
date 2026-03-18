<?php
// Объявляем пространство имен, соответствующее папке src/Controllers
namespace App\Controllers;

// Подключаем класс шаблона из корня src/
use App\Views\HomeTemplate;

class HomeController{
    public static function get(): string 
    {
        return HomeTemplate::getTemplate();
    }
}