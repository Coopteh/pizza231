<?php
namespace App\Controllers;

require_once __DIR__ . '/../Views/BaseTemplate.php';
require_once __DIR__ . '/../Views/OrderTemplate.php';

use App\Models\Product;
use App\Views\OrderTemplate;

class OrderController
{
    public function get(): string
    {
        
        // Создаем экземпляр модели Product
        $product = new Product();

        // Получаем массив данных корзины
        $data = $product->getBasketData();

        // Создаем экземпляр класса представления OrderTemplate
        $orderTemplate = new OrderTemplate();
        
        // Возвращаем результат обработки шаблона
        return $orderTemplate->getOrderTemplate($data);
    }
}