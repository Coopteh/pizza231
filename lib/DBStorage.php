<?php
// Lib/DBStorage.php
namespace Lib;

use PDO;
use PDOException;

class DBStorage
{
    protected PDO $connection;
    protected array $config;

    public function __construct(array $dbConfig)
    {
        $this->config = $dbConfig;
        try {
            $this->connection = new PDO(
                $dbConfig['dsn'],
                $dbConfig['user'],
                $dbConfig['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            $this->connection->exec("SET NAMES utf8mb4");
        } catch (PDOException $e) {
            Logger::error("Ошибка подключения к БД", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    protected function getConnection(): PDO
    {
        return $this->connection;
    }
}