<?php
namespace App\Views;

class BaseTemplate {
    public static function getTemplate(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $html = <<<LINE
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
            <style>
            :root {
                --primary-color: #0A3D62; /* Глубокий синий */
                --secondary-color: #00A896; /* Бирюзовый */
                --bg-color: #F5F5F5; /* Светлый фон */
                --text-color: #343A40; /* Тёмный текст */
            }
            body {
                background-color: var(--bg-color);
                color: var(--text-color);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                transition: background-color 0.3s;
            }
            .btn-primary:hover {
                background-color: #082D43; /* Ещё темнее синий при наведении */
            }
            .navbar {
                background-color: var(--primary-color) !important;
            }
            .navbar .nav-link {
                color: white !important;
                transition: color 0.3s;
            }
            .navbar .nav-link:hover {
                color: var(--secondary-color) !important; /* Меняем цвет ссылки при наведении */
            }
            .navbar-brand {
                color: white !important;
            }
            h1, h2, h3 {
                color: var(--primary-color);
            }
        </style>
            <title>%s</title>
        </head>
        <body>
            <header>
                <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand d-flex align-items-center" href="/">
                        <img src="/assets/img/logo.jpg" alt="Logo" width="110" height="80" class="d-inline-block align-text-top me-2">
                        <span class="logo-font">По мультивселенной</span>
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/">Администратум</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/about">О нас</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/products">Каталог</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/order">Заказ</a>
                            </li>
                        </ul>
                    </div>
                </div>
                </nav>
            </header>
        LINE;

        // Отображение флеш-сообщения
        if (isset($_SESSION['flash'])) {
            $flashMessage = htmlspecialchars($_SESSION['flash']);
            $html .= <<<ALERT
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div>{$flashMessage}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            ALERT;
            unset($_SESSION['flash']);
        }
        
        $html .= <<<LINE
            <div class="container">
            %s
            </div>
            <footer class="p-5 text-center" style="background-color: var(--primary-color); color: white;">
                © 2025 «Кемеровский кооперативный техникум»
            </footer>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        </body>
        </html>
        LINE;
        return $html;
    }
}