<?php
namespace Models;

class User
{
    private string $dbFile;
    
    public function __construct()
    {
        $this->dbFile = 'C:/xampp/htdocs/Storage/users.json';
        $dir = dirname($this->dbFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
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
            'created_at' => date('Y-m-d H:i:s'),
            'verified' => false,
            'role' => 'user'
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
    
    public function getAllUsers(): array
    {
        return $this->loadUsers();
    }
    
    public function deleteUser(int $id): bool
    {
        $users = $this->loadUsers();
        foreach ($users as $key => $user) {
            if ($user['id'] === $id) {
                unset($users[$key]);
                $this->saveUsers(array_values($users));
                return true;
            }
        }
        return false;
    }
    
    public function setRole(int $userId, string $role): bool
    {
        $users = $this->loadUsers();
        foreach ($users as &$user) {
            if ($user['id'] === $userId) {
                $user['role'] = $role;
                $this->saveUsers($users);
                return true;
            }
        }
        return false;
    }
    
    public function verifyUser(int $userId): bool
    {
        $users = $this->loadUsers();
        foreach ($users as &$user) {
            if ($user['id'] === $userId) {
                $user['verified'] = true;
                $this->saveUsers($users);
                return true;
            }
        }
        return false;
    }
    
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