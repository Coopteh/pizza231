<?php
namespace App\Views;

class BaseTemplate
{
    public static function getTemplate(): string
    {
        $basePath = '/Insurance221';
        
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%s</title>
    <link rel="stylesheet" href="{$basePath}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{$basePath}/assets/css/style.css">
    <style>
        :root { --ins-primary: #1e3a8a; --ins-secondary: #3b82f6; --ins-light: #eff6ff; }
        body { background: var(--ins-light); font-family: 'Segoe UI', system-ui, sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        .navbar { background: white !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-brand { color: var(--ins-primary) !important; font-weight: 700; }
        .logo-img { height: 50px; object-fit: contain; vertical-align: middle; margin-right: 15px; }
        main { flex: 1; padding: 2rem 0; }
        footer { background: var(--ins-primary); color: white; padding: 1rem 0; margin-top: auto; }
        .btn-ins { background: var(--ins-primary); color: white; }
        .btn-ins:hover { background: #172554; color: white; }
        .card { border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.08); transition: transform 0.2s; }
        .card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- Логотип и название расположены горизонтально слева -->
                <div class="d-flex align-items-center">
                    <img src="{$basePath}/assets/images/logo.png" alt="Логотип" class="logo-img mr-3">
                    <a class="navbar-brand" href="{$basePath}/">Страховая компания отмыв денег</a>
                </div>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link active" href="{$basePath}/">Главная</a></li>
                        <li class="nav-item"><a class="nav-link" href="{$basePath}/services">Услуги</a></li>
                        <li class="nav-item"><a class="nav-link" href="{$basePath}/quote">Рассчитать</a></li>
                        <li class="nav-item"><a class="nav-link" href="{$basePath}/claims">Заявления</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container">%s</main>
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">© 2025 Страховая компания отмыв денег</p>
        </div>
    </footer>
    <script src="{$basePath}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{$basePath}/assets/js/app.js"></script>
</body>
</html>
HTML;
    }
}