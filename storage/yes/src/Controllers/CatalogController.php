<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Views/CatalogTemplate.php';

use App\Models\Product;
use App\Views\CatalogTemplate;

class CatalogController
{
    public function get(): void
    {
        $search = trim($_GET['search'] ?? '');
        
        $productModel = new Product();
        $allProducts = $productModel->loadData() ?? [];
        
        $filteredProducts = $this->filterProducts($allProducts, $search);
        
        // 👇 Вызываем render() вместо getTemplate()
        echo CatalogTemplate::render($filteredProducts, $search);
    }
    
    private function filterProducts(array $products, string $search): array
    {
        if (empty($search)) {
            return $products;
        }
        
        $searchLower = mb_strtolower($search);
        $results = [];
        
        foreach ($products as $product) {
            $name = mb_strtolower($product['name'] ?? '');
            $description = mb_strtolower($product['description'] ?? '');
            
            if (str_contains($name, $searchLower) || str_contains($description, $searchLower)) {
                $results[] = $product;
            }
        }
        
        return $results;
    }
}