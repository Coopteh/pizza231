<?php
namespace Views;

class ServicesTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Наши услуги - Страховая компания "Чёрный Вантуз"';
        
        $customStyles = '
        <style>
            .service-card {
                border: none;
                border-radius: 20px;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                background: #ffffff;
                overflow: hidden;
                position: relative;
                z-index: 1;
            }
            
            /* Эффект при наведении */
            .service-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important;
            }

            /* Декоративная полоска сверху карточки */
            .service-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 6px;
                background: linear-gradient(90deg, #0d6efd, #0dcaf0);
                z-index: 2;
                opacity: 0.8;
            }

            .icon-box {
                width: 90px;
                height: 90px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                font-size: 3rem;
                margin: 0 auto 1.5rem auto;
                background: linear-gradient(135deg, #f0f4ff 0%, #eef2ff 100%);
                color: #0d6efd;
                box-shadow: 0 10px 20px rgba(13, 110, 253, 0.15);
                transition: transform 0.3s ease;
            }

            .service-card:hover .icon-box {
                transform: scale(1.1) rotate(5deg);
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                color: #fff;
            }

            .card-title {
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.8rem;
            }

            .card-text {
                color: #64748b;
                line-height: 1.6;
                margin-bottom: 1.5rem;
            }

            .btn-custom {
                border-radius: 50px;
                padding: 10px 25px;
                font-weight: 600;
                transition: all 0.3s ease;
                border: 2px solid #0d6efd;
                color: #0d6efd;
                background: transparent;
            }

            .btn-custom:hover {
                background: #0d6efd;
                color: #fff;
                box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            }

            /* Специальный стиль для карточки "Помощь" */
            .cta-card {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                color: white;
                border: none;
            }
            .cta-card::before {
                background: linear-gradient(90deg, #fbbf24, #f59e0b);
            }
            .cta-card .card-title {
                color: #fff;
            }
            .cta-card .card-text {
                color: #cbd5e1;
            }
            .cta-card .icon-box {
                background: rgba(255,255,255,0.1);
                color: #fbbf24;
                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            }
            .cta-card:hover .icon-box {
                background: #fbbf24;
                color: #1e293b;
            }
            .cta-card .btn-custom {
                border-color: #fbbf24;
                color: #fbbf24;
            }
            .cta-card .btn-custom:hover {
                background: #fbbf24;
                color: #1e293b;
                box-shadow: 0 5px 15px rgba(251, 191, 36, 0.4);
            }
        </style>
        ';

        $content = $customStyles . '
        <section class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark mb-3">🛡️ Наши страховые услуги</h2>
                <p class="lead text-muted mx-auto" style="max-width: 700px;">
                    Надежная защита для вас, вашего дома и бизнеса. Выберите подходящий вариант ниже.
                </p>
                <div style="width: 80px; height: 5px; background: linear-gradient(90deg, #0d6efd, #0dcaf0); margin: 25px auto; border-radius: 10px;"></div>
            </div>
    
            <!-- Сетка: 1 колонка на мобильных, 2 колонки на планшетах и ПК -->
            <div class="row g-4 justify-content-center">
                
                <!-- Услуга 1 -->
                <div class="col-md-6 col-lg-6" id="auto">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🚗</div>
                            <h4 class="card-title">Автострахование</h4>
                            <p class="card-text">Полное покрытие ОСАГО и КАСКО. Оформление онлайн за 15 минут, выплаты в день обращения.</p>
                            <a href="#" class="btn btn-custom">Рассчитать полис</a>
                        </div>
                    </div>
                </div>
                
                <!-- Услуга 2 -->
                <div class="col-md-6 col-lg-6" id="property">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🏠</div>
                            <h4 class="card-title">Имущество</h4>
                            <p class="card-text">Защита квартиры, дома и дачи от пожара, затопления, кражи и стихийных бедствий.</p>
                            <a href="#" class="btn btn-custom">Оценить риски</a>
                        </div>
                    </div>
                </div>
                
                <!-- Услуга 3 -->
                <div class="col-md-6 col-lg-6" id="health">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">🏥</div>
                            <h4 class="card-title">Здоровье (ДМС)</h4>
                            <p class="card-text">Полисы для взрослых и детей. Прием в лучших клиниках города без очередей и справок.</p>
                            <a href="#" class="btn btn-custom">Выбрать программу</a>
                        </div>
                    </div>
                </div>

                <!-- Услуга 4 -->
                <div class="col-md-6 col-lg-6">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">💼</div>
                            <h4 class="card-title">Для Бизнеса</h4>
                            <p class="card-text">Страхование ответственности, коммерческих грузов и здоровья сотрудников компании.</p>
                            <a href="#" class="btn btn-custom">Для бизнеса</a>
                        </div>
                    </div>
                </div>

                <!-- Услуга 5 -->
                <div class="col-md-6 col-lg-6">
                    <div class="card service-card h-100 shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="icon-box">✈️</div>
                            <h4 class="card-title">Путешествия</h4>
                            <p class="card-text">Туристический полис для визы и поездок за границу. Покрытие до $50,000 и помощь 24/7.</p>
                            <a href="#" class="btn btn-custom">Оформить поездку</a>
                        </div>
                    </div>
                </div>

                <!-- Услуга 6 (CTA) - Занимает всю ширину ряда, если нужно, или тоже 2 в ряд. Оставим 2 в ряд для симметрии, но сделаем её яркой -->
                <div class="col-md-6 col-lg-6">
                    <div class="card service-card cta-card h-100 shadow-lg p-4">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <div class="icon-box">🤝</div>
                            <h4 class="card-title">Нужна помощь?</h4>
                            <p class="card-text">Не знаете, что выбрать? Наши эксперты подберут идеальный тариф бесплатно.</p>
                            <a href="tel:+79999999999" class="btn btn-custom mt-2">📞 8 999 999 99 99</a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
';
        
        return sprintf($template, $title, $content);
    }
}