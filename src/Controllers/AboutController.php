<?php
namespace App\Controllers;

require_once __DIR__ . '/../Views/BaseTemplate.php';
require_once __DIR__ . '/../Views/AboutTemplate.php';

use App\Views\AboutTemplate;

class AboutController
{
    public function get(): string 
    {
        return AboutTemplate::getTemplate();
    }
}