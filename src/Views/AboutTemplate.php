<?php
namespace App\Views;

use App\Views\BaseTemplate;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(string $content = ''): string
    {
        $ourContent = '
        <div class="container mt-4">
            <h1 class="text-center mb-4">О нашем техникуме</h1>
            
            <div class="row">
                <div class="col-md-8">
                    <p class="lead">
                        Кемеровский кооперативный техникум сегодня – это первый шаг на пути к будущей успешной карьере.
                    </p>
                    
                    <p>
                        Техникум был основан в <strong>1974 году</strong> в связи с потребностью в специалистах 
                        для предприятий и организаций потребительской кооперации Кемеровской области. 
                        И сегодня мы готовим специалистов по самым престижным и востребованным специальностям.
                    </p>
                    
                    <p>
                        Более чем за 48 лет успешной работы техникум подготовил свыше 
                        <strong>12 тысяч специалистов</strong> для различных предприятий и организаций.
                    </p>
                    
                    <p>
                        Подготовку будущих квалифицированных специалистов осуществляет 
                        высокопрофессиональный коллектив преподавателей. Все специальности имеют 
                        государственную аккредитацию, а выпускники получают диплом установленного образца.
                    </p>
                    
                    <h4 class="mt-4">Контакты</h4>
                    <ul class="list-unstyled">
                        <li>📍 Адрес: 650070, г. Кемерово, ул. Тухачевского, 32</li>
                        <li>📞 Телефон: +7 (3842) 21-56-61</li>
                        <li>✉️ E-mail: info@coopteh.ru</li>
                        <li>👩‍💼 Директор: Теребова Наталья Владимировна</li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <!-- Карта Яндекс -->
                    <div class="card">
                        <div class="card-body p-0">
                            <script type="text/javascript" charset="utf-8" 
                                    async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3AYOUR_CONSTRUCTOR_ID&amp;width=100%25&amp;height=300&amp;lang=ru_RU&amp;scroll=true">
                            </script>
                            <!-- Замените YOUR_CONSTRUCTOR_ID на ID вашей карты из Конструктора Яндекс.Карт -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';
        
        return parent::getTemplate($ourContent);
    }
}