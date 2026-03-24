<?php
namespace Controllers;

use Models\Product;
use Views\ProductTemplate;

class ProductController
{
    public function get(int $id): string 
    {
        $model = new Product();
        $product = $model->getById($id);
        
        if (!$product) {
            http_response_code(404);
            return (new ErrorController())->get();
        }
        
        return ProductTemplate::render($product);
    }
}