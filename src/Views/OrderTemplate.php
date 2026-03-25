<?php
namespace App\Views;


class OrderTemplate extends BaseTemplate {

    // public static function getTemplate(string $content = ''): string {
    //     $content = <<<HTML
    //         <section>
    //         <div style="display: flex; justify-content: center;" width="100%">
    //             <h1>СТРАНИЦА В РАБОТЕ. НЕ СМОТРЕТЬ!</h1>
    //         </div>
    //         <div style="display: flex; justify-content: center;" width="100%">
    //             <img src="/assets/img/keep_out.png" class="d-block w-100 h-150" alt="...">
    //         </div>
    //         <div style="display: flex; justify-content: center;" width="100%">
    //             <img src="/assets/img/card5.png" class="d-block w-100 h-150" alt="...">
    //         </div>
    //         </section>
    //     HTML;
        
    //     return parent::getTemplate($content);
    // }
    public static function getOrderTemplate(array $arr): string {
        $all_sum=0;
        $products = $arr;
        
        $content = '<main class="row">
            <h3 class="mb-5">Корзина</h3></main>';
        foreach ($products as $product) {
            $name = $product['name'];
            $price = $product['price'];
            $id = $product['id']-1;
            $quantity = $product['quantity'];

            $sum = $price * $quantity;
            $all_sum += $sum;

            $content .= <<<HTML
            <div class="row">
                <div class="col-6">
                {$name}
                </div>
                <div class="col-2">
                {$quantity} ед. x {$price} руб.
                </div>
                <div class="col-2">
                {$sum} ₽
                </div>
            </div>
            HTML;
	        }
            $content .= <<<HTML
            <div class="row">
                    <div class="col-6">
                         
                    </div>
                    <div class="col-6 float-end">
                        <form action="/basket_clear" method="POST">
                        <button type="submit" class="btn btn-secondary mt-3">Очистить корзину
                        </form>
                    </div>
                </div> 
            HTML;
        if ($all_sum == 0) {
            $content .= <<<HTML
            <div class="row">
                <div class="col-12">
                - нет добавленных товаров -
                </div>
            </div>
            HTML;
        }
        return parent::getTemplate($content);
    }
}