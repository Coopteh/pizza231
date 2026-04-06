<?php

namespace App\Controllers;

use App\Models\Product;
use App\Views\OrderTemplate;
use App\Config\Config;
use App\Services\FileStorage;
use App\Services\DatabaseStorage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController
{
    /**
     * Отображает форму заказа или обрабатывает её отправку.
     */
    public function get(): string
    {
        // Если форма заказа была отправлена (POST-запрос), обрабатываем её
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }

        // Создаем модель для работы с товарами, чтобы получить данные корзины
        $productModel = $this->createProductModel(Config::FILE_PRODUCTS);
        $basketData = $productModel->getBasketData();

        // Отображаем шаблон страницы заказа с данными корзины
        $orderView = new OrderTemplate();
        return $orderView->getOrderTemplate($basketData);
    }

    /**
     * Обрабатывает создание заказа.
     */
    public function create(): string
    {
        // Получаем данные из формы
        $formData = $_POST;

        // 1. Получаем данные корзины (модель для товаров)
        $basketModel = $this->createProductModel(Config::FILE_PRODUCTS);
        $basketData = $basketModel->getBasketData();

        // 2. Подготавливаем данные для сохранения в формате заказа
        $orderData = $basketModel->prepareData($formData, $basketData);

        // 3. Создаем модель для работы с заказами (модель для файла заказов)
        $orderModel = $this->createProductModel(Config::FILE_ORDERS);
        
        // 4. Сохраняем заказ и проверяем результат
        $isSaved = $orderModel->saveData($orderData);

        // Действия выполняем только если сохранение прошло успешно
        if ($isSaved) {
            // Отправка письма с подтверждением
            $email = $formData['email'] ?? '';
            if (!empty($email)) {
                $clientName = $formData['name'] ?? 'Клиент';
                $this->sendMail($email, $clientName, $orderData);
            }

            // Очищаем корзину и устанавливаем сообщение об успехе
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['basket'] = [];
            $_SESSION['flash'] = "Спасибо! Нам очень приятно получить ваш заказ.";

            header("Location: /");
            exit;
        } else {
            // Обработка ошибки сохранения (можно расширить логированием)
            $_SESSION['flash'] = "Произошла ошибка при сохранении заказа.";
            header("Location: /order");
            exit;
        }
    }

    /**
     * Вспомогательный метод для создания экземпляра Product с внедренной зависимостью.
     * Избавляет от дублирования кода в контроллере.
     */
    private function createProductModel(string $resourceName): Product
    {
        if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
            $serviceStorage = new FileStorage();
        } else {
            $serviceStorage = new DatabaseStorage();
        }
        
        return new Product($serviceStorage, $resourceName);
    }

    /**
     * Отправляет письмо с подтверждением заказа.
     */
    private function sendMail(string $email, string $clientName, array $orderData): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Настройки SMTP-сервера (ваши данные)
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

            // Отправитель и получатель
            $mail->setFrom('coopteh231@mail.ru', 'По мультивселенной');
            $mail->addAddress($email, $clientName);
            $mail->addReplyTo('coopteh231@mail.ru', 'Поддержка');
            
            $mail->isHTML(true);
            $mail->Subject = 'Заказ подтверждён';

            // --- ИСПРАВЛЕННЫЙ БЛОК ГЕНЕРАЦИИ ТЕЛА ПИСЬМА ---
            // Использование оператора "??" для предотвращения ошибок "Use of unassigned variable"
            
            $itemsHtml = '';
            
            foreach ($orderData['products'] ?? [] as $item) {
                // Задаем значения по умолчанию, если ключи отсутствуют в массиве
                $name = htmlspecialchars($item['name'] ?? 'Неизвестный товар');
                $price = $item['price'] ?? 0;
                $qty = $item['quantity'] ?? 1;
                
                // Расчет суммы для конкретного товара
                $subtotal = $price * $qty;
                
                $itemsHtml .= "<li>{$name} × {$qty} = <b>{$subtotal} ₽</b></li>";
            }
            
             // Поддержка разных ключей для телефона и адреса из формы и из модели
             // Это обеспечивает совместимость с методом prepareData()
             $phoneKey = isset($orderData['phone']) ? 'phone' : 'user_phone';
             $addressKey = isset($orderData['address']) ? 'address' : 'user_address';
             
             $phone = htmlspecialchars($orderData[$phoneKey] ?? '');
             $address = htmlspecialchars($orderData[$addressKey] ?? '');
             
             // Итоговая сумма заказа
             $total = $orderData['all_sum'] ?? 0;


            // HTML-версия письма (красивое оформление)
            $mail->Body = "
                <html>
                <head><style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .header { background: #0A3D62; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; }
                    .order-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
                    .items { list-style: none; padding: 0; }
                    .items li { padding: 5px 0; border-bottom: 1px solid #eee; }
                    .total { font-size: 1.2em; font-weight: bold; color: #0A3D62; margin-top: 15px; }
                    .footer { text-align: center; color: #666; font-size: 0.9em; margin-top: 30px; }
                </style></head>
                <body>
                    <div class='header'>
                        <h2>По мультивселенной</h2>
                        <p>Ваш заказ подтверждён</p>
                    </div>
                    <div class='content'>
                        <p>Здравствуйте, <b>{$clientName}</b>!</p>
                        <div class='order-info'>
                            <p><b>Дата заказа:</b> " . date('d.m.Y H:i') . "</p>
                            <p><b>Телефон:</b> {$phone}</p>
                            <p><b>Адрес доставки:</b> {$address}</p>
                        </div>
                        <p><b>Состав заказа:</b></p>
                        <ul class='items'>" . ($itemsHtml ?: '<li>Список товаров недоступен</li>') . "</ul>
                        <p class='total'>Итого к оплате: {$total} ₽</p>
                        <p>Спасибо за покупку!</p>
                        <div class='footer'>
                            <p>Это письмо сгенерировано автоматически.<br>Не отвечайте на это сообщение.</p>
                            <p>© 2025 Кемеровский кооперативный техникум</p>
                        </div>
                    </div>
                </body>
                </html>";


            // Plain-text версия письма (для почтовых клиентов без поддержки HTML)
             $altBodyItems = [];
             foreach ($orderData['products'] ?? [] as $i) {
                 // Используем те же значения по умолчанию для текста
                 $altBodyItems[] = "- " . ($i['name'] ?? 'Товар') . " × " . ($i['quantity'] ?? 1) . " = " . (($i['price'] ?? 0) * ($i['quantity'] ?? 1)) . " ₽";
             }
             
             $mail->AltBody = "Заказ подтверждён!\nЗдравствуйте, {$clientName}!\n"
                 . "Телефон: {$phone}\n"
                 . "Адрес: {$address}\n"
                 . "Итого к оплате: {$total} ₽\n"
                 . "Состав заказа:\n"
                 . implode("\n", $altBodyItems);
            
             // Отправка письма
             $mail->send();
             return true;
 
         } catch (Exception $e) {
             error_log("PHPMailer Error: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
 
             if (session_status() === PHP_SESSION_ACTIVE) {
                 $_SESSION['mail_error'] = 'Не удалось отправить email: ' . $mail->ErrorInfo;
             }
             return false;
         }
     }
}