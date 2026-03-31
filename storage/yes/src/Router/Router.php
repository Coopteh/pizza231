<?php
namespace App\Router;

// 👇 Существующие контроллеры
require_once __DIR__ . '/../Controllers/HomeController.php';
require_once __DIR__ . '/../Controllers/AboutController.php';
require_once __DIR__ . '/../Controllers/ProductController.php';
require_once __DIR__ . '/../Controllers/CatalogController.php';

// 👇 НОВЫЕ: Контроллер и модель корзины
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Controllers/CartController.php';

// 👇 НОВЫЕ: Контроллер авторизации
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Controllers/AuthController.php';

// 👇 НОВЫЕ: Контроллер админки
require_once __DIR__ . '/../Controllers/AdminController.php';

// 👇 НОВЫЕ: Контроллер профиля
require_once __DIR__ . '/../Controllers/ProfileController.php';

// 👇 Логирование ошибок
require_once __DIR__ . '/../Models/Logger.php';

use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ProductController;
use App\Controllers\CatalogController;
use App\Controllers\CartController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\ProfileController;
use App\Models\Logger;

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
                $catalog = new CatalogController();
                return $catalog->get();
            
            // 👇 НОВЫЕ МАРШРУТЫ ДЛЯ КОРЗИНЫ
            case "cart":
                $cart = new CartController();
                return $cart->get(); // Страница корзины
                
            // 👇 НОВЫЕ МАРШРУТЫ ДЛЯ АВТОРИЗАЦИИ
            case "register":
                $auth = new AuthController();
                return $auth->register();
            
            case "verify":
                $auth = new AuthController();
                return $auth->verify();
            
            case "login":
                $auth = new AuthController();
                return $auth->login();
            
            case "logout":
                $auth = new AuthController();
                return $auth->logout();
                
            // 👇 НОВЫЕ МАРШРУТЫ ДЛЯ АДМИНКИ
            case "admin":
                $admin = new AdminController();
                $action = $pieces[2] ?? 'index';
                
                switch ($action) {
                    case 'orders':
                        return $admin->orders();
                    case 'users':
                        return $admin->users();
                    case 'logs':
                        return $admin->logs();
                    case 'catalog':
                        return $admin->catalog();
                    case 'index':
                    case '':
                    default:
                        return $admin->index();
                }
                
            // 👇 НОВЫЕ МАРШРУТЫ ДЛЯ ПРОФИЛЯ
            case "profile":
                $profile = new ProfileController();
                return $profile->get();
                
            case "api":
                // Обработка API-запросов
                $subResource = $pieces[2] ?? '';
                
                // API товара
                if ($subResource === 'product') {
                    $id = isset($pieces[3]) ? intval($pieces[3]) : 0;
                    $productController = new ProductController();
                    return $productController->apiGet($id);
                }
                
                // API корзины
                $cart = new CartController();
                
                if ($subResource === 'cart') {
                    $action = $pieces[3] ?? '';
                    
                    // Читаем JSON-тело запроса
                    $input = json_decode(file_get_contents('php://input'), true);
                    
                    switch ($action) {
                        case 'add':
                            return $cart->add($input);
                        case 'update':
                            return $cart->update($input);
                        case 'remove':
                            return $cart->remove($input);
                        case 'clear':
                            return $cart->clear();
                        case 'order':
                            return $cart->order($input);
                        case 'update-status':
                            return $cart->updateStatus($input);
                        default:
                            http_response_code(400);
                            return json_encode(['error' => 'Неизвестное действие']);
                    }
                }
                
                // API авторизации
                if ($subResource === 'auth') {
                    $auth = new AuthController();
                    $action = $pieces[3] ?? '';
                    
                    switch ($action) {
                        case 'register':
                            return $auth->apiRegister();
                        case 'login':
                            return $auth->apiLogin();
                        case 'logout':
                            return $auth->apiLogout();
                        case 'verify':
                            return $auth->apiVerify();
                        case 'resend':
                            return $auth->apiResend();
                        case 'current':
                            return $auth->apiGetCurrent();
                        default:
                            http_response_code(400);
                            return json_encode(['error' => 'Неизвестное действие']);
                    }
                }
                
                // API админки
                if ($subResource === 'admin') {
                    $admin = new AdminController();
                    $action = $pieces[3] ?? '';
                    
                    switch ($action) {
                        case 'stats':
                            return $admin->apiStats();
                        case 'orders':
                            return $admin->apiOrders();
                        case 'users':
                            return $admin->apiUsers();
                        case 'logs':
                            return $admin->apiLogs();
                        case 'clear-logs':
                            return $admin->apiClearLogs();
                        case 'products':
                            return $admin->apiProducts();
                        case 'product':
                            // /api/admin/product - для POST (создание)
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                return $admin->apiCreateProduct();
                            }
                            // /api/admin/product/{id} - для PUT/DELETE
                            $id = isset($pieces[4]) ? intval($pieces[4]) : 0;
                            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                                return $admin->apiUpdateProduct($id);
                            } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                                return $admin->apiDeleteProduct($id);
                            }
                            http_response_code(400);
                            return json_encode(['error' => 'Неизвестное действие']);
                        default:
                            http_response_code(400);
                            return json_encode(['error' => 'Неизвестное действие']);
                    }
                }
                
                // API профиля
                if ($subResource === 'profile') {
                    $profile = new ProfileController();
                    $action = $pieces[3] ?? '';
                    
                    switch ($action) {
                        case 'update':
                            return $profile->apiUpdate();
                        case 'avatar':
                            return $profile->apiUploadAvatar();
                        case '':
                        case 'get':
                            return $profile->apiGet();
                        default:
                            http_response_code(400);
                            return json_encode(['error' => 'Неизвестное действие']);
                    }
                }
                
                // Если не наш под маршрут — 404
                http_response_code(404);
                return json_encode(['error' => 'API endpoint not found']);
                
            default:
                http_response_code(404);
                Logger::info('404 Not Found', ['url' => $url]);
                echo "404 - Страница не найдена";
                break;
        }
        
        return null;
    }
}