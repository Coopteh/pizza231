<?php
namespace App\Models;

use App\Configs\Config;

class Product
{
    // Существующий метод для загрузки всех данных
    public function loadData(): ?array
    {
        $file = Config::FILE_PRODUCTS;
        if (!file_exists($file)) {
            return null;
        }
        $data = file_get_contents($file);
        return json_decode($data, true);
    }

    // НОВЫЙ МЕТОД: Получить все товары
    public function getAllProducts(): array
    {
        $data = $this->loadData();
        return $data ?? []; // Если данных нет, вернем пустой массив
    }

    // Метод для получения одного товара (уже был)
    public function getProductById(int $id): ?array
    {
        $all = $this->getAllProducts();
        foreach ($all as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }
}