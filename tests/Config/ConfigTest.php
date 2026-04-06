<?php
namespace Tests\Config;

use PHPUnit\Framework\TestCase;
use App\Config\Config;

class ConfigTest extends TestCase
{
    /**
     * Тест: FILE_PRODUCTS константа определена
     */
    public function testFileProductsConstantIsDefined(): void
    {
        $this->assertTrue(defined('App\Config\Config::FILE_PRODUCTS'));
    }
    
    /**
     * Тест: SHOW_CATALOG_AT_HOME константа определена
     */
    public function testShowCatalogAtHomeConstantIsDefined(): void
    {
        $this->assertTrue(defined('App\Config\Config::SHOW_CATALOG_AT_HOME'));
    }
    
    /**
     * Тест: SHOW_CATALOG_AT_HOME имеет правильное значение
     */
    public function testShowCatalogAtHomeHasCorrectValue(): void
    {
        $this->assertIsBool(Config::SHOW_CATALOG_AT_HOME);
    }
    
    /**
     * Тест: Константы БД определены
     */
    public function testDatabaseConstantsAreDefined(): void
    {
        $this->assertTrue(defined('App\Config\Config::DB_DRIVER'));
        $this->assertTrue(defined('App\Config\Config::DB_HOST'));
        $this->assertTrue(defined('App\Config\Config::DB_DATABASE'));
        $this->assertTrue(defined('App\Config\Config::DB_USERNAME'));
    }
    
    /**
     * Тест: isDatabaseAvailable возвращает bool
     */
    public function testIsDatabaseAvailableReturnsBool(): void
    {
        $result = Config::isDatabaseAvailable();
        
        $this->assertIsBool($result);
    }
}
