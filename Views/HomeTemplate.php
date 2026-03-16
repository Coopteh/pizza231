<?php
namespace Views;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Главная - Страховая компания "Чёрный Вантуз"';
        
        // Путь к странице услуг. Замените 'services.php' на ваш реальный файл/роут.
        $servicesUrl = 'services.php'; 
        
        $customStyles = '
        <style>
            /* Стили для Герой-секции и Карусели */
            .hero-carousel .carousel-item {
                height: 550px;
                background-color: #000;
            }
            .hero-carousel img {
                height: 100%;
                object-fit: cover;
                opacity: 0.6; /* Затемнение фона для читаемости текста */
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
            .hero-caption p {
                font-size: 1.5rem;
                color: #f8f9fa;
            }

            /* Сетка преимуществ */
            .feature-card {
                border: none;
                border-radius: 20px;
                background: #fff;
                padding: 2rem 1.5rem;
                text-align: center;
                height: 100%;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                border: 1px solid #f0f2f5;
            }
            .feature-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15);
                border-color: #0d6efd;
            }
            .feature-icon-lg {
                font-size: 3.5rem;
                margin-bottom: 1.5rem;
                display: inline-block;
                background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
                width: 90px;
                height: 90px;
                line-height: 90px;
                border-radius: 50%;
                color: #0d6efd;
                transition: transform 0.3s ease;
            }
            .feature-card:hover .feature-icon-lg {
                transform: scale(1.1) rotate(5deg);
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                color: #fff;
            }

            /* Блок со скидкой */
            .promo-banner {
                background: linear-gradient(135deg, #198754 0%, #20c997 100%);
                border-radius: 25px;
                padding: 3rem;
                color: white;
                text-align: center;
                box-shadow: 0 15px 30px rgba(25, 135, 84, 0.3);
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
                text-decoration: none; /* Убираем подчеркивание для ссылок */
                display: inline-block;
            }
            .btn-hero-primary {
                background: #0d6efd;
                border: none;
                color: white;
                box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
            }
            .btn-hero-primary:hover {
                background: #0b5ed7;
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(13, 110, 253, 0.5);
                color: white;
            }
            .btn-hero-outline {
                background: transparent;
                border: 2px solid #fff;
                color: #fff;
            }
            .btn-hero-outline:hover {
                background: #fff;
                color: #0d6efd;
                transform: translateY(-3px);
                text-decoration: none;
            }

            .education-badge {
                background: #f8f9fa;
                border-left: 4px solid #0d6efd;
                padding: 1.5rem;
                border-radius: 0 10px 10px 0;
                font-size: 0.9rem;
                color: #6c757d;
                margin-top: 3rem;
            }
        </style>
        ';

        $content = $customStyles . '
        <!-- Герой-секция с Каруселью -->
        <section class="container-fluid p-0 mb-5">
            <div id="mainCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <!-- Слайд 1: Авто -->
                    <div class="carousel-item active">
                        <img src="/assets/images/img1.jpg" onerror="this.src=\'https://placehold.co/1920x1080/0d6efd/ffffff?text=Автострахование\'" class="d-block w-100" alt="Страхование авто">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>Автострахование</h5>
                            <p>Защитите свой автомобиль с надежной страховой компанией</p>
                            <!-- Ссылка ведет на страницу услуг + якорь на блок Авто -->
                            <a href="' . $servicesUrl . '#auto" class="btn btn-hero btn-hero-outline mt-3">Рассчитать ОСАГО</a>
                        </div>
                    </div>
                    
                    <!-- Слайд 2: Имущество -->
                    <div class="carousel-item">
                        <img src="/assets/images/img2.jpg" onerror="this.src=\'https://placehold.co/1920x1080/0dcaf0/ffffff?text=Имущество\'" class="d-block w-100" alt="Страхование имущества">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>Имущество и Недвижимость</h5>
                            <p>Полная защита вашего дома от любых рисков</p>
                            <!-- Ссылка ведет на страницу услуг + якорь на блок Имущество -->
                            <a href="' . $servicesUrl . '#property" class="btn btn-hero btn-hero-outline mt-3">Защитить жилье</a>
                        </div>
                    </div>
                    
                    <!-- Слайд 3: Здоровье -->
                    <div class="carousel-item">
                        <img src="/assets/images/img3.jpg" onerror="this.src=\'https://placehold.co/1920x1080/198754/ffffff?text=Здоровье\'" class="d-block w-100" alt="Медицинское страхование">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>Медицинское страхование</h5>
                            <p>Забота о вашем здоровье и здоровье ваших близких</p>
                            <!-- Ссылка ведет на страницу услуг + якорь на блок Здоровье -->
                            <a href="' . $servicesUrl . '#health" class="btn btn-hero btn-hero-outline mt-3">Выбрать клинику</a>
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
        
        <!-- Основной контент -->
        <main class="container py-4">
            
            <!-- Заголовок и вводная часть -->
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-9">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">Работаем с 2010 года</span>
                    <h2 class="display-5 fw-bold text-dark mb-4">🛡️ Страховая компания "Чёрный Вантуз"</h2>
                    <p class="lead text-muted">
                        Мы заботимся о вашем спокойствии уже более 10 лет. 
                        Надежность, проверенная тысячами клиентов и миллионами выплат.
                    </p>
                </div>
            </div>

            <!-- Сетка услуг -->
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-lg">🚗</div>
                        <h4 class="fw-bold">Автострахование</h4>
                        <p class="text-muted small">ОСАГО, КАСКО и помощь на дорогах.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-lg">🏠</div>
                        <h4 class="fw-bold">Имущество</h4>
                        <p class="text-muted small">Квартиры, дома, дачи и ипотека.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-lg">🏥</div>
                        <h4 class="fw-bold">Здоровье (ДМС)</h4>
                        <p class="text-muted small">Полисы для взрослых и детей.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-lg">💼</div>
                        <h4 class="fw-bold">Бизнес</h4>
                        <p class="text-muted small">Страхование ответственности и грузов.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon-lg">✈️</div>
                        <h4 class="fw-bold">Путешествия</h4>
                        <p class="text-muted small">Полисы для визы и отдыха за границей.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card" style="background: #f0f4ff; border-color: #0d6efd;">
                        <div class="feature-icon-lg" style="background: #fff; color: #0d6efd;">❤️</div>
                        <h4 class="fw-bold">Жизнь</h4>
                        <p class="text-muted small">Накопительное страхование жизни.</p>
                    </div>
                </div>
            </div>

            <!-- Промо блок со скидкой -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="promo-banner">
                        <h3 class="display-6 fw-bold mb-3">🎁 Скидка 10% при оформлении онлайн!</h3>
                        <p class="lead mb-4 opacity-75">
                            Оформите полис не выходя из дома и получите специальную цену.
                            <br>Быстрое оформление • Выгодные тарифы • Выплаты в течение 3 дней
                        </p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="' . $servicesUrl . '" class="btn btn-light btn-hero text-primary fw-bold">Рассчитать стоимость</a>
                            <a href="' . $servicesUrl . '" class="btn btn-outline-light btn-hero">Оформить полис</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Футер с информацией об обучении -->
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

        </main>
';
        
        return sprintf($template, $title, $content);
    }
}