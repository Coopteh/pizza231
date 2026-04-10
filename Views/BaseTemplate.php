<?php
namespace Views;

class BaseTemplate
{
    public static function getTemplate(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Путь к логотипу
        $logoPath = '/assets/images/5.png';

        // Счётчик корзины
        $cartCount = 0;
        $useSessions = $_COOKIE['cart_storage'] ?? 'session';
        if ($useSessions === 'session' && isset($_SESSION['basket']) && is_array($_SESSION['basket'])) {
            foreach ($_SESSION['basket'] as $item) {
                $cartCount += $item['quantity'] ?? 0;
            }
        } elseif ($useSessions === 'cookie' && isset($_COOKIE['basket'])) {
            $cookieCart = json_decode($_COOKIE['basket'], true);
            if (is_array($cookieCart)) {
                foreach ($cookieCart as $item) {
                    $cartCount += $item['quantity'] ?? 0;
                }
            }
        }

        $cartBadge = $cartCount > 0
            ? '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.65rem;line-height:1;">' . $cartCount . '</span>'
            : '';

        // Блок авторизации
        $authBlock = '';
        if (isset($_SESSION['user_id'])) {
            $userName = htmlspecialchars($_SESSION['user_name'] ?? 'Пользователь');
            $authBlock = '
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    👤 ' . $userName . '
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/profile">Профиль</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/logout">Выйти</a></li>
                </ul>
            </li>';
        } else {
            $authBlock = '
            <li class="nav-item"><a class="nav-link" href="/login">Войти</a></li>
            <li class="nav-item"><a class="nav-link" href="/register">Регистрация</a></li>';
        }

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
                        <img src="' . $logoPath . '" alt="Логотип" width="30" height="30" class="d-inline-block align-middle me-2" onerror="this.style.display=\'none\'">
                        gym low cortisol
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item"><a class="nav-link" href="/">Главная</a></li>
                            <li class="nav-item"><a class="nav-link" href="/services">Зоны</a></li>
                            <li class="nav-item"><a class="nav-link" href="/products">Клубные карты</a></li>
                            <li class="nav-item"><a class="nav-link" href="/about">О клубе</a></li>
                            <li class="nav-item ms-2">
                                <a class="nav-link position-relative d-flex align-items-center" href="/cart" style="font-size:1.4rem;text-decoration:none">
                                    🛒' . $cartBadge . '
                                </a>
                            </li>
                            ' . $authBlock . '
                        </ul>
                    </div>
                </div>
            </nav>
            <main class="container mt-4">%s</main>
            <footer class="bg-dark text-white text-center py-4 mt-5">
                <div class="container"><p class="mb-0">Фитнес-клуб «gym low cortisol»</p></div>
            </footer>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>';
    }
}