<?php
namespace App\Router;

require_once __DIR__ . '/../Controllers/HomeController.php';
require_once __DIR__ . '/../Controllers/AboutController.php';
require_once __DIR__ . '/../Controllers/ProductController.php';
require_once __DIR__ . '/../Controllers/CatalogController.php';
require_once __DIR__ . '/../Controllers/BasketController.php';
require_once __DIR__ . '/../Controllers/OrderController.php';
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ProductController;
use App\Controllers\CatalogController;
use App\Controllers\BasketController;
use App\Controllers\OrderController;

class Router
{
    public function route(string $url): ?string 
    {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        
        $resource = $pieces[1] ?? '';

        switch ($resource) {
            case "about":
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

            case "catalog":
                $product = new CatalogController();
                return $product->get();
            case "basket":
                $basketController = new BasketController();
                $basketController->add();
                $prevUrl = $_SERVER['HTTP_REFERER'];
                header("Location: {$prevUrl}");
		        return "";
            case "order":
                $controller = new OrderController();
                return $controller->get();
            default:
                http_response_code(404);
                echo "404 - Страница не найдена";
                break;
        }
    }
}