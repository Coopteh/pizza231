<?php
/**
 * Конфигурация email для отправки уведомлений о заказах
 * Скопируйте этот файл как email.php и заполните реальными данными
 */

return [
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'ваш_email@gmail.com',
        'password' => 'ваш_пароль_приложения',
        'encryption' => 'tls', // tls или ssl
        'from_email' => 'ваш_email@gmail.com',
        'from_name' => 'Магазин автозапчастей'
    ],
    'notifications' => [
        // Email администратора для получения уведомлений о новых заказах
        'admin_email' => 'admin@example.com',
        // Включить отправку клиенту
        'send_to_customer' => true,
        // Включить отправку администратору
        'send_to_admin' => true
    ]
];
?>