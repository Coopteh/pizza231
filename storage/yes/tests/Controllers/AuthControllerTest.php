<?php
namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;

class AuthControllerTest extends TestCase
{
    private AuthController $authController;
    
    protected function setUp(): void
    {
        $this->authController = new AuthController();
        
        // Запускаем сессию для тестов
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Очищаем сессию
        $_SESSION = [];
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
    
    /**
     * Тест: getCurrentUser возвращает null когда пользователь не вошёл
     */
    public function testGetCurrentUserReturnsNullWhenNotLoggedIn(): void
    {
        $_SESSION = [];
        
        $result = AuthController::getCurrentUser();
        
        $this->assertNull($result);
    }
    
    /**
     * Тест: getCurrentUser возвращает данные пользователя когда он вошёл
     */
    public function testGetCurrentUserReturnsUserWhenLoggedIn(): void
    {
        $_SESSION = [
            'user_id' => 1,
            'user_name' => 'Test User',
            'user_email' => 'test@example.com',
            'is_admin' => false,
            'is_verified' => true
        ];
        
        $result = AuthController::getCurrentUser();
        
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('Test User', $result['name']);
        $this->assertEquals('test@example.com', $result['email']);
        $this->assertFalse($result['is_admin']);
        $this->assertTrue($result['is_verified']);
    }
    
    /**
     * Тест: getCurrentUser возвращает is_admin из сессии
     */
    public function testGetCurrentUserReturnsIsAdmin(): void
    {
        $_SESSION = [
            'user_id' => 1,
            'user_name' => 'Admin User',
            'user_email' => 'admin@example.com',
            'is_admin' => true,
            'is_verified' => true
        ];
        
        $result = AuthController::getCurrentUser();
        
        $this->assertTrue($result['is_admin']);
    }
}
