<?php
namespace App\Views;

use App\Views\BaseTemplate;

class AboutTemplate extends BaseTemplate
{
    /**
     * Путь к файлу шаблона
     */
    private const TEMPLATE_PATH = __DIR__ . '/templates/about.html.php';

    /**
     * Путь к файлу с текстами страницы
     */
    private const TEXTS_PATH = __DIR__ . '/../../storage/templates/about.json';

    /**
     * Путь к базовым текстам (nav, footer)
     */
    private const BASE_TEXTS_PATH = __DIR__ . '/../../storage/templates/base.json';

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
     * Загружает базовые тексты (nav, footer)
     */
    private static function loadBaseTexts(): array
    {
        $path = self::BASE_TEXTS_PATH;
        if (!file_exists($path)) {
            return [];
        }
        $json = file_get_contents($path);
        return json_decode($json, true) ?? [];
    }

    public static function getTemplate(string $content = '', array $texts = []): string
    {
        // Загружаем тексты страницы (about.json)
        $pageTexts = self::loadTexts();
        
        // Загружаем базовые тексты (nav, footer)
        $baseTexts = self::loadBaseTexts();
        
        // Объединяем: базовые тексты + тексты страницы
        $texts = array_merge($baseTexts, $pageTexts);

        // Подключаем шаблон
        ob_start();
        include self::TEMPLATE_PATH;
        $ourContent = ob_get_clean();
        
        return parent::getTemplate($ourContent, $texts);
    }
}