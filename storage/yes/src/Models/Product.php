<?php
namespace App\Models;

require_once __DIR__ . '/../Config/Config.php';
require_once __DIR__ . '/../Database/Database.php';

use App\Config\Config;
use App\Database\Database;
use PDO;

class Product
{
    private ?PDO $db = null;

    public function __construct()
    {
        // Пытаемся подключиться к БД
        if (Config::isDatabaseAvailable()) {
            $this->db = Database::getConnection();
        }
    }

    /**
     * Загрузить все товары (сначала БД, потом JSON fallback)
     */
    public function loadData(): ?array
    {
        // Пробуем получить из БД
        if ($this->db !== null) {
            return $this->loadFromDatabase();
        }

        // Fallback на JSON
        return $this->loadFromJson();
    }

    /**
     * Загрузка из базы данных
     */
    private function loadFromDatabase(): ?array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM products ORDER BY id");
            $products = $stmt->fetchAll();

            if (empty($products)) {
                return null;
            }

            $indexedData = [];
            foreach ($products as $product) {
                $indexedData[$product['id']] = $product;
            }

            return $indexedData;
        } catch (\PDOException $e) {
            // Логируем ошибку и возвращаемся к JSON
            error_log("Database error: " . $e->getMessage());
            return $this->loadFromJson();
        }
    }

    /**
     * Загрузка из JSON файла (fallback)
     */
    private function loadFromJson(): ?array
    {
        if (!file_exists(Config::FILE_PRODUCTS)) {
            return null;
        }

        $data = file_get_contents(Config::FILE_PRODUCTS);
        $arr = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($arr)) {
            return null;
        }

        $indexedData = [];
        foreach ($arr as $item) {
            if (isset($item['id'])) {
                $indexedData[$item['id']] = $item;
            }
        }

        return $indexedData;
    }

    /**
     * Получить товар по ID
     */
    public function findById(int $id): ?array
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $product = $stmt->fetch();
                return $product ?: null;
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Fallback на JSON
        $products = $this->loadFromJson();
        return $products[$id] ?? null;
    }

    /**
     * Получить товары по категории
     */
    public function findByCategory(string $category): array
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("SELECT * FROM products WHERE category = :category ORDER BY id");
                $stmt->execute([':category' => $category]);
                return $stmt->fetchAll();
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Fallback на JSON
        $products = $this->loadFromJson() ?? [];
        return array_filter($products, fn($p) => ($p['category'] ?? '') === $category);
    }

    /**
     * Получить все категории
     */
    public function getCategories(): array
    {
        if ($this->db !== null) {
            try {
                $stmt = $this->db->query("SELECT DISTINCT category FROM products ORDER BY category");
                return array_column($stmt->fetchAll(), 'category');
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Fallback на JSON
        $products = $this->loadFromJson() ?? [];
        return array_values(array_unique(array_column($products, 'category')));
    }

    /**
     * Поиск товаров
     */
    public function search(string $query): array
    {
        $searchTerm = "%{$query}%";

        if ($this->db !== null) {
            try {
                $stmt = $this->db->prepare("
                    SELECT * FROM products 
                    WHERE name LIKE :query OR description LIKE :query OR category LIKE :query
                    ORDER BY id
                ");
                $stmt->execute([':query' => $searchTerm]);
                return $stmt->fetchAll();
            } catch (\PDOException $e) {
                error_log("Database error: " . $e->getMessage());
            }
        }

        // Fallback на JSON
        $products = $this->loadFromJson() ?? [];
        $queryLower = mb_strtolower($query);
        return array_filter($products, fn($p) => 
            mb_strpos(mb_strtolower($p['name'] ?? ''), $queryLower) !== false ||
            mb_strpos(mb_strtolower($p['description'] ?? ''), $queryLower) !== false ||
            mb_strpos(mb_strtolower($p['category'] ?? ''), $queryLower) !== false
        );
    }

    /**
     * Создать товар (только для БД)
     */
    public function create(array $data): ?int
    {
        if ($this->db === null) {
            return null; // JSON mode не поддерживает создание
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO products (name, image, description, price, category)
                VALUES (:name, :image, :description, :price, :category)
            ");
            $stmt->execute([
                ':name' => $data['name'],
                ':image' => $data['image'] ?? '/assets/img/no-image.jpg',
                ':description' => $data['description'] ?? '',
                ':price' => $data['price'],
                ':category' => $data['category'] ?? 'Без категории',
            ]);

            return (int) $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Обновить товар (только для БД)
     */
    public function update(int $id, array $data): bool
    {
        if ($this->db === null) {
            return false;
        }

        try {
            $stmt = $this->db->prepare("
                UPDATE products 
                SET name = :name, image = :image, description = :description, 
                    price = :price, category = :category
                WHERE id = :id
            ");
            return $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':image' => $data['image'] ?? '/assets/img/no-image.jpg',
                ':description' => $data['description'] ?? '',
                ':price' => $data['price'],
                ':category' => $data['category'] ?? 'Без категории',
            ]);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Удалить товар (только для БД)
     */
    public function delete(int $id): bool
    {
        if ($this->db === null) {
            return false;
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}