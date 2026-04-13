<?php
namespace App\Config;

class Config {
    const FILE_DATA=".\storage\data.json";
    const FILE_ORDERS=".\storage\order.json";
    const TYPE_FILE="file";
    const TYPE_DB="db";
    const STORAGE_TYPE= self::TYPE_DB;
    const MYSQL_DNS = 'mysql:dbname=is231;host=localhost';
    const MYSQL_USER = 'root';
    const MYSQL_PASSWORD = '';

    const TABLE_PRODUCTS="products";
    const TABLE_ORDERS="orders";
}
