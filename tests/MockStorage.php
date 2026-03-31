<?php
namespace Test;

use App\Services\IStorage;

class MockStorage implements IStorage 
{
    public function loadData(string $name): ?array
    {
        return [];
    }
    public function saveData(string $name, array $data): bool
    {
        return true;
    }   
}