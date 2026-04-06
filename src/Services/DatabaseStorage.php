<?php
namespace App\Services;

class DatabaseStorage implements IStorage
{
    public function loadData(string $name): ?array
    {
        // TODO: Реализовать загрузку из базы данных по ключу $name
        // Например: SELECT * FROM orders WHERE resource_name = '$name';
        return [];
    }

    public function saveData(string $name, array $data): bool
    {
        // TODO: Реализовать сохранение в базу данных по ключу $name
        // Например: INSERT INTO orders (resource_name, data) VALUES ('$name', '$data');
        return true;
    }
}