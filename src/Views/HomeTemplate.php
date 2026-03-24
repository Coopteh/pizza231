<?php
namespace App\Views;

class HomeTemplate extends BaseTemplate
{
    public static function getTemplate()
    {
        $template = parent::getTemplate(); 
        $title = 'Главная страница';
        $content = <<<HTML
                <section>        
            <div class="h-50 w-50 mx-auto">          
                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="height:80vh;">
                        <div class="carousel-item active">
                        <img src="/../../assets/images/img1.jpg" class="d-block w-100 h-100" alt="...">
                        </div>
                        <div class="carousel-item">
                        <img src="/../../assets/images/img2.jpg" class="d-block w-100 h-100" alt="...">
                        </div>
                        <div class="carousel-item">
                        <img src="/../../assets/images/img3.jpg" class="d-block w-100 h-100" alt="...">
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
            <main class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-8 text-center">
                        
                        <h1 class="display-4 fw-bold text-dark mb-3">
                            Добро пожаловать в <span class="text-primary">«Пиксель»</span>
                        </h1>
                        
                        <p class="lead text-secondary mb-4">
                            Твой надежный проводник в мир цифровых технологий. 
                            Мы собираем мечты в реальность, предлагая лучшие комплектующие по городу.
                        </p>

                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h5 class="fw-bold">🚀 Быстро</h5>
                                    <p class="small text-muted">Оперативная обработка заказа и доставка по Кемерово в день обращения.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h5 class="fw-bold">💎 Качественно</h5>
                                    <p class="small text-muted">Только оригинальная продукция от ведущих мировых брендов.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light h-100">
                                    <h5 class="fw-bold">💰 Выгодно</h5>
                                    <p class="small text-muted">Честные цены без скрытых наценок и регулярные акции.</p>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
            </main> 
        HTML;
        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }
}