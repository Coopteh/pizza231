<?php
namespace App\Models;
use App\Config\Config;
use App\Services\IStorage;

class Product {
    private IStorage $dataStorage;
    private string $nameResource;
    
    // Внедрение зависимости через конструктор (Dependency Injection)
    public function __construct(IStorage $service, string $name)
    {
        $this->dataStorage = $service;
        $this->nameResource = $name;
    }
 
    /**
     * Загружает товары из хранилища.
     */
    public function loadData(): ?array
    {
        return $this->dataStorage->loadData($this->nameResource);
    }
    
    /**
     * Возвращает товары из корзины с полными данными.
     */
    public function getBasketData(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['basket'])) {
            $_SESSION['basket'] = [];
        }
        
        $products = $this->loadData();
        if ($products === null) {
            return [];
        }
        
        $basketProducts = [];

        foreach ($products as $product) {
            $id = $product['id'];

            if (array_key_exists($id, $_SESSION['basket'])) {
                $quantity = $_SESSION['basket'][$id]['quantity'];
                $name = $product['name'];
                $price = $product['price'];
                $sum = $price * $quantity;

                $basketProducts[] = [
                    'id' => $id,
                    'name' => $name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'sum' => $sum,
                ];
            }
        }
        
        return $basketProducts;
    }
    
    /**
     * Подготавливает данные заказа для сохранения.
     */
    public function prepareData(array $formData, array $basketData): array
    {
        // Обработка данных формы для единообразия с OrderController
        // Это "то, чего не хватало" для идеальной работы.
        $safeFormData = [
            'fio' => isset($formData['fio']) ? trim(urldecode($formData['fio'])) : '',
            'phone' => isset($formData['phone']) ? trim($formData['phone']) : '',
            'address' => isset($formData['address']) ? trim(urldecode($formData['address'])) : '',
            'comment' => isset($formData['comment']) ? trim(urldecode($formData['comment'])) : '',
        ];

        $allSum = 0;
        $items = [];

        foreach ($basketData as $item) {
            $price = (float)($item['price'] ?? 0);
            $quantity = (int)($item['quantity'] ?? 0);
            
            $itemSum = $price * $quantity;
            $allSum += $itemSum;

            $items[] = [
                'id' => $item['id'] ?? null,
                'name' => $item['name'] ?? '',
                'price' => $price,
                'quantity' => $quantity,
                'sum' => $itemSum,
            ];
        }

        return [
            'order_id' => uniqid('ord_', true),
             'fio' => $safeFormData['fio'],
             'user_phone' => $safeFormData['phone'],
             'user_address' => $safeFormData['address'],
             'user_comment' => $safeFormData['comment'],
             'products' => $items,
             'all_sum' => round($allSum, 2),
             'created_at' => date('Y-m-d H:i:s'),
             'status' => 'new',
        ];
    }
    
    /**
     * Сохраняет данные заказа в хранилище.
     */
    public function saveData(array $arr): bool // Возвращаем bool для соответствия интерфейсу IStorage
    {
         return $this->dataStorage->saveData($this->nameResource, $arr);
    }
}