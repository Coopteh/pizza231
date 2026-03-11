<?php
namespace App\Views;

class HomeTemplate extends BaseTemplate {
    public static function getTemplate(): string {
        $template = parent::getTemplate();
        $title = 'Главная страница — Супермаркет ИС-231';
        $content = <<<LINE
        <!-- Герой-секция с промо -->
        <section class="mb-5">
            <div class="p-4 p-md-5 bg-light rounded-3 border">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="display-5 fw-bold text-dark">Свежие продукты с доставкой на дом</h1>
                        <p class="lead text-muted mb-4">
                            Заказывайте продукты онлайн — привезём в течение 2 часов! 
                            Скидки до 30% на товары недели.
                        </p>
                        <a href="/catalog" class="btn btn-success btn-lg px-4 me-2">
                            🛒 Перейти в каталог
                        </a>
                        <a href="/promotions" class="btn btn-outline-secondary btn-lg px-4">
                            🔥 Акции
                        </a>
                    </div>
                    <div class="col-md-6 text-center">
                        <img src="/assets/img/hero-groceries.svg" alt="Свежие продукты" class="img-fluid" style="max-height: 300px;">
                    </div>
                </div>
            </div>
        </section>

        <!-- Карусель акций -->
        <section class="mb-5">
            <h2 class="h4 mb-3">🎁 Горячие предложения</h2>
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded-3 shadow-sm" style="height: 400px;">
                    <div class="carousel-item active">
                        <img src="/assets/img/promo-fresh.jpg" 
                            class="d-block w-100 h-100" 
                            style="object-fit: fill;" 
                            alt="Свежие овощи и фрукты">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3">
                            <h5>Свежие фрукты и овощи</h5>
                            <p>Прямые поставки от фермеров — гарантия качества</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/assets/img/promo-dairy.jpg" 
                            class="d-block w-100 h-100" 
                            style="object-fit: fill;" 
                            alt="Молочная продукция">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3">
                            <h5>Молочная продукция</h5>
                            <p>Скидка 20% на творог, сыр и йогурты до конца недели</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/assets/img/promo-household.jpg" 
                            class="d-block w-100 h-100" 
                            style="object-fit: fill;" 
                            alt="Товары для дома">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3">
                            <h5>Товары для дома</h5>
                            <p>Бытовая химия и гигиена по выгодным ценам</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Предыдущий</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Следующий</span>
                </button>
            </div>
        </section>

        <!-- Преимущества -->
        <section class="mb-5">
            <h2 class="h4 mb-4">Почему выбирают нас</h2>
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="card h-100 border-0 text-center shadow-sm">
                        <div class="card-body">
                            <div class="fs-1 mb-2">🚚</div>
                            <h6 class="card-title">Быстрая доставка</h6>
                            <p class="card-text small text-muted mb-0">от 45 минут</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card h-100 border-0 text-center shadow-sm">
                        <div class="card-body">
                            <div class="fs-1 mb-2">🥬</div>
                            <h6 class="card-title">Свежие продукты</h6>
                            <p class="card-text small text-muted mb-0">каждый день</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card h-100 border-0 text-center shadow-sm">
                        <div class="card-body">
                            <div class="fs-1 mb-2">💳</div>
                            <h6 class="card-title">Удобная оплата</h6>
                            <p class="card-text small text-muted mb-0">картой или наличными</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card h-100 border-0 text-center shadow-sm">
                        <div class="card-body">
                            <div class="fs-1 mb-2">🎁</div>
                            <h6 class="card-title">Бонусная система</h6>
                            <p class="card-text small text-muted mb-0">копите и экономьте</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Призыв к действию + примечание -->
        <section class="text-center py-4 bg-light rounded-3">
            <h3 class="mb-3">Готовы сделать заказ?</h3>
            <p class="lead mb-4">Первый заказ — скидка 15% по промокоду <strong>WELCOME15</strong></p>
            <a href="/catalog" class="btn btn-success btn-lg">Начать покупки</a>
            
            <p class="mt-5 small text-muted">
                (*) Сайт разработан в рамках обучения в «Кемеровском кооперативном техникуме»<br>
                по специальности 09.02.07 «Специалист по информационным системам и программированию».
            </p>
        </section>
        LINE;
        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}