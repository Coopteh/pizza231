<?php

namespace App\Controllers;
use App\Views\ProductTemplate;
use App\Models\Product;


class ProductController {
    public function get($id = null): string {
        $model = new Product();
        $data = $model->loadData();
        if ($id)
            $data = $data[$id];
        return ProductTemplate::getCard($data);        
    }
}