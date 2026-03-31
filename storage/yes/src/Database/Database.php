<?php
namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    private static array $config = [];

    /**
     * Инициализация конфигурации базы данных
     */
    public static function init(array $config): void
    {
        self::$config = $config;
    }

    /**
     * Получить соединение с базой данных (Singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::$connection = self::createConnection();
        }

        return self::$connection;
    }

    /**
     * Создать новое соединение
     */
    private static function createConnection(): PDO
    {
        $config = self::$config;

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $config['driver'] ?? 'mysql',
            $config['host'] ?? 'localhost',
            $config['port'] ?? 3306,
            $config['database'] ?? '',
            $config['charset'] ?? 'utf8mb4'
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($dsn, $config['username'] ?? '', $config['password'] ?? '', $options);
            return $pdo;
        } catch (PDOException $e) {
            throw new \RuntimeException("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }

    /**
     * Закрыть соединение
     */
    public static function closeConnection(): void
    {
        self::$connection = null;
    }

    /**
     * Проверить соединение
     */
    public static function isConnected(): bool
    {
        return self::$connection !== null;
    }
}
