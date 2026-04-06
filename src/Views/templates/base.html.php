<?php
/**
 * Базовый шаблон страницы
 * Доступные переменные:
 * - $content - основной контент страницы
 * - $texts - массив текстов из storage/templates/base.json
 */

// Значения по умолчанию
$texts = $texts ?? [];
$navText = $texts['nav'] ?? [];
$footerText = $texts['footer'] ?? [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кемеровский кооперативный техникум</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container"> 
            <a class="navbar-brand" href="/">
                <img src="/assets/img/logo.jpg" alt="Логотип" width="120" height="40">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><?= htmlspecialchars($navText['home'] ?? 'Главная') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/catalog"><?= htmlspecialchars($navText['catalog'] ?? 'Каталог') ?></a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="/cart">
                            <i class="bi bi-cart"></i>
                            <span class="cart-counter badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" 
                                style="display: none; font-size: 0.7rem;">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about"><?= htmlspecialchars($navText['about'] ?? 'О нас') ?></a>
                    </li>
                    <!-- Авторизация загружается через JavaScript -->
                    <li class="nav-item" id="auth-nav-item">
                        <a class="nav-link" href="/login">
                            <i class="bi bi-person me-1"></i><?= htmlspecialchars($navText['login'] ?? 'Вход') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <?= $content ?>
    </main>

    <footer class="footer text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; 2026 <?= htmlspecialchars($footerText['copyright'] ?? 'Кемеровский кооперативный техникум. Все права защищены.') ?></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/cart.js"></script>
</body>
</html>
