<?php
namespace App\Views;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        // Получаем базовый шаблон от родительского класса
        $template = parent::getTemplate();

        // Заголовок страницы
        $title = 'О нас';

        // Контент страницы с улучшенной структурой и адаптивностью
        $content = <<<LINE2
        <main class="container py-5">
            <div class="row align-items-center gx-5">
                <!-- Текстовая колонка -->
                <div class="col-md-6 order-2 order-md-1">
                    <h1 class="display-4 fw-bold mb-4">О нас</h1>
                    <p class="lead text-muted">
                        Наше туристическое агентство находится на территории «Кемеровского кооперативного техникума».
                        Мы помогаем планировать путешествия мечты, предлагая лучшие туры по доступным ценам.
                    </p>
                    <p>
                        Наша команда — это профессионалы своего дела, которые знают все тонкости организации отдыха.
                        Мы заботимся о вашем комфорте с первого звонка до возвращения домой.
                    </p>
                </div>

                <!-- Колонка с картой -->
                <div class="col-md-6 text-center order-1 order-md-2">
                    <div class="ratio ratio-16x9 mb-4">
                        <img src="/assets/img/map-ya.png" class="img-fluid rounded shadow-sm" alt="Карта расположения офиса">
                    </div>
                    <p class="text-muted small">Наш офис на карте</p>
                </div>
            </div>
        </main>
LINE2;

        // Подставляем заголовок и контент в шаблон
        $resultTemplate = sprintf($template, $title, $content);

        return $resultTemplate;
    }
}