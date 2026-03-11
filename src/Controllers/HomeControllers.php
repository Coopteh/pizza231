<?php
namespace App\Controllers;

use App\Views\HomeTemplate;
class HomeControllers{
    public static function get(): string 
    {
        return HomeTemplate::getTemplate();
    }
}