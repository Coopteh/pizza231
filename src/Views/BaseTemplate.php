<?php
namespace App\Views;

class BaseTemplate {
    public static function getTemplate(): string {
        $html = <<<LINE
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Супермаркет — свежие продукты, выгодные цены, доставка на дом">
             <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
            <title>%s</title>
        </head>
        <body>
            <header>
                <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                    <div class="container-fluid">
                        <a class="navbar-brand d-flex align-items-center" href="/">
                            <img src="/assets/img/logo.svg" alt="Логотип Супермаркета" width="40" height="40" class="me-2">
                            <span class="fw-bold">Супермаркет ИС-231</span>
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Переключить навигацию">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/">Главная</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/catalog">Каталог</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/promotions">Акции</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/delivery">Доставка</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/contacts">Контакты</a>
                                </li>
                            </ul>
                            <div class="d-flex align-items-center gap-3">
                                <a href="/search" class="btn btn-outline-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                </a>
                                <a href="/cart" class="btn btn-outline-success btn-sm position-relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">0</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            
            <main class="container py-4">
                %s
            </main>
            
            <footer class="bg-dark text-light pt-5 pb-3 mt-auto">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h5 class="fw-bold">Супермаркет ИС-231</h5>
                            <p class="small text-muted">Всегда свежие продукты по выгодным ценам. Доставка в течение 2 часов.</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="fw-bold">Контакты</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>📍 г. Кемерово, пр. Советский, 45</li>
                                <li>📞 +7 (3842) 00-00-00</li>
                                <li>✉️ info@supermarket-is231.ru</li>
                                <li>🕒 Ежедневно: 8:00 — 22:00</li>
                            </ul>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="fw-bold">Полезные ссылки</h6>
                            <ul class="list-unstyled small">
                                <li><a href="/about" class="text-decoration-none text-muted">О нас</a></li>
                                <li><a href="/privacy" class="text-decoration-none text-muted">Политика конфиденциальности</a></li>
                                <li><a href="/terms" class="text-decoration-none text-muted">Условия доставки</a></li>
                            </ul>
                        </div>
                    </div>
                    <hr class="border-secondary">
                    <div class="text-center small text-muted">
                        © 2025 «Кемеровский кооперативный техникум». Все права защищены.
                    </div>
                </div>
            </footer>
            
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        </body>
        </html>
        LINE;

        return $html;
    }
}