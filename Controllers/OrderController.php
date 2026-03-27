<?php
namespace Controllers;

use Models\Product;
use Views\OrderTemplate;

class OrderController
{
    /**
     * Отображение формы оформления заказа (только GET)
     */
    public function get()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Получаем товары из корзины
        $model = new Product();
        $basketItems = $model->getBasketData();
        
        // Считаем общую сумму
        $total = 0;
        foreach ($basketItems as $item) {
            $total += $item['product']['price'] * $item['quantity'];
        }

        // Возвращаем отрендеренный шаблон
        return OrderTemplate::render($basketItems, $total);
    }

    /**
     * Обработка создания заказа (только POST)
     * ✅ Метод public для вызова из index.php
     */
    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Валидация обязательных полей
        $fio = trim($_POST['fio'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $deliveryType = $_POST['delivery_type'] ?? 'email';

        // Определяем контакт в зависимости от способа доставки
        $email = '';
        $address = '';
        if ($deliveryType === 'email') {
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        } else {
            $address = trim($_POST['address'] ?? '');
        }

        // Простая валидация
        if (empty($fio) || empty($phone) || empty($deliveryType === 'email' ? $email : $address)) {
            $_SESSION['flash'] = '⚠️ Заполните все обязательные поля';
            $_SESSION['flash_type'] = 'error';
            header('Location: /order');
            exit;
        }

        // Собираем данные заказа
        $orderData = [
            'fio' => htmlspecialchars($fio),
            'phone' => htmlspecialchars($phone),
            'delivery_type' => $deliveryType,
            'email' => $email,
            'address' => $address,
            'created_at' => date('d-m-Y H:i:s')
        ];

        // Получаем товары из корзины
        $model = new Product();
        $products = $model->getBasketData();
        $orderData['products'] = $products;

        // Считаем общую сумму
        $allSum = 0;
        foreach ($products as $product) {
            $allSum += $product['product']['price'] * $product['quantity'];
        }
        $orderData['all_sum'] = $allSum;

        // Сохраняем заказ
        $model->saveData($orderData);

        // Очищаем корзину
        $_SESSION['basket'] = [];

        // Flash-сообщение
        $_SESSION['flash'] = '✅ Ваш заказ успешно создан и передан службе доставки';
        $_SESSION['flash_type'] = 'success';

        // 🔹 Редирект ОБРАТНО на /order?success=1
        // Это важно: именно здесь сработает показ уведомлений и очистка формы
        $basePath = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $basePath = $basePath === '' ? '' : $basePath;
        header("Location: {$basePath}/order?success=1");
        exit;
    }
}