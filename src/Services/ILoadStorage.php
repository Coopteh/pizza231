<?php

namespace App\Services;

interface ILoadStorage
{
    /**
     * Загружает данные из хранилища
     * @param string $name Имя ресурса (путь к файлу или имя таблицы)
     * @return array|null Массив данных или null
     */
    public function loadData(string $name): ?array;
}