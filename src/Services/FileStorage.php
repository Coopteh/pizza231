<?php 
namespace App\Services;

class FileStorage implements ILoadStorage, ISaveStorage
    // public function loadData(string $name): ?array
    // {   
    //     $data = file_get_contents($name);
    //     if ($data) {
    //         $arr = json_decode($data, true);
    //         return $arr;
    //     }
    //     return null;
    // }

    // public function saveData(string $name, array $arr): bool
    // {
    //     $handle = fopen($name, "r");
    //     if (filesize($name) > 0){ 
    //         $data = fread($handle, filesize($name)); 
    //         $allRecords = json_decode($data, true); 
    //     } else {
    //         $allRecords = [];
    //     }
    //     fclose($handle);
        
    //     $allRecords[]= $arr;
    //     $json = json_encode($allRecords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    //     $handle = fopen($name, "w");
    //     fwrite($handle, $json);
    //     fclose($handle);

    //     return true;
    // }
    {
        public function loadData($nameFile): ?array {
            if (!file_exists($nameFile)) {
                return [];
            }
            $content = file_get_contents($nameFile);
            $data = json_decode($content, true);
            return is_array($data) ? $data : [];
        }
        
        public function saveData($nameFile, $data): bool {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return file_put_contents($nameFile, $json) !== false;
        }
    }