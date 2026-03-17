<?php
namespace App\Views;

// Если BaseTemplate нужен только для наследования, оставьте. 
// Если нет - можно убрать extends и сделать класс обычным.
use App\Views\BaseTemplate; 

class ProductTemplate // Можно убрать extends BaseTemplate, если не используете его методы
{
    public static function getCardTemplate(array $data): string
    {
        // Если данных нет, возвращаем сообщение об ошибке
        if (empty($data)) {
            return self::renderPage("<h1>Товар не найден</h1>");
        }

        $name = htmlspecialchars($data['name']);
        $price = number_format($data['price'], 0, '.', ' ');
        $desc = htmlspecialchars($data['description']);
        $img = htmlspecialchars($data['image']);

        // HTML код самой карточки
        $cardHtml = "
        <div class='container mt-5'>
            <div class='card mb-3' style='max-width: 900px; margin: 0 auto;'>
                <div class='row g-0'>
                    <div class='col-md-4'>
                        <img src='$img' class='img-fluid rounded-start' alt='$name' style='object-fit: cover; height: 100%; min-height: 300px;'>
                    </div>
                    <div class='col-md-8'>
                        <div class='card-body'>
                            <h2 class='card-title'>$name</h2>
                            <p class='card-text lead'>$desc</p>
                            <hr>
                            <h3 class='text-primary'>$price ₽</h3>
                            <button class='btn btn-success btn-lg mt-3'>Добавить в корзину</button>
                            <a href='/' class='btn btn-secondary btn-lg mt-3 ms-2'>На главную</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ";

        // Возвращаем полную страницу
        return self::renderPage($cardHtml);
    }

    // Вспомогательный метод для сборки полной HTML страницы
    private static function renderPage(string $content): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карточка товара</title>
    <!-- Подключаем Bootstrap (пути могут отличаться, проверьте свои) -->
    <link href="/pizza221/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">Pizza Shop</a>
        </div>
    </nav>

    <main>
        $content
    </main>

    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="container p-4">
            <p class="text-muted">© 2026 Pizza Shop</p>
        </div>
    </footer>
    
    <script src="/pizza221/assets/js/bootstrap.bundle.js"></script>
</body>
</html>
HTML;
    }
}