<?php
namespace Controllers; 

use Views\AboutTemplate;

class AboutController
{
    public function get(): string 
    {
        return AboutTemplate::getTemplate();
    }
}