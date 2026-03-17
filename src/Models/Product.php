<?php
namespace App\Models;

require_once __DIR__ . '/../Config/Config.php';

use App\Config\Config;

class Product
{
    public function loadData(): ?array
    {
        if (!file_exists(Config::FILE_PRODUCTS)) {
            return null;
        }

        $data = file_get_contents(Config::FILE_PRODUCTS);
        $arr = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($arr)) {
            return null;
        }

        $indexedData = [];
        foreach ($arr as $item) {
            if (isset($item['id'])) {
                $indexedData[$item['id']] = $item;
            }
        }

        return $indexedData;
    }
}