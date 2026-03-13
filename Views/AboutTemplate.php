<?php
namespace Views;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'О нас - Страховая компания "чёрный вантуз"';
        
        $content = '
        <section class="container my-5">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="text-primary mb-4">🛡️ О компании "чёрный вантуз"</h2>
                    
                    <div class="card shadow-sm mb-4 text-start">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Лидер рынка страхования с 1488 года</h5>
                            <p class="card-text mt-3">
                                Мы — команда профессионалов, посвятивших себя защите ваших интересов. 
                                Наша миссия — сделать страхование простым, понятным и доступным для каждого.
                                За годы работы мы выплатили более <strong>10 000 страховых случаев</strong>, 
                                подтвердив свою надежность делом.
                            </p>
                            <hr>
                            <h5 class="mt-4">📞 Свяжитесь с нами прямо сейчас!</h5>
                            <p class="display-6 text-success fw-bold my-3">
                                📱 8 999 999 99 99
                            </p>
                            <p class="text-muted">
                                Звоните круглосуточно! Консультация бесплатна.
                            </p>
                            
                            <div class="alert alert-info mt-4">
                                <strong>Почему выбирают нас?</strong>
                                <ul class="mt-2 mb-0">
                                    <li>✅ Выплаты в течение 3-х дней</li>
                                    <li>✅ Оформление полиса за 15 минут</li>
                                    <li>✅ Персональный менеджер для каждого клиента</li>
                                    <li>✅ Офисы в каждом районе города</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <h4 class="mb-3 text-start">📍 Наш главный офис</h4>
                    <div class="ratio ratio-16x9 mb-4 border rounded overflow-hidden">
                         <!-- Вставим статичную картинку карты для надежности, либо iframe Яндекс -->
                         <iframe src="https://yandex.ru/map-widget/v1/?ll=37.617635%2C55.755814&z=10" width="100%" height="400" frameborder="0" allowfullscreen="true"></iframe>
                    </div>
                    
                    <p class="text-muted small">
                        <em>Разработано специально для демонстрации возможностей веб-разработки.</em><br>
                        Автор проекта: <strong>КрутойВаня2015</strong>
                    </p>
                </div>
            </div>
        </section>';
        
        return sprintf($template, $title, $content);
    }
}