<?php
namespace App\Views;

class BaseTemplate
{
    public static function getTemplate(string $content): string
    {
        $HTML = <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кемеровский кооперативный техникум</title>
    
    <!-- 1. Подключаем Bootstrap 5 (Стили) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- 2. Подключаем ваши личные стили -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- Иконки (опционально) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Навигация (Меню) -->
    <nav class="navbar navbar-expand-lg ">
    <div class="container"> 
            <!-- 👇 ЛОГОТИП -->
            <a class="navbar-brand" href="/">
                <img src="/assets/img/logo.jpg" 
                    alt="Логотип" 
                    width="120" 
                    height="40"
                    class="d-inline-block align-top">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">О нас</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/order">Корзина</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/catalog">Каталог</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
HTML;
    session_start();
    if (isset($_SESSION['flash'])) {
        $HTML .= <<<END
            <div id="liveAlertBtn" class="alert alert-info alert-dismissible" role="alert">
                <div>{$_SESSION['flash']}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                onclick="this.parentNode.style.display='none';"></button>
            </div>
        END;
        unset($_SESSION['flash']);
    }
$HTML .= <<<HTML
    <!-- Основной контент -->
    <main class="py-4">
        $content
    </main>

        

    <!-- Подвал (Footer) -->
    <footer class="footer bg-dark text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; 2026 Кемеровский кооперативный техникум. Все права защищены.</p>
            <small>Разработано студентом группы ИС-231</small>
        </div>
    </footer>

    <!-- Скрипт Bootstrap (для работы меню и карусели) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
HTML;
    return $HTML;
    }
}