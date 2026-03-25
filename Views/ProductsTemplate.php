<?php
namespace Views;

class ProductsTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Каталог - Продукты24';

        $customStyles = '
<style>
.catalog-header {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    border-radius: 0 0 60px 60px;
    padding: 4rem 2rem;
    text-align: center;
    color: white;
    margin-bottom: 3rem;
}
.catalog-header h2 {
    font-size: 3rem;
    font-weight: 900;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}
.product-card {
    background: white;
    border-radius: 25px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 40px rgba(255,107,53,0.1);
    border: 3px solid transparent;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none;
    color: inherit;
    display: block;
    position: relative;
    overflow: hidden;
}
.product-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #ff6b35, #f7931e);
}
.product-card:hover {
    transform: translateY(-15px) rotate(2deg);
    border-color: #ff6b35;
    box-shadow: 0 20px 60px rgba(255,107,53,0.25);
}
.product-emoji {
    font-size: 5rem;
    margin-bottom: 1rem;
    display: inline-block;
    transition: transform 0.3s ease;
}
.product-card:hover .product-emoji {
    transform: scale(1.2) rotate(-10deg);
}
.product-title {
    font-size: 1.5rem;
    font-weight: 900;
    color: #1a1a2e;
    margin-bottom: 0.5rem;
}
.product-desc {
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
}
.btn-product {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    font-weight: 700;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}
.btn-product:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(255,107,53,0.4);
    color: white;
    text-decoration: none;
}
.filter-bar {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 20px rgba(255,107,53,0.1);
}
</style>';

        $content = $customStyles . '
<div class="catalog-header">
    <h2>🛍️ Каталог продуктов</h2>
    <p class="lead mb-0" style="opacity: 0.95;">Выбирайте свежие продукты с доставкой на дом</p>
</div>

<section class="container py-4">
    <div class="filter-bar">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="fw-bold mb-0" style="color: #ff6b35;">📦 Все категории</h4>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">6 категорий</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <a href="/course/1" class="product-card">
                <div class="product-emoji">🍎</div>
                <h4 class="product-title">Фрукты</h4>
                <p class="product-desc">Свежие сезонные фрукты</p>
                <span class="btn-product">Подробнее →</span>
            </a>
            <form method="POST" action="/cart/add" class="mt-2 text-center">
                <input type="hidden" name="courseId" value="1">
                <button type="submit" class="btn btn-outline-warning rounded-pill px-4">🛒 В корзину</button>
            </form>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="/course/2" class="product-card">
                <div class="product-emoji">🥬</div>
                <h4 class="product-title">Овощи</h4>
                <p class="product-desc">Фермерские овощи</p>
                <span class="btn-product">Подробнее →</span>
            </a>
            <form method="POST" action="/cart/add" class="mt-2 text-center">
                <input type="hidden" name="courseId" value="2">
                <button type="submit" class="btn btn-outline-warning rounded-pill px-4">🛒 В корзину</button>
            </form>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="/course/3" class="product-card">
                <div class="product-emoji">🥛</div>
                <h4 class="product-title">Молочное</h4>
                <p class="product-desc">Молоко, сыр, творог</p>
                <span class="btn-product">Подробнее →</span>
            </a>
            <form method="POST" action="/cart/add" class="mt-2 text-center">
                <input type="hidden" name="courseId" value="3">
                <button type="submit" class="btn btn-outline-warning rounded-pill px-4">🛒 В корзину</button>
            </form>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="/course/4" class="product-card">
                <div class="product-emoji">🥩</div>
                <h4 class="product-title">Мясо</h4>
                <p class="product-desc">Свежее охлаждённое</p>
                <span class="btn-product">Подробнее →</span>
            </a>
            <form method="POST" action="/cart/add" class="mt-2 text-center">
                <input type="hidden" name="courseId" value="4">
                <button type="submit" class="btn btn-outline-warning rounded-pill px-4">🛒 В корзину</button>
            </form>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="/course/5" class="product-card">
                <div class="product-emoji">🐟</div>
                <h4 class="product-title">Рыба</h4>
                <p class="product-desc">Морепродукты daily</p>
                <span class="btn-product">Подробнее →</span>
            </a>
            <form method="POST" action="/cart/add" class="mt-2 text-center">
                <input type="hidden" name="courseId" value="5">
                <button type="submit" class="btn btn-outline-warning rounded-pill px-4">🛒 В корзину</button>
            </form>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="/course/6" class="product-card">
                <div class="product-emoji">🍞</div>
                <h4 class="product-title">Выпечка</h4>
                <p class="product-desc">Свежая каждый день</p>
                <span class="btn-product">Подробнее →</span>
            </a>
            <form method="POST" action="/cart/add" class="mt-2 text-center">
                <input type="hidden" name="courseId" value="6">
                <button type="submit" class="btn btn-outline-warning rounded-pill px-4">🛒 В корзину</button>
            </form>
        </div>
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}