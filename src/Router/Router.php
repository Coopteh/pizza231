<?php
namespace App\Router;

use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ProductController; // Не забудьте этот импорт

class Router
{
    // Убедитесь, что слово public есть, и название метода route
    public function route(string $url): ?string 
    {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        
        // Адаптация под вашу структуру (см. предыдущий ответ)
        // Если URL: http://localhost/pizza221/product/1
        // То $pieces[1] = pizza221, $pieces[2] = product, $pieces[3] = 1
        
        // Попытка определить ресурс умно:
        $resource = '';
        $id = null;

        if (isset($pieces[2]) && $pieces[1] === 'pizza221') {
            $resource = $pieces[2];
            $id = $pieces[3] ?? null;
        } elseif (isset($pieces[1])) {
            // Если папки pizza221 в пути нет
            $resource = $pieces[1];
            $id = $pieces[2] ?? null;
        }

        switch ($resource) {
            case "about":
                $about = new AboutController();
                return $about->get();
            
            case "product":
                $product = new ProductController();
                $productId = $id ? intval($id) : 0;
                return $product->get($productId);

            default:
                $home = new HomeController();
                return $home->get();
        }
    }
}