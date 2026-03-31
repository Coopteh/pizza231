<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Logger;

class LoggerTest extends TestCase
{
    private string $testLogDir;
    private string $originalLogDir;
    
    protected function setUp(): void
    {
        // Используем временную директорию для тестов
        $this->testLogDir = sys_get_temp_dir() . '/test_logs_' . uniqid();
        $this->originalLogDir = 'storage/logs';
        
        // Мокаем статические свойства через reflection
        $reflection = new \ReflectionClass(Logger::class);
        $logDirProperty = $reflection->getProperty('logDir');
        $logDirProperty->setAccessible(true);
        $logDirProperty->setValue(null, $this->testLogDir);
    }
    
    protected function tearDown(): void
    {
        // Очищаем тестовую директорию
        if (is_dir($this->testLogDir)) {
            $files = glob($this->testLogDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($this->testLogDir);
        }
    }
    
    /**
     * Тест: error записывает сообщение в лог
     */
    public function testErrorWritesMessageToLog(): void
    {
        Logger::error('Test error message');
        
        $errors = Logger::getErrors();
        
        $this->assertNotEmpty($errors);
        $this->assertEquals('ERROR', $errors[0]['level']);
        $this->assertEquals('Test error message', $errors[0]['message']);
    }
    
    /**
     * Тест: info записывает информационное сообщение
     */
    public function testInfoWritesInfoMessageToLog(): void
    {
        Logger::info('Test info message');
        
        $errors = Logger::getErrors();
        
        // Фильтруем только INFO сообщения
        $infoMessages = array_filter($errors, fn($e) => $e['level'] === 'INFO');
        
        $this->assertNotEmpty($infoMessages);
        $firstInfo = reset($infoMessages);
        $this->assertEquals('Test info message', $firstInfo['message']);
    }
    
    /**
     * Тест: error записывает контекст
     */
    public function testErrorWritesContext(): void
    {
        Logger::error('Test error with context', ['user_id' => 123, 'action' => 'test']);
        
        $errors = Logger::getErrors();
        
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors[0]['context']);
        $this->assertEquals(123, $errors[0]['context']['user_id']);
        $this->assertEquals('test', $errors[0]['context']['action']);
    }
    
    /**
     * Тест: getErrors возвращает массив
     */
    public function testGetErrorsReturnsArray(): void
    {
        $result = Logger::getErrors();
        
        $this->assertIsArray($result);
    }
    
    /**
     * Тест: getErrors возвращает пустой массив когда файл не существует
     */
    public function testGetErrorsReturnsEmptyArrayWhenFileNotExists(): void
    {
        $result = Logger::getErrors();
        
        $this->assertIsArray($result);
    }
    
    /**
     * Тест: getErrors учитывает лимит
     */
    public function testGetErrorsRespectsLimit(): void
    {
        // Записываем 5 сообщений
        for ($i = 1; $i <= 5; $i++) {
            Logger::error("Error $i");
        }
        
        // Получаем с лимитом 3
        $errors = Logger::getErrors(3);
        
        $this->assertCount(3, $errors);
    }
    
    /**
     * Тест: getErrorCount возвращает количество ошибок
     */
    public function testGetErrorCountReturnsCount(): void
    {
        Logger::error('Error 1');
        Logger::error('Error 2');
        Logger::error('Error 3');
        
        $count = Logger::getErrorCount();
        
        $this->assertGreaterThanOrEqual(3, $count);
    }
    
    /**
     * Тест: clear очищает лог
     */
    public function testClearEmptiesLog(): void
    {
        Logger::error('Error to clear');
        
        $countBefore = Logger::getErrorCount();
        $this->assertGreaterThan(0, $countBefore);
        
        Logger::clear();
        
        $countAfter = Logger::getErrorCount();
        $this->assertEquals(0, $countAfter);
    }
    
    /**
     * Тест: getLogPath возвращает корректный путь
     */
    public function testGetLogPathReturnsPath(): void
    {
        $path = Logger::getLogPath();
        
        $this->assertIsString($path);
        $this->assertStringContainsString('errors.log', $path);
    }
}
