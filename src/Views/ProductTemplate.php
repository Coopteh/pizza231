<?php
namespace App\Views;

class ProductTemplate extends BaseTemplate
{
    public static function getCardTemplate($data): string
    {


        if (!$data) {
            return '
            <div class="container mt-5">
                <div class="alert alert-warning text-center shadow-sm" role="alert">
                    <h4 class="alert-heading">Товар не найден!</h4>
                    <p>К сожалению, товар с таким идентификатором отсутствует в нашем каталоге.</p>
                    <hr>
                    <a href="/product/1" class="btn btn-outline-warning">Попробовать товар №1</a>
                </div>
            </div>';
        }
        
        // Данные
        $image = $data['image'] ?? '';
        // Красивая заглушка, если картинки нет или путь битый
        $fallbackImage = 'https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        
        $title = htmlspecialchars($data['name'] ?? 'Без названия');
        $description = htmlspecialchars($data['description'] ?? 'Описание отсутствует.');
        $price = number_format((float)($data['price'] ?? 0), 0, '.', ' '); // Цена без копеек выглядит лучше для пиццы
        
        // Логика отображения картинки: если путь пустой или невалидный, ставим заглушку
        // (Простая проверка, можно усложнить при необходимости)
        $finalImage = (!empty($image)) ? $image : $fallbackImage;

        return parent::getTemplate('
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="row g-0 h-100">
                            
                            <!-- Блок с изображением -->
                            <div class="col-md-5 col-lg-4 bg-light d-flex align-items-center justify-content-center p-4" style="min-height: 300px;">
                                <img src="' . $finalImage . '" 
                                     class="img-fluid rounded-3 shadow-sm" 
                                     alt="' . $title . '" 
                                     style="max-height: 350px; width: 100%; object-fit: contain;"
                                     onerror="this.src=\'' . $fallbackImage . '\'; this.onerror=null;">
                            </div>
                            
                            <!-- Блок с информацией -->
                            <div class="col-md-7 col-lg-8">
                                <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                                    <h5 class="text-uppercase text-secondary fw-bold ls-1 mb-2" style="font-size: 0.9rem;">Наше меню</h5>
                                    <h2 class="card-title display-6 fw-bold text-dark mb-3">' . $title . '</h2>
                                    
                                    <p class="card-text text-muted lead mb-4" style="line-height: 1.6;">
                                        ' . $description . '
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex align-items-center mb-4">
                                            <span class="display-5 fw-bold text-primary me-3">' . $price . ' ₽</span>
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">В наличии</span>
                                        </div>
                                        
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                            <button type="button" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart-plus me-2" viewBox="0 0 16 16">
                                                    <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                                                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                                </svg>
                                                В корзину
                                            </button>
                                            <a href="/" class="btn btn-outline-secondary btn-lg px-4">На главную</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Дополнительная информация (опционально) -->
                    <div class="row mt-4 text-center text-muted small">
                        <div class="col-4">
                            <i class="bi bi-truck me-1"></i> Быстрая доставка
                        </div>
                        <div class="col-4">
                            <i class="bi bi-shield-check me-1"></i> Гарантия качества
                        </div>
                        <div class="col-4">
                            <i class="bi bi-heart me-1"></i> Свежие ингредиенты
                        </div>
                    </div>
                </div>
            </div>
        </div>');
    }
}