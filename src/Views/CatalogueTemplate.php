<?php
namespace App\Views;

use App\Views\BaseTemplate;

class CatalogueTemplate extends BaseTemplate {

        public static function getCatalogue(array $arr): string {
        
            $str='<div class="row justify-content-center">
                        ';
            foreach($arr as $key=> $item) {
                // DATA EXTRACTION
                $id = $item['id']-1;
                $image = $item['image'] ?? '';
                $fallbackImage = '/assets/img/keep_out.png';
                $fallbackImageJs = json_encode($fallbackImage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $title = htmlspecialchars($item['name'] ?? 'Нет названия');
                $description = htmlspecialchars($item['description'] ?? 'Нет описания');
                $price = htmlspecialchars($item['price'] ?? 0);
                
                // INDIVIDUAL ITEM CREATION
                $element_template=<<<HTML
                    <div style="padding-bottom: 15px; display: grid; place-items: center">
                        <div class="col-lg-10 col-xl-9">
                            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                                <div class="row g-0 h-100">
                                    
                                    <!-- Блок с изображением -->
                                    <div class="col-md-5 col-lg-4 bg-light d-flex align-items-center justify-content-center p-4" style="min-height: 300px;">
                                        <img src="$image"
                                            class="img-fluid rounded-3 shadow-sm" 
                                            alt="$title"
                                            style="max-height: 350px; width: 100%; object-fit: contain;"
                                            onerror='this.src=$fallbackImageJs; this.onerror=null;'>
                                    </div>
                                    
                                    <!-- Блок с информацией -->
                                    <div class="col-md-7 col-lg-8">
                                        <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                                            <h5 class="text-uppercase text-secondary fw-bold ls-1 mb-2" style="font-size: 0.9rem;">Товар</h5>
                                            <a href="/products/$id"><h2 class="card-title display-6 fw-bold text-dark mb-3">$title</h2></a>
                                            
                                            <p class="card-text text-muted lead mb-4" style="line-height: 1.6;">
                                                $description
                                            </p>
                                            
                                            <div class="mt-auto">
                                                <div class="d-flex align-items-center mb-4">
                                                    <span class="display-5 fw-bold text-primary me-3">$price ₽</span>
                                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">В наличии</span>
                                                </div>
                                                
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                                    <button type="button" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart-plus me-2" viewBox="0 0 16 16">
                                                            <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                                                            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                                        </svg>
                                                        В корзину
                                                    </button>
                                                    <a href="/" class="btn btn-outline-secondary btn-lg px-4">На главную</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>       
                HTML;

                $str.= $element_template;        
            }
            $str.= '</div>';
            return parent::getTemplate($str);

    }
}