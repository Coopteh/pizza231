<?php
namespace App\Services;

use PDO;

class ProductStorage implements ILoadStorage {
    private PDO $connection;
    
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }
    
    public function loadData($tableName): ?array {
        $stmt = $this->connection->query("SELECT * FROM {$tableName}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}