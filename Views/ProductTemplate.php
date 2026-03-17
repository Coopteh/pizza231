<?php
namespace Views;

class ProductTemplate extends BaseTemplate
{
    /**
     * Страница конкретного продукта
     */
    public static function renderProduct(int $productId, array $data): string
    {
        $template = parent::getTemplate();
        $product = null;
        
        foreach ($data as $item) {
            if ($item['id'] === $productId) {
                $product = $item;
                break;
            }
        }
        
        if (!$product) {
            http_response_code(404);
            return (new \Controllers\ErrorController())->get();
        }
        
        $title = $product['name'] . ' — Страховая компания «Чёрный Вантуз»';
        
        $featuresHtml = '';
        foreach ($product['features'] as $feature) {
            $featuresHtml .= '<li class="py-2 border-bottom">' . $feature . '</li>';
        }
        
        $priceDisplay = $product['price'] > 0 
            ? number_format($product['price'], 0, '.', ' ') . ' ₽/' . $product['period']
            : 'По запросу';
        
        $content = '
        <style>
            .product-header {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                border-radius: 20px;
                padding: 2.5rem 2rem;
                color: #fff;
                margin-bottom: 2rem;
            }
            .product-price { font-size: 2rem; font-weight: 700; margin: 1rem 0; }
            .coverage-badge {
                background: rgba(255,255,255,0.2);
                padding: 0.4rem 1.25rem;
                border-radius: 50px;
                display: inline-block;
                font-size: 0.95rem;
            }
            .feature-list { list-style: none; padding: 0; }
            .feature-list li { color: #475569; padding-left: 1.5rem; position: relative; }
            .feature-list li::before {
                content: "✓";
                position: absolute;
                left: 0;
                color: #0d6efd;
                font-weight: bold;
            }
            .back-link {
                color: #64748b;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1.5rem;
            }
            .back-link:hover { color: #0d6efd; }
            .btn-primary-custom {
                background: linear-gradient(135deg, #0d6efd, #0dcaf0);
                border: none;
                padding: 12px 32px;
                border-radius: 50px;
                color: #fff;
                font-weight: 600;
            }
        </style>
        
        <section class="container py-5">
            <a href="/products" class="back-link">← Назад к каталогу</a>
            
            <div class="product-header">
                <h1 class="display-5 fw-bold mb-3">' . $product['name'] . '</h1>
                <p class="lead mb-4 opacity-90">' . $product['description'] . '</p>
                <div class="product-price">' . $priceDisplay . '</div>
                <span class="coverage-badge">Покрытие: ' . $product['coverage'] . '</span>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h4 class="fw-bold mb-4">Преимущества</h4>
                        <ul class="feature-list">' . $featuresHtml . '</ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                        <h5 class="fw-bold mb-3">Оформление</h5>
                        <p class="text-muted mb-4">Расчёт стоимости полиса</p>
                        <a href="/services#calculator" class="btn btn-primary-custom w-100 mb-3">Рассчитать</a>
                        <a href="tel:+79999999999" class="text-decoration-none fw-bold">+7 (999) 999-99-99</a>
                    </div>
                </div>
            </div>
        </section>';
        
        return sprintf($template, $title, $content);
    }

    /**
     * Страница каталога всех продуктов
     */
    public static function getAllTemplate(array $products): string
    {
        $template = parent::getTemplate();
        $title = 'Каталог услуг — Страховая компания «Чёрный Вантуз»';
        
        $itemsHtml = '';
        foreach ($products as $product) {
            $priceDisplay = $product['price'] > 0 
                ? number_format($product['price'], 0, '.', ' ') . ' ₽/' . $product['period']
                : 'По запросу';
            
            $itemsHtml .= '
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-4 col-lg-3">
                        <img src="' . $product['image'] . '" 
                             class="img-fluid h-100 w-100" 
                             style="object-fit: cover; min-height: 200px;"
                             alt="' . $product['name'] . '"
                             onerror="this.src=\'https://placehold.co/400x300/0d6efd/ffffff?text=' . urlencode($product['name']) . '\'">
                    </div>
                    <div class="col-md-8 col-lg-9">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h3 class="card-title h4 fw-bold mb-0">
                                    <a href="/products/' . $product['id'] . '" class="text-decoration-none text-dark">
                                        ' . $product['name'] . '
                                    </a>
                                </h3>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                    ' . $priceDisplay . '
                                </span>
                            </div>
                            <p class="card-text text-muted mb-4">' . $product['description'] . '</p>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-light text-dark border">' . $product['coverage'] . '</span>
                                ' . self::renderFeaturesBadges($product['features']) . '
                            </div>
                            <a href="/products/' . $product['id'] . '" class="btn btn-outline-primary rounded-pill px-4">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        $content = '
        <style>
            .catalog-header {
                text-align: center;
                margin-bottom: 3rem;
            }
            .catalog-title {
                font-size: 2rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.75rem;
            }
            .catalog-subtitle {
                color: #64748b;
                font-size: 1.05rem;
                max-width: 600px;
                margin: 0 auto;
            }
            .divider {
                width: 70px;
                height: 4px;
                background: linear-gradient(90deg, #0d6efd, #0dcaf0);
                margin: 20px auto;
                border-radius: 2px;
            }
            .card:hover {
                transform: translateY(-4px);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                box-shadow: 0 12px 30px rgba(13, 110, 253, 0.15) !important;
            }
            .badge {
                font-weight: 500;
                font-size: 0.85rem;
            }
        </style>
        
        <section class="container py-5">
            <div class="catalog-header">
                <h2 class="catalog-title">Каталог страховых услуг</h2>
                <p class="catalog-subtitle">
                    Выберите подходящий продукт для защиты ваших интересов
                </p>
                <div class="divider"></div>
            </div>
            
            ' . $itemsHtml . '
        </section>';
        
        return sprintf($template, $title, $content);
    }
    
    /**
     * Вспомогательный метод для рендеринга бейджей преимуществ
     */
    private static function renderFeaturesBadges(array $features): string
    {
        $html = '';
        $limit = min(3, count($features));
        for ($i = 0; $i < $limit; $i++) {
            $html .= '<span class="badge bg-light text-dark border">' . $features[$i] . '</span>';
        }
        return $html;
    }

    /**
     * Обязательный метод для совместимости с наследованием
     */
    public static function getTemplate(): string
    {
        return parent::getTemplate();
    }
}