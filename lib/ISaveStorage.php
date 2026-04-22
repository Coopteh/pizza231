<?php
// Lib/ISaveStorage.php
namespace Lib;

/**
 * Интерфейс для операций записи данных
 */
interface ISaveStorage
{
    public function saveData(string $resource, array $data): bool;
}