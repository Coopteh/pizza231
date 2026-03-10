<?php
namespace App\Models;

class Quote
{
    private $filePath;
    
    public function __construct()
    {
        $this->filePath = __DIR__ . '/../../Storage/quotes.json';
        
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }
    
    public function create(array $data): ?int
    {
        try {
            $quotes = json_decode(file_get_contents($this->filePath), true) ?: [];
            
            $newId = count($quotes) > 0 ? max(array_column($quotes, 'id')) + 1 : 1;
            $data['id'] = $newId;
            $data['status'] = 'new';
            
            $quotes[] = $data;
            file_put_contents($this->filePath, json_encode($quotes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            return $newId;
        } catch (\Exception $e) {
            error_log("Quote create error: " . $e->getMessage());
            return null;
        }
    }
    
    public function getAll(): array
    {
        return json_decode(file_get_contents($this->filePath), true) ?: [];
    }
}