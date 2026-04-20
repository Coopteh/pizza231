<?php
namespace Controllers;
use Models\Product;
use Models\Order;
use Models\Log;
use Views\OrderTemplate;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController
{
    public function get()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $model = new Product();
        $basketItems = $model->getBasketData();
        $total = 0;
        foreach ($basketItems as $item) { $total += $item['product']['price'] * $item['quantity']; }
        return OrderTemplate::render($basketItems, $total);
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $fio = trim($_POST['fio'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $deliveryType = $_POST['delivery_type'] ?? 'email';
        $email = ''; $address = '';

        if ($deliveryType === 'email') {
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash'] = '⚠️ Укажите корректный email'; $_SESSION['flash_type'] = 'error';
                header('Location: /order'); exit;
            }
        } else {
            $address = trim($_POST['address'] ?? '');
            if (empty($address)) {
                $_SESSION['flash'] = '⚠️ Укажите адрес доставки'; $_SESSION['flash_type'] = 'error';
                header('Location: /order'); exit;
            }
        }

        if (empty($fio) || empty($phone)) {
            $_SESSION['flash'] = '⚠️ Заполните все обязательные поля'; $_SESSION['flash_type'] = 'error';
            header('Location: /order'); exit;
        }

        $productModel = new Product();
        $products = $productModel->getBasketData();
        if (empty($products)) {
            $_SESSION['flash'] = '⚠️ Корзина пуста'; $_SESSION['flash_type'] = 'error';
            header('Location: /cart'); exit;
        }

        $allSum = 0;
        foreach ($products as $product) { $allSum += $product['product']['price'] * $product['quantity']; }

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
            'created_at' => date('d.m.Y H:i:s'),
            'status' => 'new'
        ];

        // 🔹 СОХРАНЕНИЕ В SQL
        $orderModel = new Order();
        if (!$orderModel->saveOrder($orderData)) {
            $_SESSION['flash'] = '❌ Ошибка сохранения заказа. Попробуйте позже.';
            $_SESSION['flash_type'] = 'error';
            header('Location: /order'); exit;
        }

        // 🔔 Логирование
        $logModel = new Log();
        $logModel->add('Создание заказа', $_SESSION['user_name'] ?? 'Гость', ['order_id' => $orderData['order_id'], 'total' => $allSum, 'items' => count($products)]);

        // 📧 Отправка письма
        if ($deliveryType === 'email' && !empty($email)) { $this->sendOrderEmail($email, $orderData); }

        // Очистка корзины и редирект
        $_SESSION['basket'] = [];
        $_SESSION['flash'] = '✅ Ваш заказ успешно создан!';
        $_SESSION['flash_type'] = 'success';
        header("Location: /order?success=1"); exit;
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
            $mail->isSMTP(); $mail->Host = $env['SMTP_HOST'] ?? 'smtp.yandex.ru';
            $mail->SMTPAuth = true; $mail->Username = $env['SMTP_USER'] ?? ''; $mail->Password = $env['SMTP_PASS'] ?? '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; $mail->Port = (int)($env['SMTP_PORT'] ?? 465);
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($env['SMTP_USER'], 'Gym Low Cortisol');
            $mail->addAddress($customerEmail);
            $mail->isHTML(true);
            $mail->Subject = '📋 Ваш заказ №'.$orderData['order_id'].' оформлен';
            $mail->Body = $this->generateOrderEmailHTML($orderData, $env);
            $mail->AltBody = $this->generateOrderEmailText($orderData);
            $mail->send();
            return true;
        } catch (Exception $e) { error_log("SMTP Error: {$e->getMessage()}"); return false; }
    }

    private function generateOrderEmailText(array $orderData): string
    {
        $text = "Заказ №{$orderData['order_id']} успешно оформлен!\n";
        $text .= "Здравствуйте, {$orderData['fio']}!\n";
        $text .= "Состав заказа:\n";
        $text .= str_repeat('-', 50) . "\n";
        foreach ($orderData['products'] as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            $subtotal = $product['price'] * $quantity;
            $text .= "{$product['name']} x {$quantity} шт. = ".number_format($subtotal, 0, '.', ' ')." ₽\n";
        }
        $text .= str_repeat('-', 50) . "\n";
        $text .= "Итого: ".number_format($orderData['total'], 0, '.', ' ')." ₽\n";
        $text .= "Дата заказа: {$orderData['created_at']}\n";
        $text .= "Телефон: {$orderData['phone']}\n";
        if ($orderData['delivery_type'] === 'email') {
            $text .= "Доставка: на электронную почту ({$orderData['email']})\n";
        } else {
            $text .= "Доставка: курьером по адресу: {$orderData['address']}\n";
        }
        $text .= "\nСпасибо за покупку в Gym Low Cortisol!\n";
        $text .= "По вопросам: +767676767\n";
        return $text;
    }

    private function generateOrderEmailHTML(array $orderData, array $env): string
    {
        $productsHtml = '';
        foreach ($orderData['products'] as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            $subtotal = $product['price'] * $quantity;
            $productName = htmlspecialchars($product['name'] ?? 'Товар');
            $productArticle = htmlspecialchars($product['id'] ?? '-');
            $productsHtml .= "
            <tr style='border-bottom: 1px solid #eee;'>
                <td style='padding: 12px 15px; vertical-align: top;'>
                    <strong style='color: #2c3e50; font-size: 14px;'>{$productName}</strong>
                    <br><small style='color: #7f8c8d; font-size: 12px;'>Артикул: {$productArticle}</small>
                </td>
                <td style='padding: 12px 15px; text-align: center; vertical-align: middle; font-size: 14px;'>{$quantity} шт.</td>
                <td style='padding: 12px 15px; text-align: right; vertical-align: middle; font-weight: 600; font-size: 14px; color: #2c3e50;'>
                    ".number_format($subtotal, 0, '.', ' ')." ₽
                </td>
            </tr>";
        }

        $deliveryInfo = $orderData['delivery_type'] === 'email'
            ? "<tr><td style='padding: 5px 0; font-size: 14px; color: #555;'><strong>📧 Тип доставки:</strong> Электронная почта</td></tr>
               <tr><td style='padding: 5px 0; font-size: 14px; color: #555;'><strong>📩 Email:</strong> <a href='mailto:{$orderData['email']}' style='color: #667eea; text-decoration: none;'>{$orderData['email']}</a></td></tr>"
            : "<tr><td style='padding: 5px 0; font-size: 14px; color: #555;'><strong>🚚 Тип доставки:</strong> Курьером</td></tr>
               <tr><td style='padding: 5px 0; font-size: 14px; color: #555;'><strong>📍 Адрес:</strong> {$orderData['address']}</td></tr>";

        $contactEmail = $env['SMTP_USER'] ?? 'support@gymlowcortisol.ru';
        $currentYear = date('Y');
        return "
        <!DOCTYPE html>
        <html lang='ru' xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
        <head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Заказ №{$orderData['order_id']}</title></head>
        <body style='margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,Arial,sans-serif;background:#f8f9fa;color:#333;line-height:1.5;'>
            <table width='100%' cellpadding='0' cellspacing='0' border='0' style='background:#f8f9fa;padding:20px 0;'>
            <tr><td align='center'>
                <table width='600' cellpadding='0' cellspacing='0' border='0' style='background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.08);margin:10px;overflow:hidden;'>
                    <tr><td style='background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:35px 30px;text-align:center;'>
                        <h1 style='margin:0;color:#fff;font-size:26px;font-weight:700;'>🏋️ Gym Low Cortisol</h1>
                    </td></tr>
                    <tr><td style='padding:30px;'>
                        <div style='text-align:center;padding:20px;background:#d4edda;border-radius:10px;margin-bottom:25px;'>
                            <h2 style='margin:0 0 8px;color:#155724;'>✅ Заказ успешно оформлен!</h2>
                            <p>Номер заказа: <strong>{$orderData['order_id']}</strong></p>
                        </div>
                        <p>Здравствуйте, <strong>{$orderData['fio']}</strong>!</p>
                        <p>Спасибо за ваш заказ. Мы начали его обработку.</p>
                    </td></tr>
                    <tr><td style='padding:0 30px 25px;'>
                        <table width='100%' style='background:#f8f9fa;border-radius:10px;padding:18px;'>
                            <tr><td>👤 <strong>Получатель:</strong> {$orderData['fio']}</td></tr>
                            <tr><td>📱 <strong>Телефон:</strong> {$orderData['phone']}</td></tr>
                            <tr><td>🕐 <strong>Дата:</strong> {$orderData['created_at']}</td></tr>
                            {$deliveryInfo}
                        </table>
                    </td></tr>
                    <tr><td style='padding:0 30px 20px;'>
                        <table width='100%' style='border-collapse:collapse;'>
                            <thead><tr style='background:#f8f9fa;'><th style='padding:12px;text-align:left;'>Товар</th><th style='padding:12px;text-align:center;'>Кол-во</th><th style='padding:12px;text-align:right;'>Сумма</th></tr></thead>
                            <tbody>{$productsHtml}</tbody>
                        </table>
                    </td></tr>
                    <tr><td style='padding:0 30px 30px;'>
                        <table width='100%' style='background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:10px;padding:20px;'>
                            <tr><td style='text-align:right;color:#fff;font-size:24px;font-weight:800;'>💰 Итого: ".number_format($orderData['total'], 0, '.', ' ')." ₽</td></tr>
                        </table>
                    </td></tr>
                    <tr><td style='background:#2c3e50;padding:25px;text-align:center;color:#fff;'>
                        <p>© {$currentYear} Gym Low Cortisol. Все права защищены.</p>
                    </td></tr>
                </table>
            </td></tr></table>
        </body></html>";
    }
}