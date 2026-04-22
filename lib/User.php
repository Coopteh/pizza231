<?php

// 🔐 ОБНОВЛЕНО: Добавлена поддержка кода для смены пароля

namespace Lib;

use Lib\Logger; // 📝 LOGGER: Добавлен импорт

class User
{
    private static array $users = [];
    private static string $usersFile = '';

    public static function init(): void
    {
        self::$usersFile = __DIR__ . '/../data/users.php';
        if (file_exists(self::$usersFile)) {
            self::$users = require self::$usersFile;
        }
    }

    public static function getAllUsers(): array
    {
        if (empty(self::$users)) {
            self::init();
        }
        return self::$users;
    }

    public static function getUserById(int $id): ?array
    {
        if (empty(self::$users)) {
            self::init();
        }
        return self::$users[$id] ?? null;
    }

    public static function getUserByEmail(string $email): ?array
    {
        if (empty(self::$users)) {
            self::init();
        }
        foreach (self::$users as $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                return $user;
            }
        }
        return null;
    }

    public static function generateVerificationCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public static function createUser(array $data): int
    {
        if (empty(self::$users)) {
            self::init();
        }

        $newId = !empty(self::$users) ? max(array_keys(self::$users)) + 1 : 1;

        $user = [
            'id' => $newId,
            'email' => strtolower(trim($data['email'])),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'name' => trim($data['name']),
            'phone' => trim($data['phone'] ?? ''),
            'role' => 'user',
            'verified' => false,
            'verification_code' => self::generateVerificationCode(),
            'code_expires' => date('Y-m-d H:i:s', strtotime('+15 minutes')),
            'verification_token' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'last_login' => null,
            'password_change_code' => null,
            'password_change_code_expires' => null
        ];

        self::$users[$newId] = $user;
        self::saveUsers();

        return $newId;
    }

    public static function verifyUserByCode(string $email, string $code): bool
    {
        if (empty(self::$users)) {
            self::init();
        }

        foreach (self::$users as $id => $user) {
            if (strtolower($user['email']) === strtolower($email) &&
                $user['verification_code'] === $code &&
                isset($user['code_expires']) &&
                strtotime($user['code_expires']) > time()) {

                self::$users[$id]['verified'] = true;
                self::$users[$id]['verification_code'] = null;
                self::$users[$id]['code_expires'] = null;
                self::saveUsers();

                return true;
            }
        }

        return false;
    }

    public static function isCodeExpired(string $email): bool
    {
        $user = self::getUserByEmail($email);
        if (!$user || empty($user['code_expires'])) {
            return true;
        }
        return strtotime($user['code_expires']) <= time();
    }

    public static function resendVerificationCode(string $email): ?string
    {
        $user = self::getUserByEmail($email);
        if (!$user || $user['verified']) {
            return null;
        }

        $newCode = self::generateVerificationCode();
        self::$users[$user['id']]['verification_code'] = $newCode;
        self::$users[$user['id']]['code_expires'] = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        self::saveUsers();

        return $newCode;
    }

    // 🔐 ДОБАВЛЕНО: Генерация кода для смены пароля
    public static function generatePasswordChangeCode(int $userId): ?string
    {
        if (empty(self::$users)) {
            self::init();
        }
        if (!isset(self::$users[$userId])) {
            return null;
        }

        $code = self::generateVerificationCode();
        self::$users[$userId]['password_change_code'] = $code;
        self::$users[$userId]['password_change_code_expires'] =
            date('Y-m-d H:i:s', strtotime('+10 minutes'));
        self::saveUsers();

        return $code;
    }

    // 🔐 ДОБАВЛЕНО: Проверка кода смены пароля
    public static function verifyPasswordChangeCode(int $userId, string $code): bool
    {
        if (empty(self::$users)) {
            self::init();
        }
        if (!isset(self::$users[$userId])) {
            return false;
        }

        $user = self::$users[$userId];

        if (isset($user['password_change_code']) &&
            $user['password_change_code'] === $code &&
            isset($user['password_change_code_expires']) &&
            strtotime($user['password_change_code_expires']) > time()) {

            self::$users[$userId]['password_change_code'] = null;
            self::$users[$userId]['password_change_code_expires'] = null;
            self::saveUsers();

            return true;
        }

        return false;
    }

    // 🔐 ДОБАВЛЕНО: Очистка кода смены пароля
    public static function clearPasswordChangeCode(int $userId): void
    {
        if (empty(self::$users)) {
            self::init();
        }
        if (isset(self::$users[$userId])) {
            self::$users[$userId]['password_change_code'] = null;
            self::$users[$userId]['password_change_code_expires'] = null;
            self::saveUsers();
        }
    }

    public static function updateUser(int $id, array $data): bool
    {
        if (empty(self::$users)) {
            self::init();
        }
        if (!isset(self::$users[$id])) {
            return false;
        }

        foreach ($data as $key => $value) {
            if (isset(self::$users[$id][$key]) && $key !== 'id') {
                self::$users[$id][$key] = $value;
            }
        }

        self::saveUsers();
        return true;
    }

    public static function updatePassword(int $id, string $newPassword): bool
    {
        if (empty(self::$users)) {
            self::init();
        }
        if (!isset(self::$users[$id])) {
            return false;
        }

        self::$users[$id]['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        self::saveUsers();

        return true;
    }

    public static function validateLogin(string $email, string $password): ?array
    {
        $user = self::getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if (!$user['verified']) {
                return ['error' => 'email_not_verified', 'user' => $user];
            }
            return $user;
        }

        return null;
    }

    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
    }

    public static function getCurrentUser(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        return self::getUserById($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        $user = self::getCurrentUser();
        return $user && ($user['role'] === 'admin');
    }

    public static function login(int $userId): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = self::getUserById($userId);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
            self::updateUser($userId, ['last_login' => date('Y-m-d H:i:s')]);
        }
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
    }

    public static function getUserOrders(int $userId): array
    {
        $ordersFile = __DIR__ . '/../data/orders.json';
        if (!file_exists($ordersFile)) {
            return [];
        }

        $orders = json_decode(file_get_contents($ordersFile), true) ?? [];

        $userOrders = array_filter(
            $orders,
            fn ($o) =>
            isset($o['user_id']) && $o['user_id'] === $userId
        );

        usort(
            $userOrders,
            fn ($a, $b) =>
            strtotime($b['created_at']) - strtotime($a['created_at'])
        );

        return array_values($userOrders);
    }

    public static function linkOrderToUser(int $userId, string $orderId): bool
    {
        $ordersFile = __DIR__ . '/../data/orders.json';
        if (!file_exists($ordersFile)) {
            return false;
        }

        $orders = json_decode(file_get_contents($ordersFile), true) ?? [];
        $user = self::getUserById($userId);

        foreach ($orders as $key => $order) {
            if ($order['id'] === $orderId) {
                $orders[$key]['user_id'] = $userId;
                $orders[$key]['user_email'] = $user['email'];
                file_put_contents(
                    $ordersFile,
                    json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                );
                return true;
            }
        }

        return false;
    }

    public static function generateResetToken(int $userId): ?string
    {
        if (empty(self::$users)) {
            self::init();
        }
        if (!isset(self::$users[$userId])) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        self::$users[$userId]['verification_token'] = $token;
        self::$users[$userId]['token_expires'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
        self::saveUsers();

        return $token;
    }

    public static function validateResetToken(string $token): ?int
    {
        if (empty(self::$users)) {
            self::init();
        }

        foreach (self::$users as $id => $user) {
            if ($user['verification_token'] === $token &&
                isset($user['token_expires']) &&
                strtotime($user['token_expires']) > time()) {
                return $id;
            }
        }

        return null;
    }

    private static function saveUsers(): void
    {
        try {
            $content = "<?php\n// data/users.php\nreturn " . var_export(self::$users, true) . ";";
            file_put_contents(self::$usersFile, $content);

            // 📝 LOGGER: Логирование сохранения пользователей (debug)
            Logger::debug("Файл пользователей сохранён", ['file' => self::$usersFile]);
        } catch (\Exception $e) {
            // 📝 LOGGER: Логирование ошибки сохранения
            Logger::error("Ошибка сохранения пользователей", [
                'file' => self::$usersFile,
                'exception' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
