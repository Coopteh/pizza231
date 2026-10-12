<?php

namespace App\Config;

class Config
{
    public const TYPE_FILE = "file";
    public const TYPE_DB = "db";
    
    // 🔧 Меняйте здесь: self::TYPE_DB или self::TYPE_FILE
    public const STORAGE_TYPE = self::TYPE_DB;

    // 🔹 Настройки MySQL
    public const MYSQL_DSN = 'mysql:dbname=is231;host=localhost';
    public const MYSQL_USER = 'root';
    public const MYSQL_PASSWORD = '';

    // 🔹 Имена таблиц
    public const TABLE_PRODUCTS = "products";
    public const TABLE_ORDERS = "orders";

    // 🔹 Пути к файлам (для режима TYPE_FILE)
    // __DIR__ = src/Configs, поэтому ../../storage = хранилище в корне проекта
    public const FILE_PRODUCTS = __DIR__ . '/../../storage/data.json';
    public const FILE_ORDERS = __DIR__ . '/../../storage/orders.json';
}