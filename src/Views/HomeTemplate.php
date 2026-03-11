<?php
namespace App\Views;

use App\Views\BaseTemplate; 
class HomeTemplate extends BaseTemplate {
    public static function getTemplate($pageTitle, $pageContent)
    {
    // $content = "
    //     <div class='jumbotron'>
    //         <h1 class='display-4'>Добро пожаловать в Лучший Магазин Бытовой Техники!</h1>
    //         <p class='lead'>Я коммандер Шепард и это мой любимый магазин бытовой техники на Цитадели.</p>
    //         <a class='btn btn-primary btn-lg' href='/catalogue' role='button'>Перейти в каталог</a>
    //     </div>
    //     ";
    // BASETEMPLATE METHODE, LEGACY
    
    //NON-AUTOPLAYING CAROUSEL
    // $content = <<<HTML
    //     <section>
    //     <div id="carouselExample" class="carousel slide">
    //         <div class="carousel-inner">
    //             <div class="carousel-item active">
    //                 <img src="../../assets/img/img1.png" class="d-block w-100" alt="...">
    //             </div>
    //             <div class="carousel-item">
    //                 <img src="../../assets/img/img2.png" class="d-block w-100" alt="...">
    //             </div>
    //             <div class="carousel-item">
    //                 <img src="../../assets/img/img3.png" class="d-block w-100" alt="...">
    //             </div>
    //         </div>
    //         <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    //             <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    //             <span class="visually-hidden">Previous</span>
    //         </button>
    //         <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    //             <span class="carousel-control-next-icon" aria-hidden="true"></span>
    //             <span class="visually-hidden">Next</span>
    //         </button>
    //     </div>
    //     </section>
    //     HTML;
    
    // AUTOPLAYING CAROUSEL! WHAT A DELIGHT!
    $content = <<<HTML
        <section>
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../../assets/img/img1.png" class="d-block w-100 h-150" alt="...">
            </div>
            <div class="carousel-item">
                <img src="../../assets/img/img2.png" class="d-block w-100 h-150" alt="...">
            </div>
            <div class="carousel-item">
                <img src="../../assets/img/img3.png" class="d-block w-100 h-150" alt="...">
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
        </section>
    HTML;
    $template = parent::getTemplate("Главная - Магазин 231", $content);
    $title= 'Главная страница';
    $content = 'здесь будет контент главной страницы';
    $resultTemplate =  sprintf($template, $title, $content);
    return $resultTemplate;
    }
}