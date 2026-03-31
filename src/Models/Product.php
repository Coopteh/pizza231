<?php
namespace App\Models;

use App\Config\Config;
use App\Services\ILoadStorage;
use App\Services\ISaveStorage;
use App\Services\IStorage;

class Product
{
    private IStorage $dataStorage;
    private string $nameResourceLoad;
    private string $nameResourceSave;
    
    // Внедряем зависимость через конструктор
    public function __construct(IStorage $service, string $nameLoad, string $nameSave)
    {
        $this->dataStorage = $service;
        $this->nameResourceLoad = $nameLoad;
        $this->nameResourceSave = $nameSave;
    }

    public function loadData(): ?array {
        return $this->dataStorage->loadData( $this->nameResourceLoad ); 
    }

    public function saveData($arr): bool {
        return $this->dataStorage->saveData( $this->nameResourceSave, $arr ); 
    }

    public function getBasketData(): array {
        // проверка корзины на существование
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
            }//if
        }//foreach

	    return $basketProducts;
    }

    public function prepareData(array $form_data, array $basket_data): array {
        $arr = [];

	    $arr['fio'] = $form_data['fio'];
        $arr['address'] = $form_data['address'];
        $arr['phone'] = $form_data['phone'];
        $arr['email'] = $form_data['email'];
        $arr['created_at'] = date("d-m-Y H:i:s");	// добавим дату и время создания заказа

        $arr['products'] = $basket_data;
	    
        // подсчитаем общую сумму заказа
        $all_sum = 0;
        foreach ($basket_data as $product) {
		    $all_sum += $product['price'] * $product['quantity'];
        }
        $arr['all_sum'] = $all_sum;

        return $arr;
     }
}
