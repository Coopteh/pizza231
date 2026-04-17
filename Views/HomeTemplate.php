<?php
namespace Views;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Главная — Страховая компания «Чёрный Вантуз»';
        $servicesUrl = '/services';
        
        $customStyles = '
        <style>
            /* === Оптимизированная карусель — без чёрных полос === */
            .hero-carousel {
                position: relative;
                background: #000;
            }
            
            .hero-carousel .carousel-item {
                height: 500px;
                background: #000;
            }
            
            .hero-carousel .carousel-item img {
                /* Картинка заполняет всю область без чёрных полос */
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                display: block;
            }
            
            /* Затемнение поверх картинки для читаемости текста */
            .hero-carousel .carousel-item::after {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.4);
                pointer-events: none;
            }
            
            .hero-caption {
                bottom: 25%;
                left: 50%;
                transform: translateX(-50%);
                text-align: center;
                text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
                max-width: 800px;
                padding: 0 20px;
                z-index: 10;
            }
            
            .hero-caption h5 {
                font-size: 2.5rem;
                font-weight: 700;
                color: #ffffff;
                margin-bottom: 1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            
            .hero-caption p {
                font-size: 1.2rem;
                color: #f8f9fa;
                margin-bottom: 1.5rem;
                line-height: 1.5;
            }
            
            .btn-hero {
                padding: 12px 36px;
                font-size: 1rem;
                border-radius: 50px;
                font-weight: 600;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }
            
            .btn-hero-outline {
                background: transparent;
                border: 2px solid #ffffff;
                color: #ffffff;
            }
            
            .btn-hero-outline:hover {
                background: #ffffff;
                color: #0d6efd;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
                text-decoration: none;
            }
            
            /* Адаптивность карусели */
            @media (max-width: 992px) {
                .hero-carousel .carousel-item {
                    height: 400px;
                }
                .hero-caption h5 {
                    font-size: 2rem;
                }
                .hero-caption p {
                    font-size: 1rem;
                }
            }
            
            @media (max-width: 768px) {
                .hero-carousel .carousel-item {
                    height: 350px;
                }
                .hero-caption {
                    bottom: 20%;
                    padding: 0 15px;
                }
                .hero-caption h5 {
                    font-size: 1.5rem;
                    margin-bottom: 0.75rem;
                }
                .hero-caption p {
                    font-size: 0.9rem;
                    margin-bottom: 1rem;
                }
                .btn-hero {
                    padding: 10px 24px;
                    font-size: 0.9rem;
                }
            }
            
            @media (max-width: 576px) {
                .hero-carousel .carousel-item {
                    height: 300px;
                }
                .hero-caption h5 {
                    font-size: 1.25rem;
                }
                .hero-caption p {
                    font-size: 0.85rem;
                }
            }
            
            /* === Карточки услуг === */
            .feature-card {
                border: none;
                border-radius: 16px;
                background: #ffffff;
                padding: 1.75rem 1.25rem;
                text-align: center;
                height: 100%;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: 1px solid #eef2f7;
            }
            
            .feature-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 30px rgba(13, 110, 253, 0.15);
                border-color: #0d6efd;
            }
            
            .feature-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                width: 72px;
                height: 72px;
                line-height: 72px;
                border-radius: 50%;
                margin: 0 auto 1rem;
                background: linear-gradient(135deg, #eef2ff 0%, #f0f4ff 100%);
                color: #0d6efd;
                transition: all 0.3s ease;
            }
            
            .feature-card:hover .feature-icon {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                color: #ffffff;
                transform: scale(1.1);
            }
            
            /* === Промо блок === */
            .promo-banner {
                background: linear-gradient(135deg, #198754 0%, #20c997 100%);
                border-radius: 20px;
                padding: 2.5rem 2rem;
                color: #ffffff;
                text-align: center;
                margin: 3rem 0;
                position: relative;
                overflow: hidden;
            }
            
            .promo-banner::before {
                content: "🎁";
                position: absolute;
                top: -20px;
                right: -20px;
                font-size: 8rem;
                opacity: 0.1;
                transform: rotate(15deg);
            }
            
            .btn-hero-primary {
                background: #ffffff;
                border: none;
                color: #198754;
                font-weight: 600;
                padding: 12px 32px;
                border-radius: 50px;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }
            
            .btn-hero-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
                color: #198754;
                text-decoration: none;
            }
            
            .btn-hero-outline-light {
                background: transparent;
                border: 2px solid #ffffff;
                color: #ffffff;
                padding: 12px 32px;
                border-radius: 50px;
                font-weight: 600;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-block;
            }
            
            .btn-hero-outline-light:hover {
                background: #ffffff;
                color: #198754;
                transform: translateY(-2px);
                text-decoration: none;
            }
            
            /* === Информационный блок === */
            .education-note {
                background: #f8f9fa;
                border-left: 4px solid #0d6efd;
                padding: 1.25rem;
                border-radius: 0 8px 8px 0;
                font-size: 0.9rem;
                color: #6c757d;
                margin-top: 2rem;
            }
            
            /* === Заголовки секций === */
            .section-title {
                font-size: 2rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.75rem;
            }
            
            .section-subtitle {
                color: #64748b;
                font-size: 1.05rem;
                line-height: 1.6;
            }
            
            /* === Индикаторы карусели === */
            .carousel-indicators {
                bottom: 20px;
                z-index: 15;
            }
            
            .carousel-indicators button {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin: 0 6px;
                background-color: rgba(255, 255, 255, 0.5);
                border: none;
            }
            
            .carousel-indicators button.active {
                background-color: #ffffff;
                opacity: 1;
            }
            
            /* === Кнопки управления каруселью === */
            .carousel-control-prev,
            .carousel-control-next {
                width: 50px;
                height: 50px;
                top: 50%;
                transform: translateY(-50%);
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: 15;
            }
            
            .hero-carousel:hover .carousel-control-prev,
            .hero-carousel:hover .carousel-control-next {
                opacity: 0.8;
            }
            
            .carousel-control-prev:hover,
            .carousel-control-next:hover {
                opacity: 1 !important;
            }
            
            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 40px;
                height: 40px;
                background-color: rgba(255, 255, 255, 0.9);
                border-radius: 50%;
                padding: 8px;
            }
        </style>';

        $content = $customStyles . '
        <!-- Герой-секция с каруселью -->
        <section class="container-fluid p-0 mb-5">
            <!-- data-bs-interval="4000" = автопрокрутка каждые 4 секунды -->
            <div id="mainCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                
                <div class="carousel-inner">
                    <!-- Слайд 1: Авто -->
                    <div class="carousel-item active">
                        <img src="/assets/images/img1.jpg" 
                             onerror="this.src=\'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=1920&h=800&fit=crop\'" 
                             class="d-block w-100" 
                             alt="Автострахование">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>Автострахование</h5>
                            <p>Надёжная защита вашего транспортного средства. ОСАГО и КАСКО с быстрым оформлением.</p>
                            <a href="' . $servicesUrl . '#auto" class="btn btn-hero btn-hero-outline">Рассчитать полис</a>
                        </div>
                    </div>
                    
                    <!-- Слайд 2: Имущество -->
                    <div class="carousel-item">
                        <img src="/assets/images/img2.jpg" 
                             onerror="this.src=\'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1920&h=800&fit=crop\'" 
                             class="d-block w-100" 
                             alt="Страхование имущества">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>Имущество и недвижимость</h5>
                            <p>Комплексная защита жилой и коммерческой недвижимости от любых рисков.</p>
                            <a href="' . $servicesUrl . '#property" class="btn btn-hero btn-hero-outline">Оценить риски</a>
                        </div>
                    </div>
                    
                    <!-- Слайд 3: Здоровье -->
                    <div class="carousel-item">
                        <img src="/assets/images/img3.jpg" 
                             onerror="this.src=\'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1920&h=800&fit=crop\'" 
                             class="d-block w-100" 
                             alt="Медицинское страхование">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>Медицинское страхование</h5>
                            <p>Доступ к качественной медицинской помощи в лучших клиниках.</p>
                            <a href="' . $servicesUrl . '#health" class="btn btn-hero btn-hero-outline">Выбрать программу</a>
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
        <div class="container py-4">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-9">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">Работаем с 1488 года</span>
                    <h2 class="section-title">Страховая компания «Чёрный Вантуз»</h2>
                    <p class="section-subtitle">
                        Обеспечиваем финансовую защиту клиентов более 10 лет. 
                        Надёжность, подтверждённая практикой и своевременными выплатами.
                    </p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">🚗</div>
                        <h5 class="fw-bold">Автострахование</h5>
                        <p class="text-muted small">ОСАГО, КАСКО, помощь на дорогах</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">🏠</div>
                        <h5 class="fw-bold">Имущество</h5>
                        <p class="text-muted small">Квартиры, дома, коммерческая недвижимость</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">🏥</div>
                        <h5 class="fw-bold">Здоровье (ДМС)</h5>
                        <p class="text-muted small">Полисы для физических и юридических лиц</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">💼</div>
                        <h5 class="fw-bold">Бизнес</h5>
                        <p class="text-muted small">Страхование ответственности и грузов</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">✈️</div>
                        <h5 class="fw-bold">Путешествия</h5>
                        <p class="text-muted small">Полисы для выезжающих за рубеж</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card" style="background:#f0f4ff;border-color:#0d6efd">
                        <div class="feature-icon" style="background:#ffffff;color:#0d6efd">❤️</div>
                        <h5 class="fw-bold">Жизнь</h5>
                        <p class="text-muted small">Накопительное страхование жизни</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="promo-banner">
                        <h4 class="fw-bold mb-3">Скидка 10% при оформлении онлайн</h4>
                        <p class="mb-4 opacity-90">
                            Быстрое оформление • Конкурентные тарифы • Выплаты в установленные сроки
                        </p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="' . $servicesUrl . '" class="btn btn-hero-primary">Рассчитать стоимость</a>
                            <a href="' . $servicesUrl . '" class="btn btn-hero-outline-light">Оформить полис</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="education-note">
                        <p class="mb-1"><strong>Учебный проект</strong></p>
                        <p class="mb-0">
                            Сайт разработан в рамках обучения в «Кузбасском кооперативном техникуме» 
                            по специальности «Специалист по информационным технологиям».
                        </p>
                    </div>
                </div>
            </div>
        </div>';
        
        return sprintf($template, $title, $content);
    }
}