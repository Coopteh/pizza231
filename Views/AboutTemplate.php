<?php
namespace Views;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'О компании — Страховая компания «Чёрный Вантуз»';
        
        $customStyles = '
        <style>
            .about-hero {
                background: linear-gradient(135deg, #f0f4ff, #fff);
                border-radius: 20px; padding: 2.5rem 2rem;
                margin-bottom: 2.5rem; border: 1px solid #eef2f7;
            }
            .feature-box {
                background: #f8f9fa; border-radius: 12px;
                padding: 1.5rem; text-align: center;
                border: 1px solid #eef2f7; height: 100%;
            }
            .feature-icon {
                font-size: 2rem; margin-bottom: 0.75rem;
                width: 64px; height: 64px; line-height: 64px;
                border-radius: 50%; margin: 0 auto 1rem;
                background: #eef2ff; color: #0d6efd;
            }
            .contact-card {
                background: linear-gradient(135deg, #0d6efd, #0dcaf0);
                color: #fff; border-radius: 20px;
                padding: 2rem; text-align: center; margin: 2.5rem 0;
            }
            .contact-card a { color: #fff; text-decoration: none; }
            .phone { font-size: 1.75rem; font-weight: 700; display: block; margin: 0.75rem 0; }
            .map-box {
                border-radius: 16px; overflow: hidden;
                border: 1px solid #e2e8f0;
            }
            .author-note {
                text-align: center; color: #64748b;
                font-size: 0.9rem; margin-top: 2rem;
            }
        </style>';

        $content = $customStyles . '
        <section class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="about-hero text-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-3">С 1488 года</span>
                        <h2 class="display-5 fw-bold text-dark mb-4">О компании</h2>
                        <p class="lead text-muted mx-auto" style="max-width:800px">
                            Команда профессионалов, обеспечивающая финансовую защиту клиентов. 
                            За период работы успешно урегулировано более 10 000 страховых случаев.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-12 text-center mb-3">
                    <h4 class="fw-bold">Преимущества</h4>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">⚡</div>
                        <h6 class="fw-bold">Оперативные выплаты</h6>
                        <p class="text-muted small mb-0">В течение 3 рабочих дней</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">⏱️</div>
                        <h6 class="fw-bold">Быстрое оформление</h6>
                        <p class="text-muted small mb-0">Полис в день обращения</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">🤝</div>
                        <h6 class="fw-bold">Персональный менеджер</h6>
                        <p class="text-muted small mb-0">Сопровождение на всех этапах</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">🏢</div>
                        <h6 class="fw-bold">Офисы</h6>
                        <p class="text-muted small mb-0">Представительства в регионах</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-card">
                        <h4 class="fw-bold mb-3">Консультация</h4>
                        <p class="mb-3 opacity-90">Подбор тарифа — бесплатно</p>
                        <a href="tel:+79999999999" class="phone">+7 (999) 999-99-99</a>
                        <a href="tel:+79999999999" class="btn btn-light rounded-pill px-4 fw-bold">Позвонить</a>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-lg-10">
                    <h5 class="text-center fw-bold mb-3">Офис</h5>
                    <div class="map-box mb-4">
                        <iframe src="https://yandex.ru/map-widget/v1/?ll=37.617635%2C55.755814&z=10" width="100%" height="400" frameborder="0"></iframe>
                    </div>
                    <div class="author-note">
                        <p class="mb-1">Учебный проект</p>
                        <p class="mb-0">Разработано в «***»</p>
                    </div>
                </div>
            </div>
        </section>';
        
        return sprintf($template, $title, $content);
    }
}