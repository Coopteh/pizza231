<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use App\Models\Product;
use App\Services\ILoadStorage;

class ProductTest extends TestCase
{
    private Product $model;
    private ILoadStorage $storageMock;

    protected function setUp(): void
    {
        parent::setUp();
        // Создаём мок для интерфейса IStorage
        $this->storageMock = $this->createMock(ILoadStorage::class);
        // Передаём зависимости в конструктор Product
        $this->model = new Product($this->storageMock, 'orders');
    }

    public function testPrepareDataCalculatesTourOrderSum(): void
    {
        $formData = [
            'fio' => 'Иванов Иван Иванович',
            'phone' => '7-900-000-00-00',
            'address' => 'г. Москва',
        ];
        $basketData = [
            ['id' => 1, 'name' => 'Тур А', 'price' => 350, 'quantity' => 2, 'sum' => 700],
            ['id' => 2, 'name' => 'Тур Б', 'price' => 500, 'quantity' => 1, 'sum' => 500],
        ];

        $result = $this->model->prepareData($formData, $basketData);

        // ✅ Ключи соответствуют возвращаемому массиву из Product::prepareData()
        $this->assertEquals('Иванов Иван Иванович', $result['fio']);
        $this->assertEquals('7-900-000-00-00', $result['user_phone']); // не 'phone'!
        $this->assertEquals('г. Москва', $result['user_address']);       // не 'address'!
        $this->assertEquals(1200.0, $result['all_sum']);
        $this->assertCount(2, $result['products']);
    }

    public function testPrepareDataHandlesMaliciousInput(): void
    {
        $formData = ['fio' => '<script>alert("XSS")</script>', 'phone' => '123'];
        $result = $this->model->prepareData($formData, []);

        $this->assertArrayHasKey('fio', $result);
        $this->assertStringContainsString('<script>', $result['fio']);
    }
}
