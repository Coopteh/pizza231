<?php

namespace App\Controllers;
use App\Views\CatalogueTemplate;
use App\Models\Product;

class CatalogueController
{
    public function get(): string 
    {
        // MODEL CREATION
        $model = new Product();
        $data = $model->loadData();

        return CatalogueTemplate::getCatalogue($data);
    }
}  