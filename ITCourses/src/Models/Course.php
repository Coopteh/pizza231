<?php
namespace App\Models;
use App\Models\Database;

class Course {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM courses ORDER BY id");
        return $stmt->fetchAll();
    }

    public function getById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}