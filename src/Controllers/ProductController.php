<?php
namespace App\Controllers;

use App\Models\Product;
use App\Views\ProductTemplate;
use App\Config\Config;
use App\Services\ProductDBStorage;
use App\Services\FileStorage;

class ProductController {
    public function get($id = null): string 
    {
        if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
            $serviceStorage = new FileStorage();
            $model = new Product($serviceStorage, Config::FILE_PRODUCTS, Config::FILE_ORDERS);
        } 
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceStorage = new ProductDBStorage();
            $model = new Product($serviceStorage, Config::TABLE_PRODUCTS, Config::TABLE_ORDERS);
        }

        $data = $model->loadData();
        if ($id) {
            $data = $data[$id-1];
            return ProductTemplate::getCardTemplate($data);
        } else {
            return ProductTemplate::getAllTemplate($data);            
        }
    }
}