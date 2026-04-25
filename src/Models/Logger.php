<?php
namespace App\Models;

/**
 * Класс для логирования ошибок в файл
 */
class Logger
{
    private static string $logDir = __DIR__ . '/../../storage/logs';
    private static string $logFile = 'errors.log';
    
    /**
     * Инициализировать директорию для логов
     */
    private static function init(): void
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }
    
    /**
     * Записать сообщение об ошибке
     */
    public static function error(string $message, array $context = []): void
    {
        self::init();
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logMessage = "[{$timestamp}] ERROR: {$message}{$contextStr}\n";
        
        $filePath = self::$logDir . '/' . self::$logFile;
        file_put_contents($filePath, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Записать информационное сообщение
     */
    public static function info(string $message, array $context = []): void
    {
        self::init();
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logMessage = "[{$timestamp}] INFO: {$message}{$contextStr}\n";
        
        $filePath = self::$logDir . '/' . self::$logFile;
        file_put_contents($filePath, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Получить все ошибки из лога
     */
    public static function getErrors(int $limit = 100): array
    {
        self::init();
        
        $filePath = self::$logDir . '/' . self::$logFile;
        
        if (!file_exists($filePath)) {
            return [];
        }
        
        $content = file_get_contents($filePath);
        $lines = array_filter(explode("\n", trim($content)));
        
        // Переворачиваем, чтобы новые были сверху
        $lines = array_reverse($lines);
        
        $errors = [];
        foreach (array_slice($lines, 0, $limit) as $line) {
            if (empty(trim($line))) continue;
            
            // Парсим строку лога с контекстом
            // Формат: [timestamp] LEVEL: message | Context: {...}
            if (preg_match('/^\[(.*?)\] (\w+): (.*?)(?:\s*\|\s*Context:\s*(.*))?$/', $line, $matches)) {
                $timestamp = $matches[1];
                $level = $matches[2];
                $message = $matches[3];
                $contextJson = $matches[4] ?? '';
                
                $context = [];
                if (!empty($contextJson)) {
                    $decoded = json_decode($contextJson, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $context = $decoded;
                    }
                }
                
                $errors[] = [
                    'timestamp' => $timestamp,
                    'level' => $level,
                    'message' => $message,
                    'context' => $context
                ];
            } else {
                $errors[] = [
                    'timestamp' => '',
                    'level' => 'UNKNOWN',
                    'message' => $line,
                    'context' => []
                ];
            }
        }
        
        return $errors;
    }
    
    /**
     * Получить количество ошибок
     */
    public static function getErrorCount(): int
    {
        $filePath = self::$logDir . '/' . self::$logFile;
        
        if (!file_exists($filePath)) {
            return 0;
        }
        
        $content = file_get_contents($filePath);
        $lines = array_filter(explode("\n", trim($content)));
        
        return count($lines);
    }
    
    /**
     * Очистить лог
     */
    public static function clear(): void
    {
        self::init();
        
        $filePath = self::$logDir . '/' . self::$logFile;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    /**
     * Получить путь к файлу лога
     */
    public static function getLogPath(): string
    {
        return self::$logDir . '/' . self::$logFile;
    }
}
