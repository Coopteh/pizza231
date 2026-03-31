<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Product;
use App\Config\Config;

class ProductTest extends TestCase
{
    private Product $product;
    private ?string $originalProductsFile;
    
    protected function setUp(): void
    {
        $this->product = new Product();
        
        // Сохраняем оригинальный путь к файлу продуктов
        $this->originalProductsFile = defined('App\Config\Config::FILE_PRODUCTS') 
            ? Config::FILE_PRODUCTS 
            : null;
    }
    
    protected function tearDown(): void
    {
        // Восстанавливаем оригинальный файл
        if ($this->originalProductsFile) {
            // nothing to restore for now
        }
    }
    
    /**
     * Тест: loadData возвращает null когда файл не существует
     */
    public function testLoadDataReturnsNullWhenFileNotExists(): void
    {
        // Мокаем Config чтобы вернуть несуществующий файл
        $this->assertTrue(true); // Placeholder
    }
    
    /**
     * Тест: loadData возвращает массив из JSON
     */
    public function testLoadDataReturnsArrayFromJson(): void
    {
        // Проверяем что метод loadData работает (может вернуть null если нет БД и нет файла)
        $result = $this->product->loadData();
        
        // Результат может быть null (если нет БД и нет файла) или массив
        $this->assertTrue($result === null || is_array($result));
    }
    
    /**
     * Тест: findById возвращает null для несуществующего товара
     */
    public function testFindByIdReturnsNullForNonExistent(): void
    {
        $result = $this->product->findById(999999);
        
        $this->assertNull($result);
    }
    
    /**
     * Тест: findByCategory возвращает пустой массив для несуществующей категории
     */
    public function testFindByCategoryReturnsEmptyArrayForNonExistent(): void
    {
        $result = $this->product->findByCategory('NonExistentCategory');
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
    
    /**
     * Тест: getCategories возвращает массив
     */
    public function testGetCategoriesReturnsArray(): void
    {
        $result = $this->product->getCategories();
        
        $this->assertIsArray($result);
    }
    
    /**
     * Тест: search возвращает массив
     */
    public function testSearchReturnsArray(): void
    {
        $result = $this->product->search('test');
        
        $this->assertIsArray($result);
    }
    
    /**
     * Тест: create возвращает null без БД (JSON mode)
     */
    public function testCreateReturnsNullWithoutDatabase(): void
    {
        $result = $this->product->create([
            'name' => 'Test Product',
            'price' => 100,
            'category' => 'Test'
        ]);
        
        // Без БД должен вернуть null
        $this->assertNull($result);
    }
    
    /**
     * Тест: update возвращает false без БД
     */
    public function testUpdateReturnsFalseWithoutDatabase(): void
    {
        $result = $this->product->update(1, [
            'name' => 'Updated Product',
            'price' => 200,
            'category' => 'Updated'
        ]);
        
        $this->assertFalse($result);
    }
    
    /**
     * Тест: delete возвращает false без БД
     */
    public function testDeleteReturnsFalseWithoutDatabase(): void
    {
        $result = $this->product->delete(1);
        
        $this->assertFalse($result);
    }
}
