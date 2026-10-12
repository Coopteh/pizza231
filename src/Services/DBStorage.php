<?php

namespace App\Services;

use PDO;
use App\Config\Config;

class DBStorage 
{
    protected $connection;

    public function __construct() {
        $this->connection = new PDO(
            Config::MYSQL_DSN,
            Config::MYSQL_USER,
            Config::MYSQL_PASSWORD
        );
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}