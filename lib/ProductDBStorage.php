<?php
// Lib/ProductDBStorage.php
namespace Lib;

use PDO;

class ProductDBStorage extends DBStorage implements ILoadStorage
{
    public function loadData(string $resource): ?array
    {
        $tableName = $this->config['table_products'] ?? 'products';
        try {
            $stmt = $this->connection->query("SELECT * FROM {$tableName} ORDER BY created DESC");
            $rows = $stmt->fetchAll();
            if (empty($rows)) return null;

            $result = [];
            foreach ($rows as $row) {
                $priceNumeric = (float)($row['price'] ?? 0);
                $priceFormatted = number_format($priceNumeric, 0, '.', ' ') . ' ₽';
                $id = (int)$row['id'];

                $result[$id] = [
                    'id' => $id,
                    'title' => ['ru' => $row['name'] ?? '', 'en' => $row['name'] ?? ''],
                    'icon' => $row['image'] ?? '??',
                    'description' => ['ru' => $row['description'] ?? '', 'en' => $row['description'] ?? ''],
                    'features' => [],
                    'price_from' => $priceFormatted,
                    'price_numeric' => $priceNumeric,
                    'duration' => ['ru' => '', 'en' => ''],
                    'duration_weeks' => 0,
                    'level' => ['ru' => '', 'en' => ''],
                    'format' => [['ru' => 'Онлайн', 'en' => 'Online']],
                    'certificate' => true,
                    'job_assistance' => false,
                    'created_at' => $row['created'] ?? date('Y-m-d H:i:s'),
                    'updated_at' => $row['updated'] ?? date('Y-m-d H:i:s')
                ];
            }
            return $result;
        } catch (\PDOException $e) {
            Logger::error("Ошибка загрузки продуктов из БД", ['error' => $e->getMessage()]);
            return null;
        }
    }
}