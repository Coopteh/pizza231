<?php
namespace App\Services;

use PDO;

class ProductDBStorage extends DBStorage implements ILoadStorage
{
    public function loadData($nameFile): ?array
    {
        $sql = "SELECT перечень полей FROM products";
        $result = $this->connection->query($sql, PDO::FETCH_ASSOC);
        $rows = $result->____ ;
        return $rows; 
    }
}