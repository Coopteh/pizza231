<?php

namespace App\Controllers;
use App\Views\ProductTemplate;
use App\Models\Product;
use App\Config\Config;
use App\Services\ProductDBStorage;
use App\Services\DatabaseStorage;

class ProductController
{
    public function get($id): string 
    {
        // MODEL CREATION
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceStorage = new ProductDBStorage();
            $model = new Product($serviceStorage, Config::TABLE_PRODUCTS);
            $data = $model->loadData();
        } else {
            // Обработка других типов хранилищ или ошибка
            $data = [];
        }
        
        // $model = new Product();
        // DOES THE KEY EXIST AT ALL?
        if ($data && isset($data[$id])) {
            $productData = $data[$id];
            return ProductTemplate::getCardTemplate($productData);
        }
        // IMPLEMENT THIS CHECK LATER
        // return '<div class="alert alert-danger">Товар с ID ' . $id . ' не найден</div>';
    }
}  