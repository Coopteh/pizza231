<?php
namespace App\Services;

use PDO;

class OrderStorage implements ISaveStorage {
    private PDO $connection;
    
    public function __construct(PDO $connection) {
        $this->connection = new PDO(
            Config::MYSQL_DNS,
            Config::MYSQL_USER,
            Config::MYSQL_PASSWORD
        );
    }
    public function getPDO(){
        return $connection;
    }
    
    public function saveData($tableName, $data): bool {
        // Логика сохранения заказа в БД
        // Например:
        $sql = "INSERT INTO order (fio, address, phone, email, created_at, products, all_sum) 
                VALUES (:fio, :address, :phone, :email, :created_at, :products, :all_sum)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            ':fio' => $data['fio'],
            ':address' => $data['address'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':created_at' => $data['created_at'],
            ':products' => json_encode($data['products']),
            ':all_sum' => $data['all_sum']
        ]);
    }
}