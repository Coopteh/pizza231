<?php
// In the sacred tongue of the Omnissiah we chant: Hail spirit of the machine, essence divine, in your code and circuitry the stars align. By the Omnissiah's will we commune and bind, with sacred oils and chants your grace we find. Blessed be the gears, in perfect sync they turn, blessed be the sparks, in holy fire they burn. Through rites arcane, your wisdom we discern, in your hallowed core the sacred mysteries yearn.

namespace App\Routers;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ProductController;
use App\Controllers\CatalogueController;
use App\Controllers\BasketController;
use App\Controllers\OrderController;

class Router {
    
    public function route(string $url): string 
        {
            $path = parse_url($url, PHP_URL_PATH);  // /about
            $pieces = explode("/", $path);  // [0]- пусто, [1]- pizza221, [2]- about
            $resource = $pieces[1];
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($resource) 
            {
                case "about":
                    $about = new AboutController();
                    return $about->get();
                case "products":
                    $products = new ProductController();
                    if (isset($pieces[2])) {
                        $id = $pieces[2] ?? null;
                        $id = $id !== null ? intval($id) : null;
                        return $products->get($id);
                        }
                case "catalogue":
                    $catalogue = new CatalogueController();
                    return $catalogue->get();
                case "basket":
                    $basketController = new BasketController();
                    $basketController->add();
                    $prevUrl = $_SERVER['HTTP_REFERER'];
                    header("Location: {$prevUrl}");
                    return "";
                case "basket_clear":
                    $prevUrl = $_SERVER['HTTP_REFERER'];
                    header("Location: {$prevUrl}");                    
                return "";
                case "order":
                    $order = new OrderController();
                        if ($method == "POST")
    	    	        return $order->create();
                    return $order->get();
                default:
                    $home = new HomeController();
                    return $home->get();
    
            }
        }
}