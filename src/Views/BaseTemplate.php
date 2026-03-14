<?php
namespace App\Views;

class BaseTemplate
{
    public static function getTemplate()
    {
        $template= <<<HTML
        <!DOCTYPE html>
        <html lang="ru">
        <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        <script src="../../assets/css/bootstrap.bundle.js"></script>
        <title>%s</title>
        </head>
        <body>
            <header>
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <div class="container-fluid">
                        <a class="navbar-brand d-flex align-items-center" href="/">
                            <img src="../../assets/images/logo.jpg" alt="Пиксель" width="70" height="55" class="me-3">
                            Компьютерный магазин
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                                aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav me-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="/">Главная</a>
                                </li>
                                <li class="nav-item">
                                   <a class="nav-link active" aria-current="page" href="/about">Каталог</a>
                                </li>
                                 <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/about">О нас</a>
                        </li>     
                            </ul>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="/cart">
                                        Корзина <span class="badge bg-danger" id="cart-count">0</span>
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

            <footer class="bg-dark text-white text-center py-3 mt-5">
                <div class="container">
                    <p class="mb-0">&copy; 2026 Кемеровский кооперативный техникум. Все права защищены.</p>
                    <small>Разработано студентом группы ИС-231</small>
                </div>
            </footer>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        </body>
        </html>
        HTML;
        return $template;
    }
}