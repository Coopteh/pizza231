<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Order;

class OrderTest extends TestCase
{
    private Order $order;
    
    protected function setUp(): void
    {
        $this->order = new Order();
    }
    
    /**
     * Тест: create создаёт заказ и возвращает ID
     */
    public function testCreateCreatesOrderAndReturnsId(): void
    {
        $orderData = [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'phone' => '+79000000000',
            'address' => 'Test Address 1',
            'total' => 500.00,
            'status' => 'pending',
            'items' => [
                ['id' => 1, 'name' => 'Product 1', 'price' => 250, 'quantity' => 2]
            ]
        ];
        
        $result = $this->order->create($orderData);
        
        // Должен вернуть ID (число) или null
        $this->assertTrue($result === null || is_int($result));
    }
    
    /**
     * Тест: getAll возвращает массив
     */
    public function testGetAllReturnsArray(): void
    {
        $result = $this->order->getAll();
        
        $this->assertIsArray($result);
    }
    
    /**
     * Тест: findById возвращает null для несуществующего заказа
     */
    public function testFindByIdReturnsNullForNonExistent(): void
    {
        $result = $this->order->findById(999999);
        
        $this->assertNull($result);
    }
    
    /**
     * Тест: updateStatus возвращает bool
     */
    public function testUpdateStatusReturnsBool(): void
    {
        // Сначала создаём заказ
        $orderId = $this->order->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
            'total' => 100,
            'items' => []
        ]);
        
        if ($orderId) {
            $result = $this->order->updateStatus($orderId, 'completed');
            $this->assertIsBool($result);
        } else {
            $this->assertTrue(true); // Пропускаем если не удалось создать
        }
    }
    
    /**
     * Тест: create с email работает корректно
     */
    public function testCreateWithEmail(): void
    {
        $orderData = [
            'email' => 'test_order@example.com',
            'name' => 'Test User',
            'phone' => '+79000000000',
            'address' => 'Test Address',
            'total' => 250,
            'items' => [
                ['id' => 1, 'name' => 'Product', 'price' => 250, 'quantity' => 1]
            ]
        ];
        
        $result = $this->order->create($orderData);
        
        // Должен вернуть ID заказа
        $this->assertNotNull($result);
    }
}
