<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Mocks\MockStorage;

// Подключаем ваш Product класс
// use App\Models\Product;
// use App\Config\Config;

class ProductTest extends TestCase
{
    private MockStorage $mockStorage;
    private string $fileProducts = 'products.json';
    private string $fileOrders = 'orders.json';

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockStorage = new MockStorage();
    }

    protected function tearDown(): void
    {
        $this->mockStorage->reset();
        parent::tearDown();
    }

    // ✅ Тест создания модели с моком
    public function testProductCanBeConstructedWithMockStorage(): void
    {
        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        $this->assertInstanceOf(Product::class, $product);
    }

    // ✅ Тест загрузки продуктов из мока
    public function testLoadProductsReturnsArrayFromMock(): void
    {
        $expectedProducts = [
            1 => ['id' => 1, 'name' => 'Product A', 'price' => 100],
            2 => ['id' => 2, 'name' => 'Product B', 'price' => 200]
        ];

        $this->mockStorage = new MockStorage([$this->fileProducts => $expectedProducts]);
        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        // Предполагаем метод getAll() или similar
        $result = $product->getAll();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Product A', $result[1]['name']);
    }

    // ✅ Тест сохранения продукта через мокированный storage
    public function testSaveProductCallsStorageSave(): void
    {
        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        $newProduct = [
            'id' => 3,
            'name' => 'Product C',
            'price' => 300
        ];

        $result = $product->add($newProduct);

        $this->assertTrue($result);
        $this->assertContains($this->fileProducts, $this->mockStorage->getSavedFiles());

        $savedData = $this->mockStorage->getSavedData($this->fileProducts);
        $this->assertArrayHasKey(3, $savedData);
        $this->assertEquals('Product C', $savedData[3]['name']);
    }

    // ✅ Тест обновления продукта
    public function testUpdateProductModifiesExisting(): void
    {
        $initialData = [
            1 => ['id' => 1, 'name' => 'Old Name', 'price' => 100]
        ];
        $this->mockStorage = new MockStorage([$this->fileProducts => $initialData]);

        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        $updated = [
            'id' => 1,
            'name' => 'New Name',
            'price' => 150
        ];

        $result = $product->update(1, $updated);

        $this->assertTrue($result);

        $savedData = $this->mockStorage->getSavedData($this->fileProducts);
        $this->assertEquals('New Name', $savedData[1]['name']);
        $this->assertEquals(150, $savedData[1]['price']);
    }

    // ✅ Тест удаления продукта
    public function testDeleteProductRemovesFromStorage(): void
    {
        $initialData = [
            1 => ['id' => 1, 'name' => 'To Delete', 'price' => 100],
            2 => ['id' => 2, 'name' => 'Keep', 'price' => 200]
        ];
        $this->mockStorage = new MockStorage([$this->fileProducts => $initialData]);

        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        $result = $product->delete(1);

        $this->assertTrue($result);

        $savedData = $this->mockStorage->getSavedData($this->fileProducts);
        $this->assertArrayNotHasKey(1, $savedData);
        $this->assertArrayHasKey(2, $savedData);
    }

    // ✅ Тест обработки ошибки загрузки (mock с ошибкой)
    public function testLoadProductsHandlesStorageException(): void
    {
        $this->mockStorage = new MockStorage([], true, $this->fileProducts);
        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        // Ожидаем исключение или пустой массив в зависимости от реализации
        $this->expectException(\RuntimeException::class);
        $product->getAll();
    }

    // ✅ Тест поиска продукта по имени
    public function testFindByNameReturnsMatchingProducts(): void
    {
        $initialData = [
            1 => ['id' => 1, 'name' => 'Apple iPhone', 'price' => 50000],
            2 => ['id' => 2, 'name' => 'Samsung Galaxy', 'price' => 40000],
            3 => ['id' => 3, 'name' => 'Apple iPad', 'price' => 30000]
        ];
        $this->mockStorage = new MockStorage([$this->fileProducts => $initialData]);

        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        $results = $product->findByName('Apple');

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertEquals(1, $results[0]['id']);
        $this->assertEquals(3, $results[1]['id']);
    }

    // ✅ Тест фильтрации по цене
    public function testFilterByPriceRange(): void
    {
        $initialData = [
            1 => ['id' => 1, 'name' => 'Cheap', 'price' => 100],
            2 => ['id' => 2, 'name' => 'Medium', 'price' => 500],
            3 => ['id' => 3, 'name' => 'Expensive', 'price' => 1000]
        ];
        $this->mockStorage = new MockStorage([$this->fileProducts => $initialData]);

        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        $results = $product->filterByPrice(200, 800);

        $this->assertCount(1, $results);
        $this->assertEquals('Medium', $results[0]['name']);
    }

    // ✅ Тест валидации данных продукта
    public function testAddProductValidatesRequiredFields(): void
    {
        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        // Пустое имя
        $invalid1 = ['id' => 4, 'name' => '', 'price' => 100];
        $this->assertFalse($product->add($invalid1));

        // Отрицательная цена
        $invalid2 = ['id' => 4, 'name' => 'Test', 'price' => -50];
        $this->assertFalse($product->add($invalid2));

        // Валидные данные
        $valid = ['id' => 4, 'name' => 'Valid Product', 'price' => 250];
        $this->assertTrue($product->add($valid));
    }

    // ✅ Тест работы с заказами (второй файл)
    public function testProductInteractsWithOrdersFile(): void
    {
        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        $order = [
            'id' => 101,
            'product_id' => 1,
            'quantity' => 2,
            'total' => 200
        ];

        $result = $product->addOrder($order);

        $this->assertTrue($result);
        $this->assertContains($this->fileOrders, $this->mockStorage->getSavedFiles());

        $savedOrders = $this->mockStorage->getSavedData($this->fileOrders);
        $this->assertArrayHasKey(101, $savedOrders);
        $this->assertEquals(2, $savedOrders[101]['quantity']);
    }

    // ✅ Тест получения статистики
    public function testGetStatsReturnsCorrectCounts(): void
    {
        $initialProducts = [
            1 => ['id' => 1, 'name' => 'A', 'price' => 100],
            2 => ['id' => 2, 'name' => 'B', 'price' => 200],
            3 => ['id' => 3, 'name' => 'C', 'price' => 300]
        ];
        $initialOrders = [
            101 => ['id' => 101, 'product_id' => 1, 'quantity' => 1]
        ];

        $this->mockStorage = new MockStorage([
            $this->fileProducts => $initialProducts,
            $this->fileOrders => $initialOrders
        ]);

        $product = new Product($this->mockStorage, $this->fileProducts, $this->fileOrders);

        $stats = $product->getStats();

        $this->assertEquals(3, $stats['products_count']);
        $this->assertEquals(1, $stats['orders_count']);
        $this->assertEquals(600, $stats['total_value']); // 100+200+300
    }
}
