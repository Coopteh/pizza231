<?php
namespace Models;

use PDO;
use Database;

class User
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    private function loadUsers(): array
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll();
    }
    
    private function saveUsers(array $users): void
    {
        // Метод больше не нужен для MySQL, оставлен для совместимости
    }
    
    public function create(string $name, string $email, string $passwordHash): ?int
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (name, email, password, role, verified, created_at)
                VALUES (:name, :email, :password, 'user', 0, NOW())
            ");
            
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $passwordHash
            ]);
            
            return (int)$this->db->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Duplicate entry
                return null;
            }
            throw $e;
        }
    }
    
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
    
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        if ($user) {
            unset($user['password']);
        }
        return $user ?: null;
    }
    
    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll();
    }
    
    public function deleteUser(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    public function setRole(int $userId, string $role): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET role = :role WHERE id = :id");
        return $stmt->execute([':role' => $role, ':id' => $userId]);
    }
    
    public function verifyUser(int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET verified = 1 WHERE id = :id");
        return $stmt->execute([':id' => $userId]);
    }
    
    public function updatePhone(int $userId, string $phone): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET phone = :phone WHERE id = :id");
        return $stmt->execute([':phone' => $phone, ':id' => $userId]);
    }
    
    public function updatePassword(int $userId, string $newPasswordHash): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute([':password' => $newPasswordHash, ':id' => $userId]);
    }
    
    public function updateCard(int $userId, string $cardNumber): bool
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $cardLast4 = strlen($cardNumber) >= 4 ? substr($cardNumber, -4) : '';
        $stmt = $this->db->prepare("UPDATE users SET card_last4 = :card WHERE id = :id");
        return $stmt->execute([':card' => $cardLast4, ':id' => $userId]);
    }
    
    public function verifyPassword(int $userId, string $password): bool
    {
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();
        
        if (!$user) return false;
        return password_verify($password, $user['password']);
    }
}