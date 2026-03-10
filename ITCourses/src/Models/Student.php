<?php
namespace App\Models;
use App\Models\Database;

class Student {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(array $data): bool {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO students (name, email, phone, course_id, created_at) 
                 VALUES (:name, :email, :phone, :course_id, NOW())"
            );
            return $stmt->execute([
                ':name' => htmlspecialchars($data['name']),
                ':email' => htmlspecialchars($data['email']),
                ':phone' => htmlspecialchars($data['phone']),
                ':course_id' => (int)$data['course_id']
            ]);
        } catch (\PDOException $e) {
            error_log("Student create error: " . $e->getMessage());
            return false;
        }
    }
}