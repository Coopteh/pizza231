<?php

namespace App\Services;

interface ISaveStorage
{
    /**
     * Сохраняет данные в хранилище
     * @param string $name Имя ресурса
     * @param array $data Данные для сохранения
     * @return bool true при успехе
     */
    public function saveData(string $name, array $data): bool;
}