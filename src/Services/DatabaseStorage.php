<?php
namespace App\Services;
use App\Config\Config;
use PDO;

class DatabaseStorage implements ILoadStorage, ISaveStorage 
    {
    protected PDO $connection;
    
    public function __construct() {
        $this->connection = new PDO(
            Config::MYSQL_DNS,
            Config::MYSQL_USER,
            Config::MYSQL_PASSWORD
        );
    }

    // Реализация метода loadData из ILoadStorage
    public function loadData($tableName): ?array {
        try {
            $stmt = $this->connection->query("SELECT * FROM {$tableName}");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("DB Error: " . $e->getMessage());
            return [];
        }
    }
    
    // ✅ Реализация ISaveStorage
    public function saveData($tableName, $data): bool {
        try {
            $columns = array_keys($data);
            $placeholders = ':' . implode(', :', $columns);
            $sql = "INSERT INTO {$tableName} (" . implode(', ', $columns) . ") VALUES ($placeholders)";
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute($data);
        } catch (\PDOException $e) {
            error_log("DB Save Error: " . $e->getMessage());
            return false;
        }
    }
}