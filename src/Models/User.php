<?php
namespace App\Models;

require_once __DIR__ . '/../Config/Config.php';
require_once __DIR__ . '/../Database/Database.php';

use App\Config\Config;
use App\Database\Database;
use PDO;

class User
{
    private const FILE_USERS = __DIR__ . '/../../storage/users.json';
    private const FILE_ADMINS = __DIR__ . '/../../storage/admins.json';
    
    private ?PDO $db = null;

    public function __construct()
    {
        if (Config::isDatabaseAvailable()) {
            $this->db = Database::getConnection();
        }
    }

    /**
     * Загрузить всех пользователей
     */
    public function loadData(): array
    {
        if ($this->db !== null) {
            return $this->loadFromDatabase();
        }
        return $this->loadFromJson();
    }
        
    /**
     * Загрузка из БД
     */
    private function loadFromDatabase(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM users ORDER BY id");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return $this->loadFromJson();
        }
    }
    
    /**
     * Загрузка из JSON (fallback)
     */
    private function loadFromJson(): array
    {
        if (!file_exists(self::FILE_USERS)) {
            return [];
        }
        
        $data = file_get_contents(self::FILE_USERS);
        $users = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($users)) {
            return [];
        }
        
        return $users;
    }

    /**
     * Загрузить всех администраторов
     */
    public function loadAdmins(): array
    {
        // Админы всегда из JSON (или можно создать таблицу admins)
        if (!file_exists(self::FILE_ADMINS)) {
            return [];
        }

        $data = file_get_contents(self::FILE_ADMINS);
        $admins = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($admins)) {
            return [];
        }

        return $admins;
    }

    /**
     * Сохранить всех пользователей (JSON fallback)
     */
    private function saveData(array $users): bool
    {
        $dir = dirname(self::FILE_USERS);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return file_put_contents(self::FILE_USERS, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Найти пользователя по email
     */
    public function findByEmail(string $email): ?array
    {
        // Проверяем среди админов (всегда из JSON)
        $admins = $this->loadAdmins();
        foreach ($admins as $admin) {
            if (strtolower($admin['email']) === strtolower($email)) {
                $admin['is_admin'] = true;
                $admin['is_verified'] = true;
                return $admin;
            }
        }

        // Ищем в БД
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(:email)");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();
                if ($user) {
                    return $user;
                }
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Fallback на JSON
        $users = $this->loadFromJson();
        foreach ($users as $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Найти пользователя по ID
     */
    public function findById(int $id): ?array
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $user = $stmt->fetch();
                if ($user) {
                    return $user;
                }
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Fallback на JSON
        $users = $this->loadFromJson();
        foreach ($users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Проверить, является ли пользователь админом
     */
    public function isAdmin(int $userId): bool
    {
        if ($userId === 0) {
            return true;
        }

        $user = $this->findById($userId);
        if (!$user) return false;

        // Проверяем поле is_admin в БД
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("SELECT is_admin FROM users WHERE id = :id");
                $stmt->execute([':id' => $userId]);
                $result = $stmt->fetch();
                if ($result && $result['is_admin']) {
                    return true;
                }
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Fallback: проверяем по email среди админов
        $admins = $this->loadAdmins();
        foreach ($admins as $admin) {
            if (strtolower($admin['email']) === strtolower($user['email'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Создать нового пользователя
     */
    public function create(string $email, string $password, string $name): ?array
    {
        $verificationCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $verificationExpires = date('Y-m-d H:i:s', time() + 3600);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Пробуем создать в БД
        if ($this->db !== null) {
            try {
                // Проверяем, существует ли пользователь
                $existing = $this->findByEmail($email);
                if ($existing) {
                    if ($existing['is_verified'] ?? false) {
                        return null; // Уже подтверждён
                    }
                    // Обновляем код подтверждения
                    return $this->updateUnverifiedUser($existing['id'], $email, $hashedPassword, $name, $verificationCode, $verificationExpires);
                }

                $stmt = $this->db->prepare("
                    INSERT INTO users (email, password, name, verification_code, verification_expires)
                    VALUES (:email, :password, :name, :code, :expires)
                ");
                $stmt->execute([
                    ':email' => $email,
                    ':password' => $hashedPassword,
                    ':name' => $name,
                    ':code' => $verificationCode,
                    ':expires' => $verificationExpires,
                ]);

                return [
                    'id' => (int) $this->db->lastInsertId(),
                    'email' => $email,
                    'name' => $name,
                    'is_verified' => false,
                    'verification_code' => $verificationCode
                ];
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Fallback на JSON
        return $this->createJson($email, $password, $name, $verificationCode, $verificationExpires);
    }

    /**
     * Обновить неподтверждённого пользователя
     */
    private function updateUnverifiedUser(int $id, string $email, string $password, string $name, string $code, string $expires): ?array
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET password = :password, name = :name, verification_code = :code, verification_expires = :expires
                    WHERE id = :id AND (is_verified = 0 OR is_verified IS NULL)
                ");
                $stmt->execute([
                    ':id' => $id,
                    ':password' => $password,
                    ':name' => $name,
                    ':code' => $code,
                    ':expires' => $expires,
                ]);

                return [
                    'id' => $id,
                    'email' => $email,
                    'name' => $name,
                    'is_verified' => false,
                    'verification_code' => $code
                ];
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Создание пользователя в JSON (fallback)
     */
    private function createJson(string $email, string $password, string $name, string $code, string $expires): ?array
    {
        $users = $this->loadFromJson();

        $existingKey = null;
        foreach ($users as $key => $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            if ($users[$existingKey]['is_verified'] ?? false) {
                return null;
            }

            $users[$existingKey]['password'] = password_hash($password, PASSWORD_DEFAULT);
            $users[$existingKey]['name'] = $name;
            $users[$existingKey]['verification_code'] = $code;
            $users[$existingKey]['verification_expires'] = $expires;

            if ($this->saveData($users)) {
                return [
                    'id' => $users[$existingKey]['id'],
                    'email' => $email,
                    'name' => $name,
                    'is_verified' => false,
                    'verification_code' => $code
                ];
            }

            return null;
        }

        $maxId = 0;
        foreach ($users as $user) {
            if ($user['id'] > $maxId) {
                $maxId = $user['id'];
            }
        }

        $newUser = [
            'id' => $maxId + 1,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'name' => $name,
            'is_verified' => false,
            'verification_code' => $code,
            'verification_expires' => $expires,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $users[] = $newUser;

        if ($this->saveData($users)) {
            unset($newUser['password']);
            return $newUser;
        }

        return null;
    }

    /**
     * Подтвердить email пользователя
     */
    public function verifyEmail(string $email, string $code): bool
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("
                    SELECT * FROM users WHERE LOWER(email) = LOWER(:email)
                ");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();

                if (!$user) {
                    return false;
                }

                if ($user['is_verified']) {
                    return true;
                }

                if ($user['verification_code'] !== $code) {
                    return false;
                }

                $expires = strtotime($user['verification_expires']);
                if ($expires < time()) {
                    return false;
                }

                $updateStmt = $this->db->prepare("
                    UPDATE users 
                    SET is_verified = 1, verification_code = NULL, verification_expires = NULL, verified_at = NOW()
                    WHERE id = :id
                ");
                return $updateStmt->execute([':id' => $user['id']]);
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // JSON fallback
        $users = $this->loadFromJson();
        foreach ($users as &$user) {
            if (strtolower($user['email']) === strtolower($email)) {
                if ($user['is_verified'] ?? false) {
                    return true;
                }
                if (($user['verification_code'] ?? '') !== $code) {
                    return false;
                }
                $expires = strtotime($user['verification_expires'] ?? '');
                if ($expires < time()) {
                    return false;
                }
                $user['is_verified'] = true;
                $user['verification_code'] = null;
                $user['verification_expires'] = null;
                $user['verified_at'] = date('Y-m-d H:i:s');
                return $this->saveData($users);
            }
        }

        return false;
    }

    /**
     * Проверить, подтверждён ли email
     */
    public function isVerified(string $email): bool
    {
        $user = $this->findByEmail($email);
        // MySQL возвращает is_verified как число 1/0, поэтому используем !empty()
        return $user !== null && !empty($user['is_verified']);
    }

    /**
     * Обновить код подтверждения
     */
    public function regenerateVerificationCode(string $email): ?string
    {
        $verificationCode = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $verificationExpires = date('Y-m-d H:i:s', time() + 3600);

        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET verification_code = :code, verification_expires = :expires
                    WHERE LOWER(email) = LOWER(:email) AND (is_verified = 0 OR is_verified IS NULL)
                ");
                $stmt->execute([
                    ':code' => $verificationCode,
                    ':expires' => $verificationExpires,
                    ':email' => $email,
                ]);

                if ($stmt->rowCount() > 0) {
                    return $verificationCode;
                }
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // JSON fallback
        $users = $this->loadFromJson();
        foreach ($users as &$user) {
            if (strtolower($user['email']) === strtolower($email)) {
                if ($user['is_verified'] ?? false) {
                    return null;
                }
                $user['verification_code'] = $verificationCode;
                $user['verification_expires'] = $verificationExpires;
                if ($this->saveData($users)) {
                    return $verificationCode;
                }
            }
        }

        return null;
    }

    /**
     * Проверить пароль
     */
    public function verifyPassword(string $email, string $password): ?array
    {
        // Проверяем среди админов (JSON)
        $admins = $this->loadAdmins();
        foreach ($admins as $admin) {
            if (strtolower($admin['email']) === strtolower($email)) {
                if ($password === $admin['password']) {
                    return [
                        'id' => 0,
                        'email' => $admin['email'],
                        'name' => $admin['name'],
                        'is_admin' => true
                    ];
                }
                return null;
            }
        }

        // Ищем в БД
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(:email)");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    unset($user['password']);
                    return $user;
                }
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // JSON fallback
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return null;
    }

    /**
     * Обновить профиль пользователя
     */
    public function updateProfile(int $userId, array $data): bool
    {
        if ($this->db !== null) {
            try {
                $fields = [];
                $params = [':id' => $userId];

                if (isset($data['name'])) {
                    $fields[] = 'name = :name';
                    $params[':name'] = trim($data['name']);
                }
                if (isset($data['phone'])) {
                    $fields[] = 'phone = :phone';
                    $params[':phone'] = trim($data['phone']);
                }
                if (isset($data['address'])) {
                    $fields[] = 'address = :address';
                    $params[':address'] = trim($data['address']);
                }
                if (isset($data['avatar'])) {
                    $fields[] = 'avatar = :avatar';
                    $params[':avatar'] = $data['avatar'];
                }

                if (empty($fields)) {
                    return false;
                }

                $fields[] = 'updated_at = NOW()';

                $stmt = $this->db->prepare("UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id");
                return $stmt->execute($params);
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // JSON fallback
        $users = $this->loadFromJson();
        foreach ($users as &$user) {
            if ($user['id'] === $userId) {
                if (isset($data['name'])) $user['name'] = trim($data['name']);
                if (isset($data['phone'])) $user['phone'] = trim($data['phone']);
                if (isset($data['address'])) $user['address'] = trim($data['address']);
                if (isset($data['avatar'])) $user['avatar'] = $data['avatar'];
                $user['updated_at'] = date('Y-m-d H:i:s');
                return $this->saveData($users);
            }
        }

        return false;
    }

    /**
     * Получить полный профиль пользователя
     */
    public function getFullProfile(int $userId): ?array
    {
        $user = $this->findById($userId);

        if ($user) {
            return array_merge([
                'phone' => '',
                'address' => '',
                'avatar' => ''
            ], $user);
        }

        return null;
    }

    /**
     * Получить историю заказов пользователя
     */
    public function getOrders(int $userId): array
    {
        $user = $this->findById($userId);
        if (!$user) {
            return [];
        }

        // Пробуем получить из БД
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("
                    SELECT * FROM orders 
                    WHERE user_id = :user_id OR LOWER(email) = LOWER(:email)
                    ORDER BY created_at DESC
                ");
                $stmt->execute([':user_id' => $userId, ':email' => $user['email']]);
                $orders = $stmt->fetchAll();

                // Декодируем JSON-поле items
                foreach ($orders as &$order) {
                    if (isset($order['items'])) {
                        $order['items'] = json_decode($order['items'], true) ?? [];
                    }
                }

                return $orders;
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // JSON fallback
        $ordersFile = __DIR__ . '/../../storage/orders.json';
        if (!file_exists($ordersFile)) {
            return [];
        }

        $content = file_get_contents($ordersFile);
        $allOrders = json_decode($content, true) ?: [];
        
        $userOrders = [];
        foreach ($allOrders as $order) {
            if (isset($order['user_id']) && $order['user_id'] === $userId) {
                $userOrders[] = $order;
                continue;
            }
            if (!isset($order['user_id']) && isset($order['email']) && 
                strtolower($order['email']) === strtolower($user['email'])) {
                $userOrders[] = $order;
            }
        }
        
        usort($userOrders, function($a, $b) {
            return strtotime($b['created_at'] ?? '') - strtotime($a['created_at'] ?? '');
        });
        
        return $userOrders;
    }

    /**
     * Получить всех пользователей (для админки)
     */
    public function getAll(): array
    {
        return $this->loadData();
    }
}
