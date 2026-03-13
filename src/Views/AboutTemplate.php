<?php
namespace App\Views;

use App\Views\BaseTemplate;

class AboutTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $content = '
        <div class="container py-5">
            <h1 class="text-center mb-4">О нашем техникуме</h1>
            <div class="row">
                <div class="col-md-6">
                    <p class="lead">
                        Кооперативный техникум — современное образовательное учреждение, 
                        готовящее квалифицированных специалистов в сфере торговли, 
                        общественного питания и экономики.
                    </p>
                    <ul class="list-unstyled">
                        <li>✅ Качественное образование</li>
                        <li>✅ Практико-ориентированный подход</li>
                        <li>✅ Трудоустройство выпускников</li>
                        <li>✅ Современная материальная база</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <!-- Карта Яндекс -->
                    <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3AYOUR_CONSTRUCTOR_ID&amp;source=constructor" 
                            width="100%" 
                            height="400" 
                            frameborder="0" 
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
        ';
        
        return parent::getTemplate();
    }
}