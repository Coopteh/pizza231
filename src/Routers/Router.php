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
    private $id;
    private function getRoutes(): array {
        return [
            'about' => ['controller' => AboutController::class, 
                        'method' => 'get'],
            'products' => ['controller' => ProductController::class, 
                        'method' => 'get',
                        'params' => ['id']],
            'catalogue' => ['controller' => CatalogueController::class, 
                        'method' => 'get', 
                        'params' => ['id' => $this->id]],
            'basket' => ['controller' => BasketController::class, 
                        'method' => 'add', 
                        'redirect' => true],
            'order' => [
                'GET' => ['controller' => OrderController::class, 
                        'method' => 'get'],
                'POST' => ['controller' => OrderController::class, 
                        'method' => 'create'],
            ],
            'basket_clear' => ['controller' => BasketController::class,
                        'method' => 'clear',                         
                        'redirect' => true],
        ];
    }

    public function route(string $url): string {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1];
        $this->id = (isset($pieces[2])) ? intval($pieces[2]) : 0;
        $method = $_SERVER['REQUEST_METHOD'];

        $routes = $this->getRoutes();
        if (!isset($routes[$resource])) {
            return $this->handleDefault();
        }
    
        $route = $routes[$resource];
    
        // Обработка методов для ресурса (например, order)
        if (isset($route[$method])) {
            $route = $route[$method];
        }
    
        return $this->executeRoute($route, $pieces);
            
            // $path = parse_url($url, PHP_URL_PATH);  // /about
            // $pieces = explode("/", $path);  // [0]- пусто, [1]- pizza221, [2]- about
            // $resource = $pieces[1];
            // $method = $_SERVER['REQUEST_METHOD'];
            // switch ($resource) 
            // {
            //     case "about":
            //         $about = new AboutController();
            //         return $about->get();
            //     case "products":
            //         $products = new ProductController();
            //         if (isset($pieces[2])) {
            //             $id = $pieces[2] ?? null;
            //             $id = $id !== null ? intval($id) : null;
            //             return $products->get($id);
            //             }
            //     case "catalogue":
            //         $catalogue = new CatalogueController();
            //         return $catalogue->get();
            //     case "basket":
            //         $basketController = new BasketController();
            //         $basketController->add();
            //         $prevUrl = $_SERVER['HTTP_REFERER'];
            //         header("Location: {$prevUrl}");
            //         return "";
            //     case "basket_clear":
            //         $prevUrl = $_SERVER['HTTP_REFERER'];
            //         header("Location: {$prevUrl}");                    
            //     return "";
            //     case "order":
            //         $order = new OrderController();
            //             if ($method == "POST")
    	    // 	        return $order->create();
            //         return $order->get();
            //     default:
            //         $home = new HomeController();
            //         return $home->get();   
            // }
        }
        private function executeRoute(array $route, array $pieces): string {
        $controller = new $route['controller']();
        $params = ($this->id) ? ['id' => $this->id] : [];
        $result = $controller->{$route['method']}(...$params);
    
        if ($route['redirect'] ?? false) {
            $prevUrl = $server['HTTP_REFERER'] ?? '/';
            header("Location: {$prevUrl}");
            return '';
        }
        return $result ?? '';
    }

    private function handleDefault(): string {
        return (new HomeController())->get();
    }
    

}