<?php
namespace App\Views;

class AboutTemplate extends BaseTemplate {
public static function getTemplate(): string {
    $template = parent::getTemplate();
    $title = 'О нас — Магазин Пиксель';
    $content = <<<HTML
    <main class="container my-5">
        <div class="row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-5 fw-bold mb-3">Месторасположение <span class="text-primary">«Пиксель»</span></h1>
            </div>
        </div>

            <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg">
                    <img src="/../../assets/images/karta.jpg" class="card-img-top" alt="Карта расположения магазина Пиксель" style="height: 450px; object-fit: cover;">
                    <div class="card-body bg-light">
                        <p class="card-text text-center text-muted small mb-0">
                            📍 г. Кемерово, территория Кузбасского кооперативного техникума
                        </p>
                    </div>
                </div>
            </div>
        </div>
    HTML;

    $resultTemplate = sprintf($template, $title, $content);
    return $resultTemplate;
}
}