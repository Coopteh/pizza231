<?php
namespace App\Routers;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
class Router {

    // I TRIED BUT IT WAS TO NO AVAIL.
    // STILL, A NICE LITTLE PRACTICE.

    // public function route(string $url): string {
    //     $path = parse_url($url, PHP_URL_PATH);
    //     $pieces = explode("/", $path);
    //     $resource = $pieces[2] ?? 'home';
    //     $resourse = trim($resourse, '/');

    //     if (empty($resource)) {
    //         $resource = 'home';
    //     }

    //     $controller = ucfirst($resource) . 'Controller';
        
    //     return $controller;
    // }

    // THE FUNCTION TAKEN FROM THE LECTURE.
    // MAY THE MACHINE GOD FORGIVE ME.

    public function route(string $url): string 
        {
            $path = parse_url($url, PHP_URL_PATH);  // /about
            $pieces = explode("/", $path);  // [0]- пусто, [1]- pizza221, [2]- about
            $resource = $pieces[1];
            switch ($resource) 
            {
                case "about":
                    $about = new AboutController();
                    return $about->get();
                    break;
                default:
                    $home = new HomeController();
                    return $home->get();
            }
        }
}