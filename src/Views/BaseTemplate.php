<?php

namespace App\Views;

class BaseTemplate {
    public static function getTemplate(string $content): string
    {
        // Шаблон с двумя вставками %s: первая для title, вторая для контента
        return <<<HTML
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- Вставка 1: Заголовок страницы -->
            <title>ККТ</title>
            
            <!-- Подключение Bootstrap CSS -->
            <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        </head>
        <body>

            <!-- Header секция -->
            <header>
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container">
                        <!-- Логотип и Заголовок "Магазин 231" -->
                        <a class="navbar-brand d-flex align-items-center" href="/">
                            <img src="/assets/img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                            Бытовая Техника
                        </a>
                        
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/">Главная</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/product/1">Каталог</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/about">О нас</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>

            <main class="container mt-4">
                $content
            </main>

        <footer class="mt-5">©2025 «Кемеровский кооперативный техникум»<footer>
            <!-- SCRIPTS -->
            <script src="../../assets/js/bootstrap.bundle.js"></script>
        </body>
        </html>
        HTML;
        // LEGACY CRAP
        // $title="BASETPLT";
        // $content="test";
        // return sprintf($template, $title, $content);
    }
}