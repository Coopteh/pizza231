<?php
namespace App\Views;

require_once __DIR__ . '/BaseTemplate.php';
require_once __DIR__ . '/../Models/Product.php'; // Подключаем модель

use App\Models\Product;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate(string $content = ''): string 
    {
        // 👇 Загружаем продукты через модель
        $productModel = new Product();
        $products = $productModel->loadData() ?? [];
        
        // 👇 Генерируем HTML для карточек товаров
        $productsHtml = self::renderProducts($products);

        // Формируем основной контент
        $ourContent = '
        <!-- Герой-блок (Баннер) -->
        <div class="hero-section text-center">
            <div class="container">
                <h1 class="display-4 fw-bold">Добро пожаловать на сайт запчастей для всех марок авто!</h1>
                <p class="lead">Запчасти разных марок в наличии и под заказ.</p>
                <a href="/catalog" class="btn btn-dark btn-lg mt-3">Каталог</a>
            </div>
        </div>

        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <h2 class="mb-3">Почему выбирают нас?</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent"><i class="bi bi-check-circle-fill text-success"></i> Оригинальные запчасти</li>
                        <li class="list-group-item bg-transparent"><i class="bi bi-check-circle-fill text-success"></i> Наличие большого количества деталей</li>
                        <li class="list-group-item bg-transparent"><i class="bi bi-check-circle-fill text-success"></i> Доступные цены</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <img src="/assets/img/123.jpg" 
                         alt="Запчасти" 
                         class="img-fluid rounded shadow-lg"
                         onerror="this.src=\'/assets/img/error.jpg\';">
                </div>
            </div>
            
            <!-- 👇 Секция с товарами -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="text-center mb-4">Каталог</h2>
                    ' . $productsHtml . '
                </div>
            </div>
        </div>
        ';
        
        return parent::getTemplate($ourContent);
    }
    
    /**
     * Рендерит карточки товаров
     */
    private static function renderProducts(array $products): string
    {
        if (empty($products)) {
            return '<p class="text-center text-muted">Товары временно отсутствуют</p>';
        }

        $html = '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';
        
        foreach ($products as $product) {
            // Экранируем вывод для безопасности
            $name = htmlspecialchars($product['name'] ?? 'Без названия');
            $description = htmlspecialchars($product['description'] ?? '');
            $price = number_format($product['price'] ?? 0, 0, '.', ' ');
            $image = htmlspecialchars($product['image'] ?? '/assets/img/no-image.jpg');
            $id = (int)($product['id'] ?? 0);
            
            $html .= '
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="' . $image . '" 
                         class="card-img-top" 
                         alt="' . $name . '"
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src=\'/assets/img/error.jpg\';">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">' . $name . '</h5>
                        <p class="card-text text-muted small flex-grow-1">' . $description . '</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="h5 mb-0 text-primary">' . $price . ' ₽</span>
                            <a href="/product/' . $id . '" class="btn btn-outline-primary btn-sm">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        $html .= '</div>';
        return $html;
    }
}