<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Cart;

class CartTest extends TestCase
{
    protected function setUp(): void
    {
        // Очищаем сессию перед каждым тестом
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['cart'] = [];
    }
    
    protected function tearDown(): void
    {
        // Очищаем после теста
        $_SESSION['cart'] = [];
    }
    
    /**
     * Тест: getItems возвращает пустой массив для новой корзины
     */
    public function testGetItemsReturnsEmptyArrayForNewCart(): void
    {
        $_SESSION['cart'] = [];
        $result = Cart::getItems();
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
    
    /**
     * Тест: add добавляет товар в корзину
     */
    public function testAddAddsItemToCart(): void
    {
        $_SESSION['cart'] = [];
        
        $result = Cart::add(1, 'Test Product', 100.00, '/img.jpg', 1);
        
        $this->assertTrue($result);
        
        $items = Cart::getItems();
        $this->assertCount(1, $items);
        $this->assertEquals(1, $items[0]['id']);
        $this->assertEquals('Test Product', $items[0]['name']);
        $this->assertEquals(100.00, $items[0]['price']);
        $this->assertEquals(1, $items[0]['quantity']);
    }
    
    /**
     * Тест: add увеличивает количество при добавлении того же товара
     */
    public function testAddIncreasesQuantityForExistingItem(): void
    {
        $_SESSION['cart'] = [];
        Cart::add(1, 'Test Product', 100.00, '/img.jpg', 1);
        Cart::add(1, 'Test Product', 100.00, '/img.jpg', 2);
        
        $items = Cart::getItems();
        
        $this->assertCount(1, $items);
        $this->assertEquals(3, $items[0]['quantity']);
    }
    
    /**
     * Тест: add добавляет разные товары
     */
    public function testAddAddsDifferentItems(): void
    {
        $_SESSION['cart'] = [];
        
        Cart::add(1, 'Product 1', 100.00, '/img1.jpg', 1);
        Cart::add(2, 'Product 2', 200.00, '/img2.jpg', 1);
        
        $items = Cart::getItems();
        
        $this->assertCount(2, $items);
    }
    
    /**
     * Тест: updateQuantity обновляет количество товара
     */
    public function testUpdateQuantityUpdatesItemQuantity(): void
    {
        $_SESSION['cart'] = [];
        Cart::add(1, 'Test Product', 100.00, '/img.jpg', 1);
        
        $result = Cart::updateQuantity(1, 5);
        
        $this->assertTrue($result);
        
        $items = Cart::getItems();
        $this->assertEquals(5, $items[0]['quantity']);
    }
    
    /**
     * Тест: updateQuantity удаляет товар при количестве <= 0
     */
    public function testUpdateQuantityRemovesItemWhenQuantityIsZero(): void
    {
        $_SESSION['cart'] = [];
        Cart::add(1, 'Test Product', 100.00, '/img.jpg', 1);
        
        $result = Cart::updateQuantity(1, 0);
        
        $this->assertTrue($result);
        
        $items = Cart::getItems();
        $this->assertEmpty($items);
    }
    
    /**
     * Тест: updateQuantity возвращает false для несуществующего товара
     */
    public function testUpdateQuantityReturnsFalseForNonExistent(): void
    {
        $_SESSION['cart'] = [];
        
        $result = Cart::updateQuantity(999, 5);
        
        $this->assertFalse($result);
    }
    
    /**
     * Тест: remove удаляет товар из корзины
     */
    public function testRemoveRemovesItemFromCart(): void
    {
        $_SESSION['cart'] = [];
        Cart::add(1, 'Test Product', 100.00, '/img.jpg', 1);
        
        $result = Cart::remove(1);
        
        $this->assertTrue($result);
        
        $items = Cart::getItems();
        $this->assertEmpty($items);
    }
    
    /**
     * Тест: remove возвращает true даже если товар не найден
     */
    public function testRemoveReturnsTrueEvenIfItemNotFound(): void
    {
        $_SESSION['cart'] = [];
        
        $result = Cart::remove(999);
        
        $this->assertTrue($result);
    }
    
    /**
     * Тест: clear очищает корзину
     */
    public function testClearEmptiesCart(): void
    {
        $_SESSION['cart'] = [];
        Cart::add(1, 'Test Product', 100.00, '/img.jpg', 1);
        Cart::add(2, 'Test Product 2', 200.00, '/img2.jpg', 2);
        
        $result = Cart::clear();
        
        $this->assertTrue($result);
        
        $items = Cart::getItems();
        $this->assertEmpty($items);
    }
    
    /**
     * Тест: getTotal возвращает 0 для пустой корзины
     */
    public function testGetTotalReturnsZeroForEmptyCart(): void
    {
        $_SESSION['cart'] = [];
        
        $result = Cart::getTotal();
        
        $this->assertEquals(0.0, $result);
    }
    
    /**
     * Тест: getTotal возвращает правильную сумму
     */
    public function testGetTotalReturnsCorrectSum(): void
    {
        $_SESSION['cart'] = [];
        Cart::add(1, 'Product 1', 100.00, '/img1.jpg', 2); // 200
        Cart::add(2, 'Product 2', 50.00, '/img2.jpg', 3);  // 150
        
        $result = Cart::getTotal();
        
        $this->assertEquals(350.00, $result);
    }
    
    /**
     * Тест: getCount возвращает 0 для пустой корзины
     */
    public function testGetCountReturnsZeroForEmptyCart(): void
    {
        $_SESSION['cart'] = [];
        
        $result = Cart::getCount();
        
        $this->assertEquals(0, $result);
    }
    
    /**
     * Тест: getCount возвращает правильное количество
     */
    public function testGetCountReturnsCorrectCount(): void
    {
        $_SESSION['cart'] = [];
        Cart::add(1, 'Product 1', 100.00, '/img1.jpg', 2);
        Cart::add(2, 'Product 2', 50.00, '/img2.jpg', 3);
        
        $result = Cart::getCount();
        
        $this->assertEquals(5, $result);
    }
}
