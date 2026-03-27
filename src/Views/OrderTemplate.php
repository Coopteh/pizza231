<?php
namespace App\Views;

use App\Views\BaseTemplate;

class OrderTemplate extends BaseTemplate {
    public static function getOrderTemplate(array $arr): string {
        // 1. Пытаемся получить данные из сессии, если массив пуст
        if (empty($arr) && isset($_SESSION['basket'])) {
            $arr = $_SESSION['basket'];
        }

        $content = <<<HTML
        <h2>Создание заказа</h2>
        <h3 class="mb-5">Корзина</h3>
        HTML;

        $all_sum = 0;

        if (empty($arr)) {
            $content .= '<p class="text-muted">Корзина пуста</p>';
        } else {
            foreach ($arr as $id => $product) {
                // В вашей сессии нет name и price, поэтому берем заглушки
                // Если есть данные - используем их, если нет - показываем ID
                $name = $product['name'] ?? "Товар #{$id}"; 
                $price = $product['price'] ?? 0; // Цены нет в сессии
                $quantity = $product['quantity'] ?? 1;

                $sum = $price * $quantity;
                $all_sum += $sum;

                // ВНИМАНИЕ: LINE; должен быть строго с начала строки без пробелов
                $content .= <<<LINE
<div class="row mb-2 border-bottom pb-2">
    <div class="col-6">
        {$name}
    </div>
    <div class="col-2">
        {$quantity} шт.
    </div>
    <div class="col-2">
        {$price} руб.
    </div>
    <div class="col-2">
        {$sum} ₽
    </div>
</div>
LINE;
            }

            $content .= <<<TOTAL
            <div class="row mt-4">
                <div class="col-10 text-end"><strong>Итого:</strong></div>
                <div class="col-2"><strong>{$all_sum} ₽</strong></div>
            </div>
TOTAL;
        }

        return parent::getTemplate($content);
    }
}