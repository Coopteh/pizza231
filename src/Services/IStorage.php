<?php
namespace App\Services;

interface IStorage
{
    /**
     * Загружает данные из хранилища по имени ресурса.
     * 
     * @param string $name Имя файла или идентификатор ресурса.
     * @return array|null Массив данных или null, если данных нет.
     */
    public function loadData(string $name): ?array;

    /**
     * Сохраняет данные в хранилище.
     * 
     * @param string $name Имя файла или идентификатор ресурса.
     * @param array $data Данные для сохранения.
     * @return bool Результат операции.
     */
    public function saveData(string $name, array $data): bool;
}