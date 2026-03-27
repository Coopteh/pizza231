<?php
namespace Views;

class BaseTemplate
{
    public static function getTemplate(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cartCount = count($_SESSION['cart'] ?? []);
        $cartBadge = $cartCount > 0 ? '<span class="cart-badge">' . $cartCount . '</span>' : '';

        return '
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{TITLE}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Montserrat", sans-serif;
        }
        body {
            background: linear-gradient(180deg, #fff5f0 0%, #ffffff 100%);
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 50%, #f7931e 100%) !important;
            box-shadow: 0 4px 20px rgba(255,107,53,0.3);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 900;
            font-size: 1.8rem;
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .nav-link {
            color: white !important;
            font-weight: 600;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        .cart-badge {
            background: #fff;
            color: #ff6b35;
            border-radius: 50%;
            padding: 0.25rem 0.6rem;
            font-size: 0.8rem;
            font-weight: 900;
            position: absolute;
            top: -5px;
            right: -10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .cart-link {
            position: relative;
        }
        footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 3rem 0;
            margin-top: 5rem;
        }
        .footer-link {
            color: #ff6b35;
            text-decoration: none;
        }
        .footer-link:hover {
            color: #f7931e;
        }
        .notification-toast {
            position: fixed;
            top: 100px;
            right: 20px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            padding: 1rem 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(255,107,53,0.4);
            z-index: 9999;
            display: none;
            animation: slideInRight 0.3s ease;
        }
        .notification-toast.show {
            display: block;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .btn-primary {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            border: none;
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(255,107,53,0.3);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255,107,53,0.4);
            background: linear-gradient(135deg, #f7931e, #ff6b35);
        }
    </style>
</head>
<body>
    <div class="notification-toast" id="notification">
        <span id="notification-message">✅ Товар добавлен!</span>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                🛒 Продукты24
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/home">Главная</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products">Каталог</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">О нас</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link cart-link" href="/cart">
                            🛒 Корзина ' . $cartBadge . '
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">{{CONTENT}}</main>

    <footer>
        <div class="container text-center">
            <h4 class="fw-bold mb-3">🛒 Продукты24</h4>
            <p class="mb-3">Ваш надёжный онлайн-супермаркет</p>
            <p class="small text-muted mb-3">
                📧 info@produkty24.ru | 📞 +7 (999) 123-45-67
            </p>
            <p class="small mb-0">© 2024 Продукты24. Все права защищены.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showNotification(message) {
            const toast = document.getElementById("notification");
            const msg = document.getElementById("notification-message");
            msg.textContent = message;
            toast.classList.add("show");
            setTimeout(() => {
                toast.classList.remove("show");
            }, 3000);
        }

        function updateCartCount(count) {
            let badge = document.querySelector(".cart-badge");
            const cartLink = document.querySelector(".cart-link");
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement("span");
                    badge.className = "cart-badge";
                    cartLink.appendChild(badge);
                }
                badge.textContent = count;
            } else if (badge) {
                badge.remove();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(\'form[action="/cart/add"]\').forEach(form => {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch("/cart/add", { method: "POST", body: formData })
                        .then(response => {
                            if (response.ok) {
                                showNotification("✅ Товар добавлен в корзину!");
                                fetch("/cart/count").then(r => r.json()).then(data => updateCartCount(data.count));
                            } else {
                                showNotification("❌ Ошибка добавления");
                            }
                        })
                        .catch(() => showNotification("❌ Ошибка сети"));
                });
            });
        });
    </script>
</body>
</html>';
    }
}