<?php
namespace Views;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Главная страница - Страховая компания "чёрный вантуз"';
        
        $content = '
        <!-- Карусель изображений -->
        <section class="container my-4">
            <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner rounded">
                    <div class="carousel-item active">
                        <img src="/assets/images/img1.jpg" class="d-block w-100" alt="Страхование авто" style="height: 400px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                            <h5>Автострахование</h5>
                            <p>Защитите свой автомобиль с надежной страховой компанией</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/assets/images/img2.jpg" class="d-block w-100" alt="Страхование имущества" style="height: 400px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                            <h5>Страхование имущества</h5>
                            <p>Защита вашего дома и имущества от любых рисков</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/assets/images/img3.jpg" class="d-block w-100" alt="Медицинское страхование" style="height: 400px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                            <h5>Медицинское страхование</h5>
                            <p>Забота о вашем здоровье и здоровье ваших близких</p>
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
        
        <!-- Основное описание -->
        <main class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-4 text-primary">🛡️ Страховая компания "чёрный вантуз"</h2>
                    <p class="lead">
                        <strong>Мы заботимся о вашем спокойствии с 2010 года!</strong><br><br>
                        Предлагаем полный спектр страховых услуг для физических и юридических лиц:<br>
                        ✅ Автострахование (ОСАГО, КАСКО)<br>
                        ✅ Страхование имущества и недвижимости<br>
                        ✅ Медицинское страхование (ДМС)<br>
                        ✅ Страхование жизни и здоровья<br>
                        ✅ Корпоративное страхование<br><br>
                        <span class="text-success fw-bold">🎁 Скидка 10% при оформлении онлайн!</span><br>
                        <span class="text-muted">Быстрое оформление • Выгодные тарифы • Выплаты в течение 5 дней</span>
                    </p>
                    <div class="mt-4">
                        <a href="#" class="btn btn-primary btn-lg me-2">Рассчитать стоимость</a>
                        <a href="#" class="btn btn-outline-secondary btn-lg">Оформить полис</a>
                    </div>
                    <p class="text-muted small mt-5 pt-3 border-top">
                        * Сайт разработан в рамках обучения в "Кузбасском кооперативном техникуме" 
                        по специальности "Специалист по информационным технологиям".
                    </p>
                </div>
            </div>
        </main>';
        
        return sprintf($template, $title, $content);
    }
}