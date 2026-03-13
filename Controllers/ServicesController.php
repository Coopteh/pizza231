<?php
namespace Controllers;

use Views\ServicesTemplate;

class ServicesController
{
    public function get(): string 
    {
        return ServicesTemplate::getTemplate();
    }
}