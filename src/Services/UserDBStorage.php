<?php
namespace App\Services;

use PDO;

class UserDBStorage extends DBStorage implements ISaveStorage
{
    public function saveData(string $name, array $data): bool
    {
        $sql = "INSERT INTO `$name`
        (`username`, `email`, `password`, `token`) 
        VALUES (:name, :email, :pass, :token)";

        $sth = $this->connection->prepare($sql);
// var_dump( $data );
// exit();
        $result= $sth->execute( [
            'name' => $data['username'],
            'email' => $data['email'],
            'pass' => $data['password'],
            'token' => $data['token']
        ] );
        return $result;
    }

    public function uniqueEmail($email): bool 
    {
        $stmt = $this->connection->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) 
            return false;
        return true;
    }

    public function saveVerified($token): bool
    {
        $stmt = $this->connection->prepare(
            "SELECT id FROM users WHERE token = ? 
            AND is_verified = 0");
        $stmt->execute([$token]);

        if ($stmt->rowCount() > 0) {

            $user = $stmt->fetch();
            $update = $this->connection->prepare(
                "UPDATE users SET is_verified = 1, 
                token = '' 
                WHERE id = ?");
            $update->execute([$user['id']]);

            return true;
        }
        return false;
    }

    /**
     * Аутентификация пользователя
     */
    public function loginUser($username, $password) {   
        // Поиск пользователя
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE username = ? OR email = ?"
        );
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        // проверка пароля
        if ($user === false) 
            return false;
        if (!password_verify($password, $user['password']))
            return false;
        
        // Установка переменных сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        return true;
    }

    public function getUserData($user_id) {
        $stmt = $this->connection->prepare(
            "SELECT fio, email, address, phone FROM users WHERE id=?"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function updateProfile($data):bool {
        global $user_id;
        try {
            $update = $this->connection->prepare(
                "UPDATE users SET address= ?, phone= ?, fio= ?
                WHERE id = ?");

            $update->execute([
                $data['address'],
                $data['phone'],
                $data['fio'],
                $user_id
            ]);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function getDataHistory(int $idUser): ?array 
    {
        $stmt = $this->connection->prepare(
            "SELECT id, created, all_sum
            FROM orders WHERE user_id = :userId ");
        $stmt->execute(["userId" => $idUser]);

        if ($stmt->rowCount() > 0) {
            $orders = $stmt->fetchAll();
            return $orders;
        }
        return null;
    }
}
