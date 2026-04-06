<?php
/**
 * Миграция: Создание таблиц для базы данных
 * Запустить: php -r "require_once 'migrate.php';"
 */

require_once __DIR__ . '/src/Config/Config.php';
require_once __DIR__ . '/src/Database/Database.php';

use App\Config\Config;
use App\Database\Database;

echo "=== Миграция базы данных ===\n\n";

try {
    // Инициализируем подключение
    Config::initDatabase();
    $db = Database::getConnection();

    echo "✓ Подключение к БД установлено\n\n";

    // === Таблица products ===
    echo "Создание таблицы products...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS `products` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `image` VARCHAR(500) DEFAULT '/assets/img/no-image.jpg',
            `description` TEXT,
            `price` DECIMAL(10,2) NOT NULL,
            `category` VARCHAR(100) DEFAULT 'Без категории',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_category` (`category`),
            INDEX `idx_price` (`price`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Таблица products создана\n";

    // === Таблица users ===
    echo "Создание таблицы users...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `email` VARCHAR(255) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(50) DEFAULT '',
            `address` TEXT DEFAULT '',
            `avatar` VARCHAR(500) DEFAULT '',
            `is_verified` TINYINT(1) DEFAULT 0,
            `verification_code` VARCHAR(10) DEFAULT NULL,
            `verification_expires` DATETIME DEFAULT NULL,
            `verified_at` DATETIME DEFAULT NULL,
            `is_admin` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_email` (`email`),
            INDEX `idx_is_verified` (`is_verified`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Таблица users создана\n";

    // === Таблица orders ===
    echo "Создание таблицы orders...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS `orders` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED DEFAULT NULL,
            `email` VARCHAR(255) NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(50) DEFAULT '',
            `address` TEXT,
            `total` DECIMAL(10,2) NOT NULL,
            `status` ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
            `items` JSON NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_user_id` (`user_id`),
            INDEX `idx_status` (`status`),
            INDEX `idx_created_at` (`created_at`),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Таблица orders создана\n";

    echo "\n=== Миграция завершена успешно! ===\n";

    // === Импорт данных из JSON (опционально) ===
    echo "\nХотите импортировать данные из JSON файлов? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    
    if (trim(strtolower($line)) === 'y') {
        importFromJson($db);
    }

} catch (\PDOException $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Импорт данных из JSON файлов
 */
function importFromJson($db): void
{
    echo "\n=== Импорт данных из JSON ===\n";

    // Импорт товаров
    $productsFile = __DIR__ . '/storage/data.json';
    if (file_exists($productsFile)) {
        $data = json_decode(file_get_contents($productsFile), true);
        if (is_array($data)) {
            $stmt = $db->prepare("
                INSERT INTO products (id, name, image, description, price, category) 
                VALUES (:id, :name, :image, :description, :price, :category)
                ON DUPLICATE KEY UPDATE name = VALUES(name), image = VALUES(image), 
                    description = VALUES(description), price = VALUES(price), category = VALUES(category)
            ");
            
            foreach ($data as $product) {
                $stmt->execute([
                    ':id' => $product['id'] ?? null,
                    ':name' => $product['name'] ?? '',
                    ':image' => $product['image'] ?? '/assets/img/no-image.jpg',
                    ':description' => $product['description'] ?? '',
                    ':price' => $product['price'] ?? 0,
                    ':category' => $product['category'] ?? 'Без категории',
                ]);
            }
            echo "✓ Импортировано " . count($data) . " товаров\n";
        }
    }

    // Импорт пользователей
    $usersFile = __DIR__ . '/storage/users.json';
    if (file_exists($usersFile)) {
        $data = json_decode(file_get_contents($usersFile), true);
        if (is_array($data)) {
            $stmt = $db->prepare("
                INSERT INTO users (id, email, password, name, phone, address, avatar, is_verified) 
                VALUES (:id, :email, :password, :name, :phone, :address, :avatar, :is_verified)
                ON DUPLICATE KEY UPDATE email = VALUES(email)
            ");
            
            foreach ($data as $user) {
                $stmt->execute([
                    ':id' => $user['id'] ?? null,
                    ':email' => $user['email'] ?? '',
                    ':password' => $user['password'] ?? '',
                    ':name' => $user['name'] ?? '',
                    ':phone' => $user['phone'] ?? '',
                    ':address' => $user['address'] ?? '',
                    ':avatar' => $user['avatar'] ?? '',
                    ':is_verified' => $user['is_verified'] ?? 0,
                ]);
            }
            echo "✓ Импортировано " . count($data) . " пользователей\n";
        }
    }

    // Импорт заказов
    $ordersFile = __DIR__ . '/storage/orders.json';
    if (file_exists($ordersFile)) {
        $data = json_decode(file_get_contents($ordersFile), true);
        if (is_array($data)) {
            // Сначала получаем маппинг email -> user_id
            $userIdMap = [];
            $userStmt = $db->query("SELECT id, email FROM users");
            foreach ($userStmt->fetchAll() as $user) {
                $userIdMap[strtolower($user['email'])] = $user['id'];
            }

            $stmt = $db->prepare("
                INSERT INTO orders (id, user_id, email, name, phone, address, total, status, items, created_at) 
                VALUES (:id, :user_id, :email, :name, :phone, :address, :total, :status, :items, :created_at)
                ON DUPLICATE KEY UPDATE status = VALUES(status)
            ");
            
            foreach ($data as $order) {
                // Находим user_id по email
                $userId = null;
                if (!empty($order['email']) && isset($userIdMap[strtolower($order['email'])])) {
                    $userId = $userIdMap[strtolower($order['email'])];
                }
                
                $stmt->execute([
                    ':id' => $order['id'] ?? null,
                    ':user_id' => $userId,
                    ':email' => $order['email'] ?? '',
                    ':name' => $order['name'] ?? '',
                    ':phone' => $order['phone'] ?? '',
                    ':address' => $order['address'] ?? '',
                    ':total' => $order['total'] ?? 0,
                    ':status' => $order['status'] ?? 'pending',
                    ':items' => json_encode($order['items'] ?? []),
                    ':created_at' => $order['created_at'] ?? date('Y-m-d H:i:s'),
                ]);
            }
            echo "✓ Импортировано " . count($data) . " заказов\n";
        }
    }

    echo "\n=== Импорт завершён! ===\n";
}
