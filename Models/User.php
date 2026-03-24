<?php
namespace Models;

class User
{
    private string $dbFile;
    
    public function __construct()
    {
        $this->dbFile = __DIR__ . '/../Storage/users.json';
        if (!file_exists($this->dbFile)) {
            file_put_contents($this->dbFile, json_encode([]));
        }
    }
    
    private function loadUsers(): array
    {
        $content = file_get_contents($this->dbFile);
        return json_decode($content, true) ?? [];
    }
    
    private function saveUsers(array $users): void
    {
        file_put_contents($this->dbFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    public function create(string $name, string $email, string $passwordHash): ?int
    {
        $users = $this->loadUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) return null;
        }
        $newUser = [
            'id' => count($users) + 1,
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
            'phone' => '',
            'card_last4' => '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $users[] = $newUser;
        $this->saveUsers($users);
        return $newUser['id'];
    }
    
    public function findByEmail(string $email): ?array
    {
        $users = $this->loadUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) return $user;
        }
        return null;
    }
    
    public function findById(int $id): ?array
    {
        $users = $this->loadUsers();
        foreach ($users as $user) {
            if ($user['id'] === $id) {
                unset($user['password']);
                return $user;
            }
        }
        return null;
    }
    
    // 🔧 Методы для профиля
    public function updatePhone(int $userId, string $phone): bool
    {
        $users = $this->loadUsers();
        foreach ($users as &$user) {
            if ($user['id'] === $userId) {
                $user['phone'] = $phone;
                $this->saveUsers($users);
                return true;
            }
        }
        return false;
    }
    
    public function updatePassword(int $userId, string $newPasswordHash): bool
    {
        $users = $this->loadUsers();
        foreach ($users as &$user) {
            if ($user['id'] === $userId) {
                $user['password'] = $newPasswordHash;
                $this->saveUsers($users);
                return true;
            }
        }
        return false;
    }
    
    public function updateCard(int $userId, string $cardNumber): bool
    {
        $users = $this->loadUsers();
        foreach ($users as &$user) {
            if ($user['id'] === $userId) {
                // Сохраняем только последние 4 цифры
                $cardNumber = preg_replace('/\D/', '', $cardNumber);
                $user['card_last4'] = strlen($cardNumber) >= 4 ? substr($cardNumber, -4) : '';
                $this->saveUsers($users);
                return true;
            }
        }
        return false;
    }
    
    public function verifyPassword(int $userId, string $password): bool
    {
        $users = $this->loadUsers();
        foreach ($users as $user) {
            if ($user['id'] === $userId) {
                return password_verify($password, $user['password']);
            }
        }
        return false;
    }
}