<?php
namespace Views;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Главная — Фитнес-клуб «gym low cortisol»';
        $servicesUrl = '/services';
        $customStyles = '
        <style>
        .hero-carousel { position: relative; background: #000; }
        .hero-carousel .carousel-item { height: 500px; background: #000; }
        .hero-carousel .carousel-item img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; }
        .hero-carousel .carousel-item::after { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); pointer-events: none; }
        .hero-caption { bottom: 25%; left: 50%; transform: translateX(-50%); text-align: center; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8); max-width: 800px; padding: 0 20px; z-index: 10; }
        .hero-caption h5 { font-size: 2.5rem; font-weight: 700; color: #ffffff; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; }
        .hero-caption p { font-size: 1.2rem; color: #f8f9fa; margin-bottom: 1.5rem; line-height: 1.5; }
        .btn-hero { padding: 12px 36px; font-size: 1rem; border-radius: 50px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .btn-hero-outline { background: transparent; border: 2px solid #ffffff; color: #ffffff; }
        .btn-hero-outline:hover { background: #ffffff; color: #8b5cf6; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3); text-decoration: none; }
        .feature-card { border: none; border-radius: 16px; background: #ffffff; padding: 1.75rem 1.25rem; text-align: center; height: 100%; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid #eef2f7; }
        .feature-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(13, 110, 253, 0.15); border-color: #8b5cf6; }
        .feature-icon { font-size: 2.5rem; margin-bottom: 1rem; width: 72px; height: 72px; line-height: 72px; border-radius: 50%; margin: 0 auto 1rem; background: linear-gradient(135deg, #eef2ff 0%, #f0f4ff 100%); color: #8b5cf6; transition: all 0.3s ease; }
        .feature-card:hover .feature-icon { background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%); color: #ffffff; transform: scale(1.1); }
        .promo-banner { background: linear-gradient(135deg, #198754 0%, #20c997 100%); border-radius: 20px; padding: 2.5rem 2rem; color: #ffffff; text-align: center; margin: 3rem 0; position: relative; overflow: hidden; }
        .promo-banner::before { content: "🎁"; position: absolute; top: -20px; right: -20px; font-size: 8rem; opacity: 0.1; transform: rotate(15deg); }
        .btn-hero-primary { background: #ffffff; border: none; color: #198754; font-weight: 600; padding: 12px 32px; border-radius: 50px; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); color: #198754; text-decoration: none; }
        .section-title { font-size: 2rem; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; }
        .section-subtitle { color: #64748b; font-size: 1.05rem; line-height: 1.6; }
        .education-note { background: #f8f9fa; border-left: 4px solid #8b5cf6; padding: 1.25rem; border-radius: 0 8px 8px 0; font-size: 0.9rem; color: #6c757d; margin-top: 2rem; }
        </style>';

        $content = $customStyles . '
        <section class="container-fluid p-0 mb-5">
            <div id="mainCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="/assets/images/img1.jpg" onerror="this.src=\'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1920&h=800&fit=crop\'" class="d-block w-100" alt="Тренажёрный зал">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>Современный зал</h5>
                            <p>Оборудование Technogym и свободные веса. Просторные зоны для тренировок.</p>
                            <a href="' . $servicesUrl . '#gym" class="btn btn-hero btn-hero-outline">Выбрать карту</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/assets/images/img2.jpg" onerror="this.src=\'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=1920&h=800&fit=crop\'" class="d-block w-100" alt="Групповые программы">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>Групповые программы</h5>
                            <p>Йога, пилатес, зумба и кроссфит. Заряд энергии под руководством профи.</p>
                            <a href="' . $servicesUrl . '#groups" class="btn btn-hero btn-hero-outline">Расписание</a>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="/assets/images/img3.jpg" onerror="this.src=\'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=1920&h=800&fit=crop\'" class="d-block w-100" alt="SPA и бассейн">
                        <div class="carousel-caption d-none d-md-block hero-caption">
                            <h5>SPA и Бассейн</h5>
                            <p>Сауна, хаммам и бассейн для восстановления после тренировок.</p>
                            <a href="' . $servicesUrl . '#spa" class="btn btn-hero btn-hero-outline">Узнать подробнее</a>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Предыдущий</span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Следующий</span></button>
            </div>
        </section>
        <div class="container py-4">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-9">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">Работаем с 2015 года</span>
                    <h2 class="section-title">Фитнес-клуб «gym low cortisol»</h2>
                    <p class="section-subtitle">Ваше пространство силы и здоровья. Профессиональные тренеры, современный сервис и атмосфера успеха.</p>
                </div>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">🏋️</div><h5 class="fw-bold">Тренажёрный зал</h5><p class="text-muted small">Кардио, силовые, свободные веса</p></div></div>
                <div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">🧘</div><h5 class="fw-bold">Групповые</h5><p class="text-muted small">Йога, пилатес, танцы, кроссфит</p></div></div>
                <div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">🏊</div><h5 class="fw-bold">Бассейн</h5><p class="text-muted small">25 метров, подогрев, дорожки</p></div></div>
                <div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">🥊</div><h5 class="fw-bold">Единоборства</h5><p class="text-muted small">Бокс, ММА, борьба</p></div></div>
                <div class="col-md-6 col-lg-4"><div class="feature-card"><div class="feature-icon">🧖</div><h5 class="fw-bold">SPA-зона</h5><p class="text-muted small">Сауна, хаммам, массаж</p></div></div>
                <div class="col-md-6 col-lg-4"><div class="feature-card" style="background:#f0f4ff;border-color:#8b5cf6"><div class="feature-icon" style="background:#ffffff;color:#8b5cf6">👶</div><h5 class="fw-bold">Детский клуб</h5><p class="text-muted small">Присмотр пока вы тренируетесь</p></div></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="promo-banner">
                        <h4 class="fw-bold mb-3">Скидка 10% на первую карту</h4>
                        <p class="mb-4 opacity-90">Быстрое оформление • Заморозка карты • Гостевые визиты</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="' . $servicesUrl . '" class="btn btn-hero-primary">Рассчитать стоимость</a>
                            <a href="' . $servicesUrl . '" class="btn btn-hero-outline-light">Оформить карту</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="education-note">
                        <p class="mb-1"><strong>Учебный проект</strong></p>
                        <p class="mb-0">Сайт разработан в рамках обучения в «Кузбасском кооперативном техникуме» по специальности «Специалист по информационным технологиям».</p>
                    </div>
                </div>
            </div>
        </div>';

        return sprintf($template, $title, $content);
    }
}