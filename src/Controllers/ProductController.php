<?php

namespace App\Controllers;
use App\Views\ProductTemplate;
use App\Models\Product;


// class ProductController {
//     public function get($id = null): string {
//         $model = new Product();
//         $data = $model->loadData();
//         if ($id)
//             $data = $data[$id];
//         return ProductTemplate::getCardTemplate($data);        
//     }
// }

class ProductController
{
    public function get($id): string 
    {
        // MODEL CREATION
        $model = new Product();
        $data = $model->loadData();
        
        // DOES THE KEY EXIST AT ALL?
        if ($data && isset($data[$id])) {
            $productData = $data[$id];
            return \App\Views\ProductTemplate::getCardTemplate($productData);
        }
        
        return '<div class="alert alert-danger">Товар с ID ' . $id . ' не найден</div>';
    }
}  