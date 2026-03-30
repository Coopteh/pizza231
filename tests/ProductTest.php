<?php
use PHPUnit\Framework\TestCase;
use App\Models\Product;
use App\Config\Config;

class ProductTest extends TestCase
{
    private Product $product;
    private string $testDataFile;
    private string $testOrdersFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаём временные файлы для тестов
        $this->testDataFile = sys_get_temp_dir() . '/test_products.json';
        $this->testOrdersFile = sys_get_temp_dir() . '/test_orders.json';
        
        // Переопределяем константы конфигурации для тестов
        $this->overrideConfigConstants();
        
        // Инициализируем модель
        $this->product = new Product();
        
        // Очищаем сессию перед каждым тестом
        $this->clearSession();
    }

    protected function tearDown(): void
    {
        // Удаляем временные файлы
        if (file_exists($this->testDataFile)) {
            unlink($this->testDataFile);
        }
        if (file_exists($this->testOrdersFile)) {
            unlink($this->testOrdersFile);
        }
        
        $this->clearSession();
        parent::tearDown();
    }

    private function overrideConfigConstants(): void
    {
        // Если константы уже определены - пропускаем
        if (!defined('App\Config\Config::FILE_DATA')) {
            // Для гибкости лучше изменить класс Config, чтобы он поддерживал 
            // переопределение путей через свойства или методы
        }
    }

    private function clearSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
    }

    private function createTestProductsFile(): void
    {
        $products = [
            ['id' => 1, 'name' => 'Товар А', 'price' => 350],
            ['id' => 2, 'name' => 'Товар Б', 'price' => 350],
            ['id' => 3, 'name' => 'Товар В', 'price' => 500],
        ];
        file_put_contents($this->testDataFile, json_encode($products, JSON_UNESCAPED_UNICODE));
    }
    // BEFORE PHP 8
    // /**
    //  * Тест расчёта суммы заказа: 2 товара по 350₽ + 1 товар по 500₽ = 1200₽
    //  * 
    //  * @requires PHP 8.0
    //  */

    // AFTER PHP 8
    #[RequiresPhp('>=8.1')]
    public function testPrepareDataCalculatesCorrectTotalSum(): void
    {
        // ARRANGE: Подготовка тестовых данных
        $this->createTestProductsFile();
        
        // Мокаем метод loadData, чтобы он возвращал тестовые данные
        $productMock = $this->getMockBuilder(Product::class)
            ->onlyMethods(['loadData'])
            ->getMock();
        
        $testProducts = [
            ['id' => 1, 'name' => 'Товар А', 'price' => 350],
            ['id' => 2, 'name' => 'Товар Б', 'price' => 350], 
            ['id' => 3, 'name' => 'Товар В', 'price' => 500],
        ];
        $productMock->method('loadData')->willReturn($testProducts);
        
        // Формируем данные корзины: 
        // - товар 1 (350₽) в количестве 2 шт = 700₽
        // - товар 3 (500₽) в количестве 1 шт = 500₽
        // Итого: 1200₽
        $_SESSION['basket'] = [
            1 => ['quantity' => 2],  // 2 × 350 = 700
            3 => ['quantity' => 1],  // 1 × 500 = 500
        ];
        
        // Тестовые данные формы
        $formData = [
            'customer_name' => 'Иван Иванов',
            'customer_email' => 'ivan@example.com',
            'delivery_address' => 'г. Москва, ул. Тестовая, д. 1'
        ];
        
        // Данные корзины для передачи в метод
        $basketData = [
            1 => ['quantity' => 2],
            3 => ['quantity' => 1],
        ];

        // ACT: Вызов тестируемого метода
        // ⚠️ Метод prepareData должен быть реализован в классе Product
        $result = $productMock->prepareData($formData, $basketData);

        // ASSERT: Проверка расчётов
        $this->assertIsArray($result, 'Метод должен возвращать массив');
        $this->assertArrayHasKey('all_sum', $result, 'Результат должен содержать ключ all_sum');
        
        // ✅ Ожидаемая сумма: 2×350 + 1×500 = 1200
        $this->assertEquals(1200, $result['all_sum'], 'Сумма заказа рассчитана неверно');
        
        // Дополнительные проверки структуры
        $this->assertArrayHasKey('items', $result, 'Результат должен содержать список товаров');
        $this->assertCount(2, $result['items'], 'В заказе должно быть 2 позиции товаров');
        
        // Проверка расчёта по каждому товару
        $items = array_column($result['items'], 'sum', 'id');
        $this->assertEquals(700, $items[1], 'Сумма для товара #1 рассчитана неверно');
        $this->assertEquals(500, $items[3], 'Сумма для товара #3 рассчитана неверно');
    }

    /**
     * Тест: проверка обработки пустой корзины
     */
    public function testPrepareDataWithEmptyBasket(): void
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->onlyMethods(['loadData'])
            ->getMock();
        $productMock->method('loadData')->willReturn([]);
        
        $_SESSION['basket'] = [];
        
        $formData = ['customer_name' => 'Test'];
        $basketData = [];
        
        $result = $productMock->prepareData($formData, $basketData);
        
        $this->assertEquals(0, $result['all_sum'], 'Сумма пустого заказа должна быть 0');
        $this->assertEmpty($result['items'], 'Список товаров должен быть пустым');
    }

    /**
     * Тест: валидация входных данных
     */
    public function testPrepareDataValidatesInput(): void
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->onlyMethods(['loadData'])
            ->getMock();
        $productMock->method('loadData')->willReturn([
            ['id' => 1, 'name' => 'Test', 'price' => 100]
        ]);
        
        // Передаём некорректное количество (отрицательное)
        $_SESSION['basket'] = [1 => ['quantity' => -5]];
        
        $formData = [];
        $basketData = [1 => ['quantity' => -5]];
        
        // Метод должен либо выбросить исключение, либо вернуть ошибку
        $this->expectException(\InvalidArgumentException::class);
        $productMock->prepareData($formData, $basketData);
    }
}