<?php
namespace Views;

class ServicesTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Наши услуги - Страховая компания "чёрный вантуз"';
        
        $content = '
        <section class="container my-5">
            <h2 class="text-primary mb-4 text-center">🛡️ Наши страховые услуги</h2>
            <p class="lead text-center mb-5">Мы предлагаем полный спектр защиты для вас и вашего бизнеса.</p>
            
            <div class="row g-4">
                <!-- Услуга 1 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="display-4 mb-3">🚗</div>
                            <h4 class="card-title">Автострахование</h4>
                            <p class="card-text">ОСАГО и КАСКО. Оформление за 15 минут, выплаты в день обращения.</p>
                            <a href="#" class="btn btn-outline-primary mt-2">Подробнее</a>
                        </div>
                    </div>
                </div>
                
                <!-- Услуга 2 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="display-4 mb-3">🏠</div>
                            <h4 class="card-title">Имущество</h4>
                            <p class="card-text">Страхование квартиры, дома и дачи от пожара, затопления и кражи.</p>
                            <a href="#" class="btn btn-outline-primary mt-2">Подробнее</a>
                        </div>
                    </div>
                </div>
                
                <!-- Услуга 3 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="display-4 mb-3">🏥</div>
                            <h4 class="card-title">Здоровье</h4>
                            <p class="card-text">ДМС для взрослых и детей. Лучшие клиники города без очередей.</p>
                            <a href="#" class="btn btn-outline-primary mt-2">Подробнее</a>
                        </div>
                    </div>
                </div>

                <!-- Услуга 4 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="display-4 mb-3">💼</div>
                            <h4 class="card-title">Бизнес</h4>
                            <p class="card-text">Страхование ответственности, грузов и сотрудников компании.</p>
                            <a href="#" class="btn btn-outline-primary mt-2">Подробнее</a>
                        </div>
                    </div>
                </div>

                <!-- Услуга 5 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="display-4 mb-3">✈️</div>
                            <h4 class="card-title">Путешествия</h4>
                            <p class="card-text">Полис для визы и поездок за границу. Покрытие до $50,000.</p>
                            <a href="#" class="btn btn-outline-primary mt-2">Подробнее</a>
                        </div>
                    </div>
                </div>

                <!-- Услуга 6 -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 bg-light">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h4 class="card-title">Нужна помощь?</h4>
                            <p class="card-text">Не знаете, что выбрать? Позвоните нам!</p>
                            <a href="tel:+79999999999" class="btn btn-success mt-2">📞 8 999 999 99 99</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
        
        return sprintf($template, $title, $content);
    }
}