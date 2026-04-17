<?php
namespace Models;

use PDO;
use Database;

class Log
{
    private PDO $db;
    private const MAX_LOGS = 500;
    
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
    
    public function add(string $action, string $user = 'Система', array $details = []): bool
    {
        try {
            // Удаляем старые логи если превышен лимит
            $stmt = $this->db->query("SELECT COUNT(*) as cnt FROM logs");
            $count = $stmt->fetch()['cnt'];
            
            if ($count >= self::MAX_LOGS) {
                $toDelete = $count - self::MAX_LOGS + 1;
                $this->db->exec("DELETE FROM logs ORDER BY timestamp ASC LIMIT {$toDelete}");
            }
            
            $stmt = $this->db->prepare("
                INSERT INTO logs (id, timestamp, user, action, ip, details)
                VALUES (:id, :timestamp, :user, :action, :ip, :details)
            ");
            
            return $stmt->execute([
                ':id' => 'log_' . uniqid(),
                ':timestamp' => date('Y-m-d H:i:s'),
                ':user' => $user,
                ':action' => $action,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                ':details' => json_encode($details)
            ]);
        } catch (\PDOException $e) {
            error_log("Ошибка логирования: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAll(int $limit = 100): array
    {
        $stmt = $this->db->prepare("SELECT * FROM logs ORDER BY timestamp DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $logs = $stmt->fetchAll();
        
        // Декодируем details
        foreach ($logs as &$log) {
            if (isset($log['details']) && is_string($log['details'])) {
                $log['details'] = json_decode($log['details'], true) ?? [];
            }
        }
        
        return $logs;
    }
    
    public function filter(string $action = '', string $user = '', string $dateFrom = '', int $limit = 100): array
    {
        $sql = "SELECT * FROM logs WHERE 1=1";
        $params = [];
        
        if (!empty($action)) {
            $sql .= " AND action LIKE :action";
            $params[':action'] = "%{$action}%";
        }
        if (!empty($user)) {
            $sql .= " AND user LIKE :user";
            $params[':user'] = "%{$user}%";
        }
        if (!empty($dateFrom)) {
            $sql .= " AND timestamp >= :dateFrom";
            $params[':dateFrom'] = $dateFrom;
        }
        
        $sql .= " ORDER BY timestamp DESC LIMIT :limit";
        $params[':limit'] = $limit;
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();
        
        $logs = $stmt->fetchAll();
        
        foreach ($logs as &$log) {
            if (isset($log['details']) && is_string($log['details'])) {
                $log['details'] = json_decode($log['details'], true) ?? [];
            }
        }
        
        return $logs;
    }
    
    public function getStats(): array
    {
        $logs = $this->getAll(1000);
        $stats = ['total' => count($logs), 'today' => 0, 'by_action' => [], 'by_user' => []];
        
        $today = date('Y-m-d');
        foreach ($logs as $log) {
            if (date('Y-m-d', strtotime($log['timestamp'])) === $today) {
                $stats['today']++;
            }
            
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
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $stmt = $this->db->prepare("DELETE FROM logs WHERE timestamp < :cutoff");
        $stmt->execute([':cutoff' => $cutoff]);
        return $stmt->rowCount();
    }
    
    public function clearAll(): bool
    {
        $this->db->exec("TRUNCATE TABLE logs");
        return true;
    }
}