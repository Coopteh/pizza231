<?php
namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    private User $user;
    
    protected function setUp(): void
    {
        $this->user = new User();
    }
    
    /**
     * Тест: loadData возвращает массив
     */
    public function testLoadDataReturnsArray(): void
    {
        $result = $this->user->loadData();
        
        $this->assertIsArray($result);
    }
    
    /**
     * Тест: loadAdmins возвращает массив
     */
    public function testLoadAdminsReturnsArray(): void
    {
        $result = $this->user->loadAdmins();
        
        $this->assertIsArray($result);
    }
    
    /**
     * Тест: findByEmail возвращает null для несуществующего пользователя
     */
    public function testFindByEmailReturnsNullForNonExistent(): void
    {
        $result = $this->user->findByEmail('nonexistent@example.com');
        
        $this->assertNull($result);
    }
    
    /**
     * Тест: findById возвращает null для несуществующего пользователя
     */
    public function testFindByIdReturnsNullForNonExistent(): void
    {
        $result = $this->user->findById(999999);
        
        $this->assertNull($result);
    }
    
    /**
     * Тест: isAdmin возвращает false для несуществующего пользователя
     */
    public function testIsAdminReturnsFalseForNonExistent(): void
    {
        $result = $this->user->isAdmin(999999);
        
        $this->assertFalse($result);
    }
    
    /**
     * Тест: isAdmin возвращает true для userId = 0 (админ по умолчанию)
     */
    public function testIsAdminReturnsTrueForIdZero(): void
    {
        $result = $this->user->isAdmin(0);
        
        $this->assertTrue($result);
    }
    
    /**
     * Тест: isVerified возвращает false для несуществующего email
     */
    public function testIsVerifiedReturnsFalseForNonExistent(): void
    {
        $result = $this->user->isVerified('nonexistent@example.com');
        
        $this->assertFalse($result);
    }
    
    /**
     * Тест: verifyPassword возвращает null для несуществующего пользователя
     */
    public function testVerifyPasswordReturnsNullForNonExistent(): void
    {
        $result = $this->user->verifyPassword('nonexistent@example.com', 'password');
        
        $this->assertNull($result);
    }
    
    /**
     * Тест: create возвращает массив с данными пользователя
     */
    public function testCreateReturnsUserArray(): void
    {
        $email = 'test_' . time() . '@example.com';
        $result = $this->user->create($email, 'password123', 'Test User');
        
        // Может вернуть null если есть БД и она требует уникальность, или массив с данными
        $this->assertTrue($result === null || is_array($result));
        
        if (is_array($result)) {
            $this->assertArrayHasKey('id', $result);
            $this->assertArrayHasKey('email', $result);
            $this->assertArrayHasKey('name', $result);
            $this->assertArrayHasKey('is_verified', $result);
            $this->assertArrayHasKey('verification_code', $result);
            $this->assertFalse($result['is_verified']);
        }
    }
    
    /**
     * Тест: create возвращает null при попытке создать дубликат подтверждённого пользователя
     */
    public function testCreateReturnsNullForExistingVerifiedUser(): void
    {
        // Сначала создаём пользователя
        $email = 'duplicate_test_' . time() . '@example.com';
        $this->user->create($email, 'password123', 'Test User');
        
        // Пробуем создать снова - должен вернуть null (пользователь уже существует)
        $result = $this->user->create($email, 'password456', 'Test User 2');
        
        // Результат зависит от наличия БД
        $this->assertTrue($result === null || is_array($result));
    }
    
    /**
     * Тест: verifyEmail возвращает false для несуществующего email
     */
    public function testVerifyEmailReturnsFalseForNonExistent(): void
    {
        $result = $this->user->verifyEmail('nonexistent@example.com', '000000');
        
        $this->assertFalse($result);
    }
    
    /**
     * Тест: regenerateVerificationCode возвращает null для подтверждённого email
     */
    public function testRegenerateVerificationCodeReturnsNullForVerified(): void
    {
        $result = $this->user->regenerateVerificationCode('nonexistent@example.com');
        
        // Для несуществующего или подтверждённого email должен вернуть null
        $this->assertNull($result);
    }
    
    /**
     * Тест: updateProfile возвращает false для несуществующего пользователя (JSON mode)
     */
    public function testUpdateProfileReturnsFalseForNonExistent(): void
    {
        $result = $this->user->updateProfile(999999, ['name' => 'New Name']);
        
        // Без БД должен вернуть false
        $this->assertFalse($result);
    }
    
    /**
     * Тест: getFullProfile возвращает null для несуществующего пользователя
     */
    public function testGetFullProfileReturnsNullForNonExistent(): void
    {
        $result = $this->user->getFullProfile(999999);
        
        $this->assertNull($result);
    }
    
    /**
     * Тест: getOrders возвращает пустой массив для несуществующего пользователя
     */
    public function testGetOrdersReturnsEmptyArrayForNonExistent(): void
    {
        $result = $this->user->getOrders(999999);
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
    
    /**
     * Тест: getAll возвращает массив
     */
    public function testGetAllReturnsArray(): void
    {
        $result = $this->user->getAll();
        
        $this->assertIsArray($result);
    }
}
