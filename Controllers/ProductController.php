<?php
namespace Controllers;

use Models\Product;
use Views\ProductTemplate;

class ProductController
{
    public function get(?int $id = null): string 
    {
        $model = new Product();
        $data = $model->loadData();
        
        // Если ID не передан — показываем каталог
        if ($id === null) {
            return ProductTemplate::getAllTemplate($data);
        }
        
        // Иначе — страница конкретного продукта
        return ProductTemplate::renderProduct($id, $data);
    }
}