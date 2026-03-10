<?php
namespace App\Models;

class Policy
{
    private static $policies = [
        ['id' => 1, 'name' => 'Автострахование КАСКО', 'description' => 'Полная защита вашего автомобиля', 'price' => 15000.00],
        ['id' => 2, 'name' => 'Страхование имущества', 'description' => 'Защита недвижимости и домашнего имущества', 'price' => 5000.00],
        ['id' => 3, 'name' => 'Медицинское страхование', 'description' => 'ДМС для вас и вашей семьи', 'price' => 12000.00],
        ['id' => 4, 'name' => 'Страхование жизни', 'description' => 'Финансовая защита ваших близких', 'price' => 8000.00],
        ['id' => 5, 'name' => 'Страхование путешествий', 'description' => 'Защита во время поездок', 'price' => 3000.00],
    ];
    
    public function getAll(): array
    {
        return self::$policies;
    }
    
    public function findById(int $id): ?array
    {
        foreach (self::$policies as $policy) {
            if ($policy['id'] === $id) {
                return $policy;
            }
        }
        return null;
    }
}