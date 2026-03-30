<?php
namespace App\Models;
use App\Config\Config;

class Product {
    public function loadData(): ?array {
        
        $file = file_get_contents(Config::FILE_DATA);
        $data = json_decode($file, true);

        return $data;
    }
    public function getBasketData(): array {
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
        public function saveData($arr) {
        $nameFile= Config::FILE_ORDERS;

        $handle = fopen($nameFile, "r");
        if (filesize($nameFile) > 0){ 
            $data = fread($handle, filesize($nameFile)); 
            $allRecords = json_decode($data, true); 
        } else {
            $allRecords = [];
        }
        fclose($handle);
        
        $allRecords[]= $arr;
        $json = json_encode($allRecords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $handle = fopen($nameFile, "w");
        fwrite($handle, $json);
        fclose($handle);
    }
    // В класс Product добавьте:

    public function prepareData(array $form_data,array $basket_data){
            $arr = [];
            $arr['fio'] = $form_data['fio'];
            $arr['address'] = $form_data['address'];
            $arr['phone'] = $form_data['phone'];
            $arr['created_at'] = date("d-m-Y H:i:s");   

            $arr['products'] = $basket_data;
            $all_sum = 0;
            foreach($basket_data as $product){
                $all_sum += $product['price'] * $product['quantity'];
            }
            $arr['all_sum'] = $all_sum;
            return $arr;   
        // LEGACY FUCKASS CODE, MAY I BE STRUCK BY LIGHTNING FOR MY SINS
        // public function prepareData($basketProducts): array {
        // $arr=[];
        // $arr['fio'] = urldecode( $_POST['fio'] );
        //     $arr['address'] = urldecode( $_POST['address'] );
        //     $arr['phone'] = $_POST['phone'];
        //     $arr['created_at'] = date("d-m-Y H:i:s");
        // $products = $this->loadData();
        // $items = [];
        // $all_sum = 0;
        
        // foreach ($basketProducts as $product_id => $data) {
        //     $quantity = (int)($data['quantity'] ?? 0);
            
        //     // Валидация
        //     if ($quantity <= 0) {
        //         throw new \InvalidArgumentException("Некорректное количество для товара #$product_id");
        //     }
            
        //     // Поиск товара в базе
        //     $product = current(array_filter($products, fn($p) => $p['id'] == $product_id));
            
        //     if (!$product) {
        //         throw new \Exception("Товар с ID #$product_id не найден");
        //     }
            
        //     $price = (float)$product['price'];
        //     $sum = $price * $quantity;
        //     $all_sum += $sum;
            
        //     $items[] = [
        //         'id' => $product_id,
        //         'name' => $product['name'],
        //         'quantity' => $quantity,
        //         'price' => $price,
        //         'sum' => $sum,
        //     ];
        // }
        
        // return [
        //     'form_data' => $arr,
        //     'items' => $items,
        //     'all_sum' => $all_sum,
        //     'created_at' => date('Y-m-d H:i:s'),
        // ];
        // }
    }
}