<?php
namespace App\Views;

class OrderTemplate extends BaseTemplate {
    public static function getOrderTemplate(array $arr): string {
        $template = parent::getTemplate();
        $title= 'Оформление заказа';
        $content = '<main class="row">
            <h1 class="mt-3">Оформление заказа</h1>
            <h3 class="mt-3">Корзина</h3>';

        $all_sum = 0;
        foreach ($arr as $product) {
		    $name = $product['name'];
            $price = $product['price'];
		    $quantity = $product['quantity'];

            $sum = $price * $quantity;
            $all_sum += $sum;

            $content .= <<<LINE
                <div class="row">
                    <div class="col-5">
                    {$name}
                    </div>
                    <div class="col-3">
                    {$quantity} ед. x {$price} руб.
                    </div>
                    <div class="col-2">
                    {$sum} ₽
                    </div>
                </div>
                LINE;
	    }            
        $content .= '</main>';
        if ($all_sum == 0) {
            $content .= <<<LINE3
            <div class="row">
                <div class="col-12">
                - нет добавленных товаров -
                </div>
            </div>
            LINE3;
        } else {
            $content .= <<<LINE3
            <hr>
            <div class="row">
                <div class="col-5">
                <strong>Итоговая сумма заказа:</strong>
                </div>
                <div class="col-3">
                    &nbsp;
                </div>
                <div class="col-2">
                <strong>{$all_sum}</strong> ₽
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    &nbsp;
                </div>
                <div class="col-2 float-end">
                    <form action="/basket_clear" method="POST">
                        <button type="submit" class="btn btn-secondary mt-3">Очистить корзину</button>
                    </form>
                </div>
            </div>            
            LINE3;
        }

        /*
ФИО покупателя (input) c label "Ваше ФИО:"
Адрес покупателя (input) c label "Адрес доставки:"
Телефон покупателя (input) c label "Телефон:"
Кнопка (submit) "Создать заказ"
        */
        $content .= self::formOrder();

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }

    public static function formOrder(){
        $form = <<<ORDERFORM
        <h3 class="mt-5">Параметры для доставки</h3>
        <div class="col-8">
            <form action="/order" method="POST">
                <div class="mb-3">
                    <label for="fioId" class="form-label">Ваше ФИО:</label>
                    <input type="text" name="fio" class="form-control" id="fioId" placeholder="Иван Иванов">
                </div>
                <div class="mb-3">
                    <label for="addressId" class="form-label">Адрес доставки:</label>
                    <input type="textarea" name="address" class="form-control" id="addressId" placeholder="Кемерово, пр.Ленина, д.7, кв.5">
                </div>
                <div class="mb-3">
                    <label for="phoneId" class="form-label">Телефон:</label>
                    <input type="textarea" name="phone" class="form-control" id="phoneId" placeholder="89990000123">
                </div> 
                <div class="mb-3">
                    <label for="emailId" class="form-label">Емайл:</label>
                    <input type="email" name="email" class="form-control" id="emailId" placeholder="ivan@mail.ru">
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