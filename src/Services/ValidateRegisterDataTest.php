<?php
namespace Test\Services;

use App\Services\ValidateRegisterData;
use PHPUnit\Framework\TestCase;

class ValidateRegisterDataTest extends TestCase
{
    private ValidateRegisterData $validator;
    
    protected function setUp(): void
    {
        $this->validator = new ValidateRegisterData();
    }
    
    public function testValidRegistrationData(): void
    {
        $data = [
            'username' => '  Ivan Ivanov  ',
            'email' => ' test@Example.COM ',
            'password' => 'secure123',
            'password_confirm' => 'secure123'
        ];
        
        $result = $this->validator->validate($data);
        
        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
        $this->assertEquals('Ivan Ivanov', $result['data']['username']);
        $this->assertEquals('test@example.com', $result['data']['email']);
    }
    
    public function testEmptyUsername(): void
    {
        $result = $this->validator->validate([
            'username' => '',
            'email' => 'test@example.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);
        
        $this->assertFalse($result['valid']);
        $this->assertContains('Имя пользователя обязательно для заполнения', $result['errors']);
    }
    
    public function testShortUsername(): void
    {
        $result = $this->validator->validate([
            'username' => 'A',
            'email' => 'test@example.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);
        
        $this->assertContains('Имя должно содержать не менее 2 символов', $result['errors']);
    }
    
    public function testInvalidEmail(): void
    {
        $result = $this->validator->validate([
            'username' => 'Ivan',
            'email' => 'not-an-email',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);
        
        $this->assertContains('Некорректный формат email', $result['errors']);
    }
    
    public function testEmailUniqueness(): void
    {
        $this->validator->setEmailExistsCallback(fn($email) => $email === 'exists@example.com');
        
        $result = $this->validator->validate([
            'username' => 'Ivan',
            'email' => 'exists@example.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);
        
        $this->assertContains('Пользователь с таким email уже зарегистрирован', $result['errors']);
    }
    
    public function testShortPassword(): void
    {
        $result = $this->validator->validate([
            'username' => 'Ivan',
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirm' => '123'
        ]);
        
        $this->assertContains('Пароль должен содержать не менее 6 символов', $result['errors']);
    }
    
    public function testPasswordMismatch(): void
    {
        $result = $this->validator->validate([
            'username' => 'Ivan',
            'email' => 'test@example.com',
            'password' => 'password1',
            'password_confirm' => 'password2'
        ]);
        
        $this->assertContains('Пароли не совпадают', $result['errors']);
    }
    
    public function testXssSanitization(): void
    {
        $result = $this->validator->validate([
            'username' => '<script>alert("xss")</script>Ivan',
            'email' => 'test@example.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);
        
        $this->assertTrue($result['valid']);
        $this->assertEquals('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;Ivan', $result['data']['username']);
    }
    
    public function testSqlInjectionAttempt(): void
    {
        $result = $this->validator->validate([
            'username' => "admin' OR '1'='1",
            'email' => 'test@example.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);
        
        $this->assertTrue($result['valid']);
        // htmlspecialchars экранирует кавычки
        $this->assertStringContainsString('&#039;', $result['data']['username']);
    }
}