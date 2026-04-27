<?php
namespace Views;

class ProductTemplate extends BaseTemplate
{
    public static array $courses = [
        1 => [
            'id' => 1,
            'title' => 'Фрукты и ягоды',
            'icon' => '🍎',
            'description' => 'Свежие сезонные фрукты и ягоды прямо от фермеров.',
            'features' => [
                '🍎 Яблоки, груши, апельсины',
                '🍓 Клубника, малина, черника',
                '🥭 Экзотические фрукты',
                '✅ Гарантия свежести'
            ],
            'price_from' => 'от 150 ₽/кг',
            'duration' => 'В наличии • Доставка 60 мин'
        ],
        2 => [
            'id' => 2,
            'title' => 'Овощи и зелень',
            'icon' => '🥬',
            'description' => 'Фермерские овощи и свежая зелень.',
            'features' => [
                '🥒 Огурцы, помидоры, перец',
                '🥔 Картофель, морковь, лук',
                '🌿 Свежая зелень',
                '✅ Органические продукты'
            ],
            'price_from' => 'от 80 ₽/кг',
            'duration' => 'В наличии • Доставка 60 мин'
        ],
        3 => [
            'id' => 3,
            'title' => 'Молочные продукты',
            'icon' => '🥛',
            'description' => 'Молоко, сыр, творог и йогурты.',
            'features' => [
                '🥛 Свежее молоко',
                '🧀 Твёрдые и мягкие сыры',
                '🥣 Натуральный творог',
                '✅ Йогурты без добавок'
            ],
            'price_from' => 'от 90 ₽',
            'duration' => 'В наличии • Доставка 60 мин'
        ],
        4 => [
            'id' => 4,
            'title' => 'Мясо и птица',
            'icon' => '🥩',
            'description' => 'Свежее охлаждённое мясо и птица.',
            'features' => [
                '🥩 Говядина, свинина',
                '🍗 Курица, индейка',
                '🥟 Фарш домашний',
                '✅ Халяль опция'
            ],
            'price_from' => 'от 350 ₽/кг',
            'duration' => 'В наличии • Доставка 60 мин'
        ],
        5 => [
            'id' => 5,
            'title' => 'Рыба и морепродукты',
            'icon' => '🐟',
            'description' => 'Свежая рыба и морепродукты daily.',
            'features' => [
                '🐟 Лосось, форель',
                '🦐 Креветки, мидии',
                '🐠 Речная рыба',
                '✅ Заморозка и охлаждение'
            ],
            'price_from' => 'от 450 ₽/кг',
            'duration' => 'В наличии • Доставка 60 мин'
        ],
        6 => [
            'id' => 6,
            'title' => 'Хлеб и выпечка',
            'icon' => '🍞',
            'description' => 'Свежая выпечка ежедневно.',
            'features' => [
                '🍞 Белый и чёрный хлеб',
                '🥐 Булочки, круассаны',
                '🎂 Торты на заказ',
                '✅ Безглютеновая опция'
            ],
            'price_from' => 'от 50 ₽',
            'duration' => 'В наличии • Доставка 60 мин'
        ]
    ];

    public static function renderCourse(int $courseId): string
    {
        $template = parent::getTemplate();
        $course = self::$courses[$courseId] ?? null;

        if (!$course) {
            http_response_code(404);
            return '<h1>Товар не найден</h1>';
        }

        $title = $course['title'] . ' - Продукты24';

        $customStyles = '
<style>
.product-detail-hero {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    border-radius: 30px;
    padding: 3rem;
    color: white;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}
.product-detail-hero::before {
    content: "' . $course['icon'] . '";
    position: absolute;
    right: -30px;
    bottom: -50px;
    font-size: 15rem;
    opacity: 0.1;
}
.hero-emoji {
    font-size: 5rem;
    background: white;
    width: 120px;
    height: 120px;
    line-height: 120px;
    border-radius: 50%;
    display: inline-block;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.feature-box {
    background: #fff5f0;
    border-left: 5px solid #ff6b35;
    padding: 1.5rem;
    border-radius: 0 20px 20px 0;
    margin-bottom: 1rem;
}
.feature-box li {
    padding: 0.5rem 0;
    font-size: 1.1rem;
    list-style: none;
}
.order-panel {
    background: linear-gradient(135deg, #fff5f0, #ffffff);
    border: 3px dashed #ff6b35;
    border-radius: 25px;
    padding: 2.5rem;
    text-align: center;
}
.btn-order {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    color: white;
    border: none;
    padding: 1rem 3rem;
    font-size: 1.2rem;
    font-weight: 900;
    border-radius: 50px;
    width: 100%;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}
.btn-order:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(255,107,53,0.4);
    color: white;
}
.back-btn {
    color: #666;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: inline-block;
}
.back-btn:hover {
    color: #ff6b35;
    text-decoration: none;
}
</style>';

        $featuresHtml = '';
        foreach ($course['features'] as $feature) {
            $featuresHtml .= '<li>' . $feature . '</li>';
        }

        $content = $customStyles . '
<section class="container py-5">
    <a href="/products" class="back-btn">← Назад в каталог</a>
    
    <div class="product-detail-hero">
        <div class="hero-emoji">' . $course['icon'] . '</div>
        <h1 class="display-4 fw-bold mb-3">' . $course['title'] . '</h1>
        <p class="lead mb-4" style="opacity: 0.95;">' . $course['description'] . '</p>
        <div class="fs-2 fw-bold">' . $course['price_from'] . '</div>
        <span class="badge bg-white text-dark px-3 py-2 mt-2">' . $course['duration'] . '</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h3 class="fw-bold mb-4" style="color: #ff6b35;">✨ В категории</h3>
                <ul class="feature-box" style="padding: 0; margin: 0;">' . $featuresHtml . '</ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="order-panel">
                <h4 class="fw-bold mb-3">🛒 Готовы заказать?</h4>
                <p class="text-muted mb-4">Добавьте в корзину прямо сейчас</p>
                <form method="POST" action="/cart/add">
                    <input type="hidden" name="courseId" value="' . $course['id'] . '">
                    <button type="submit" class="btn-order">
                        Добавить в корзину
                    </button>
                </form>
                <p class="small text-muted mb-0">📞 +7 (999) 123-45-67</p>
            </div>
        </div>
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    public static function getTemplate(): string
    {
        return parent::getTemplate();
    }
}