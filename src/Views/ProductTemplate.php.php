<?php
namespace App\Views;

class ProductTemplate extends BaseTemplate{
    public static function getCardTemplate($data):string{
        $template = parent::getTemplate();
        $name = $data['name'];
        $price = $data['price'];
        $image = $data['image'];
        $description = $data['description'];
        $title = "Страница товара $name";

        $content = <<<HTML
            <div class="card" style="width: 18rem;">
                <img src="$image" class="card-img-top" alt="loading">
                <div class="card-body">
                    <h5 class="card-title">$name</h5>
                    <p class="card-text">$description</p>
                </div>
                <div class="card-body">
                    <a href="github.com" class="card-link">Добавить в карзину</a>
                </div>
            </div>
        HTML;
        $resultTemplate =  sprintf($template, $title, $content);
        return $resultTemplate;
    }
}