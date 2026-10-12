<?php
namespace App\Services;

use PDO;
use PDOException;
use DateTime;

class UserDBStorage implements ILoadStorage, ISaveStorage
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            \App\Config\Config::MYSQL_DSN,
            \App\Config\Config::MYSQL_USER,
            \App\Config\Config::MYSQL_PASSWORD,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function loadData(string $name): ?array
    {
        $parts = explode(',', $name);
        $resource = $parts[0];
        if ($resource !== 'users') return null;
        
        if (count($parts) > 1) {
            $email = $parts[1];
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? [$result] : null;
        }
        
        $stmt = $this->pdo->query("SELECT id, email, username, fio, phone, created_at FROM users");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function saveData(string $name, array $userData): bool
    {
        if ($name !== 'users') return false;
        try {
            $check = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $check->execute([$userData['email']]);
            if ($check->fetch()) return false;

            // ✅ Исправлено: token → verification_token
            $stmt = $this->pdo->prepare("
                INSERT INTO users (username, email, password, verification_token, is_verified, created_at)
                VALUES (:username, :email, :password, :verification_token, :is_verified, NOW())
            ");
            $stmt->execute([
                ':username' => $userData['username'],
                ':email' => $userData['email'],
                ':password' => $userData['password'],
                ':verification_token' => $userData['verification_token'],
                ':is_verified' => $userData['is_verified'] ?? 0
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("UserDBStorage save error: " . $e->getMessage());
            return false;
        }
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, email, username, fio, phone, created_at, last_login FROM users WHERE id = ?");
        $stmt->execute([$id]); 
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateLastLogin(int $userId): bool
    {
         try {
             $stmt = $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
             return $stmt->execute([$userId]); 
         } catch (PDOException $e) {
             error_log("UserDBStorage updateLastLogin error: " . $e->getMessage());
             return false;
         }
     }
 
     public function getUserOrders(int $userId): array
     {
         try {
              $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
              $stmt->execute([$userId]);
              $orders = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
              
              foreach ($orders as &$order) {
                  if (isset($order['id'])) {
                      $itemStmt = $this->pdo->prepare("SELECT product_name, price, quantity, subtotal FROM order_item WHERE order_id = ?");
                      $itemStmt->execute([$order['id']]);
                      $order['items'] = $itemStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
                  } else {
                      $order['items'] = [];
                  }
              }
              return $orders;
          } catch (PDOException $e) {
              error_log("UserDBStorage getUserOrders error: " . $e->getMessage());
              return [];
          }
      }
 
      public function verifyToken(string $token): array
      {
          try {
              $stmt = $this->pdo->prepare("
                  SELECT id, verification_token, is_verified, created_at 
                  FROM users 
                  WHERE verification_token = :token AND is_verified = 0
                  LIMIT 1
              ");
              $stmt->execute([':token' => $token]);
              
              if (!$user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  return ['success' => false, 'message' => 'Токен не найден или аккаунт уже подтвержден.'];
              }
      
              if (!$createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $user['created_at'])) {
                  return ['success' => false, 'message' => 'Неверный формат даты в базе.'];
              }
                  
              $now = new DateTime();
              if ($now->getTimestamp() - $createdAt->getTimestamp() > 86400) {
                  return ['success' => false, 'message' => 'Срок действия ссылки истёк. Пожалуйста, зарегистрируйтесь заново.'];
              }
      
              $updateStmt = $this->pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = :id");
              if ($updateStmt->execute([':id' => (int)$user['id']])) {
                  return ['success' => true, 'message' => 'Email успешно подтверждён! Теперь вы можете войти.'];
              } else {
                  return ['success' => false, 'message' => 'Произошла ошибка при подтверждении. Попробуйте позже.'];
              }
      
          } catch (\Throwable $e) {
              error_log("UserDBStorage verifyToken error: " . $e->getMessage());
              return ['success' => false, 'message' => 'Произошла внутренняя ошибка.'];
          }
      }
 
      public function load(string $name): ?array { return $this->loadData($name); }
      public function save(string $name, array $data): bool { return $this->saveData($name, $data); }
}