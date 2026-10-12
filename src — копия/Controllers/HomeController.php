<?php

namespace App\Controllers;

use App\Views\HomeTemplate;
use App\Models\Product;
use App\Config\Config;
use App\Services\ProductDBStorage;
use App\Services\FileStorage;

class HomeController
{
    public function get(): string
    {
        $searchQuery = trim($_GET['search'] ?? '');
        $products = [];
        
        if (!empty($searchQuery)) {
            $storage = Config::STORAGE_TYPE === Config::TYPE_DB 
                ? new ProductDBStorage() 
                : new FileStorage();
            $productModel = new Product($storage, Config::TABLE_PRODUCTS);
            $allProducts = $productModel->loadData() ?: [];
            
            $queryLower = mb_strtolower($searchQuery);
            foreach ($allProducts as $p) {
                if (!is_array($p)) continue;
                $name = mb_strtolower($p['name'] ?? '');
                $desc = mb_strtolower($p['description'] ?? '');
                if (str_contains($name, $queryLower) || str_contains($desc, $queryLower)) {
                    $products[] = $p;
                }
            }
        }
        
        return HomeTemplate::getTemplate($searchQuery, $products);
    }
}