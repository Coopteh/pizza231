<?php
namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Базовый класс для всех тестов
 */
abstract class BaseTestCase extends TestCase
{
    /**
     * Создать мок PDOStatement
     */
    protected function createMockStatement(array $rows = []): \PDOStatement
    {
        $stmt = $this->createMock(\PDOStatement::class);
        
        $stmt->method('fetch')
            ->willReturnCallback(function() use (&$rows) {
                return array_shift($rows) ?: false;
            });
        
        $stmt->method('fetchAll')
            ->willReturn($rows);
        
        $stmt->method('execute')
            ->willReturn(true);
        
        $stmt->method('rowCount')
            ->willReturn(count($rows));
        
        return $stmt;
    }
    
    /**
     * Создать мок PDO
     */
    protected function createMockDb(array $data = []): \PDO
    {
        $pdo = $this->createMock(\PDO::class);
        
        $pdo->method('prepare')
            ->willReturn($this->createMockStatement($data));
        
        $pdo->method('query')
            ->willReturn($this->createMockStatement($data));
        
        $pdo->method('lastInsertId')
            ->willReturn('1');
        
        return $pdo;
    }
    
    /**
     * Создать временный файл с JSON данными
     */
    protected function createTempJsonFile(array $data): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_');
        file_put_contents($tempFile, json_encode($data));
        return $tempFile;
    }
    
    /**
     * Удалить временный файл
     */
    protected function cleanupTempFile(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
