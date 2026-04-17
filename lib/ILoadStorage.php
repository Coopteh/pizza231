<?php
// Lib/ILoadStorage.php
namespace Lib;

/**
 * Интерфейс для операций чтения данных
 */
interface ILoadStorage
{
    public function loadData(string $resource): ?array;
}