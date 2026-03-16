<?php
namespace App\Controllers;

require_once __DIR__ . '/../Views/BaseTemplate.php';
require_once __DIR__ . '/../Views/HomeTemplate.php';

use App\Views\HomeTemplate;

class HomeController
{
    public function get(): string 
    {
        return HomeTemplate::getTemplate();
    }
}