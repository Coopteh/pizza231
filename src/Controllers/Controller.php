<?php
namespace App\Controllers;

class Controller
{
    protected function view(string $name, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . "/../Views/{$name}.php";
        require __DIR__ . "/../Views/layouts/main.php";
    }
}