<?php
namespace Models;

use PDO;
use Database;

class Product
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    public function loadData(): array
    {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY id");
        $products = $stmt->fetchAll();
        
        // Декодируем features из JSON
        foreach ($products as &$product) {
            if (isset($product['features']) && is_string($product['features'])) {
                $product['features'] = json_decode($product['features'], true) ?? [];
            }
        }
        
        return $products;
    }
    
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch();
        
        if ($product && isset($product['features']) && is_string($product['features'])) {
            $product['features'] = json_decode($product['features'], true) ?? [];
        }
        
        return $product ?: null;
    }
    
    public function getBasketData(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $basket = $_SESSION['basket'] ?? [];
        $result = [];
        
        if (empty($basket)) return $result;
        
        $ids = array_keys($basket);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $products = $stmt->fetchAll();
        
        foreach ($products as $product) {
            if (isset($product['features']) && is_string($product['features'])) {
                $product['features'] = json_decode($product['features'], true) ?? [];
            }
            
            $quantity = $basket[$product['id']]['quantity'] ?? 1;
            $result[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => (float)$product['price'] * $quantity
            ];
        }
        
        return $result;
    }
        
    public function saveData(array $orderData): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO orders (order_id, user_id, user_email, fio, phone, delivery_type, email, address, products, total, status, created_at)
                VALUES (:order_id, :user_id, :user_email, :fio, :phone, :delivery_type, :email, :address, :products, :total, :status, :created_at)
            ");
            
            return $stmt->execute([
                ':order_id' => $orderData['order_id'],
                ':user_id' => $orderData['user_id'] ?? null,
                ':user_email' => $orderData['user_email'] ?? '',
                ':fio' => $orderData['fio'],
                ':phone' => $orderData['phone'],
                ':delivery_type' => $orderData['delivery_type'],
                ':email' => $orderData['email'] ?? '',
                ':address' => $orderData['address'] ?? '',
                ':products' => json_encode($orderData['products']),
                ':total' => $orderData['total'],
                ':status' => $orderData['status'] ?? 'new',
                ':created_at' => $orderData['created_at'] ?? date('Y-m-d H:i:s')
            ]);
        } catch (\PDOException $e) {
            error_log("Ошибка сохранения заказа: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateProduct(array $data): bool
    {
        $featuresJson = isset($data['features']) && is_array($data['features']) 
            ? json_encode($data['features']) 
            : null;
        
        $stmt = $this->db->prepare("
            UPDATE products SET 
                name = :name,
                description = :description,
                price = :price,
                period = :period,
                image = :image,
                coverage = :coverage,
                features = :features
            WHERE id = :id
        ");
        
        return $stmt->execute([
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':period' => $data['period'],
            ':image' => $data['image'],
            ':coverage' => $data['coverage'],
            ':features' => $featuresJson
        ]);
    }
    
    public function addProduct(array $data): bool
    {
        $featuresJson = isset($data['features']) && is_array($data['features']) 
            ? json_encode($data['features']) 
            : null;
        
        $stmt = $this->db->prepare("
            INSERT INTO products (name, description, price, period, image, coverage, features)
            VALUES (:name, :description, :price, :period, :image, :coverage, :features)
        ");
        
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':period' => $data['period'],
            ':image' => $data['image'],
            ':coverage' => $data['coverage'],
            ':features' => $featuresJson
        ]);
    }
    
    /**
     * 🔹 Подготовка данных заказа для сохранения
     * @param array $formData - данные из формы ($_POST)
     * @param array $basketData - товары из корзины
     * @return array - подготовленный массив для сохранения
     */
    public function prepareData(array $formData, array $basketData): array
    {
        $sanitized = [];
        $textFields = ['fio', 'phone', 'email', 'address', 'delivery_type'];
        foreach ($textFields as $field) {
            if (isset($formData[$field])) {
                $value = trim($formData[$field]);
                $sanitized[$field] = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
            }
        }
        
        $allSum = 0;
        $preparedProducts = [];
        
        foreach ($basketData as $item) {
            if (isset($item['product'], $item['quantity'])) {
                $product = $item['product'];
                $quantity = max(1, (int)$item['quantity']);
                $price = (float)$product['price'];
                $subtotal = $price * $quantity;
                
                $allSum += $subtotal;
                
                $preparedProducts[] = [
                    'id' => (int)$product['id'],
                    'name' => htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'),
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }
        }
        
        return [
            'order_id' => 'ORD_' . uniqid(),
            'user_id' => $_SESSION['user_id'] ?? 0,
            'user_email' => $_SESSION['user_email'] ?? '',
            'fio' => $sanitized['fio'] ?? '',
            'phone' => $sanitized['phone'] ?? '',
            'delivery_type' => $sanitized['delivery_type'] ?? 'email',
            'email' => $sanitized['email'] ?? '',
            'address' => $sanitized['address'] ?? '',
            'products' => $preparedProducts,
            'all_sum' => $allSum,
            'total' => $allSum,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'new'
        ];
    }
}