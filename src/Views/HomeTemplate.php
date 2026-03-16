<?php
namespace App\Views;

use App\Views\BaseTemplate; 
class HomeTemplate extends BaseTemplate {
    public static function getTemplate(string $content = ''): string
    {    
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
    
    // AUTOPLAYING CAROUSEL! WHAT A DELIGHT! WITH MINOR TWEAKS NOW SO IT IS SHOWING FINE AND NOT TAKING THE WHOLE PAGE FOR ITSELF
    $content = <<<HTML
    <div style="max-width: 600px; margin: 0 auto;">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/assets/img/card1.png" class="d-block w-100" style="height: 600px; object-fit: cover;" alt="Card 1">
                </div>
                <div class="carousel-item">
                    <img src="/assets/img/card2.png" class="d-block w-100" style="height: 600px; object-fit: cover;" alt="Card 2">
                </div>
                <div class="carousel-item">
                    <img src="/assets/img/card3.png" class="d-block w-100" style="height: 600px; object-fit: cover;" alt="Card 3">
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
    HTML;
    
    return parent::getTemplate($content);
    }
}