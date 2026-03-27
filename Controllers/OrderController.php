<?php
namespace Controllers;

class OrderController
{
    private $ordersFile;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Путь к файлу заказов
        $this->ordersFile = __DIR__ . '/../data/orders.json';

        // Автоматическое создание папки и файла, если их нет
        if (!file_exists(dirname($this->ordersFile))) {
            mkdir(dirname($this->ordersFile), 0777, true);
        }
        if (!file_exists($this->ordersFile)) {
            file_put_contents($this->ordersFile, json_encode([]));
        }
    }

    // Страница оформления заказа
    public function checkout()
    {
        if (empty($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
            header('Location: /cart?error=empty_cart');
            exit;
        }
        return \Views\OrderTemplate::getTemplate();
    }

    // Обработка заказа (ЗАПИСЬ В ФАЙЛ)
    public function submit()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
            header('Location: /cart?error=empty_cart');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($name) || empty($phone) || empty($email)) {
            header('Location: /order/checkout?error=fill_all');
            exit;
        }

        // Считаем сумму
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
            $quantity = $item['quantity'] ?? 1;
            $totalPrice += $priceNum * $quantity;
        }

        // Формируем массив заказа
        $order = [
            'id' => 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'new',
            'customer' => [
                'name' => $name,
                'phone' => $phone,
                'email' => $email
            ],
            'items' => $_SESSION['cart'],
            'total' => $totalPrice
        ];

        // Читаем старые заказы, добавляем новый и записываем в JSON
        $orders = json_decode(file_get_contents($this->ordersFile), true) ?? [];
        array_unshift($orders, $order);
        file_put_contents($this->ordersFile, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Сохраняем номер заказа для страницы успеха
        $_SESSION['last_order_id'] = $order['id'];
        $_SESSION['last_order_total'] = $totalPrice;
        $_SESSION['cart'] = [];

        header('Location: /order/success');
        exit;
    }

    // Страница подтверждения
    public function success()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['last_order_id'])) {
            header('Location: /home');
            exit;
        }
        return \Views\OrderSuccessTemplate::getTemplate();
    }

    // Админка (просмотр заказов из файла)
    public function admin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_POST['admin_password'])) {
            if ($_POST['admin_password'] === 'admin123') {
                $_SESSION['admin_logged'] = true;
            } else {
                $_SESSION['admin_error'] = 'Неверный пароль';
            }
        }

        if (empty($_SESSION['admin_logged'])) {
            return \Views\AdminLoginTemplate::getTemplate();
        }

        // Читаем заказы из файла
        $orders = json_decode(file_get_contents($this->ordersFile), true) ?? [];
        return \Views\AdminOrdersTemplate::getTemplate($orders);
    }

    // Обновление статуса заказа
    public function updateStatus()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_logged'])) {
            http_response_code(403);
            echo json_encode(['success' => false]);
            exit;
        }

        $orderId = $_POST['order_id'] ?? '';
        $status = $_POST['status'] ?? '';

        $orders = json_decode(file_get_contents($this->ordersFile), true) ?? [];

        foreach ($orders as &$order) {
            if ($order['id'] === $orderId) {
                $order['status'] = $status;
                break;
            }
        }

        file_put_contents($this->ordersFile, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['success' => true]);
        exit;
    }
}