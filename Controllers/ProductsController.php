<?php
namespace Controllers;
use Views\ProductsTemplate;

class ProductsController
{
    public function get(): string
    {
        return ProductsTemplate::getTemplate();
    }
}