<?php

namespace App\Services;

use PDO;
use App\Config\Config;

// ✅ ДОБАВЛЕНО: implements ILoadStorage, ISaveStorage
class ProductDBStorage extends DBStorage implements ILoadStorage, ISaveStorage
{
    public function loadData(string $name): ?array
    {
        $table = ($name === Config::FILE_ORDERS) ? Config::TABLE_ORDERS : Config::TABLE_PRODUCTS;
        
        try {
            $stmt = $this->connection->query("SELECT * FROM `{$table}`");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (\PDOException $e) {
            error_log("ProductDBStorage::loadData Error: " . $e->getMessage());
            return null;
        }
    }

    public function saveData(string $name, array $data): bool
    {
        // Записываем только заказы
        if ($name !== Config::FILE_ORDERS) return false;

        $this->connection->beginTransaction();
        try {
            $sql = "INSERT INTO `orders` 
                    (order_id, fio, user_phone, user_address, user_comment, all_sum, created_at, status) 
                    VALUES 
                    (:order_id, :fio, :user_phone, :user_address, :user_comment, :all_sum, :created_at, :status)";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':order_id'     => $data['order_id'] ?? uniqid('ord_'),
                ':fio'          => $data['fio'] ?? '',
                ':user_phone'   => $data['user_phone'] ?? '',
                ':user_address' => $data['user_address'] ?? '',
                ':user_comment' => $data['user_comment'] ?? '',
                ':all_sum'      => (float)($data['all_sum'] ?? 0),
                ':created_at'   => $data['created_at'] ?? date('Y-m-d H:i:s'),
                ':status'       => $data['status'] ?? 'new',
            ]);

            $orderId = $this->connection->lastInsertId();

            if (!empty($data['products']) && is_array($data['products'])) {
                $itemSql = "INSERT INTO `order_item` 
                            (order_id, product_id, product_name, price, quantity, subtotal) 
                            VALUES 
                            (:order_id, :product_id, :product_name, :price, :quantity, :subtotal)";
                $itemStmt = $this->connection->prepare($itemSql);

                foreach ($data['products'] as $product) {
                    $itemStmt->execute([
                        ':order_id'     => $orderId,
                        ':product_id'   => (int)($product['id'] ?? 0),
                        ':product_name' => $product['name'] ?? 'Товар',
                        ':price'        => (float)($product['price'] ?? 0),
                        ':quantity'     => (int)($product['quantity'] ?? 1),
                        ':subtotal'     => (float)($product['subtotal'] ?? 0),
                    ]);
                }
            }

            $this->connection->commit();
            return true;

        } catch (\PDOException $e) {
            $this->connection->rollBack();
            error_log("ProductDBStorage::saveData CRITICAL: " . $e->getMessage());
            return false;
        }
    }
}