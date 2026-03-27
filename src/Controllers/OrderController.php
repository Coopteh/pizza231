<?php
namespace App\Controllers;
use App\Views\OrderTemplate;
use App\Models\Product;

class OrderController {
        public function get(): string {
        // MODEL CREATION
        $model = new Product();
        $data = $model->getBasketData();
        // var_dump($_SESSION);
        // exit();
        return OrderTemplate::getOrderTemplate($data);
    }
    public function create() {
        $arr = [];
        $arr['fio'] = urldecode( $_POST['fio'] );
            $arr['address'] = urldecode( $_POST['address'] );
            $arr['phone'] = $_POST['phone'];
            $arr['created_at'] = date("d-m-Y H:i:s");	// добавим дату и время создания заказа

        $model = new Product();
        // список заказанных продуктов - берем список товаров из корзины
            $products = $model->getBasketData();
            $arr['products'] = $products;
        // подсчитаем общую сумму заказа
            $all_sum = 0;
            foreach ($products as $product) {
            $all_sum += $product['price'] * $product['quantity'];
            }
            $arr['all_sum'] = $all_sum;

            $model->saveData($arr);

            $_SESSION['basket'] = [];

            $_SESSION['flash'] = "Спасибо! Ваш заказ успешно создан и передан службе доставки";

	        header("Location: /");
	        return '';
    }
}  