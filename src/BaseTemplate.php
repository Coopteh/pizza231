<?php
namespace App;

class BaseTemplate
{
    public static function getTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
<script src="../../assets/css/bootstrap.bundle.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="/assets/images/logo.png" alt="Пиксель" width="50" height="50" class="me-2">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#navbarNav" aria-controls="navbarNav" 
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">🏠 Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/catalog">💻 Каталог</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/laptops">Ноутбуки</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/pcs">Компьютеры</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/accessories">Аксессуары</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/cart">
                                🛒 Корзина <span class="badge bg-danger" id="cart-count">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        %s
    </main>

   <footer class="mt-5">
                © 2025 «Кемеровский кооперативный техникум»
            <footer>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
    }
}