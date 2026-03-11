<?php
namespace App\Views;

class HomeTemplate extends BaseTemplate {
    public static function getTemplate(): string {
        $template = parent::getTemplate();
        $title= 'Главная страница';
        $content = <<<LINE
        <section>        
        <div class="h-50 w-50 mx-auto">          
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" style="height:80vh;">
                    <div class="carousel-item active">
                    <img src="/../../asserts/img/code01.jpg" class="d-block w-100 h-100" alt="...">
                    </div>
                    <div class="carousel-item">
                    <img src="/../../asserts/img/code02.jpg" class="d-block w-100 h-100" alt="...">
                    </div>
                    <div class="carousel-item">
                    <img src="/../../asserts/img/code03.jpg" class="d-block w-100 h-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>        
        </div>
        </section>
        <main class="row">
            <div class="p-5">
                <p>Здесь можно освоить новую профессию в сфере IT с нуля.</p>
                <p>Актуальные технологии, практические задания, помощь в трудоустройстве!</p>
                <p class="mt-5">Первый модуль бесплатно, при покупке полного курса!</p>
                <p> (*) Сайт разработан в рамках обучения в "Кузбасском кооперативном техникуме"<br>
                по специальности 09.02.07 "Специалист по информационным технологиям".</p>
            </div>
        </main> 
        LINE;
        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}