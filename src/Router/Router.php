<?php
namespace App\Router;

require_once __DIR__ . '/../Controllers/HomeController.php';
require_once __DIR__ . '/../Controllers/AboutController.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ProductController;

class Router
{
    public function route(string $url): ?string 
    {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        
        // Получаем ресурс (например, 'about' или пустую строку для главной)
        $resource = $pieces[1] ?? '';

        switch ($resource) {
            case "about":
                // Теперь класс должен найтись, так как файл подключен выше
                $controller = new AboutController();
                return $controller->get();
            
            case "home":
            case "":
                $controller = new HomeController();
                return $controller->get();

            case "product":
                $product = new ProductController();
                $id = isset($pieces[2]) ? intval($pieces[2]) : 0;
                return $product->get($id);
                
            default:
                break;
        }
    }
}