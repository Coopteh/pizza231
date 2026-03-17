<?php
namespace App\Controllers;

use App\Models\Product;
use App\Views\ProductTemplate;

class ProductController
{
    public function get($id): string 
    {
        $model = new Product();
        $allData = $model->loadData();
        
        // Ищем товар по ID (учитываем, что массив может быть с ключами 0, 1, а ID товара 1, 2)
        $productData = null;
        if ($allData) {
            foreach ($allData as $item) {
                if ($item['id'] == $id) {
                    $productData = $item;
                    break;
                }
            }
        }

        return ProductTemplate::getCardTemplate($productData ?? []);
    }
}