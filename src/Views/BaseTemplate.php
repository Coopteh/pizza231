<?php
namespace App\Views;

class BaseTemplate {
    public static function getTemplate(): string {
        $html = <<<LINE
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="/../../asserts/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
            <title>%s</title>
        </head>
        <body>
            <header>
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                    <img src="/../../asserts/img/logo.jpg" alt="Logo" width="52" height="52" class="d-inline-block align-text-top">
                    </a>
                    <a class="navbar-brand logo-font" href="#">Пиццерия ИС-231</a>                    
                    <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/about">О нас</a>
                        </li>                        
                    </ul>
                    </div>
                </div>
                </nav>
            </header>
            <div class="container">
            %s
            </div>
            <footer class="p-5">
                © 2025 «Кемеровский кооперативный техникум»
            <footer>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        </body>
        </html>
        LINE;

        return $html;
    }
}