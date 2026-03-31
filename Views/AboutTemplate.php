<?php
namespace Views;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'О магазине - Продукты24';

        $customStyles = '
<style>
.about-hero {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    border-radius: 0 0 50px 50px;
    padding: 4rem 2rem;
    color: white;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}
.about-hero::before {
    content: "🛒";
    position: absolute;
    right: 5%;
    top: 10%;
    font-size: 15rem;
    opacity: 0.1;
    transform: rotate(20deg);
}
.about-hero h2 {
    font-size: 3rem;
    font-weight: 900;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 40px rgba(255,107,53,0.15);
    border: 3px solid #ff6b35;
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 50px rgba(255,107,53,0.25);
}
.stat-number {
    font-size: 3.5rem;
    font-weight: 900;
    color: #ff6b35;
    line-height: 1;
}
.stat-label {
    color: #666;
    font-size: 1.1rem;
    margin-top: 0.5rem;
}
.team-section {
    background: linear-gradient(135deg, #fff5f0 0%, #ffffff 100%);
    border-radius: 30px;
    padding: 3rem;
    margin: 3rem 0;
}
.team-member {
    text-align: center;
    padding: 1.5rem;
}
.team-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    margin: 0 auto 1rem;
    border: 4px solid white;
    box-shadow: 0 5px 20px rgba(255,107,53,0.3);
}
.contact-section {
    background: #ff6b35;
    border-radius: 30px;
    padding: 3rem;
    color: white;
    text-align: center;
    margin-top: 3rem;
}
.contact-section a {
    color: white;
    text-decoration: none;
}
.contact-phone {
    font-size: 2.5rem;
    font-weight: 900;
    display: block;
    margin: 1.5rem 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}
</style>';

        $content = $customStyles . '
<section class="container py-5">
    <div class="about-hero text-center">
        <span class="badge bg-white text-orange mb-3 px-4 py-2 rounded-pill fw-bold">🎉 С 2020 года на рынке</span>
        <h2 class="mb-4">🛒 О Продукты24</h2>
        <p class="lead mx-auto" style="max-width: 800px; opacity: 0.95;">
            Мы — современный онлайн-супермаркет с доставкой продуктов на дом.
            Наша цель — сделать покупки продуктов быстрыми, удобными и выгодными.
            За 4 года работы мы обслужили более <strong>100 000 заказов</strong>!
        </p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">100K+</div>
                <div class="stat-label">Заказов выполнено</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Городов доставки</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Поддержка клиентов</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number">60 мин</div>
                <div class="stat-label">Средняя доставка</div>
            </div>
        </div>
    </div>

    <div class="team-section">
        <h3 class="text-center fw-bold mb-4" style="color: #ff6b35;">👥 Наша команда</h3>
        <div class="row">
            <div class="col-md-4 team-member">
                <div class="team-avatar">👨‍🍳</div>
                <h5 class="fw-bold">Алексей Петров</h5>
                <p class="text-muted small">Генеральный директор</p>
            </div>
            <div class="col-md-4 team-member">
                <div class="team-avatar">👩‍💼</div>
                <h5 class="fw-bold">Мария Иванова</h5>
                <p class="text-muted small">Руководитель отдела закупок</p>
            </div>
            <div class="col-md-4 team-member">
                <div class="team-avatar">🚚</div>
                <h5 class="fw-bold">Дмитрий Сидоров</h5>
                <p class="text-muted small">Руководитель доставки</p>
            </div>
        </div>
    </div>

    <div class="contact-section">
        <h3 class="fw-bold mb-3">📞 Свяжитесь с нами</h3>
        <p class="mb-4">Мы всегда готовы помочь с выбором продуктов</p>
        <a href="tel:+79991234567" class="contact-phone">+7 (999) 123-45-67</a>
        <a href="mailto:info@produkty24.ru" class="btn btn-light btn-lg rounded-pill px-5 fw-bold" style="color: #ff6b35;">
            ✉️ Написать письмо
        </a>
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}