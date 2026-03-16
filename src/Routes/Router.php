<?php
namespace App\Routes;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ProductController;
class Router{
    public function route(string $url):?string 
    {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1];
        switch ($resource) {
            case "about":
                $about = new AboutController();
                return $about->get();
            case "home":
                $home = new HomeController();
                return $home->get();
            case "":
                $home = new HomeController();
                return $home->get();
            case "product":
                $product = new ProductController();
                $id = ($pieces[2]) ? intval($pieces[2]) : 0;
                return $product->get($id);
        }
    }
}