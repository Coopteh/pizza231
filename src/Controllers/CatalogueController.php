<?php

namespace App\Controllers;
use App\Config\Config;
use App\Views\CatalogueTemplate;
use App\Models\Product;
use App\Services\ProductDBStorage;
use App\Services\DatabaseStorage;


class CatalogueController
{
    public function get(): string 
    {
        // MODEL CREATION
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceStorage = new ProductDBStorage();
            $model = new Product($serviceStorage, Config::TABLE_PRODUCTS);
        }
        
        // if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
        //     $serviceStorage = new FileStorage();
        // } else {
        //     $serviceStorage = new DatabaseStorage();
        // }
        // $model = new Product($serviceStorage, Config::FILE_DATA, Config::FILE_ORDERS);
        $data = $model->loadData();
        // $model = new Product();
        $data = $model->loadData();

        return CatalogueTemplate::getCatalogue($data);
    }
}  