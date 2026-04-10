<?php
namespace Models;

class Order
{
    private string $orderFile;
    
    public function __construct()
    {
        $this->orderFile = 'C:/xampp/htdocs/storage/order.json';
        $dir = dirname($this->orderFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($this->orderFile)) {
            file_put_contents($this->orderFile, json_encode([]));
        }
    }
    
    public function getAllOrders(): array
    {
        $data = file_get_contents($this->orderFile);
        return json_decode($data, true) ?? [];
    }
    
    public function getUserOrders(int $userId): array
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userEmail = $_SESSION['user_email'] ?? '';
        
        if (empty($userEmail)) return [];
        
        $allOrders = $this->getAllOrders();
        $userOrders = [];
        
        foreach ($allOrders as $order) {
            if (($order['user_id'] ?? 0) === $userId || 
                ($order['email'] ?? '') === $userEmail) {
                $userOrders[] = $order;
            }
        }
        
        return array_reverse($userOrders);
    }
    
    public function saveOrder(array $orderData): bool
    {
        $orders = $this->getAllOrders();
        $orders[] = $orderData;
        file_put_contents($this->orderFile, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return true;
    }
    
    public function getOrderById(string $orderId): ?array
    {
        $orders = $this->getAllOrders();
        foreach ($orders as $order) {
            if (($order['order_id'] ?? '') === $orderId) {
                return $order;
            }
        }
        return null;
    }
}