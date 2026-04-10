<?php
namespace Utils;

class MailSender
{
    /**
     * Отправка письма клиенту с ПОЛНЫМ составом заказа
     */
    public static function sendOrderConfirmation(array $orderData, string $customerEmail): bool
    {
        $subject = '✓ Ваш заказ в gym low cortisol оформлен';
        
        // 🔹 Формируем список товаров (ОБЯЗАТЕЛЬНО проверяем, что массив не пустой)
        $productsList = '';
        $productsListText = '';
        
        // Проверяем, что products существует и это массив
        if (!empty($orderData['products']) && is_array($orderData['products'])) {
            foreach ($orderData['products'] as $item) {
                $name = $item['name'] ?? 'Услуга';
                $qty = (int)($item['quantity'] ?? 1);
                $price = number_format($item['price'] ?? 0, 0, '.', ' ');
                $subtotal = number_format($item['subtotal'] ?? 0, 0, '.', ' ');
                
                // HTML-строка для таблицы
                $productsList .= "
                <tr>
                    <td style='padding:12px 15px;border-bottom:1px solid #eee'>
                        <strong>{$name}</strong>
                    </td>
                    <td style='padding:12px 15px;border-bottom:1px solid #eee;text-align:center'>
                        × {$qty}
                    </td>
                    <td style='padding:12px 15px;border-bottom:1px solid #eee;text-align:right;font-weight:600'>
                        {$subtotal} ₽
                    </td>
                </tr>";
                
                // Текстовая версия
                $productsListText .= "• {$name} × {$qty} — {$subtotal} ₽\n";
            }
        } else {
            // Если товаров нет — покажем сообщение
            $productsList = "<tr><td colspan='3' style='padding:15px;text-align:center;color:#999'>Список услуг пуст</td></tr>";
            $productsListText = "Список услуг пуст\n";
        }
        
        $totalFormatted = number_format($orderData['total'] ?? 0, 0, '.', ' ');
        $fio = htmlspecialchars($orderData['fio'] ?? 'Клиент');
        $orderId = htmlspecialchars($orderData['order_id'] ?? '—');
        
        // 🔹 HTML-тело письма
        $htmlBody = "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'><style>
            body{font-family:Arial,sans-serif;line-height:1.6;color:#333}
            .container{max-width:600px;margin:0 auto;padding:20px}
            .header{background:linear-gradient(135deg,#8b5cf6,#a78bfa);color:#fff;padding:25px;border-radius:12px 12px 0 0;text-align:center}
            .content{background:#fff;padding:25px;border:1px solid #e5e7eb;border-radius:0 0 12px 12px}
            .order-info{background:#f9fafb;padding:15px;border-radius:8px;margin:20px 0}
            table{width:100%;border-collapse:collapse;margin:20px 0}
            th{background:#f3f0ff;padding:12px 15px;text-align:left;font-weight:600;color:#5b21b6}
            .total-row{font-size:1.2rem;font-weight:700;color:#7c3aed;border-top:2px solid #a78bfa}
            .footer{text-align:center;color:#6b728b;font-size:0.9rem;margin-top:30px;padding-top:20px;border-top:1px solid #eee}
        </style></head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2 style='margin:0'>✓ Заказ оформлен!</h2>
                    <p style='margin:5px 0 0;opacity:0.9'>gym low cortisol</p>
                </div>
                <div class='content'>
                    <p>Здравствуйте, <strong>{$fio}</strong>!</p>
                    <p>Ваш заказ <strong>#{$orderId}</strong> успешно оформлен.</p>
                    
                    <div class='order-info'><strong>📋 Состав заказа:</strong></div>
                    
                    <table>
                        <thead>
                            <tr><th>Услуга</th><th style='text-align:center'>Кол-во</th><th style='text-align:right'>Сумма</th></tr>
                        </thead>
                        <tbody>{$productsList}</tbody>
                        <tfoot>
                            <tr class='total-row'>
                                <td colspan='2' style='padding:15px;text-align:right'>Итого:</td>
                                <td style='padding:15px;text-align:right'>{$totalFormatted} ₽</td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class='order-info'>
                        <strong>📬 Доставка:</strong> ".($orderData['delivery_type'] === 'email' ? 'Email' : 'Курьер')."<br>
                        <strong>📧 Email:</strong> ".htmlspecialchars($orderData['email'] ?? '—')."<br>
                        <strong>📱 Телефон:</strong> ".htmlspecialchars($orderData['phone'] ?? '—')."
                    </div>
                    
                    <p style='text-align:center;margin:25px 0'>
                        <a href='tel:+79999999999' style='display:inline-block;background:#8b5cf6;color:#fff;padding:12px 30px;text-decoration:none;border-radius:50px'>📞 +7 (999) 999-99-99</a>
                    </p>
                    
                    <div class='footer'>
                        <p style='margin:0'><strong>gym low cortisol</strong><br>📍 Ваш фитнес-клуб рядом с домом</p>
                        <p style='margin:10px 0 0;font-size:0.8rem;color:#9ca3af'>Это письмо отправлено автоматически.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
        
        // 🔹 Текстовая версия (для старых почтовых клиентов)
        $textBody = "Здравствуйте, {$fio}!\n\n".
                   "Ваш заказ #{$orderId} оформлен.\n\n".
                   "📋 Состав заказа:\n{$productsListText}\n".
                   "💰 Итого: {$totalFormatted} ₽\n\n".
                   "📬 Доставка: ".($orderData['delivery_type'] === 'email' ? 'Email' : 'Курьер')."\n".
                   "📧 Email: ".htmlspecialchars($orderData['email'] ?? '—')."\n".
                   "📱 Телефон: ".htmlspecialchars($orderData['phone'] ?? '—')."\n\n".
                   "📞 Вопросы: +7 (999) 999-99-99\ngym low cortisol";
        
        // 🔹 Заголовки письма (multipart: HTML + текст)
        $boundary = "=_boundary_".uniqid();
        $headers = "From: no-reply@gym-low-cortisol.ru\r\n";
        $headers .= "Reply-To: support@gym-low-cortisol.ru\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
        $headers .= "X-Mailer: PHP/".phpversion()."\r\n";
        
        // 🔹 Формируем multipart-сообщение
        $message = "--{$boundary}\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= $textBody."\r\n\r\n";
        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= $htmlBody."\r\n\r\n";
        $message .= "--{$boundary}--";
        
        // 🔹 Отправка
        $result = mail($customerEmail, $subject, $message, $headers);
        
        // Логируем ошибку если не отправилось
        if (!$result) {
            error_log("Mail error to {$customerEmail}: ".print_r(error_get_last(), true));
        }
        
        return $result;
    }
    
    /**
     * Уведомление администратору
     */
    public static function notifyAdmin(array $orderData): bool
    {
        $subject = '🔔 Новый заказ #'.($orderData['order_id'] ?? '—');
        
        $productsText = '';
        if (!empty($orderData['products']) && is_array($orderData['products'])) {
            foreach ($orderData['products'] as $item) {
                $productsText .= "• ".($item['name'] ?? 'Услуга').
                               " × ".(int)($item['quantity'] ?? 1).
                               " = ".number_format($item['subtotal'] ?? 0, 0, '.', ' ')." ₽\n";
            }
        }
        
        $message = "НОВЫЙ ЗАКАЗ\n".str_repeat("=",40)."\n\n".
                   "🆔 Заказ: #".($orderData['order_id'] ?? '—')."\n".
                   "👤 Клиент: ".htmlspecialchars($orderData['fio'] ?? '—')."\n".
                   "📧 Email: ".htmlspecialchars($orderData['email'] ?? '—')."\n".
                   "📱 Телефон: ".htmlspecialchars($orderData['phone'] ?? '—')."\n\n".
                   "📦 Состав:\n{$productsText}\n".
                   "💰 СУММА: ".number_format($orderData['total'] ?? 0, 0, '.', ' ')." ₽\n".
                   "🕐 Время: ".($orderData['created_at'] ?? date('d.m.Y H:i:s'));
        
        $headers = "From: no-reply@gym-low-cortisol.ru\r\nContent-Type: text/plain; charset=UTF-8\r\n";
        
        return mail('admin@gym-low-cortisol.ru', $subject, $message, $headers);
    }
}