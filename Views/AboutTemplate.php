<?php
namespace Views;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'О нас - Страховая компания "Чёрный Вантуз"';
        
        $customStyles = '
        <style>
            /* Общие стили, совместимые с ServicesTemplate */
            .about-card {
                border: none;
                border-radius: 20px;
                background: #ffffff;
                transition: all 0.3s ease;
                overflow: hidden;
            }
            
            .feature-box {
                background: #f8f9fa;
                border-radius: 15px;
                padding: 1.5rem;
                text-align: center;
                height: 100%;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: 1px solid #eef2f7;
            }
            
            .feature-box:hover {
                transform: translateY(-5px);
                background: #fff;
                box-shadow: 0 10px 25px rgba(0,0,0,0.08);
                border-color: #0d6efd;
            }

            .feature-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                display: inline-block;
                background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
                width: 70px;
                height: 70px;
                line-height: 70px;
                border-radius: 50%;
                color: #0d6efd;
            }

            .hero-section {
                background: linear-gradient(135deg, #f0f4ff 0%, #ffffff 100%);
                border-radius: 25px;
                padding: 3rem 2rem;
                margin-bottom: 3rem;
                border: 1px solid #eef2f7;
            }

            .contact-block {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                color: white;
                border-radius: 20px;
                padding: 2.5rem;
                text-align: center;
                margin: 3rem 0;
                box-shadow: 0 15px 30px rgba(13, 110, 253, 0.25);
            }
            
            .contact-block h4, .contact-block p {
                color: white;
            }

            .phone-display {
                font-size: 2.5rem;
                font-weight: 800;
                color: #fff;
                text-decoration: none;
                display: block;
                margin: 1rem 0;
                text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .phone-display:hover {
                color: #f8f9fa;
                transform: scale(1.02);
                transition: transform 0.2s;
            }

            .map-wrapper {
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                border: 5px solid #fff;
            }

            .author-badge {
                display: inline-block;
                background: #eef2f7;
                color: #495057;
                padding: 5px 15px;
                border-radius: 50px;
                font-size: 0.9rem;
                font-weight: 600;
                margin-top: 10px;
            }
        </style>
        ';

        $content = $customStyles . '
        <section class="container py-5">
            
            <!-- Герой-блок: О компании -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="hero-section text-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">С 1488 года на страже вашего спокойствия</span>
                        <h2 class="display-5 fw-bold text-dark mb-4">🛡️ О компании "Чёрный Вантуз"</h2>
                        <p class="lead text-muted mx-auto" style="max-width: 800px;">
                            Мы — команда профессионалов, посвятивших себя защите ваших интересов. 
                            Наша миссия — сделать страхование простым, понятным и доступным для каждого.
                            За годы работы мы выплатили более <strong class="text-primary">10 000 страховых случаев</strong>, 
                            подтвердив свою надежность делом, а не словами.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Преимущества (Сетка 2x2) -->
            <div class="row g-4 mb-5">
                <div class="col-12 text-center mb-3">
                    <h3 class="fw-bold">Почему выбирают нас?</h3>
                    <div style="width: 60px; height: 4px; background: #0d6efd; margin: 15px auto; border-radius: 2px;"></div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">⚡</div>
                        <h5 class="fw-bold">Быстрые выплаты</h5>
                        <p class="text-muted small mb-0">Деньги на счету в течение 3-х дней после одобрения.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">⏱️</div>
                        <h5 class="fw-bold">Оформление за 15 мин</h5>
                        <p class="text-muted small mb-0">Минимум бумаг, максимум скорости. Полис сразу на email.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">🤝</div>
                        <h5 class="fw-bold">Персональный менеджер</h5>
                        <p class="text-muted small mb-0">Всегда на связи, поможет с любым вопросом 24/7.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">🏢</div>
                        <h5 class="fw-bold">Офисы в каждом районе</h5>
                        <p class="text-muted small mb-0">Найдите ближайший пункт обслуживания за пару кликов.</p>
                    </div>
                </div>
            </div>

            <!-- Блок контактов -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-block">
                        <h3 class="fw-bold mb-3">📞 Нужна консультация?</h3>
                        <p class="mb-4 opacity-75">Звоните круглосуточно! Первый разговор и подбор тарифа — бесплатно.</p>
                        <a href="tel:+79999999999" class="phone-display">
                            📱 8 999 999 99 99
                        </a>
                        <a href="tel:+79999999999" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-primary mt-2">
                            Позвонить сейчас
                        </a>
                    </div>
                </div>
            </div>

            <!-- Карта и Футер -->
            <div class="row justify-content-center mt-5">
                <div class="col-lg-10">
                    <h4 class="mb-3 text-center fw-bold">📍 Наш главный офис</h4>
                    <div class="map-wrapper mb-5">
                         <iframe src="https://yandex.ru/map-widget/v1/?ll=37.617635%2C55.755814&z=10" width="100%" height="450" frameborder="0" allowfullscreen="true"></iframe>
                    </div>
                    
                    <div class="text-center pb-5">
                        <p class="text-muted small mb-2">
                            <em>Разработано специально для демонстрации возможностей веб-разработки.</em>
                        </p>
                        <span class="author-badge">
                            👨‍💻 Автор проекта: КрутойВаня2015
                        </span>
                    </div>
                </div>
            </div>

        </section>
';
        
        return sprintf($template, $title, $content);
    }
}