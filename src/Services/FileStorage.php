<?php
namespace App\Services;

class FileStorage implements IStorage
{
    public function loadData(string $name): ?array
    {
        if (!file_exists($name)) {
            return null;
        }
        $json = file_get_contents($name);
        return json_decode($json, true) ?: null;
    }

    public function saveData(string $name, array $data): bool
    {
        // Обеспечиваем существование директории
        $dir = dirname($name);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        // Читаем существующие записи (если есть)
        $allRecords = [];
        if (file_exists($name) && filesize($name) > 0) {
            $json = file_get_contents($name);
            $allRecords = json_decode($json, true) ?: [];
        }
        
        // Добавляем новую запись
        $allRecords[] = $data;
        
        $json = json_encode($allRecords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return file_put_contents($name, $json) !== false;
    }
}