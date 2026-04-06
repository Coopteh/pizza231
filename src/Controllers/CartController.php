<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models/EmailSender.php';
require_once __DIR__ . '/../Views/CartTemplate.php';

use App\Models\Cart;
use App\Models\EmailSender;
use App\Views\CartTemplate;

class CartController
{
    private const ORDERS_FILE = __DIR__ . '/../../storage/orders.json';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Отображение страницы корзины
     */
    public function get(): string
    {
        return CartTemplate::render();
    }

    /**
     * API: Оформить заказ
     * @param array $data ['fio', 'email', 'phone', 'address', 'payment', 'items', 'total']
     */
    public function order(?array $data): string
    {
        if (!$data) {
            http_response_code(400);
            return json_encode(['success' => false, 'error' => 'Некорректные данные']);
        }

        // Валидация обязательных полей
        $required = ['fio', 'email', 'phone', 'address', 'payment'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                return json_encode(['success' => false, 'error' => 'Заполните все обязательные поля']);
            }
        }

        // Получаем товары из корзины
        $items = Cart::getItems();
        if (empty($items)) {
            http_response_code(400);
            return json_encode(['success' => false, 'error' => 'Корзина пуста']);
        }

        $total = Cart::getTotal();

// Создаём заказ
        $order = [
            'id' => uniqid('order_'),
            'created_at' => date('Y-m-d H:i:s'),
            'fio' => htmlspecialchars(trim($data['fio'])),
            'email' => htmlspecialchars(trim($data['email'])),
            'phone' => htmlspecialchars(trim($data['phone'])),
            'address' => htmlspecialchars(trim($data['address'])),
            'payment' => htmlspecialchars($data['payment']),
            'items' => $items,
            'total' => $total,
            'status' => 'new'
        ];

        // Добавляем user_id только если пользователь авторизован
        if (isset($_SESSION['user_id'])) {
            $order['user_id'] = (int)$_SESSION['user_id'];
        }

        // Читаем существующие заказы
        $orders = [];
        if (file_exists(self::ORDERS_FILE)) {
            $content = file_get_contents(self::ORDERS_FILE);
            $orders = json_decode($content, true) ?: [];
        }
        
        // Добавляем новый заказ
        $orders[] = $order;

// Сохраняем
        if (file_put_contents(self::ORDERS_FILE, json_encode($orders, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) {
            http_response_code(500);
            return json_encode(['success' => false, 'error' => 'Ошибка сохранения заказа']);
        }
        
        // Отправляем email-уведомление
        try {
            $emailSent = EmailSender::sendOrderNotification($order, $order['email']);
            if (!$emailSent) {
                // Логируем, но не прерываем выполнение
                error_log('Не удалось отправить email-уведомление для заказа ' . $order['id']);
            }
        } catch (\Exception $e) {
            error_log('Ошибка при отправке email: ' . $e->getMessage());
        }
        
        // Очищаем корзину
        Cart::clear();

        return json_encode([
            'success' => true,
            'orderId' => $order['id']
        ]);
    }

    /**
     * API: Добавить товар в корзину
     * @param array $data ['id', 'name', 'price', 'image', 'quantity']
     */
    public function add(?array $data): string
    {
        if (!$data || !isset($data['id'], $data['name'], $data['price'])) {
            http_response_code(400);
            return json_encode(['success' => false, 'error' => 'Некорректные данные']);
        }

        $result = Cart::add(
            (int)$data['id'],
            (string)$data['name'],
            (float)$data['price'],
            $data['image'] ?? '',
            (int)($data['quantity'] ?? 1)
        );

        return json_encode([
            'success' => $result,
            'count' => Cart::getCount(),
            'total' => Cart::getTotal()
        ]);
    }

    /**
     * API: Обновить количество товара
     * @param array $data ['id', 'quantity']
     */
    public function update(?array $data): string
    {
        if (!$data || !isset($data['id'], $data['quantity'])) {
            http_response_code(400);
            return json_encode(['success' => false, 'error' => 'Некорректные данные']);
        }

        $result = Cart::updateQuantity(
            (int)$data['id'],
            (int)$data['quantity']
        );

        return json_encode([
            'success' => $result,
            'count' => Cart::getCount(),
            'total' => Cart::getTotal()
        ]);
    }

    /**
     * API: Удалить товар из корзины
     * @param array $data ['id']
     */
    public function remove(?array $data): string
    {
        if (!$data || !isset($data['id'])) {
            http_response_code(400);
            return json_encode(['success' => false, 'error' => 'Некорректные данные']);
        }

        $result = Cart::remove((int)$data['id']);

        return json_encode([
            'success' => $result,
            'count' => Cart::getCount(),
            'total' => Cart::getTotal()
        ]);
    }

    /**
     * API: Очистить корзину
     */
    public function clear(): string
    {
        $result = Cart::clear();

        return json_encode([
            'success' => $result,
            'count' => 0,
            'total' => 0
        ]);
    }
    
    /**
     * API: Изменить статус заказа (закрыть/открыть)
     * @param array $data ['id', 'status']
     */
    public function updateStatus(?array $data): string
    {
        if (!$data || !isset($data['id'], $data['status'])) {
            http_response_code(400);
            return json_encode(['success' => false, 'error' => 'Некорректные данные']);
        }
        
        $orderId = $data['id'];
        $newStatus = $data['status'];
        
        // Читаем заказы
        $orders = [];
        if (file_exists(self::ORDERS_FILE)) {
            $content = file_get_contents(self::ORDERS_FILE);
            $orders = json_decode($content, true) ?: [];
        }
        
        // Ищем и обновляем заказ
        $found = false;
        foreach ($orders as &$order) {
            if ($order['id'] === $orderId) {
                $order['status'] = $newStatus;
                if ($newStatus === 'completed') {
                    $order['completed_at'] = date('Y-m-d H:i:s');
                }
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            http_response_code(404);
            return json_encode(['success' => false, 'error' => 'Заказ не найден']);
        }
        
        // Сохраняем
        if (file_put_contents(self::ORDERS_FILE, json_encode($orders, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) {
            http_response_code(500);
            return json_encode(['success' => false, 'error' => 'Ошибка сохранения']);
        }
        
        return json_encode(['success' => true]);
    }
}