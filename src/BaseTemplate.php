<?php
// Файл: src/BaseTemplate.php
namespace App;

class BaseTemplate
{
    public static function getTemplate()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%s</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
    <!-- Шапка сайта -->
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="/assets/images/logo.png" alt="Логотип" width="120" height="60" class="me-2">
                    Туристическая фирма
                </a>
                <button class="navbar-toggler" type="button" 
                        data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/menu">Варианты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/order">Заказ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Основной контент -->
    <main class="container mt-4">
        %s
    </main>

    <!-- Подвал сайта -->
    <footer class="mt-5 py-3 bg-light text-center">
        <div class="container">
            © 2025 «Кемеровский кооперативный техникум»
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
    }
}

