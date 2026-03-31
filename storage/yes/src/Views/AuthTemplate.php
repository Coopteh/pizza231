<?php
namespace App\Views;

require_once __DIR__ . '/BaseTemplate.php';

class AuthTemplate extends BaseTemplate
{
    /**
     * Путь к файлу шаблона
     */
    private const TEMPLATE_PATH = __DIR__ . '/templates/auth.html.php';

    /**
     * Путь к файлу с текстами
     */
    private const TEXTS_PATH = __DIR__ . '/../../storage/templates/auth.json';

    /**
     * Загружает тексты из JSON файла
     */
    private static function loadTexts(): array
    {
        $path = self::TEXTS_PATH;
        if (!file_exists($path)) {
            return [];
        }
        $json = file_get_contents($path);
        return json_decode($json, true) ?? [];
    }

    /**
     * Рендер страницы регистрации
     */
    public static function renderRegister(string $error = '', string $success = ''): string
    {
        $texts = self::loadTexts();
        $mode = 'register';

        // Подключаем шаблон
        ob_start();
        include self::TEMPLATE_PATH;
        $content = ob_get_clean();

        return parent::getTemplate($content, $texts);
    }

    /**
     * Рендер страницы входа
     */
    public static function renderLogin(string $error = ''): string
    {
        $texts = self::loadTexts();
        $mode = 'login';

        // Подключаем шаблон
        ob_start();
        include self::TEMPLATE_PATH;
        $content = ob_get_clean();

        return parent::getTemplate($content, $texts);
    }
    
    /**
     * Рендер страницы подтверждения email
     */
    public static function renderVerify(string $email, string $error = '', string $success = ''): string
    {
        $texts = self::loadTexts();
        $mode = 'verify';

        // Подключаем шаблон
        ob_start();
        include self::TEMPLATE_PATH;
        $content = ob_get_clean();

        return parent::getTemplate($content, $texts);
    }
}
