<?php

namespace Controllers;

use Lib\Language;

class LanguageController
{
    public function switch(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $lang = $_GET['lang'] ?? 'ru';

        if (in_array($lang, ['ru', 'en'])) {
            $_SESSION['lang'] = $lang;
        }

        // Перенаправление на предыдущую страницу
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referer);
        exit;
    }
}
