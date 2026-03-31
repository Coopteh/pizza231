<?php
namespace App\Services;

interface IStorage {
    public function loadData(string $name): ?array;
    public function saveData(string $name, array $arr): bool;
}