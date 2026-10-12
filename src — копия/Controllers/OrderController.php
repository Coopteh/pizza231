<?php
namespace App\Controllers;

use App\Models\Product;
use App\Views\OrderTemplate;
use App\Config\Config;
use App\Services\FileStorage;
use App\Services\ProductDBStorage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController
{
    public function get(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }

        $productModel = $this->createProductModel(Config::FILE_PRODUCTS);
        $basketData = $productModel->getBasketData();

        $orderView = new OrderTemplate();
        return $orderView->getOrderTemplate($basketData);
    }

    public function create(): string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $formData = $_POST;
        $basketModel = $this->createProductModel(Config::FILE_PRODUCTS);
        $basketData = $basketModel->getBasketData();

        // Защита от пустой корзины
        if (empty($basketData)) {
            $_SESSION['flash'] = "Корзина пуста! Добавьте товары перед оформлением.";
            header("Location: /order");
            exit;
        }

        $orderData = $basketModel->prepareData($formData, $basketData);
        $orderModel = $this->createProductModel(Config::FILE_ORDERS);

        try {
            $isSaved = $orderModel->saveData($orderData);
        } catch (\Throwable $e) {
            error_log("OrderController Save Exception: " . $e->getMessage());
            $isSaved = false;
        }

        if ($isSaved) {
            // 🔗 Привязка заказа к авторизованному пользователю
            if (!empty($_SESSION['user_id'])) {
                try {
                    $pdo = new \PDO(Config::MYSQL_DSN, Config::MYSQL_USER, Config::MYSQL_PASSWORD, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
                    $stmt = $pdo->prepare("UPDATE `orders` SET `user_id` = :uid WHERE `order_id` = :oid");
                    $stmt->execute([':uid' => $_SESSION['user_id'], ':oid' => $orderData['order_id']]);
                } catch (\PDOException $e) {
                    error_log("Link user to order error: " . $e->getMessage());
                }
            }

            // 📧 Отправка письма
            $email = $formData['email'] ?? '';
            if (!empty($email)) {
                $clientName = $formData['fio'] ?? 'Клиент';
                try {
                    $this->sendMail($email, $clientName, $orderData);
                } catch (\Throwable $e) {
                    error_log("Order Mail Error: " . $e->getMessage());
                }
            }

            $_SESSION['basket'] = [];
            $_SESSION['flash'] = "✅ Заказ успешно оформлен! Проверьте email.";
            header("Location: /");
            exit;
        } else {
            error_log("OrderController: Failed to save. Data: " . json_encode($orderData));
            $_SESSION['flash'] = "❌ Произошла ошибка при сохранении заказа. Попробуйте позже.";
            header("Location: /order");
            exit;
        }
    }

    private function createProductModel(string $resourceName): Product
    {
        $serviceStorage = Config::STORAGE_TYPE === Config::TYPE_FILE 
            ? new FileStorage() 
            : new ProductDBStorage();
        return new Product($serviceStorage, $resourceName);
    }

    private function sendMail(string $email, string $clientName, array $orderData): bool
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.ru';
            $mail->SMTPAuth = true;
            $mail->Username = 'coopteh231@mail.ru';
            $mail->Password = 'oBdxSwM2AWnco7ALXUk5';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('coopteh231@mail.ru', 'По мультивселенной');
            $mail->addAddress($email, $clientName);
            $mail->addReplyTo('coopteh231@mail.ru', 'Поддержка');
            $mail->isHTML(true);
            $mail->Subject = 'Заказ подтверждён';

            $itemsHtml = '';
            foreach ($orderData['products'] ?? [] as $item) {
                $name = htmlspecialchars($item['name'] ?? 'Товар');
                $price = $item['price'] ?? 0;
                $qty = $item['quantity'] ?? 1;
                $subtotal = $price * $qty;
                $itemsHtml .= "<li>{$name} × {$qty} = <b>{$subtotal} ₽</b></li>";
            }

            $phone = htmlspecialchars($orderData['user_phone'] ?? '');
            $address = htmlspecialchars($orderData['user_address'] ?? '');
            $total = $orderData['all_sum'] ?? 0;

            $mail->Body = "
                <html><body style='font-family:Arial,sans-serif;line-height:1.6;color:#333'>
                <div style='background:#0A3D62;color:white;padding:20px;text-align:center'><h2>По мультивселенной</h2></div>
                <div style='padding:20px'>
                    <p>Здравствуйте, <b>{$clientName}</b>!</p>
                    <p><b>Телефон:</b> {$phone}<br><b>Адрес:</b> {$address}</p>
                    <ul style='list-style:none;padding:0'>{$itemsHtml}</ul>
                    <p style='font-size:1.2em;font-weight:bold;color:#0A3D62'>Итого: {$total} ₽</p>
                    <p style='color:#666;font-size:0.9em'>© 2026 Кемеровский кооперативный техникум</p>
                </div></body></html>";

            $mail->AltBody = "Заказ подтверждён!\nИтого: {$total} ₽";
            return $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}