<?php
namespace App\Core;

class BaseTemplate {
    public static function getTemplate(): string {
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%s</title>
    <link rel="stylesheet" href="%sassets/css/bootstrap.min.css">
    <link rel="stylesheet" href="%sassets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="%s">
                    <img src="%sassets/img/logo.png" alt="Логотип" width="40" height="40" class="me-2">
                    🎓 ITCourses
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="%s">Главная</a></li>
                        <li class="nav-item"><a class="nav-link" href="%scourses">Курсы</a></li>
                        <li class="nav-item"><a class="nav-link" href="%senrollment">Записаться</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container my-4">
        %s
    </main>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">© 2024 ITCourses. Компьютерные курсы для вашего успеха.</p>
        </div>
    </footer>
    <script src="%sassets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
    }
}