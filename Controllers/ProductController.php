<?php
namespace Controllers;

use Views\ProductTemplate;

class ProductController
{
    private int $productId;
    
    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }
    
    public function get(): string 
    {
        // 🆕 Вызываем новый метод renderProduct вместо getTemplate
        return ProductTemplate::renderProduct($this->productId);
    }
}