<?php

// 📝 LOGGER: Новый файл для системы логирования

namespace Lib;

class Logger
{
    private const LOG_DIR = __DIR__ . '/../logs';
    private const LOG_FILE = 'error.log';
    private const MAX_FILE_SIZE = 10485760; // 10MB
    private const MAX_FILES = 5;

    private static ?Logger $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        if (!is_dir(self::LOG_DIR)) {
            mkdir(self::LOG_DIR, 0755, true);
        }
    }

    /**
     * Логирование ошибки
     */
    public static function error(string $message, array $context = []): void
    {
        self::getInstance()->write('ERROR', $message, $context);
    }

    /**
     * Логирование предупреждения
     */
    public static function warning(string $message, array $context = []): void
    {
        self::getInstance()->write('WARNING', $message, $context);
    }

    /**
     * Логирование информации
     */
    public static function info(string $message, array $context = []): void
    {
        self::getInstance()->write('INFO', $message, $context);
    }

    /**
     * Логирование критической ошибки
     */
    public static function critical(string $message, array $context = []): void
    {
        self::getInstance()->write('CRITICAL', $message, $context);
    }

    /**
     * Логирование отладочной информации
     */
    public static function debug(string $message, array $context = []): void
    {
        self::getInstance()->write('DEBUG', $message, $context);
    }

    /**
     * Запись в лог
     */
    private function write(string $level, string $message, array $context = []): void
    {
        $this->rotateLogs();

        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $userId = $_SESSION['user_id'] ?? 'guest';
        $uri = $_SERVER['REQUEST_URI'] ?? 'unknown';

        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';

        $logEntry = sprintf(
            "[%s] [%s] [IP:%s] [User:%s] [URI:%s] %s%s\n",
            $timestamp,
            $level,
            $ip,
            $userId,
            $uri,
            $message,
            $contextStr
        );

        $logFile = self::LOG_DIR . '/' . self::LOG_FILE;
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Ротация логов
     */
    private function rotateLogs(): void
    {
        $logFile = self::LOG_DIR . '/' . self::LOG_FILE;

        if (file_exists($logFile) && filesize($logFile) > self::MAX_FILE_SIZE) {
            for ($i = self::MAX_FILES - 1; $i >= 1; $i--) {
                $oldFile = self::LOG_DIR . '/error.' . $i . '.log';
                $newFile = self::LOG_DIR . '/error.' . ($i + 1) . '.log';

                if (file_exists($oldFile)) {
                    if ($i === self::MAX_FILES - 1) {
                        unlink($oldFile);
                    } else {
                        rename($oldFile, $newFile);
                    }
                }
            }

            rename($logFile, self::LOG_DIR . '/error.1.log');
        }
    }

    /**
     * Получение всех логов
     */
    public static function getLogs(int $limit = 100): array
    {
        $logFile = self::LOG_DIR . '/' . self::LOG_FILE;
        $logs = [];

        if (!file_exists($logFile)) {
            return $logs;
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines);

        foreach (array_slice($lines, 0, $limit) as $line) {
            $logs[] = $line;
        }

        return $logs;
    }

    /**
     * Очистка логов
     */
    public static function clearLogs(): bool
    {
        $logFile = self::LOG_DIR . '/' . self::LOG_FILE;

        if (file_exists($logFile)) {
            return unlink($logFile);
        }

        return true;
    }

    /**
     * Получение статистики логов
     */
    public static function getStats(): array
    {
        $logFile = self::LOG_DIR . '/' . self::LOG_FILE;
        $stats = [
            'total' => 0,
            'error' => 0,
            'warning' => 0,
            'info' => 0,
            'critical' => 0,
            'debug' => 0
        ];

        if (!file_exists($logFile)) {
            return $stats;
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $stats['total'] = count($lines);

        foreach ($lines as $line) {
            if (strpos($line, '[ERROR]') !== false) {
                $stats['error']++;
            } elseif (strpos($line, '[WARNING]') !== false) {
                $stats['warning']++;
            } elseif (strpos($line, '[INFO]') !== false) {
                $stats['info']++;
            } elseif (strpos($line, '[CRITICAL]') !== false) {
                $stats['critical']++;
            } elseif (strpos($line, '[DEBUG]') !== false) {
                $stats['debug']++;
            }
        }

        return $stats;
    }
}
