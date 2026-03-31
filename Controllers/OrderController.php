<?php
namespace Controllers;

// Подключаем конфигурацию
require_once __DIR__ . '/../config/env.php';

// Подключаем PHPMailer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) 
    require_once __DIR__ . '/../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;


class OrderController
{
    // ... остальной код
    private $ordersFile;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->ordersFile = __DIR__ . '/../data/orders.json';

        if (!file_exists(dirname($this->ordersFile))) {
            mkdir(dirname($this->ordersFile), 0777, true);
        }
        if (!file_exists($this->ordersFile)) {
            file_put_contents($this->ordersFile, json_encode([]));
        }
    }

    public function checkout()
    {
        if (empty($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
            header('Location: /cart?error=empty_cart');
            exit;
        }
        return \Views\OrderTemplate::getTemplate();
    }

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

        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $item) {
            $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
            $quantity = $item['quantity'] ?? 1;
            $totalPrice += $priceNum * $quantity;
        }

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

        // Сохраняем заказ в файл
        $orders = json_decode(file_get_contents($this->ordersFile), true) ?? [];
        array_unshift($orders, $order);
        file_put_contents($this->ordersFile, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Отправляем email
        $this->sendOrderEmail($order);

        $_SESSION['last_order_id'] = $order['id'];
        $_SESSION['last_order_total'] = $totalPrice;
        $_SESSION['cart'] = [];

        header('Location: /order/success');
        exit;
    }

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

        $orders = json_decode(file_get_contents($this->ordersFile), true) ?? [];
        return \Views\AdminOrdersTemplate::getTemplate($orders);
    }

    public function updateStatus()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_logged'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'unauthorized']);
            exit;
        }

        $orderId = $_POST['order_id'] ?? '';
        $status = $_POST['status'] ?? '';

        $validStatuses = ['new', 'processing', 'paid', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'invalid_status']);
            exit;
        }

        $orders = json_decode(file_get_contents($this->ordersFile), true) ?? [];

        foreach ($orders as &$order) {
            if ($order['id'] === $orderId) {
                $order['status'] = $status;
                $order['updated_at'] = date('Y-m-d H:i:s');
                break;
            }
        }

        file_put_contents($this->ordersFile, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['success' => true]);
        exit;
    }

    // ✅ Отправка email через переменные окружения
    private function sendOrderEmail($order): void
{
    // Проверяем, установлен ли PHPMailer
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        error_log("PHPMailer not installed. Order saved to file only.");
        return;
    }

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    
    try {
        // === НАСТРОЙКИ SMTP ===
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Исправлено с .ru на .com
        $mail->SMTPAuth = true;
        $mail->Username = 'aekbokhan214323@gmail.com';  // ⚠️ Ваш email
        $mail->Password = 'tbabwwckhvqybjsm';            // ⚠️ Пароль приложения (16 символов)
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;  // Для STARTTLS
        $mail->CharSet = 'UTF-8';
        
        // === АДРЕСА ===
        $mail->setFrom('aekbokhan214323@gmail.com', 'Продукты24');
        $mail->addAddress('aekbokhan214323@gmail.com');  // Куда получать заказы
        $mail->addReplyTo($order['customer']['email'], $order['customer']['name']);
        
        // === ПИСЬМО ===
        $mail->Subject = '🛒 Новый заказ #' . $order['id'];
        $mail->isHTML(true);
        $mail->Body = $this->generateOrderEmail($order);
        $mail->AltBody = 'Заказ #' . $order['id'] . ' на сумму ' . $order['total'] . ' ₽';
        
        $mail->send();
        
    } catch (\PHPMailer\PHPMailer\Exception $e) {  // ⚠️ Полный путь к Exception
        error_log("Email error: " . $mail->ErrorInfo);
        // Не прерываем заказ при ошибке email
    }
}

    private function generateOrderEmail($order): string
    {
        $itemsHtml = '';
        foreach ($order['items'] as $item) {
            $quantity = $item['quantity'] ?? 1;
            $itemsHtml .= "<tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>{$item['title']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>× {$quantity}</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>{$item['price']}</td>
            </tr>";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background: linear-gradient(135deg, #ff6b35, #f7931e); color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                table { width: 100%; border-collapse: collapse; background: white; }
                .total { font-size: 18px; font-weight: bold; color: #ff6b35; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin:0;'>🛒 Продукты24</h1>
                    <p>Новый заказ оформлен!</p>
                </div>
                <div class='content'>
                    <h2>Заказ #{$order['id']}</h2>
                    <p><strong>Клиент:</strong> {$order['customer']['name']}</p>
                    <p><strong>Телефон:</strong> {$order['customer']['phone']}</p>
                    <p><strong>Email:</strong> {$order['customer']['email']}</p>
                    <h3>Товары:</h3>
                    <table>{$itemsHtml}
                        <tr>
                            <td colspan='2' style='padding: 10px; text-align: right;'><strong>Итого:</strong></td>
                            <td class='total' style='padding: 10px; text-align: right;'>{$order['total']} ₽</td>
                        </tr>
                    </table>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}