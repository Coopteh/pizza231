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

        if (!isset($data[$id]) || $id === 0) {
            return ProductTemplate::getCatalogue($data);
        }
        if ($data && isset($data[$id])) {
            $productData = $data[$id];
            return ProductTemplate::getCardTemplate($productData);
        }
        // IMPLEMENT THIS CHECK LATER
        // return '<div class="alert alert-danger">Товар с ID ' . $id . ' не найден</div>';
    }
}  