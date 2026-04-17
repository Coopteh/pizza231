<?php
/**
 * 🔹 Конфигурация базы данных
 * Подключение через PDO к MySQL
 */
class Database
{
    private static ?PDO $instance = null;
    
    /**
     * 🔹 Получить подключение к БД (Singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: 'localhost';
            $port = getenv('DB_PORT') ?: '3306';
            $dbname = getenv('DB_NAME') ?: 'black_vantuz';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASS') ?: '';
            
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("❌ Ошибка подключения к БД: " . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
    
    /**
     * 🔹 Закрыть подключение
     */
    public static function close(): void
    {
        self::$instance = null;
    }
}
