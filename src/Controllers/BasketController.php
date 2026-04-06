<?php
namespace App\Controllers;

class BasketController
{
    public function add(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['id'])) {
            $product_id = $_POST['id'];
            // Инициализируем корзину, если она пустая
            if (!isset($_SESSION['basket'])) {
                $_SESSION['basket'] = [];
            }
            // Если товар уже есть, увеличиваем количество
            if (isset($_SESSION['basket'][$product_id])) {
                $_SESSION['basket'][$product_id]['quantity']++;
            } else {
                // Иначе добавляем новый товар с количеством 1
                $_SESSION['basket'][$product_id] = [
                    'quantity' => 1
                ];
            }
            // Сообщение об успехе
            $_SESSION['flash'] = "Товар успешно добавлен в корзину!";
        }
    }

    /* 
     * Очистка корзины
     */
    public function clear(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['basket'] = [];
        
        // НОВАЯ СТРОКА: флеш-сообщение об очистке
        $_SESSION['flash'] = "Корзина успешно очищена.";
        
        // Перенаправление обратно
        $prevUrl = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: {$prevUrl}");
        exit();
    }
}