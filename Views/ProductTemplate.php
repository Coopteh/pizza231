<?php
namespace Views;

class ProductTemplate extends BaseTemplate
{
    public static function render(array $product): string
    {
        $template = parent::getTemplate();
        $title = $product['name'] . ' — Фитнес-клуб «gym low cortisol»';
        $featuresHtml = '';
        foreach ($product['features'] as $feature) {
            $featuresHtml .= '<li class="py-2 border-bottom">' . htmlspecialchars($feature) . '</li>';
        }

        $priceDisplay = $product['price'] > 0
            ? number_format($product['price'], 0, '.', ' ') . ' ₽/' . htmlspecialchars($product['period'])
            : 'По запросу';

        $isAuth = isset($_SESSION['user_id']);
        if ($isAuth && $product['price'] > 0) {
            $addToCartForm = '<form method="POST" action="/cart/add" class="mb-3"><input type="hidden" name="id" value="' . $product['id'] . '"><button type="submit" class="btn btn-primary-custom w-100">Добавить в корзину</button></form>';
        } elseif ($product['price'] > 0) {
            $addToCartForm = '<button type="button" class="btn btn-outline-secondary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#authModal">Добавить в корзину</button>';
        } else {
            $addToCartForm = '';
        }

        $authModal = '
        <div class="modal fade" id="authModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0"><h5 class="modal-title fw-bold">🔐 Требуется авторизация</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <p class="mb-4">Чтобы оформить карту, пожалуйста, войдите в аккаунт или зарегистрируйтесь.</p>
                        <div class="d-grid gap-2">
                            <a href="/login" class="btn btn-primary rounded-pill">Войти</a>
                            <a href="/register" class="btn btn-outline-primary rounded-pill">Зарегистрироваться</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

        $content = '
        <style>
        .product-header { background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%); border-radius: 20px; padding: 2.5rem 2rem; color: #fff; margin-bottom: 2rem; }
        .product-price { font-size: 2rem; font-weight: 700; margin: 1rem 0; }
        .coverage-badge { background: rgba(255,255,255,0.2); padding: 0.4rem 1.25rem; border-radius: 50px; display: inline-block; font-size: 0.95rem; }
        .feature-list { list-style: none; padding: 0; }
        .feature-list li { color: #475569; padding-left: 1.5rem; position: relative; }
        .feature-list li::before { content: "✓"; position: absolute; left: 0; color: #8b5cf6; font-weight: bold; }
        .back-link { color: #64748b; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; }
        .back-link:hover { color: #8b5cf6; }
        .btn-primary-custom { background: linear-gradient(135deg, #8b5cf6, #0dcaf0); border: none; padding: 12px 32px; border-radius: 50px; color: #fff; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary-custom:hover { color: #fff; text-decoration: none; opacity: 0.95; }
        </style>
        <section class="container py-5">
            <a href="/products" class="back-link">← Назад к картам</a>
            <div class="product-header">
                <h1 class="display-5 fw-bold mb-3">' . htmlspecialchars($product['name']) . '</h1>
                <p class="lead mb-4 opacity-90">' . htmlspecialchars($product['description']) . '</p>
                <div class="product-price">' . $priceDisplay . '</div>
                <span class="coverage-badge">Доступ: ' . htmlspecialchars($product['coverage']) . '</span>
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h4 class="fw-bold mb-4">Включено в карту</h4>
                        <ul class="feature-list">' . $featuresHtml . '</ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                        <h5 class="fw-bold mb-3">Оформление</h5>
                        <p class="text-muted mb-4">Активация клубной карты</p>
                        ' . $addToCartForm . '
                        <a href="/services#calculator" class="btn btn-outline-primary rounded-pill w-100 mb-3">Рассчитать</a>
                        <a href="tel:+79999999999" class="text-decoration-none fw-bold">+7 (999) 999-99-99</a>
                    </div>
                </div>
            </div>
        </section>' . $authModal;

        return sprintf($template, $title, $content);
    }
}