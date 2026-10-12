<?php
namespace App\Views;

class BaseTemplate
{
    public static function getTemplate(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isLoggedIn = !empty($_SESSION['user_id']);
        $userName = htmlspecialchars($_SESSION['user_name'] ?? 'Кабинет');

        $authButtons = $isLoggedIn 
            ? <<<HTML
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-outline-light btn-sm" href="/profile">👤 {$userName}</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm ms-1" href="/logout">Выйти</a>
                </li>
            HTML
            : <<<HTML
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-outline-light btn-sm" href="/login">Войти</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light btn-sm ms-1" href="/register">Регистрация</a>
                </li>
            HTML;

        $flashHtml = '';
        if (isset($_SESSION['flash'])) {
            $flashMessage = htmlspecialchars($_SESSION['flash']);
            $flashHtml = <<<ALERT
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div>{$flashMessage}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            ALERT;
            unset($_SESSION['flash']);
        }

        return <<<LINE
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
            <style>
                :root { --primary-color: #0A3D62; --secondary-color: #00A896; --bg-color: #F5F5F5; --text-color: #343A40; }
                body { background-color: var(--bg-color); color: var(--text-color); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
                .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); transition: background-color 0.3s; }
                .btn-primary:hover { background-color: #082D43; }
                .navbar { background-color: var(--primary-color) !important; }
                .navbar .nav-link { color: white !important; transition: color 0.3s; }
                .navbar .nav-link:hover { color: var(--secondary-color) !important; }
                .navbar-brand { color: white !important; }
                h1, h2, h3 { color: var(--primary-color); }
                
                /* Анимации */
                a, button, .card, .btn { transition: all 0.3s ease !important; }
                .card { will-change: transform; }
                .card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
                .animate-on-scroll { opacity: 0; transform: translateY(30px); transition: opacity 0.6s, transform 0.6s; }
                .animate-on-scroll.visible { opacity: 1; transform: translateY(0); }
                .btn:active { transform: scale(0.98); }
                .text-gradient {
                    background: linear-gradient(135deg, #0A3D62, #00A896);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }
                
                /* Рейтинг */
                .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
                .rating-input input { display: none; }
                .rating-input label { font-size: 1.5rem; cursor: pointer; color: #ccc; transition: color 0.2s; }
                .rating-input input:checked ~ label,
                .rating-input label:hover,
                .rating-input label:hover ~ label { color: #ffc107; }
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
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item"><a class="nav-link" href="/">Главная</a></li>
                            <li class="nav-item"><a class="nav-link" href="/about">О нас</a></li>
                            <li class="nav-item"><a class="nav-link" href="/products">Каталог</a></li>
                            <li class="nav-item"><a class="nav-link" href="/order">Заказ</a></li>
                            {$authButtons}
                        </ul>
                    </div>
                </div>
                </nav>
            </header>
        {$flashHtml}
            <div class="container">
            %s
            </div>
            <footer class="p-5 text-center" style="background-color: var(--primary-color); color: white;">
                © 2025 «Кемеровский кооперативный техникум»
            </footer>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });
                document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
            });
            </script>
        </body>
        </html>
        LINE;
    }
}