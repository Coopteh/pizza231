<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Views/ProductTemplate.php';

use App\Models\Product; 
use App\Views\ProductTemplate;

class ProductController
{
    public function get($id): string 
    {
        $model = new Product();
        $data = $model->loadData(); 
        
        if ($data && isset($data[$id])) {
            $productData = $data[$id];
            return \App\Views\ProductTemplate::getCardTemplate($productData);
        }
        
        return '<div class="alert alert-danger">Товар с ID ' . $id . ' не найден</div>';
    }
}  