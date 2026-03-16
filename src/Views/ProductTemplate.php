<?php
namespace App\Views;

use App\Views\BaseTemplate;
class ProductTemplate extends BaseTemplate {
    public static function getTemplate()
    {
    // $content = <<<HTML
    //     <section>
    //     <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
    //     <div class="carousel-inner">
    //         <div class="carousel-item active">
    //             <img src="../../assets/img/img1.png" class="d-block w-100 h-150" alt="...">
    //         </div>
    //         <div class="carousel-item">
    //             <img src="../../assets/img/img2.png" class="d-block w-100 h-150" alt="...">
    //         </div>
    //         <div class="carousel-item">
    //             <img src="../../assets/img/img3.png" class="d-block w-100 h-150" alt="...">
    //         </div>
    //     </div>
    //     <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    //         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    //         <span class="visually-hidden">Previous</span>
    //     </button>
    //     <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    //         <span class="carousel-control-next-icon" aria-hidden="true"></span>
    //         <span class="visually-hidden">Next</span>
    //     </button>
    //     </div>
    //     </section>
    // HTML;
    
    }
    public static function getCard($data)
    {
        $card = <<<HTML
        <section>
        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0">
                <div class="col-md-4">
                <img src="..." class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">$data[name]</h5>
                    <p class="card-text">$data[description]</p>
                    <p class="card-text"><small class="text-body-secondary">$data[price]</small></p>
                </div>
                </div>
            </div>
        </div>
        </section>
    HTML;
    $template = parent::getTemplate();
    $title= 'Каталог';
    // $content = 'здесь будет контент главной страницы';
    $resultTemplate =  sprintf($template, $title, $card);
    return $resultTemplate;
    }
}