<?php
/**
 * 🔹 Скрипт миграции базы данных
 * Создает таблицы и переносит данные из JSON в MySQL
 */

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/config/database.php';

EnvLoader::load(__DIR__);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Миграция БД</title>";
echo "<style>body{font-family:monospace;background:#1a1a2e;color:#eee;padding:20px;} 
.success{color:#4ade80;} .error{color:#f87171;} .info{color:#60a5fa;} 
hr{border:0;border-top:1px solid #333;margin:20px 0;} pre{background:#16213e;padding:10px;border-radius:5px;}</style></head><body>";
echo "<h1>🚀 Миграция базы данных «Чёрный Вантуз»</h1>";
echo "<hr>";

try {
    // 🔹 Создаем базу данных если не существует
    $pdo = new PDO(
        "mysql:host=" . (getenv('DB_HOST') ?: 'localhost') . ";charset=utf8mb4",
        getenv('DB_USER') ?: 'root',
        getenv('DB_PASS') ?: ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $dbname = getenv('DB_NAME') ?: 'black_vantuz';
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p class='success'>✅ База данных '{$dbname}' создана или уже существует</p>";
    
    // 🔹 Подключаемся к созданной БД
    $pdo = Database::getConnection();
    
    // 🔹 Создаем таблицу users
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(50) DEFAULT '',
            card_last4 VARCHAR(10) DEFAULT '',
            role ENUM('user', 'admin') DEFAULT 'user',
            verified BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_role (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p class='success'>✅ Таблица 'users' создана</p>";
    
    // 🔹 Создаем таблицу products
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL DEFAULT 0,
            period VARCHAR(50) DEFAULT 'год',
            image VARCHAR(500) DEFAULT '/assets/images/default.jpg',
            coverage VARCHAR(100) DEFAULT 'до 10 млн ₽',
            features JSON DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_price (price)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p class='success'>✅ Таблица 'products' создана</p>";
    
    // 🔹 Создаем таблицу orders
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id VARCHAR(50) UNIQUE NOT NULL,
            user_id INT DEFAULT NULL,
            user_email VARCHAR(255),
            fio VARCHAR(255) NOT NULL,
            phone VARCHAR(50) NOT NULL,
            delivery_type ENUM('email', 'courier') DEFAULT 'email',
            email VARCHAR(255),
            address TEXT,
            products JSON NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            status ENUM('new', 'processing', 'completed', 'cancelled') DEFAULT 'new',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_order_id (order_id),
            INDEX idx_user_id (user_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p class='success'>✅ Таблица 'orders' создана</p>";
    
    // 🔹 Создаем таблицу logs
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS logs (
            id VARCHAR(50) PRIMARY KEY,
            timestamp DATETIME NOT NULL,
            user VARCHAR(255) NOT NULL,
            action VARCHAR(255) NOT NULL,
            ip VARCHAR(50) DEFAULT 'unknown',
            details JSON DEFAULT NULL,
            INDEX idx_timestamp (timestamp),
            INDEX idx_action (action),
            INDEX idx_user (user)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p class='success'>✅ Таблица 'logs' создана</p>";
    
    // 🔹 Создаем таблицу verification_codes
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS verification_codes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            code VARCHAR(10) NOT NULL,
            expires_at DATETIME NOT NULL,
            used BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_code (code),
            INDEX idx_expires (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p class='success'>✅ Таблица 'verification_codes' создана</p>";
    
    echo "<hr>";
    echo "<h2>📦 Перенос данных из JSON</h2>";
    
    // 🔹 Перенос пользователей
    $usersFile = __DIR__ . '/Storage/users.json';
    if (file_exists($usersFile)) {
        $usersData = json_decode(file_get_contents($usersFile), true);
        if (is_array($usersData) && !empty($usersData)) {
            $stmt = $pdo->prepare("
                INSERT INTO users (name, email, password, phone, card_last4, role, verified, created_at)
                VALUES (:name, :email, :password, :phone, :card_last4, :role, :verified, :created_at)
                ON DUPLICATE KEY UPDATE name=name
            ");
            
            $migrated = 0;
            foreach ($usersData as $user) {
                $stmt->execute([
                    ':name' => $user['name'] ?? 'Unknown',
                    ':email' => $user['email'] ?? '',
                    ':password' => $user['password'] ?? '',
                    ':phone' => $user['phone'] ?? '',
                    ':card_last4' => $user['card_last4'] ?? '',
                    ':role' => $user['role'] ?? 'user',
                    ':verified' => $user['verified'] ? 1 : 0,
                    ':created_at' => $user['created_at'] ?? date('Y-m-d H:i:s')
                ]);
                $migrated++;
            }
            echo "<p class='success'>✅ Пользователей перенесено: {$migrated}</p>";
        } else {
            echo "<p class='info'>ℹ️ Файл users.json пуст или не содержит данных</p>";
        }
    } else {
        echo "<p class='info'>ℹ️ Файл users.json не найден</p>";
    }
    
    // 🔹 Перенос продуктов
    $productsFile = __DIR__ . '/Storage/products.json';
    if (file_exists($productsFile)) {
        $productsData = json_decode(file_get_contents($productsFile), true);
        if (is_array($productsData) && !empty($productsData)) {
            $stmt = $pdo->prepare("
                INSERT INTO products (id, name, description, price, period, image, coverage, features)
                VALUES (:id, :name, :description, :price, :period, :image, :coverage, :features)
                ON DUPLICATE KEY UPDATE 
                    name=VALUES(name),
                    description=VALUES(description),
                    price=VALUES(price),
                    period=VALUES(period),
                    image=VALUES(image),
                    coverage=VALUES(coverage),
                    features=VALUES(features)
            ");
            
            $migrated = 0;
            foreach ($productsData as $product) {
                $stmt->execute([
                    ':id' => $product['id'] ?? null,
                    ':name' => $product['name'] ?? '',
                    ':description' => $product['description'] ?? '',
                    ':price' => $product['price'] ?? 0,
                    ':period' => $product['period'] ?? 'год',
                    ':image' => $product['image'] ?? '/assets/images/default.jpg',
                    ':coverage' => $product['coverage'] ?? 'до 10 млн ₽',
                    ':features' => json_encode($product['features'] ?? [])
                ]);
                $migrated++;
            }
            echo "<p class='success'>✅ Продуктов перенесено: {$migrated}</p>";
        }
    } else {
        echo "<p class='info'>ℹ️ Файл products.json не найден</p>";
    }
    
    // 🔹 Перенос заказов
    $ordersFile = __DIR__ . '/Storage/order.json';
    if (file_exists($ordersFile)) {
        $ordersData = json_decode(file_get_contents($ordersFile), true);
        if (is_array($ordersData) && !empty($ordersData)) {
            $stmt = $pdo->prepare("
                INSERT INTO orders (order_id, user_id, user_email, fio, phone, delivery_type, email, address, products, total, status, created_at)
                VALUES (:order_id, :user_id, :user_email, :fio, :phone, :delivery_type, :email, :address, :products, :total, :status, :created_at)
                ON DUPLICATE KEY UPDATE order_id=order_id
            ");
            
            $migrated = 0;
            foreach ($ordersData as $order) {
                $stmt->execute([
                    ':order_id' => $order['order_id'] ?? 'ORD_' . uniqid(),
                    ':user_id' => $order['user_id'] ?? null,
                    ':user_email' => $order['user_email'] ?? '',
                    ':fio' => $order['fio'] ?? '',
                    ':phone' => $order['phone'] ?? '',
                    ':delivery_type' => $order['delivery_type'] ?? 'email',
                    ':email' => $order['email'] ?? '',
                    ':address' => $order['address'] ?? '',
                    ':products' => json_encode($order['products'] ?? []),
                    ':total' => $order['total'] ?? 0,
                    ':status' => $order['status'] ?? 'new',
                    ':created_at' => isset($order['created_at']) ? 
                        date('Y-m-d H:i:s', strtotime($order['created_at'])) : date('Y-m-d H:i:s')
                ]);
                $migrated++;
            }
            echo "<p class='success'>✅ Заказов перенесено: {$migrated}</p>";
        }
    } else {
        echo "<p class='info'>ℹ️ Файл order.json не найден</p>";
    }
    
    // 🔹 Перенос логов
    $logsFile = __DIR__ . '/Storage/logs.json';
    if (file_exists($logsFile)) {
        $logsData = json_decode(file_get_contents($logsFile), true);
        if (is_array($logsData) && !empty($logsData)) {
            $stmt = $pdo->prepare("
                INSERT INTO logs (id, timestamp, user, action, ip, details)
                VALUES (:id, :timestamp, :user, :action, :ip, :details)
                ON DUPLICATE KEY UPDATE id=id
            ");
            
            $migrated = 0;
            foreach ($logsData as $log) {
                $stmt->execute([
                    ':id' => $log['id'] ?? 'log_' . uniqid(),
                    ':timestamp' => $log['timestamp'] ?? date('Y-m-d H:i:s'),
                    ':user' => $log['user'] ?? 'System',
                    ':action' => $log['action'] ?? '',
                    ':ip' => $log['ip'] ?? 'unknown',
                    ':details' => json_encode($log['details'] ?? [])
                ]);
                $migrated++;
            }
            echo "<p class='success'>✅ Лога перенесено: {$migrated}</p>";
        }
    } else {
        echo "<p class='info'>ℹ️ Файл logs.json не найден</p>";
    }
    
    echo "<hr>";
    echo "<h2 class='success'>✅ Миграция завершена!</h2>";
    echo "<p>Теперь сайт готов работать с базой данных MySQL.</p>";
    echo "<p><a href='/'>🏠 Перейти на главную страницу</a></p>";
    
} catch (PDOException $e) {
    echo "<p class='error'>❌ Ошибка миграции: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
