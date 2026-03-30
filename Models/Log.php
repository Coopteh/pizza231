<?php
namespace Models;

class Log
{
    private string $logFile;
    private const MAX_LOGS = 500;
    
    public function __construct()
    {
        $this->logFile = 'C:/xampp/htdocs/storage/logs.json';
        $dir = dirname($this->logFile);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        if (!file_exists($this->logFile)) file_put_contents($this->logFile, json_encode([]));
    }
    
    public function add(string $action, string $user = 'Система', array $details = []): bool
    {
        $logs = $this->getAll(1000);
        if (count($logs) >= self::MAX_LOGS) {
            $logs = array_slice($logs, -self::MAX_LOGS + 1);
        }
        $newLog = [
            'id' => uniqid('log_'),
            'timestamp' => date('Y-m-d H:i:s'),
            'user' => $user,
            'action' => $action,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'details' => $details
        ];
        $logs[] = $newLog;
        file_put_contents($this->logFile, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return true;
    }
    
    public function getAll(int $limit = 100): array
    {
        if (!file_exists($this->logFile)) return [];
        $data = file_get_contents($this->logFile);
        $logs = json_decode($data, true) ?? [];
        return array_slice(array_reverse($logs), 0, $limit);
    }
    
    public function filter(string $action = '', string $user = '', string $dateFrom = '', int $limit = 100): array
    {
        $logs = $this->getAll(1000);
        if (!empty($action)) $logs = array_filter($logs, fn($l) => stripos($l['action'], $action) !== false);
        if (!empty($user)) $logs = array_filter($logs, fn($l) => stripos($l['user'], $user) !== false);
        if (!empty($dateFrom)) $logs = array_filter($logs, fn($l) => $l['timestamp'] >= $dateFrom);
        return array_slice(array_reverse($logs), 0, $limit);
    }
    
    public function getStats(): array
    {
        $logs = $this->getAll(1000);
        $stats = ['total' => count($logs), 'today' => 0, 'by_action' => [], 'by_user' => []];
        foreach ($logs as $log) {
            if (date('Y-m-d', strtotime($log['timestamp'])) === date('Y-m-d')) $stats['today']++;
            $action = explode(' ', $log['action'])[0];
            $stats['by_action'][$action] = ($stats['by_action'][$action] ?? 0) + 1;
            $stats['by_user'][$log['user']] = ($stats['by_user'][$log['user']] ?? 0) + 1;
        }
        arsort($stats['by_action']);
        arsort($stats['by_user']);
        return $stats;
    }
    
    public function clearOld(int $days = 30): int
    {
        $logs = $this->getAll(10000);
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $filtered = array_filter($logs, fn($l) => $l['timestamp'] >= $cutoff);
        $deleted = count($logs) - count($filtered);
        file_put_contents($this->logFile, json_encode(array_values($filtered), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $deleted;
    }
    
    public function clearAll(): bool
    {
        file_put_contents($this->logFile, json_encode([]));
        return true;
    }
}