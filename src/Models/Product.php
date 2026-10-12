<?php

namespace App\Models;

use App\Services\ILoadStorage;
use App\Services\ISaveStorage;

class Product
{
    /** @var ILoadStorage|ISaveStorage */
    private object $dataStorage;
    private string $nameResource;
    
    public function __construct(ILoadStorage $service, string $name)
    {
        $this->dataStorage = $service;
        $this->nameResource = $name;
    }
    
    /**
     * Загружает данные из хранилища
     */
    public function loadData(): ?array
    {
        return $this->dataStorage->loadData($this->nameResource);
    }
    
    /**
     * Сохраняет данные (если хранилище поддерживает запись)
     */
    public function saveData(array $data): bool
    {
        if ($this->dataStorage instanceof ISaveStorage) {
            return $this->dataStorage->saveData($this->nameResource, $data);
        }
        return false;
    }
    
    /**
     * Возвращает товары из корзины с полными данными
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
            $id = $product['id'] ?? null;
            if ($id === null) continue;
            
            if (array_key_exists($id, $_SESSION['basket'])) {
                $quantity = $_SESSION['basket'][$id]['quantity'] ?? 1;
                $name = $product['name'] ?? 'Товар';
                $price = $product['price'] ?? 0;
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
     * Подготавливает данные заказа для сохранения
     */
    public function prepareData(array $formData, array $basketData): array
    {
        $products = [];
        $allSum = 0;
        
        foreach ($basketData as $item) {
            $price = $item['price'] ?? 0;
            $qty = $item['quantity'] ?? 1;
            $subtotal = $price * $qty;
            
            $products[] = [
                'id' => $item['id'] ?? 0,
                'name' => $item['name'] ?? 'Товар',
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $subtotal
            ];
            $allSum += $subtotal;
        }

        return [
            'order_id' => uniqid('ord_', true),
            'fio' => isset($formData['fio']) ? trim(urldecode($formData['fio'])) : '',
            'user_phone' => isset($formData['phone']) ? trim(urldecode($formData['phone'])) : '',
            'user_address' => isset($formData['address']) ? trim(urldecode($formData['address'])) : '',
            'user_comment' => isset($formData['comment']) ? trim(urldecode($formData['comment'])) : '',
            'products' => $products,
            'all_sum' => $allSum,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'new',
        ];
    }
}