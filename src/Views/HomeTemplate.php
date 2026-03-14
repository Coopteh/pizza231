<?php
namespace App\Views;
class HomeTemplate extends BaseTemplate{
    public static function getTemplate():string{
        $template = parent::getTemplate();
        $title= 'Главная страница';
        $content = <<<HTML
        <section>
            <div class="h-50 w-50 mx-auto">        
                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="height:65vh;">
                        <div class="carousel-item active">
                        <img src="../../assets/img/pizza01.png" class="d-block w-100 h-100" alt="loading">
                        </div>
                        <div class="carousel-item">
                        <img src="../../assets/img/tets_png.png" class="d-block w-100 h-100 " alt="loading">
                        </div>
                        <div class="carousel-item">
                        <img src="../../assets/img/pizza03.png" class="d-block w-100 h-100" alt="loading">
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
                <p>Здесь можно заказать пиццу с доставкой по городу Кемерово.</p>
                <p>Широкий ассортимент, низкие цены, быстрая доставка!</p>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </main>    

        HTML;
        $resultTemplate =  sprintf($template, $title, $content);
        return $resultTemplate;
    }
}