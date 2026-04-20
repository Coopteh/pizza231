<?php
namespace Models;
use PDO;
use Models\Database;

class Order 
{
    private PDO $db;

    public function __construct() 
    {
        $this->db = Database::getConnection();
    }

    public function saveOrder(array $orderData): bool 
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO orders (order_id, user_id, user_email, fio, phone, delivery_type, email, address, total, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $orderData['order_id'],
                (int)($orderData['user_id'] ?? 0),
                $orderData['user_email'] ?? '',
                $orderData['fio'],
                $orderData['phone'],
                $orderData['delivery_type'] ?? 'email',
                $orderData['email'] ?? '',
                $orderData['address'] ?? '',
                (float)$orderData['total'],
                $orderData['status'] ?? 'new'
            ]);

            $itemStmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            foreach ($orderData['products'] as $item) {
                $product = $item['product'];
                $itemStmt->execute([
                    $orderData['order_id'],
                    (int)$product['id'],
                    $product['name'],
                    (float)$product['price'],
                    (int)$item['quantity'],
                    (float)($product['price'] * $item['quantity'])
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            error_log("Order Save Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllOrders(): array 
    {
        $stmt = $this->db->query("SELECT * FROM orders ORDER BY created_at DESC");
        $orders = $stmt->fetchAll();
        foreach ($orders as &$order) {
            $order['products'] = $this->getOrderItems($order['order_id']);
            $order['created_at'] = date('d.m.Y H:i:s', strtotime($order['created_at']));
        }
        return $orders;
    }

    public function getUserOrders(int $userId): array 
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userEmail = $_SESSION['user_email'] ?? '';

        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ? OR user_email = ? ORDER BY created_at DESC");
        $stmt->execute([$userId, $userEmail]);
        $orders = $stmt->fetchAll();
        
        foreach ($orders as &$order) {
            $order['products'] = $this->getOrderItems($order['order_id']);
            $order['created_at'] = date('d.m.Y H:i:s', strtotime($order['created_at']));
        }
        return $orders;
    }

    public function getOrderById(string $orderId): ?array 
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();
        if ($order) {
            $order['products'] = $this->getOrderItems($order['order_id']);
            $order['created_at'] = date('d.m.Y H:i:s', strtotime($order['created_at']));
        }
        return $order ?: null;
    }

    private function getOrderItems(string $orderId): array 
    {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll();

        return array_map(fn($item) => [
            'product' => [
                'id' => $item['product_id'],
                'name' => $item['product_name'],
                'price' => $item['price']
            ],
            'quantity' => (int)$item['quantity'],
            'subtotal' => $item['subtotal']
        ], $items);
    }
}