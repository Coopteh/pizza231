<?php
namespace App\Views;
class BaseTemplate{
    public static function getTemplate():string{
        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title> %s </title>
            <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
            <script src="../../assets/css/bootstrap.bundle.js"></script>
        </head>
        <body>
            <header>
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="../../assets/img/logo.jpg" alt="loading" width="64" height="64">
                        Кафе-Пиццерия Бон Густо
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Главная</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="#">О нас</a>
                        </li>
                    </ul>
                    </div>
                </div>
                </nav>
                <h1>Кафе-Пиццерия Бон Густо</h1>
                <main>%s</main>
            </header>
        </body>
        <footer class="mt-5">
            © 2025 «Кемеровский кооперативный техникум»
        <footer>
        </html>
        HTML;
    }
}
