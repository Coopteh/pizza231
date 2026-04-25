<?php
namespace App\Models;

require_once __DIR__ . '/../Config/Config.php';
require_once __DIR__ . '/../Database/Database.php';

use App\Config\Config;
use App\Database\Database;
use PDO;

class Order
{
    private const FILE_ORDERS = __DIR__ . '/../../storage/orders.json';

    private ?PDO $db = null;

    public function __construct()
    {
        if (Config::isDatabaseAvailable()) {
            $this->db = Database::getConnection();
        }
    }

    /**
     * Создать заказ
     */
    public function create(array $orderData): ?int
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO orders (user_id, email, name, phone, address, total, status, items)
                    VALUES (:user_id, :email, :name, :phone, :address, :total, :status, :items)
                ");
                $stmt->execute([
                    ':user_id' => $orderData['user_id'] ?? null,
                    ':email' => $orderData['email'],
                    ':name' => $orderData['name'],
                    ':phone' => $orderData['phone'] ?? '',
                    ':address' => $orderData['address'] ?? '',
                    ':total' => $orderData['total'],
                    ':status' => $orderData['status'] ?? 'pending',
                    ':items' => json_encode($orderData['items'] ?? [], JSON_UNESCAPED_UNICODE),
                ]);

                return (int) $this->db->lastInsertId();
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // JSON fallback
        return $this->createJson($orderData);
    }

    /**
     * Создание заказа в JSON
     */
    private function createJson(array $orderData): ?int
    {
        $orders = $this->loadFromJson();

        // Генерация ID
        $maxId = 0;
        foreach ($orders as $order) {
            $orderId = isset($order['id']) ? (int)$order['id'] : 0;
            if ($orderId > $maxId) {
                $maxId = $orderId;
            }
        }

        $newOrder = [
            'id' => (int)$maxId + 1,
            'user_id' => $orderData['user_id'] ?? null,
            'email' => $orderData['email'],
            'name' => $orderData['name'],
            'phone' => $orderData['phone'] ?? '',
            'address' => $orderData['address'] ?? '',
            'total' => $orderData['total'],
            'status' => $orderData['status'] ?? 'pending',
            'items' => $orderData['items'] ?? [],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $orders[] = $newOrder;

        if ($this->saveToJson($orders)) {
            return $newOrder['id'];
        }

        return null;
    }

    /**
     * Получить все заказы
     */
    public function getAll(): array
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->query("SELECT * FROM orders ORDER BY created_at DESC");
                $orders = $stmt->fetchAll();

                foreach ($orders as &$order) {
                    if (isset($order['items'])) {
                        $order['items'] = json_decode($order['items'], true) ?? [];
                    }
                }

                return $orders;
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        return $this->loadFromJson();
    }

    /**
     * Получить заказ по ID
     */
    public function findById(int $id): ?array
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $order = $stmt->fetch();

                if ($order && isset($order['items'])) {
                    $order['items'] = json_decode($order['items'], true) ?? [];
                }

                return $order ?: null;
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        $orders = $this->loadFromJson();
        foreach ($orders as $order) {
            if ($order['id'] === $id) {
                return $order;
            }
        }

        return null;
    }

    /**
     * Обновить статус заказа
     */
    public function updateStatus(int $id, string $status): bool
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id");
                return $stmt->execute([':id' => $id, ':status' => $status]);
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // JSON fallback
        $orders = $this->loadFromJson();
        foreach ($orders as &$order) {
            if ($order['id'] === $id) {
                $order['status'] = $status;
                $order['updated_at'] = date('Y-m-d H:i:s');
                return $this->saveToJson($orders);
            }
        }

        return false;
    }

    /**
     * Загрузить заказы из JSON
     */
    private function loadFromJson(): array
    {
        if (!file_exists(self::FILE_ORDERS)) {
            return [];
        }

        $data = file_get_contents(self::FILE_ORDERS);
        $orders = json_decode($data, true);

        return is_array($orders) ? $orders : [];
    }

    /**
     * Сохранить заказы в JSON
     */
    private function saveToJson(array $orders): bool
    {
        $dir = dirname(self::FILE_ORDERS);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return file_put_contents(self::FILE_ORDERS, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }
}
