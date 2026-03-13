<?php
namespace App\Views;

class AboutTemplate extends BaseTemplate {
    public static function getTemplate(): string {
        $template = parent::getTemplate();
        $title= 'О нас';
        $content = <<<LINE2
        <main class="row">
            <div class="mt-5">
                <p>Наш магазин находится на территории "Кемеровского кооперативного техникума"</p>
                <p><img class="h-50 w-50" src="/../../assets/images/karta.jpg"></p>
            </div>
        </main> 
        LINE2;
        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}