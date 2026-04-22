<?php

namespace Controllers;

use Views\CoursesTemplate;

class CoursesController
{
    public function get(): string
    {
        return CoursesTemplate::getTemplate();
    }
}
