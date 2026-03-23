<?php
namespace App\Models;
use App\Config\Config;

class Product {
    public function loadData(): ?array {
        
        $file = file_get_contents(Config::FILE_DATA);
        $data = json_decode($file, true);

        return $data;
    }
}