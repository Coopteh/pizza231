<?php
namespace App\Router;

use App\Controllers\AboutController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\BasketController;
use App\Controllers\OrderController;

class Router {
    
    // Базовый путь проекта
    private const BASE_PATH = 'pizza221';
    
    // Текущий ID из URL
    private int $id = 0;
    
    /**
     * Карта маршрутов - заменяет большой switch
     */
    private function getRoutes(): array 
    {
        return [
            'about' => [
                'controller' => AboutController::class, 
                'method' => 'get'
            ],
            'products' => [
                'controller' => ProductController::class, 
                'method' => 'get',
                'withId' => true
            ],
            'basket' => [
                'controller' => BasketController::class, 
                'method' => 'add', 
                'redirect' => true
            ],
            'order' => [
                'GET' => [
                    'controller' => OrderController::class, 
                    'method' => 'get'
                ],
                'POST' => [
                    'controller' => OrderController::class, 
                    'method' => 'create'
                ],
            ],
            'basket_clear' => [
                'controller' => BasketController::class,
                'method' => 'clear',                         
                'redirect' => true
            ],
        ];
    }
    
    /**
     * Основной метод маршрутизации
     */
    public function route(string $url): string {
        // Получаем путь из URL
        $path = parse_url($url, PHP_URL_PATH);
        
        // Убираем базовый путь
        $path = $this->stripBasePath($path);
        
        // Разбиваем на части
        $pieces = array_values(array_filter(explode("/", $path)));
        
        // Получаем ресурс и параметр
        $resource = $pieces[0] ?? '';
        $this->id = isset($pieces[1]) ? intval($pieces[1]) : 0;
        
        // Текущий HTTP-метод
        $method = $_SERVER['REQUEST_METHOD'];

        // Получаем карту маршрутов
        $routes = $this->getRoutes();
        
        // Если маршрут не найден — возвращаем дефолтный обработчик
        if (!isset($routes[$resource])) {
            return $this->handleDefault();
        }
    
        $route = $routes[$resource];
    
        // Поддержка разных методов для одного ресурса (например, order: GET/POST)
        if (isset($route[$method])) {
            $route = $route[$method];
        }
    
        return $this->executeRoute($route);
    }

    /**
     * Выполняет маршрут: создаёт контроллер и вызывает метод
     */
    private function executeRoute(array $route): string 
    {
        $controller = new $route['controller']();
        
        // Формируем параметры для метода контроллера
        $params = [];
        if ($route['withId'] ?? false) {
            $params = [$this->id];
        }
        
        // Вызываем метод контроллера
        $result = $controller->{$route['method']}(...$params);
    
        // Обработка редиректа
        if ($route['redirect'] ?? false) {
            $prevUrl = $_SERVER['HTTP_REFERER'] ?? '/'.self::BASE_PATH.'/';
            header("Location: {$prevUrl}");
            exit;
        }
        
        return $result ?? '';
    }

    /**
     * Обработчик маршрута по умолчанию
     */
    private function handleDefault(): string 
    {
        return (new HomeController())->get();
    }
    
    /**
     * Убирает базовый путь из начала строки
     */
    private function stripBasePath(string $path): string {
        $basePath = '/' . self::BASE_PATH;
        
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        $path = ltrim($path, '/');
        
        return $path;
    }
}