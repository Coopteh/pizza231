<?php
namespace App\Models;

class Cart
{
    private const STORAGE_KEY = 'cart';

    /**
     * Получить корзину из localStorage (через JS) или сессии (через PHP)
     */
    public static function getItems(): array
    {
        // Не пытаемся запустить сессию если она уже активна
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        return $_SESSION[self::STORAGE_KEY] ?? [];
    }

    /**
     * Добавить товар в корзину
     */
    public static function add(int $id, string $name, float $price, string $image = '', int $quantity = 1): bool
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        $cart = $_SESSION[self::STORAGE_KEY] ?? [];
        
        // Проверяем, есть ли товар
        foreach ($cart as &$item) {
            if ($item['id'] === $id) {
                $item['quantity'] += $quantity;
                return self::save($cart);
            }
        }
        
        // Добавляем новый товар
        $cart[] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'image' => $image ?: '/assets/img/no-image.jpg',
            'quantity' => $quantity,
            'added_at' => time()
        ];
        
        return self::save($cart);
    }

    /**
     * Обновить количество товара
     */
    public static function updateQuantity(int $id, int $quantity): bool
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        $cart = $_SESSION[self::STORAGE_KEY] ?? [];
        
        foreach ($cart as &$item) {
            if ($item['id'] === $id) {
                if ($quantity <= 0) {
                    return self::remove($id);
                }
                $item['quantity'] = $quantity;
                return self::save($cart);
            }
        }
        return false;
    }

    /**
     * Удалить товар из корзины
     */
    public static function remove(int $id): bool
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        $cart = $_SESSION[self::STORAGE_KEY] ?? [];
        $cart = array_filter($cart, fn($item) => $item['id'] !== $id);
        
        return self::save(array_values($cart));
    }

    /**
     * Очистить корзину
     */
    public static function clear(): bool
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        unset($_SESSION[self::STORAGE_KEY]);
        return true;
    }

    /**
     * Получить общую сумму
     */
    public static function getTotal(): float
    {
        $cart = self::getItems();
        return array_reduce($cart, fn($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0);
    }

    /**
     * Получить количество товаров
     */
    public static function getCount(): int
    {
        $cart = self::getItems();
        return array_reduce($cart, fn($count, $item) => $count + $item['quantity'], 0);
    }

    /**
     * Сохранить корзину в сессию
     */
    private static function save(array $cart): bool
    {
        $_SESSION[self::STORAGE_KEY] = $cart;
        return true;
    }
}