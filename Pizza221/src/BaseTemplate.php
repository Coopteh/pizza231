<?php

namespace App;

class BaseTemplate
{
    public static function getTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>%s</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/assets/images/logo.png" 
                 alt="Fashion Store Logo" 
                 width="40" 
                 height="40"
                 onerror="this.style.display='none'">
            FASHION STORE
        </a>
        <!-- остальной код меню -->
    </div>
</nav>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#navbarNav" aria-controls="navbarNav" 
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/menu">Меню</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/order">Заказать</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        %s
    </main>

    <footer class="mt-5 py-3 bg-light">
        <div class="container text-center">
            © 2025 «Кемеровский кооперативный техникум»
        </div>
    </footer>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
    }
}