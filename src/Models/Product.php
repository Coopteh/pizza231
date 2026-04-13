<?php
namespace App\Models;

use App\Services\ILoadStorage;

class Product
{
    private ILoadStorage $dataStorage;
    private string $nameResourceLoad;
    
    // Внедряем зависимость через конструктор
    public function __construct(ILoadStorage $service, string $nameLoad)
    {
        $this->dataStorage = $service;
        $this->nameResourceLoad = $nameLoad;
    }

    public function loadData(): ?array {
        return $this->dataStorage->loadData( $this->nameResourceLoad ); 
    }
}
