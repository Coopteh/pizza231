<?php

namespace App\Controllers;
use App\Views\ProductTemplate;
use App\Models\Product;
use App\Config\Config;


class ProductController
{
    public function get($id): string 
    {
        // MODEL CREATION
        if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
            $serviceStorage = new FileStorage();
        } else {
            $serviceStorage = new DatabaseStorage();
        }
        $model = new Product($serviceStorage, Config::FILE_DATA, Config::FILE_ORDERS);
        $data = $model->loadData();
        
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