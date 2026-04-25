<?php

// Отключаем автоматическое начало сессии для тестов
ini_set('session.use_cookies', '0');
ini_set('session.use_only_cookies', '0');
ini_set('session.cache_limiter', '');

// Автозагрузчик Composer
require_once __DIR__ . '/../vendor/autoload.php';

