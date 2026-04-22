<?php
namespace Controllers;
use Views\OrderTemplate;
use Lib\User;
use Lib\DataLoader;
use Lib\Logger;
use Lib\Language;
use Lib\OrderValidator;

class OrderController
{
    private array $orders = [];
    private const ADMIN_EMAIL = 'toni.maslennikov.08@inbox.ru';
    private const FROM_EMAIL = 'noreply@kemt.local';
    private const FROM_NAME = 'Geek LegendS';
    private const REPLY_TO = 'info@kemt.ru';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $ordersFile = __DIR__ . '/../data/orders.json';
        if (file_exists($ordersFile)) {
            $this->orders = json_decode(file_get_contents($ordersFile), true) ?? [];
        }
    }

    public function checkout(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $cartItems = $_SESSION['cart'] ?? [];
        if (empty($cartItems)) {
            header('Location: /cart?error=empty_cart');
            exit;
        }
        return OrderTemplate::getCheckoutTemplate($cartItems);
    }

    public function process(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $cartItems = $_SESSION['cart'] ?? [];
        if (empty($cartItems)) {
            header('Location: /cart?error=empty_cart');
            exit;
        }

        // Получаем данные только из существующих полей формы
        $inputData = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'payment_method' => $_POST['payment_method'] ?? 'card',
            'comment' => $_POST['comment'] ?? ''
        ];

        // 🟢 ВЫЗОВ ВАЛИДАТОРА
        $validator = new OrderValidator();
        $result = $validator->validate($inputData);

        if (!empty($result['errors'])) {
            \Lib\Logger::warning("Ошибка валидации заказа", ['errors' => $result['errors'], 'input' => $inputData]);
            $_SESSION['order_errors'] = $result['errors'];
            $_SESSION['order_data'] = $inputData;
            header('Location: /cart/checkout');
            exit;
        }

        // Используем очищенные данные
        $sanitizedData = $result['data'];
        
        $orderId = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
            $totalPrice += $priceNum;
        }

        $order = [
            'id' => $orderId,
            'name' => $sanitizedData['name'],
            'email' => $sanitizedData['email'],
            'phone' => $sanitizedData['phone'],
            'payment_method' => $inputData['payment_method'],
            'comment' => trim(strip_tags($inputData['comment'])),
            'items' => $cartItems,
            'total' => $totalPrice,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_id' => null,
            'user_email' => null
        ];

        if (User::isLoggedIn()) {
            $currentUser = User::getCurrentUser();
            $order['user_id'] = $currentUser['id'];
            $order['user_email'] = $currentUser['email'];
            if ($currentUser['email'] !== $sanitizedData['email']) {
                User::updateUser($currentUser['id'], ['email' => $sanitizedData['email']]);
            }
        }

        $this->orders[] = $order;
        $ordersFile = __DIR__ . '/../data/orders.json';
        if (!is_dir(dirname($ordersFile))) {
            mkdir(dirname($ordersFile), 0755, true);
        }
        try {
            file_put_contents($ordersFile, json_encode($this->orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            Logger::info("Заказ успешно создан", [
                'order_id' => $orderId,
                'total' => $totalPrice,
                'user_id' => $order['user_id'] ?? 'guest',
                'email' => $sanitizedData['email']
            ]);
        } catch (\Exception $e) {
            Logger::error("Ошибка сохранения заказа", [
                'order_id' => $orderId,
                'exception' => $e->getMessage()
            ]);
            throw $e;
        }

        $_SESSION['cart'] = [];
        unset($_SESSION['order_errors']);
        unset($_SESSION['order_data']);

        $emailSent = $this->sendOrderEmail($order);
        $this->sendAdminNotification($order);

        if (!$emailSent) {
            Logger::warning("Не удалось отправить email покупателю", [
                'order_id' => $orderId,
                'email' => $sanitizedData['email']
            ]);
        }

        header('Location: /cart/success?order=' . $orderId);
        exit;
    }

    public function success(): string
    {
        $orderId = $_GET['order'] ?? '';
        return OrderTemplate::getSuccessTemplate($orderId);
    }

    public function getOrders(): array
    {
        return $this->orders;
    }

    private function sendOrderEmail(array $order): bool
    {
        $to = $order['email'];
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Logger::error("Невалидный email для отправки", ['email' => $to]);
            return false;
        }
        $subject = 'Заказ №' . $order['id'] . ' подтверждён - ' . self::FROM_NAME;
        $message = $this->buildCustomerEmailBody($order);
        $headers = $this->getEmailHeaders();
        return @mail($to, $subject, $message, $headers);
    }

    private function sendAdminNotification(array $order): bool
    {
        $to = self::ADMIN_EMAIL;
        $subject = '🔔 НОВЫЙ ЗАКАЗ №' . $order['id'] . ' на сумму ' . number_format($order['total'], 0, '.', ' ') . ' ₽';
        $message = $this->buildAdminEmailBody($order);
        $headers = $this->getEmailHeaders();
        return @mail($to, $subject, $message, $headers);
    }

    private function getEmailHeaders(): string
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . self::FROM_NAME . " <" . self::FROM_EMAIL . ">\r\n";
        $headers .= "Reply-To: " . self::REPLY_TO . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "X-Priority: 3\r\n";
        return $headers;
    }

    private function buildCustomerEmailBody(array $order): string
    {
        $lang = Language::getCurrentLang();
        $getText = function ($field, $default = '') use ($lang) {
            if (is_array($field)) {
                return $field[$lang] ?? $field['ru'] ?? $default;
            }
            return $field;
        };
        $itemsHtml = '';
        foreach ($order['items'] as $item) {
            $courseTitle = $getText($item['title'], $item['title']);
            $courseDuration = $getText($item['duration'], $item['duration']);
            $itemsHtml .= '
            <tr>
                <td style="padding: 12px 8px; border-bottom: 1px solid #e2e8f0;">
                    <strong>' . htmlspecialchars($courseTitle) . '</strong>
                </td>
                <td style="padding: 12px 8px; border-bottom: 1px solid #e2e8f0; color: #718096;">
                    ' . htmlspecialchars($courseDuration) . '
                </td>
                <td style="padding: 12px 8px; border-bottom: 1px solid #e2e8f0; text-align: right;">
                    <strong>' . htmlspecialchars($item['price']) . '</strong>
                </td>
            </tr>';
        }
        return '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f7fafc">
        <table role="presentation" style="width:100%;border-collapse:collapse"><tr><td align="center" style="padding:40px 20px">
        <table role="presentation" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1)">
        <tr><td style="background:linear-gradient(135deg,#2c5282 0%,#1a365d 100%);padding:40px 30px;text-align:center">
        <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:700">✓ Заказ подтверждён</h1>
        <p style="margin:10px 0 0 0;color:rgba(255,255,255,0.9);font-size:16px">Спасибо за покупку, ' . htmlspecialchars($order['name']) . '!</p>
        </td></tr><tr><td style="padding:0 30px 30px 30px"><h3 style="margin:0 0 15px 0;color:#2d3748;font-size:18px;font-weight:600">📚 Ваши курсы:</h3>
        <table role="presentation" style="width:100%;border-collapse:collapse"><thead><tr style="background:#f7fafc">
        <th style="padding:12px 8px;text-align:left;color:#2c5282;font-size:14px;border-bottom:2px solid #2c5282">Курс</th>
        <th style="padding:12px 8px;text-align:left;color:#2c5282;font-size:14px;border-bottom:2px solid #2c5282">Длительность</th>
        <th style="padding:12px 8px;text-align:right;color:#2c5282;font-size:14px;border-bottom:2px solid #2c5282">Цена</th>
        </tr></thead><tbody>' . $itemsHtml . '</tbody></table>
        <table role="presentation" style="width:100%;margin-top:20px"><tr><td style="text-align:right;padding:15px;background:#2c5282;border-radius:8px;color:#ffffff">
        <span style="font-size:16px;margin-right:10px">Итого к оплате:</span>
        <span style="font-size:24px;font-weight:700">' . number_format($order['total'], 0, '.', ' ') . ' ₽</span></td></tr></table>
        </td></tr><tr><td style="background:#1a365d;padding:25px 30px;text-align:center">
        <p style="margin:0;color:rgba(255,255,255,0.8);font-size:14px">© ' . date('Y') . ' ' . self::FROM_NAME . '. Все права защищены.</p>
        <p style="margin:10px 0 0 0;color:rgba(255,255,255,0.6);font-size:12px">Это письмо отправлено автоматически, пожалуйста, не отвечайте на него.</p>
        </td></tr></table></td></tr></table></body></html>';
    }

    private function buildAdminEmailBody(array $order): string
    {
        $lang = Language::getCurrentLang();
        $getText = function ($field, $default = '') use ($lang) {
            if (is_array($field)) {
                return $field[$lang] ?? $field['ru'] ?? $default;
            }
            return $field;
        };
        $itemsHtml = '';
        foreach ($order['items'] as $item) {
            $courseTitle = $getText($item['title'], $item['title']);
            $itemsHtml .= '<li style="padding:5px 0;color:#2d3748">' . htmlspecialchars($courseTitle) . ' — <strong>' . htmlspecialchars($item['price']) . '</strong></li>';
        }
        return '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f7fafc">
        <table role="presentation" style="width:100%"><tr><td align="center" style="padding:40px 20px">
        <table role="presentation" style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden">
        <tr><td style="background:#e53e3e;padding:30px;text-align:center">
        <h1 style="margin:0;color:#ffffff;font-size:24px">🔔 Новый заказ</h1></td></tr>
        <tr><td style="padding:30px"><table role="presentation" style="width:100%">
        <tr><td style="padding:8px 0"><strong>№ заказа:</strong> ' . $order['id'] . '</td></tr>
        <tr><td style="padding:8px 0"><strong>Клиент:</strong> ' . htmlspecialchars($order['name']) . '</td></tr>
        <tr><td style="padding:8px 0"><strong>Email:</strong> <a href="mailto:' . htmlspecialchars($order['email']) . '">' . htmlspecialchars($order['email']) . '</a></td></tr>
        <tr><td style="padding:8px 0"><strong>Телефон:</strong> <a href="tel:' . htmlspecialchars($order['phone']) . '">' . htmlspecialchars($order['phone']) . '</a></td></tr>
        <tr><td style="padding:8px 0"><strong>Сумма:</strong> ' . number_format($order['total'], 0, '.', ' ') . ' ₽</td></tr>
        <tr><td style="padding:8px 0"><strong>Оплата:</strong> ' . $order['payment_method'] . '</td></tr>
        </table><h3 style="color:#2d3748;margin-top:20px">Курсы:</h3><ul style="padding-left:20px;margin:10px 0">' . $itemsHtml . '</ul>';
        if (!empty($order['comment'])) {
            $return .= '<p style="background:#f7fafc;padding:15px;border-radius:6px;margin-top:15px"><strong>Комментарий:</strong><br>' . htmlspecialchars($order['comment']) . '</p>';
        }
        $return .= '</td></tr></table></td></tr></table></body></html>';
        return $return;
    }
}