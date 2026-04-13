<?php
namespace App\Services;

use PDO;

class ProductDBStorage extends DBStorage implements ILoadStorage
{
    public function loadData($nameFile): ?array
    {
        $sql = "SELECT * FROM products WHERE is_deleted=0";
        $result = $this->connection->query($sql, PDO::FETCH_ASSOC);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return $rows; 
    }
}