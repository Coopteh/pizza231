<?php

namespace Controllers;

use Views\HomeTemplate;
// 🌐 LANG: Добавлен импорт Language
use Lib\Language;

class HomeController
{
    public function get(): string
    {
        // 🌐 LANG: Инициализация языка при загрузке главной страницы
        Language::init();
        return HomeTemplate::getTemplate();
    }
}
