<?php

namespace App\Services;

use PDO;
use App\Config\Config;

class OrderDBStorage extends DBStorage implements ISaveStorage
{
    public function saveData(string $name, array $data): bool
    {
        $this->connection->beginTransaction();
        try {
            $sql = "INSERT INTO `orders`
                    (`order_id`, `fio`, `user_address`, `user_phone`, `user_comment`, `all_sum`, `created_at`, `status`) 
                    VALUES 
                    (:order_id, :fio, :address, :phone, :comment, :sum, :created_at, :status)";
            
            $sth = $this->connection->prepare($sql);
            $result = $sth->execute([
                'order_id' => $data['order_id'],
                'fio' => $data['fio'],
                'address' => $data['user_address'],
                'phone' => $data['user_phone'],
                'comment' => $data['user_comment'],
                'sum' => $data['all_sum'],
                'created_at' => $data['created_at'],
                'status' => $data['status']
            ]);
            
            if (!$result) throw new \Exception("Ошибка сохранения заказа.");
            
            $idOrder = $this->connection->lastInsertId();
            $this->saveItems($idOrder, $data['products']);
            
            $this->connection->commit();
            return true;
        } catch (\Exception $e) {
            $this->connection->rollBack();
            return false;
        }
    }
    
    public function saveItems(int $idOrder, array $products): bool 
    {
        foreach ($products as $product) {
            $id = $product['id'];
            $price = $product['price'];
            $quantity = $product['quantity'];
            $sum = $product['sum']; // Используем сумму из массива

            $sql = "INSERT INTO `order_item`
                    (`order_id`, `product_id`, `count_item`, `price_item`, `sum_item`) 
                    VALUES 
                    (:id_order, :id_product, :count, :price, :sum)";
            
            $sth = $this->connection->prepare($sql);
            $sth->execute([
                'id_order' => $idOrder,
                'id_product' => $id,
                'count' => $quantity,
                'price' => $price,
                'sum' => $sum
            ]);
        }
        return true;
    }
}