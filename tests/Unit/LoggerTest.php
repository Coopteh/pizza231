<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Lib\Logger;

class LoggerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testLoggerInstance(): void
    {
        $instance = Logger::getInstance();
        $this->assertInstanceOf(Logger::class, $instance);

        $instance2 = Logger::getInstance();
        $this->assertSame($instance, $instance2);
    }

    public function testLogLevels(): void
    {
        $levels = ['error', 'warning', 'info', 'critical', 'debug'];

        foreach ($levels as $level) {
            $method = $level;
            $this->assertTrue(
                method_exists(Logger::class, $method),
                "Метод {$method} должен существовать"
            );
        }
    }

    public function testGetStats(): void
    {
        $stats = Logger::getStats();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('error', $stats);
        $this->assertArrayHasKey('warning', $stats);
        $this->assertArrayHasKey('info', $stats);
        $this->assertArrayHasKey('critical', $stats);
        $this->assertArrayHasKey('debug', $stats);
    }

    public function testLogEntryFormat(): void
    {
        $logFile = __DIR__ . '/../../logs/error.log';

        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            if (!empty($lines)) {
                $lastLine = end($lines);

                $this->assertMatchesRegularExpression(
                    '/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \[(ERROR|WARNING|INFO|CRITICAL|DEBUG)\]/',
                    $lastLine
                );
            }
        }
    }
}
