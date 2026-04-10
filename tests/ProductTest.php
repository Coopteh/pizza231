<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Models\Product;

class ProductTest extends TestCase
{
    /**
     * 🔹 Тест-1: Проверка бизнес-логики (правильность вычислений)
     * 
     * Сценарий:
     * - 2 товара по 350₽ = 700₽
     * - 1 товар по 600₽ = 600₽  
     * ИТОГО: 1300₽ ✅
     */
    public function testPrepareDataCalculatesTotalCorrectly()
    {
        $model = new Product();
        
        $formData = [
            'fio' => 'Иванов Иван Иванович',
            'phone' => '+7 (999) 123-45-67',
            'email' => 'test@example.com',
            'delivery_type' => 'email',
            'address' => ''
        ];
        
        $basketData = [
            [
                'product' => [
                    'id' => 1,
                    'name' => 'Товар А',
                    'price' => 350,
                    'description' => 'Описание',
                    'period' => 'год',
                    'image' => '/img.jpg',
                    'coverage' => '10 млн',
                    'features' => []
                ],
                'quantity' => 2
            ],
            [
                'product' => [
                    'id' => 2,
                    'name' => 'Товар Б',
                    'price' => 600,
                    'description' => 'Описание',
                    'period' => 'год',
                    'image' => '/img.jpg',
                    'coverage' => '10 млн',
                    'features' => []
                ],
                'quantity' => 1
            ]
        ];
        
        $result = $model->prepareData($formData, $basketData);
        
        $this->assertEquals(1300, $result['all_sum'], 'Сумма заказа должна быть 1300₽');
        $this->assertEquals(1300, $result['total'], 'Поле total должно совпадать с all_sum');
        $this->assertCount(2, $result['products'], 'В заказе должно быть 2 позиции');
        $this->assertEquals('Иванов Иван Иванович', $result['fio']);
        $this->assertStringNotContainsString('<script>', $result['fio'], 'Защита от XSS');
        $this->assertEquals(700, $result['products'][0]['subtotal']);
        $this->assertEquals(600, $result['products'][1]['subtotal']);
    }
    
    /**
     * 🔹 Тест-2: Проверка защиты от вредоносного кода (XSS/инъекции)
     * 
     */
    public function testPrepareDataSanitizesInput()
    {
        $model = new Product();
        
        $maliciousFormData = [
            'fio' => '<script>alert("XSS")</script>Иванов',
            'phone' => '+79991234567\' OR "1"="1',
            'email' => 'test@example.com"><img src=x onerror=alert(1)>',
            'delivery_type' => 'email',
            'address' => '<iframe src="evil.com"></iframe>'
        ];
        
        $basketData = [
            [
                'product' => [
                    'id' => 1,
                    'name' => 'Товар <b>Test</b>',
                    'price' => 100,
                    'description' => 'Описание',
                    'period' => '',
                    'image' => '',
                    'coverage' => '',
                    'features' => []
                ],
                'quantity' => 1
            ]
        ];
        
        $result = $model->prepareData($maliciousFormData, $basketData);
        
        // ✅ Проверяем что теги УДАЛЕНЫ (strip_tags удаляет полностью)
        $this->assertStringNotContainsString('<script>', $result['fio']);
        $this->assertStringNotContainsString('<iframe>', $result['address']);
        $this->assertStringNotContainsString('<img', $result['email']);
        $this->assertStringNotContainsString('<b>', $result['products'][0]['name']);
        
        // ✅ Проверяем что спецсимволы ЭКРАНИРОВАНЫ (htmlspecialchars)
        $this->assertStringContainsString('&quot;', $result['fio']);
        $this->assertStringContainsString('&quot;', $result['email']);
        
        // ✅ Проверяем что опасные JS-атрибуты удалены
        $this->assertStringNotContainsString('onerror=', $result['email']);
        $this->assertStringNotContainsString('src=', $result['email']);
        
        // ✅ Проверяем что текст остался (только теги удалены)
        $this->assertStringContainsString('alert', $result['fio']);
        $this->assertStringContainsString('Иванов', $result['fio']);
        $this->assertStringContainsString('test@example.com', $result['email']);
        
        // ✅ Проверяем название товара (теги удалены, кавычки экранированы)
        $this->assertStringContainsString('Test', $result['products'][0]['name']);
        $this->assertStringNotContainsString('<b>', $result['products'][0]['name']);
        
        $this->assertEquals(100, $result['all_sum']);
    }
    
    /**
     * 🔹 Тест-3: Проверка минимального количества товара
     */
    public function testPrepareDataEnforcesMinimumQuantity()
    {
        $model = new Product();
        
        $formData = ['fio' => 'Test', 'phone' => '123', 'email' => 'a@b.c', 'delivery_type' => 'email'];
        
        $basketData = [
            [
                'product' => ['id' => 1, 'name' => 'Test', 'price' => 100, 'description' => '', 'period' => '', 'image' => '', 'coverage' => '', 'features' => []],
                'quantity' => 0
            ]
        ];
        
        $result = $model->prepareData($formData, $basketData);
        
        $this->assertEquals(1, $result['products'][0]['quantity']);
        $this->assertEquals(100, $result['all_sum']);
    }
}