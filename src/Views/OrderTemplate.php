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
            $name = $product['name'] ?? "Товар #{$product}"; 
            $price = $product['price'] ?? 0; // Цены нет в сессии
            $quantity = $product['quantity'] ?? 1;


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
        $content .= <<<HTML
        <section>
            <form action="/order" method="POST">
                <div class="mb-3">
                    <label for="fio" class="form-label">ФИО</label>
                    <input type="name" name ="fio" class="form-control" id="fio">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Адресс</label>
                    <input type="address" name ="address" class="form-control" id="address">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="phone" name ="phone" class="form-control" id="phone">
                </div>
                <div class="mb-3">
                    <label for="emailId" class="form-label">Почта</label>
                    <input type="email" name ="email" class="form-control" id="email">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <section>
        HTML;
        return parent::getTemplate($content);
    }
    public static function formOrder($dataProfile = null){
        $fio = (isset($dataProfile) && isset($dataProfile['fio'])) ? $dataProfile['fio'] : '';
        $address = (isset($dataProfile) && isset($dataProfile['address'])) ? $dataProfile['address'] : '';
        $phone = (isset($dataProfile) && isset($dataProfile['phone'])) ? $dataProfile['phone'] : '';
        $email = (isset($dataProfile) && isset($dataProfile['email'])) ? $dataProfile['email'] : '';

        $form = <<<ORDERFORM
        <h3 class="mt-5">Параметры для доставки</h3>
        <div class="col-8">
            <form action="/order" method="POST">
                <div class="mb-3">
                    <label for="fioId" class="form-label">Ваше ФИО:</label>
                    <input type="text" name="fio" class="form-control" id="fioId" placeholder="Иван Иванов" value="{$fio}">
                </div>
                <div class="mb-3">
                    <label for="addressId" class="form-label">Адрес доставки:</label>
                    <input type="textarea" name="address" class="form-control" id="addressId"
                     placeholder="Кемерово, пр.Ленина, д.7, кв.5" value="{$address}">
                </div>
                <div class="mb-3">
                    <label for="phoneId" class="form-label">Телефон:</label>
                    <input type="textarea" name="phone" class="form-control" id="phoneId"
                     placeholder="89990000123" value="{$phone}">
                </div> 
                <div class="mb-3">
                    <label for="emailId" class="form-label">Емайл:</label>
                    <input type="email" name="email" class="form-control" id="emailId"
                     placeholder="ivan@mail.ru" value="{$email}">
                </div>
                <div class="mb-3 float-sm-right">
                    <button type="submit" class="btn btn-primary">Создать заказ</button>
                </div>
            </form>
        </div>
        ORDERFORM;
        
        return $form;
    }
}