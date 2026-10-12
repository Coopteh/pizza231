<?php
namespace App\Router;

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\BasketController;
use App\Controllers\OrderController;
use App\Controllers\AuthController;
use App\Controllers\RegisterController;
use App\Controllers\ProfileController;
use App\Controllers\SettingsController;
use App\Controllers\ReviewController;

class Router {
    private int $id = 0;

    private function getRoutes(): array {
        return [
            'about' => ['controller' => AboutController::class, 'method' => 'get'],
            'products' => ['controller' => ProductController::class, 'method' => 'get', 'params' => ['id' => $this->id]],
            'basket' => ['controller' => BasketController::class, 'method' => 'add', 'redirect' => true],
            'order' => [
                'GET' => ['controller' => OrderController::class, 'method' => 'get'],
                'POST' => ['controller' => OrderController::class, 'method' => 'create'],
            ],
            'basket_clear' => ['controller' => BasketController::class, 'method' => 'clear', 'redirect' => true],
            
            // Авторизация
            'register' => [
                'GET'  => ['controller' => RegisterController::class, 'method' => 'get'],
                'POST' => ['controller' => RegisterController::class, 'method' => 'get'],
            ],
            'login' => [
                'GET'  => ['controller' => AuthController::class, 'method' => 'login'],
                'POST' => ['controller' => AuthController::class, 'method' => 'loginPost'],
            ],
            'logout' => ['controller' => AuthController::class, 'method' => 'logout', 'redirect' => true],
            'profile' => ['controller' => ProfileController::class, 'method' => 'get'],
            'verify' => ['controller' => RegisterController::class, 'method' => 'verify'],
            
            // Настройки и отзывы
            'settings' => [
                'GET'  => ['controller' => SettingsController::class, 'method' => 'get'],
                'POST' => ['controller' => SettingsController::class, 'method' => 'updateProfile'],
            ],
            'settings_profile' => ['controller' => SettingsController::class, 'method' => 'updateProfile', 'redirect' => true],
            'reviews' => ['controller' => ReviewController::class, 'method' => 'add', 'redirect' => true],
        ];
    }

    private function executeRoute(array $route): string {
        $controller = new $route['controller']();
        $params = ($this->id) ? ['id' => $this->id] : [];
        $result = $controller->{$route['method']}(...$params);
    
        if ($route['redirect'] ?? false) {
            $prevUrl = $_SERVER['HTTP_REFERER'] ?? '/';
            header("Location: {$prevUrl}");
            exit();
        }
        return $result ?? '';
    }

    private function handleDefault(): string {
        return (new HomeController())->get();
    }

    public function route(string $url): string {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", trim($path, "/"));
        $resource = $pieces[0] ?? '';
        $this->id = (isset($pieces[1])) ? intval($pieces[1]) : 0;
        $method = $_SERVER['REQUEST_METHOD'];

        $routes = $this->getRoutes();
        
        if (isset($routes[$resource])) {
            $routeConfig = $routes[$resource];
            
            if (isset($routeConfig[$method])) {
                $route = $routeConfig[$method];
            } elseif (isset($routeConfig['controller'])) {
                $route = $routeConfig;
            } else {
                return $this->handleDefault();
            }
        } else {
            return $this->handleDefault();
        }
    
        return $this->executeRoute($route);
    }
}