<?php
namespace App\Controllers;

use App\Views\AboutTemplate;
class AboutController{
    public static function get(): string 
    {
        return AboutTemplate::getTemplate();
    }
}