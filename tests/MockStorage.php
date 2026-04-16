<?php
namespace Test;

use App\Services\ISaveStorage;

class MockStorage implements ISaveStorage
{
    public function saveData(string $name, array $arr): bool
    {
        return true;
    }
}