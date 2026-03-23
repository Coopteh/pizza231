<?php
namespace App\Views;

use App\Views\BaseTemplate;

class ProductTemplate extends BaseTemplate {

    public static function getCatalogue(array $arr): string {
        $str= '<div class="container">';
        foreach($arr as $key=> $item) {
            $element_template= <<<HTML
            <div class="row mb-5">
                <div class="col-6">
                    <img src="{$item['image']}" class="w-100">
                </div>
                <div class="col-6">
                    <div class="block mt-3">
                        <a href="/products/{$item['id']}"><h2>{$item['name']}</h2></a>
                        <p>{$item['description']}</p>
                        <h3>{$item['price']} ₽</h3>
                    </div>
                </div>
                <hr>
            </div>
            HTML;
            
            $str.= $element_template;        
        }
        $str.= "</div>";
        return parent::getTemplate($str);

    }
    
    public static function getCardTemplate($data): string {
        
        if (!$data) {
            return <<<HTML
            <div class="container mt-5">
                <div class="alert alert-warning text-center shadow-sm" role="alert">
                    <h4 class="alert-heading">Данный товар не найден.</h4>
                    <p>К сожалению, товар с таким идентификатором отсутствует в нашем каталоге.</p>
                    <hr>
                    <a href="/product/1" class="btn btn-outline-warning">Попробовать товар №1</a>
                </div>
            </div>';
            HTML;
        }
        
        // DATA EXTRACTION
        $image = $data['image'] ?? '';
        $fallbackImage = '/assets/img/keep_out.png';
        $fallbackImageJs = json_encode($fallbackImage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $title = htmlspecialchars($data['name'] ?? 'Нет названия');
        $description = htmlspecialchars($data['description'] ?? 'Нет описания');
        $price = htmlspecialchars($data['price'] ?? 0);

        return parent::getTemplate(<<<HTML
        <div class="container py-5">
            <div class="row justify-content-center">
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
                                    <h2 class="card-title display-6 fw-bold text-dark mb-3">$title</h2>
                                    
                                    <p class="card-text text-muted lead mb-4" style="line-height: 1.6;">
                                        $description
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex align-items-center mb-4">
                                            <span class="display-5 fw-bold text-primary me-3">$price ₽</span>
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">В наличии</span>
                                        </div>
                                        
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                            <form class="mt-4" action="/basket" method="POST">
                                                <input type="hidden" name="id" value="{$data['id']}">
                                                <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                                            </form>  
                                            <a href="/" class="btn btn-outline-secondary btn-lg px-4">На главную</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 text-center text-muted small">
                        <div class="col-4">
                            <i class="bi bi-truck me-1"></i> Качественная техника*
                        </div>
                        <div class="col-4">
                            <i class="bi bi-shield-check me-1"></i> Гарантия до двух лет**
                        </div>
                        <div class="col-4">
                            <i class="bi bi-heart me-1"></i> Только заводские товары***
                        </div>
                    </div>
                </div>
            </div>
        </div>
    HTML);
    // LEGACY DISPLAY
    // $template = parent::getTemplate('<h1>НЕТАНЬЯХУ</h1>');
    // $title= 'Каталог';
    // $content = 'здесь будет контент главной страницы';
    // $resultTemplate =  sprintf($template, $title, $card);
    // return $resultTemplate;
    // LEGACY CARD
        //     <<<HTML
        //     <div class="container py-5">
        //     <div class="row justify-content-center">
        //         <div class="col-lg-10 col-xl-9">
        //             <div class="card mb-3" style="max-width: 540px;">
        //                 <div class="row g-0">
        //                     <div class="col-md-4">
        //                         <img src="$image" class="img-fluid rounded-start" alt="...">
        //                     </div>
        //                     <div class="col-md-8">
        //                         <div class="card-body">
        //                             <h4 class="card-title">$title </h4>
        //                             <p class="card-text">$description</p>
        //                             <p class="card-text"><small class="text-body-secondary">$price</small></p>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //     </div>
        // </div>
        // HTML;
    }
}