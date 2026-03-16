<?php
namespace App\Models;
use App\Config\Config;

class Product {
    public function loadData(): ?array
    {
        $file = file_get_contents('c:/xampp/htdocs/storage/data.json');
        $data = json_decode($file, true);
        // $file=fopen("c:/xampp/htdocs/storage/data.json", 'r');
        // if ($file) {
        //     $data = json_decode($file, null);
        //     return $data;
        //     $closed = fclose($file); 
        // }
        return $data;
    }
}