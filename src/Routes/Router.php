<?php
namespace App\Routes;

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\BasketController;
use App\Controllers\OrderController;
use App\Controllers\RegisterController;
use App\Controllers\UserController;

class Router {
    private $id;

    private function getRoutes(): array {
        return [
            'about' => ['controller' => AboutController::class, 
                        'method' => 'get'],
            'products' => ['controller' => ProductController::class, 
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
            'register' => [
                'controller' => RegisterController::class, 
                'method' => 'get'
            ],
            'verify' => [
                'controller' => RegisterController::class, 
                'method' => 'verify',
                'params' => ['token' => $this->id]
            ],
            'login' => [
                'controller' => UserController::class, 
                'method' => 'get'
            ],
            'logout' => [
                'controller' => UserController::class,
                'method' => 'logout'
            ],                           
        ];
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

    public function route(string $url): string {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1];
        $this->id = (isset($pieces[2])) ? $pieces[2] : 0;
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

    /* Старый код с высокой сложностью из-за switch
        switch ($resource) {
            case "about":
                $about = new AboutController();
                return $about->get();
            case "products":
                $product = new ProductController();
                $id = (isset($pieces[2])) ? intval($pieces[2]) : 0;
                return $product->get($id);
            case "basket":
                $basketController = new BasketController();
                $basketController->add();
                $prevUrl = $_SERVER['HTTP_REFERER'];
                header("Location: {$prevUrl}");
                return "";
            case 'order':
                $orderController = new OrderController();
                if ($method == "POST")
    	    	    return $orderController->create();                
                return $orderController->get();
                break;
            case 'basket_clear':
                $basketController = new BasketController();
                $basketController->clear();
                $prevUrl = $_SERVER['HTTP_REFERER'];
                header("Location: {$prevUrl}");
                return '';
            default:
                $home = new HomeController();
                return $home->get();
        }*/
    }
}