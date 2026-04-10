<?php
namespace Controllers;
use Models\Product;
use Models\Log;
use Views\OrderTemplate;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        $fio = trim($_POST['fio'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $deliveryType = $_POST['delivery_type'] ?? 'email';
        $email = '';
        $address = '';
        if ($deliveryType === 'email') {
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash'] = '⚠️ Укажите корректный email';
                $_SESSION['flash_type'] = 'error';
                header('Location: /order');
                exit;
            }
        } else {
            $address = trim($_POST['address'] ?? '');
            if (empty($address)) {
                $_SESSION['flash'] = '⚠️ Укажите адрес доставки';
                $_SESSION['flash_type'] = 'error';
                header('Location: /order');
                exit;
            }
        }
        if (empty($fio) || empty($phone)) {
            $_SESSION['flash'] = '⚠️ Заполните все обязательные поля';
            $_SESSION['flash_type'] = 'error';
            header('Location: /order');
            exit;
        }
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
            'fio' => htmlspecialchars($fio),
            'phone' => htmlspecialchars($phone),
            'delivery_type' => $deliveryType,
            'email' => $email,
            'address' => $address,
            'products' => $products,
            'total' => $allSum,
            'created_at' => date('d.m.Y H:i:s')
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
            $mail->setFrom($env['SMTP_USER'], 'gym low cortisol');
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