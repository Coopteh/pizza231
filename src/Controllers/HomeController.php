<?php
namespace App\Controllers;

use App\Views\HomeTemplate;
class HomeController{
    public static function get(): string 
    {
        return HomeTemplate::getTemplate();
    }
}