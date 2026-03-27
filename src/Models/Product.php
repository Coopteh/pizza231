<?php
namespace App\Models;

require_once __DIR__ . '/../Config/Config.php';

use App\Config\Config;

class Product
{
    public function loadData(): ?array
    {
        if (!file_exists(Config::FILE_PRODUCTS)) {
            return null;
        }

        $data = file_get_contents(Config::FILE_PRODUCTS);
        $arr = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($arr)) {
            return null;
        }

        $indexedData = [];
        foreach ($arr as $item) {
            if (isset($item['id'])) {
                $indexedData[$item['id']] = $item;
            }
        }

        return $indexedData;
    }

    public function getBasketData(): array {
        session_start();
            if (!isset($_SESSION['basket'])) {
                $_SESSION['basket'] = [];
            }
        $products = $this->loadData();
        $basketProducts= [];

            foreach ($products as $product) {
                $id = $product['id'];

                if (array_key_exists($id, $_SESSION['basket'])) {
            // количество товара берем то что указано в корзине
                    $quantity = $_SESSION['basket'][$id]['quantity'];

            // остальные характеристики берем из массива всех товаров
                    $name = $product['name'];
                    $price= $product['price'];

            // сумму вычислим 
                    $sum  = $price * $quantity;

            // добавим в новый массив
            $basketProducts[] = array( 
                'id' => $id, 
                'name' => $name, 
                'quantity' => $quantity,
                'price' => $price,
                'sum' => $sum,
            );
                }
            }
        return $basketProducts;
    }
}