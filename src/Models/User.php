<?php
namespace App\Models;

use App\Config\Config;
use App\Services\UserDBStorage;
use App\Services\ISaveStorage;

class User
{
    private ISaveStorage $dataStorage;
    private string $nameResourceSave;
    
    // Внедряем зависимость через конструктор
    public function __construct(ISaveStorage $service, string $nameSave)
    {
        $this->dataStorage = $service;
        $this->nameResourceSave = $nameSave;
    }

    public function saveData($arr): bool {
        return $this->dataStorage->saveData( $this->nameResourceSave, $arr ); 
    }

}
