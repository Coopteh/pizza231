<?php
// data/config.php
// Глобальные настройки сайта
return [
    'site' => [
        'name' => 'Iron Legend Gym',
        'short_name' => 'ILG',
        'description' => 'Современный фитнес-клуб премиум класса в центре города',
        'keywords' => 'фитнес, тренажерный зал, спорт, тренировки, абонемент, Кемерово',
        'url' => 'https://ironlegend.local'
    ],
    'contact' => [
        'email' => 'info@ironlegend.gym',
        'phone' => '+7 (999) 000-00-00',
        'address' => '650000, г. Кемерово, пр. Ленина, 1',
        'schedule' => 'Пн-Вс: 7:00–23:00, без выходных'
    ],
    'social' => [
        'vk' => 'https://vk.com/ironlegend_gym',
        'telegram' => 'https://t.me/ironlegend_news'
    ],
    'pagination' => [
        'courses_per_page' => 6, // Здесь имеются в виду услуги/абонементы
        'news_per_page' => 10
    ]
];