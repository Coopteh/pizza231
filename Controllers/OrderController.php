<?php
namespace Controllers;

use Models\Product;
use Models\Log;
use Views\OrderTemplate;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Services\ValidateOrderData; // Подключаем сервис валидации заказа

class OrderController
{
    public function get()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $model = new Product();
        $basketItems = $model->getBasketData();
        $total = 0;
        foreach ($basketItems as $item) {
            $total += $item['product']['price'] * $item['quantity'];
        }
        return OrderTemplate::render($basketItems, $total);
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Инициализируем сервис валидации
        $validator = new ValidateOrderData();
        
        // 2. Запускаем валидацию
        $result = $validator->validate($_POST);

        // 3. Если есть ошибки — прерываем выполнение
        if (!$result['valid']) {
            $_SESSION['flash'] = implode('; ', $result['errors']);
            $_SESSION['flash_type'] = 'error';
            header('Location: /order');
            exit;
        }

        // 4. Используем очищенные данные из сервиса
        $fio = $result['sanitized']['fio'];
        $phone = $result['sanitized']['phone'];
        $deliveryType = $_POST['delivery_type'] ?? 'email';
        $email = $result['sanitized']['email'];
        $address = $result['sanitized']['address'];

        $model = new Product();
        $products = $model->getBasketData();

        if (empty($products)) {
            $_SESSION['flash'] = '⚠️ Корзина пуста';
            $_SESSION['flash_type'] = 'error';
            header('Location: /cart');
            exit;
        }

        $allSum = 0;
        foreach ($products as $product) {
            $allSum += $product['product']['price'] * $product['quantity'];
        }

        $orderData = [
            'order_id' => 'ORD_' . uniqid(),
            'user_id' => $_SESSION['user_id'] ?? 0,
            'user_email' => $_SESSION['user_email'] ?? '',
            'fio' => $fio, // Уже санитизировано в сервисе
            'phone' => $phone, // Уже санитизировано
            'delivery_type' => $deliveryType,
            'email' => $email,
            'address' => $address,
            'products' => $products,
            'total' => $allSum,
            'status' => 'new',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $model->saveData($orderData);

        // 🔔 Логирование создания заказа
        $logModel = new Log();
        $logModel->add('Создание заказа', $_SESSION['user_name'] ?? 'Гость', [
            'order_id' => $orderData['order_id'],
            'total' => $allSum,
            'items' => count($products)
        ]);

        if ($deliveryType === 'email' && !empty($email)) {
            $this->sendOrderEmail($email, $orderData);
        }

        $_SESSION['basket'] = [];
        $_SESSION['flash'] = '✅ Ваш заказ успешно создан!';
        $_SESSION['flash_type'] = 'success';
        header("Location: /order?success=1");
        exit;
    }

    private function sendOrderEmail(string $customerEmail, array $orderData): bool
    {
        try {
            $envFile = 'C:/xampp/htdocs/.env';
            $env = [];
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    if (strpos($line, '=') === false) continue;
                    [$key, $value] = explode('=', $line, 2);
                    $env[trim($key)] = trim(trim($value), '"\'');
                }
            }

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $env['SMTP_HOST'] ?? 'smtp.yandex.ru';
            $mail->SMTPAuth = true;
            $mail->Username = $env['SMTP_USER'] ?? '';
            $mail->Password = $env['SMTP_PASS'] ?? '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = (int)($env['SMTP_PORT'] ?? 465);
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($env['SMTP_USER'], 'Чёрный Вантуз');
            $mail->addAddress($customerEmail);
            $mail->isHTML(true);
            $mail->Subject = '📋 Ваш заказ №'.$orderData['order_id'].' оформлен';
            $mail->Body = "<h2>Заказ оформлен!</h2><p>Сумма: {$orderData['total']} ₽</p>";
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("SMTP Error: {$e->getMessage()}");
            return false;
        }
    }
}