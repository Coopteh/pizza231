<?php
namespace App\Routes;

use App\Controllers\AboutController;
use App\Controllers\HomeController;

class Router {
    public function route(string $url): string {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1];
        switch ($resource) {
            case "about":
                $about = new AboutController();
                return $about->get();
            default:
                $home = new HomeController();
                return $home->get();
        }
    }
}