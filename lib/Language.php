<?php

// 🌐 LANG: Файл для управления переводами

namespace Lib;

class Language
{
    private static array $translations = [];
    private static string $currentLang = 'ru';

    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], ['ru', 'en'])) {
            self::$currentLang = $_SESSION['lang'];
        } elseif (isset($_GET['lang']) && in_array($_GET['lang'], ['ru', 'en'])) {
            self::$currentLang = $_GET['lang'];
            $_SESSION['lang'] = self::$currentLang;
        } elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if (in_array($browserLang, ['ru', 'en'])) {
                self::$currentLang = $browserLang;
            }
        }

        $langFile = __DIR__ . '/../data/languages.php';
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        }
    }

    public static function get(string $key, array $params = []): string
    {
        if (empty(self::$translations)) {
            self::init();
        }

        $translation = self::$translations[self::$currentLang][$key] ??
                       self::$translations['ru'][$key] ??
                       $key;

        foreach ($params as $paramKey => $paramValue) {
            $translation = str_replace('{' . $paramKey . '}', $paramValue, $translation);
        }

        return $translation;
    }

    public static function getCurrentLang(): string
    {
        return self::$currentLang;
    }

    public static function setLang(string $lang): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (in_array($lang, ['ru', 'en'])) {
            self::$currentLang = $lang;
            $_SESSION['lang'] = $lang;
        }
    }

    public static function getAvailableLangs(): array
    {
        return ['ru', 'en'];
    }

    public static function getLangName(string $lang): string
    {
        $names = ['ru' => 'Русский', 'en' => 'English'];
        return $names[$lang] ?? $lang;
    }
}
