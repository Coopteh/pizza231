<?php
namespace Views;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Главная - Продукты24 | Свежие продукты с доставкой на дом';
        $servicesUrl = '/products';

        $customStyles = '
<style>
.hero-carousel .carousel-item {
    height: 550px;
    background-color: #000;
}
.hero-carousel img {
    height: 100%;
    object-fit: cover;
    opacity: 0.5;
}
.hero-caption {
    bottom: 20%;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}
.hero-caption h5 {
    font-size: 3rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 1rem;
}
.feature-card {
    border: none;
    border-radius: 20px;
    background: #fff;
    padding: 2rem 1.5rem;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border: 1px solid #eef2f5;
}
.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(34, 197, 94, 0.15);
    border-color: #22c55e;
}
.feature-icon-lg {
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    display: inline-block;
    background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%);
    width: 90px;
    height: 90px;
    line-height: 90px;
    border-radius: 50%;
    color: #22c55e;
    transition: transform 0.3s ease;
}
.feature-card:hover .feature-icon-lg {
    transform: scale(1.1) rotate(5deg);
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #fff;
}
.promo-banner {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    border-radius: 25px;
    padding: 3rem;
    color: white;
    text-align: center;
    box-shadow: 0 15px 30px rgba(34, 197, 94, 0.3);
    margin: 4rem 0;
    position: relative;
    overflow: hidden;
}
.promo-banner::before {
    content: "🎁";
    position: absolute;
    top: -20px;
    right: -20px;
    font-size: 10rem;
    opacity: 0.1;
    transform: rotate(15deg);
}
.btn-hero {
    padding: 15px 40px;
    font-size: 1.1rem;
    border-radius: 50px;
    font-weight: 700;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}
.btn-hero-primary {
    background: #22c55e;
    border: none;
    color: white;
    box-shadow: 0 5px 15px rgba(34, 197, 94, 0.4);
}
.btn-hero-outline {
    background: transparent;
    border: 2px solid #fff;
    color: #fff;
}
.btn-hero-outline:hover {
    background: #fff;
    color: #22c55e;
    transform: translateY(-3px);
    text-decoration: none;
}
.education-badge {
    background: #f0fdf4;
    border-left: 4px solid #22c55e;
    padding: 1.5rem;
    border-radius: 0 10px 10px 0;
    font-size: 0.9rem;
    color: #166534;
    margin-top: 3rem;
}
.btn-custom {
    border-radius: 50px;
    padding: 10px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid #22c55e;
    color: #22c55e;
    background: transparent;
}
.btn-custom:hover {
    background: #22c55e;
    color: #fff;
}
</style>';

        $content = $customStyles . '
<section class="container-fluid p-0 mb-5">
    <div id="mainCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner">
            <!-- Слайд 1: Фрукты -->
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=1920&h=1080&fit=crop" 
                     class="d-block w-100" alt="Свежие фрукты">
                <div class="carousel-caption d-none d-md-block hero-caption">
                    <h5>🍎 Свежие фрукты</h5>
                    <p>Сезонные и экзотические фрукты прямо от фермеров</p>
                    <a href="' . $servicesUrl . '#fruits" class="btn btn-hero btn-hero-outline mt-3">Выбрать фрукты</a>
                </div>
            </div>
            <!-- Слайд 2: Овощи -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?w=1920&h=1080&fit=crop" 
                     class="d-block w-100" alt="Фермерские овощи">
                <div class="carousel-caption d-none d-md-block hero-caption">
                    <h5>🥬 Фермерские овощи</h5>
                    <p>Свежие овощи с доставкой на дом каждый день</p>
                    <a href="' . $servicesUrl . '#vegetables" class="btn btn-hero btn-hero-outline mt-3">Выбрать овощи</a>
                </div>
            </div>
            <!-- Слайд 3: Молочные продукты -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1628088062854-d1870b4553da?w=1920&h=1080&fit=crop" 
                     class="d-block w-100" alt="Молочные продукты">
                <div class="carousel-caption d-none d-md-block hero-caption">
                    <h5>🥛 Молочные продукты</h5>
                    <p>Молоко, сыр, творог от проверенных производителей</p>
                    <a href="' . $servicesUrl . '#dairy" class="btn btn-hero btn-hero-outline mt-3">Выбрать молочное</a>
                </div>
            </div>
            <!-- Слайд 4: Мясо -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1607623814075-e51df1bd6565?w=1920&h=1080&fit=crop" 
                     class="d-block w-100" alt="Свежее мясо">
                <div class="carousel-caption d-none d-md-block hero-caption">
                    <h5>🥩 Свежее мясо</h5>
                    <p>Охлаждённое мясо и птица высшего качества</p>
                    <a href="' . $servicesUrl . '#meat" class="btn btn-hero btn-hero-outline mt-3">Выбрать мясо</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Предыдущий</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Следующий</span>
        </button>
    </div>
</section>

<main class="container py-4">
    <div class="row justify-content-center text-center mb-5">
        <div class="col-lg-9">
            <span class="badge bg-success bg-opacity-10 text-success mb-3 px-3 py-2 rounded-pill">🛒 Работаем с 2020 года</span>
            <h2 class="display-5 fw-bold text-dark mb-4">🥦 Продукты24</h2>
            <p class="lead text-muted">
                Мы доставляем свежие продукты с 2020 года.
                Надёжность, проверенная тысячами покупателей.
            </p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="feature-card">
                <div class="feature-icon-lg">🍎</div>
                <h4 class="fw-bold">Фрукты и ягоды</h4>
                <p class="text-muted small">Свежие сезонные фрукты и ягоды.</p>
                <form method="POST" action="/cart/add" class="mt-3">
                    <input type="hidden" name="courseId" value="1">
                    <button type="submit" class="btn btn-custom">🛒 В корзину</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="feature-card">
                <div class="feature-icon-lg">🥬</div>
                <h4 class="fw-bold">Овощи и зелень</h4>
                <p class="text-muted small">Фермерские овощи каждый день.</p>
                <form method="POST" action="/cart/add" class="mt-3">
                    <input type="hidden" name="courseId" value="2">
                    <button type="submit" class="btn btn-custom">🛒 В корзину</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="feature-card">
                <div class="feature-icon-lg">🥛</div>
                <h4 class="fw-bold">Молочные продукты</h4>
                <p class="text-muted small">Молоко, сыр, творог, йогурты.</p>
                <form method="POST" action="/cart/add" class="mt-3">
                    <input type="hidden" name="courseId" value="3">
                    <button type="submit" class="btn btn-custom">🛒 В корзину</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="feature-card">
                <div class="feature-icon-lg">🥩</div>
                <h4 class="fw-bold">Мясо и птица</h4>
                <p class="text-muted small">Свежее охлаждённое мясо.</p>
                <form method="POST" action="/cart/add" class="mt-3">
                    <input type="hidden" name="courseId" value="4">
                    <button type="submit" class="btn btn-custom">🛒 В корзину</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="feature-card">
                <div class="feature-icon-lg">🐟</div>
                <h4 class="fw-bold">Рыба и морепродукты</h4>
                <p class="text-muted small">Свежая рыба и морепродукты.</p>
                <form method="POST" action="/cart/add" class="mt-3">
                    <input type="hidden" name="courseId" value="5">
                    <button type="submit" class="btn btn-custom">🛒 В корзину</button>
                </form>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="feature-card" style="background: #f0fdf4; border-color: #22c55e;">
                <div class="feature-icon-lg" style="background: #fff; color: #22c55e;">🍞</div>
                <h4 class="fw-bold">Хлеб и выпечка</h4>
                <p class="text-muted small">Свежая выпечка ежедневно.</p>
                <form method="POST" action="/cart/add" class="mt-3">
                    <input type="hidden" name="courseId" value="6">
                    <button type="submit" class="btn btn-custom">🛒 В корзину</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="promo-banner">
                <h3 class="display-6 fw-bold mb-3">🎁 Скидка 20% на первый заказ!</h3>
                <p class="lead mb-4 opacity-75">
                    Закажите продукты не выходя из дома и получите специальную цену.
                    <br>Быстрая доставка • Свежие продукты • Бонусная система
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="' . $servicesUrl . '" class="btn btn-light btn-hero text-success fw-bold">Выбрать продукты</a>
                    <a href="' . $servicesUrl . '" class="btn btn-outline-light btn-hero">Бесплатная доставка</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="education-badge">
                <p class="mb-1"><strong>Учебный проект</strong></p>
                <p class="mb-0">
                    Сайт разработан в рамках обучения в <strong>"Кузбасском кооперативном техникуме"</strong><br>
                    по специальности <em>"Специалист по информационным технологиям"</em>.
                </p>
            </div>
        </div>
    </div>
</main>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}