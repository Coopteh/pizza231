<?php
namespace Views;

class BaseTemplate
{
    public static function getTemplate(): string
    {
        return '
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%s</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="assets/images/5.png" alt="Логотип" width="30" height="30" class="d-inline-block align-middle me-2">
                Чёрный Вантуз
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Главная</a></li>
                    <li class="nav-item"><a class="nav-link" href="/services">Услуги</a></li>
                    <li class="nav-item"><a class="nav-link" href="/products">Каталог</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">О компании</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        %s
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">© 1488 Страховая компания «Чёрный Вантуз»</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
    }
}