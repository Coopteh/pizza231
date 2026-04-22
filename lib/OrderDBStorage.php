<?php
// Lib/OrderDBStorage.php
namespace Lib;

use PDO;

class OrderDBStorage extends DBStorage implements ISaveStorage
{
    public function saveData(string $resource, array $data): bool
    {
        try {
            $this->connection->beginTransaction();

            $sqlOrder = "INSERT INTO `orders` (`fio`, `address`, `phone`, `email`, `all_sum`, `created`) 
                         VALUES (:fio, :address, :phone, :email, :sum, NOW())";
            $sth = $this->connection->prepare($sqlOrder);
            $sth->execute([
                'fio'     => $data['name'] ?? $data['fio'] ?? 'Гость',
                'address' => $data['address'] ?? '',
                'phone'   => $data['phone'] ?? '',
                'email'   => $data['email'] ?? '',
                'sum'     => (float)($data['total'] ?? 0)
            ]);

            $orderId = $this->connection->lastInsertId();
            $this->saveItems((int)$orderId, $data['items'] ?? $data['products'] ?? []);

            $this->connection->commit();
            return true;
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            Logger::error("Ошибка сохранения заказа в БД", ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function saveItems(int $orderId, array $items): void
    {
        $sql = "INSERT INTO `order_item` (`order_id`, `product_id`, `count_item`, `price_item`, `sum_item`) 
                VALUES (:order_id, :product_id, :count, :price, :sum)";
        $sth = $this->connection->prepare($sql);

        foreach ($items as $item) {
            $priceStr = is_array($item['price']) ? '' : (string)$item['price'];
            $price = (float)preg_replace('/[^0-9.]/', '', $priceStr);
            $qty = 1;
            $sum = $price * $qty;

            $sth->execute([
                'order_id'   => $orderId,
                'product_id' => (int)$item['id'],
                'count'      => $qty,
                'price'      => $price,
                'sum'        => $sum
            ]);
        }
    }
}