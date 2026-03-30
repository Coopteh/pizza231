<?php
namespace Controllers;
use Models\Product;
use Views\CatalogTemplate;

class CatalogController
{
    public function get(): string
    {
        $model = new Product();
        $data = $model->loadData();
        return CatalogTemplate::render($data);
    }
}