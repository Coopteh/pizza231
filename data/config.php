<?php
// data/config.php
// Глобальные настройки сайта
return [
    'site' => [
        'name' => 'Geek Legend',
        'short_name' => 'ККТ',
        'description' => 'Официальный сайт учебного заведения среднего профессионального образования',
        'keywords' => 'образование, СПО, Кемерово, курсы, обучение, профессии',
        'url' => 'https://kemt.local'
    ],
    'contact' => [
        'email' => 'toni.maslennikov.08@inbox.ru',
        'phone' => '+7 (228) 39-60-00',
        'address' => '650066, г. Кемерово, ул. Тухачевского, 32а',
        'schedule' => 'Пн-Сб: 8:00–19:45, Вс: выходной'
    ],
    'social' => [
        'vk' => 'https://vk.com/kemt_official',
        'telegram' => 'https://t.me/kemt_news'
    ],
    'pagination' => [
        'courses_per_page' => 6,
        'news_per_page' => 10
    ],
    // 🔐 НОВОЕ: Настройки подключения к базе данных
    'db' => [
        'enabled' => true,           // true - использовать БД, false - файлы
        'dsn' => 'mysql:dbname=is231;host=localhost;charset=utf8mb4',
        'user' => 'root',
        'pass' => '',
        'table_products' => 'products',
        'table_orders' => 'orders',
        'table_order_items' => 'order_item'
    ]
];