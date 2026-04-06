<?php
namespace App\Views;

class BaseTemplate
{
    /**
     * Путь к файлу базового шаблона
     */
    private const TEMPLATE_PATH = __DIR__ . '/templates/base.html.php';

    /**
     * Путь к файлу с текстами
     */
    private const TEXTS_PATH = __DIR__ . '/../../storage/templates/base.json';

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

    public static function getTemplate(string $content, array $texts = []): string
    {
        // Если тексты не переданы, загружаем базовые
        if (empty($texts)) {
            $texts = self::loadTexts();
        }

        // Буферизация вывода для подключения PHP-шаблона
        ob_start();
        include self::TEMPLATE_PATH;
        return ob_get_clean();
    }
}