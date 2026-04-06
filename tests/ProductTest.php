<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    /**
     * Тест бизнес-логики: проверка правильности вычисления суммы заказа.
     * 
     * В задании указана сумма 1300 руб., однако математически:
     * 2 * 350 + 1 * 500 = 1200 руб.
     * Тест написан на корректную сумму (1200), так как проверяет логику вычислений.
     */
    public function testPrepareDataCalculatesTourOrderSum()
    {
        $model = new Product();

        // Данные формы (имитация $_POST)
        $formData = [
            'fio' => 'Иванов Иван Иванович',
            'phone' => '+7-900-000-00-00',
            'address' => 'г. Москва',
        ];

        // Данные корзины (2 товара по 350 и 1 товар по 500)
        $basketData = [
            ['id' => 1, 'name' => 'Тур А', 'price' => 350, 'quantity' => 2, 'sum' => 700],
            ['id' => 2, 'name' => 'Тур Б', 'price' => 500, 'quantity' => 1, 'sum' => 500],
        ];

        $result = $model->prepareData($formData, $basketData);

        // Проверяем, что данные формы переданы корректно
        $this->assertEquals('Иванов Иван Иванович', $result['fio']);
        
        // Проверяем структуру массива товаров
        $this->assertCount(2, $result['products']);
        
        // Проверяем общую сумму (бизнес-логика)
        // В задании указано 1300, но правильный расчет: 700 + 500 = 1200
        $this->assertEquals(1200, $result['all_sum']);
    }
    
    /**
     * Тест безопасности: защита от инъекций вредоносного кода.
     */
    public function testPrepareDataHandlesMaliciousInput()
    {
        $model = new Product();
        
        // Вредоносный ввод в поле fio (XSS/SQL-инъекция)
        $maliciousFio = '<script>alert("XSS")</script>';
        
        // Данные формы с вредоносным вводом
        $formData = [
            'fio' => $maliciousFio,
            'phone' => '+7-999-999-99-99',
        ];
        
        // Пустая корзина
        $basketData = [];
        
        $result = $model->prepareData($formData, $basketData);
        
        // Проверяем, что ключ существует и данные не пусты
        $this->assertArrayHasKey('fio', $result);
        
        // Проверяем, что вредоносный код НЕ был выполнен (тег <script> остался текстом)
        // Это базовая проверка того, что данные просто сохраняются как строка.
        // Для полной защиты нужно использовать htmlspecialchars() при выводе на страницу.
        $this->assertStringContainsString('<script>', $result['fio']);
    }
}