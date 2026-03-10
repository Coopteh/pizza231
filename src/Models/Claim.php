<?php
namespace App\Models;

class Claim
{
    private $filePath;
    
    public function __construct()
    {
        $this->filePath = __DIR__ . '/../../Storage/claims.json';
        
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }
    
    public function create(array $data): ?int
    {
        try {
            $claims = json_decode(file_get_contents($this->filePath), true) ?: [];
            
            $newId = count($claims) > 0 ? max(array_column($claims, 'id')) + 1 : 1;
            $data['id'] = $newId;
            
            $claims[] = $data;
            file_put_contents($this->filePath, json_encode($claims, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            return $newId;
        } catch (\Exception $e) {
            error_log("Claim create error: " . $e->getMessage());
            return null;
        }
    }
    
    public function getAll(): array
    {
        return json_decode(file_get_contents($this->filePath), true) ?: [];
    }
}