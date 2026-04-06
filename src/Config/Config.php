<?php
namespace App\Config;

class Config {
    const TYPE_FILE = "file";
    const TYPE_DB = "db";
    const STORAGE_TYPE = self::TYPE_FILE; // Меняйте на self::TYPE_DB для использования БД

    const FILE_PRODUCTS = ".\\storage\\data.json";
    const FILE_ORDERS = ".\\storage\\order.json";
}