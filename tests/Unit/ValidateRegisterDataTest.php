<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Services\ValidateRegisterData;
use Models\User;

class ValidateRegisterDataTest extends TestCase
{
    public function testValidRegistrationData(): void
    {
        $validator = new ValidateRegisterData();
        $result = $validator->validate([
            'name' => 'Иван Иванов',
            'email' => 'ivan@example.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
    }

    public function testInvalidEmail(): void
    {
        $validator = new ValidateRegisterData();
        $result = $validator->validate([
            'name' => 'Иван',
            'email' => 'invalid-email',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);

        $this->assertFalse($result['valid']);
        $this->assertContains('Некорректный email', $result['errors']);
    }

    public function testShortPassword(): void
    {
        $validator = new ValidateRegisterData();
        $result = $validator->validate([
            'name' => 'Иван',
            'email' => 'ivan@example.com',
            'password' => '123',
            'password_confirm' => '123'
        ]);

        $this->assertFalse($result['valid']);
        $this->assertContains('Пароль должен содержать минимум 6 символов', $result['errors']);
    }

    public function testPasswordMismatch(): void
    {
        $validator = new ValidateRegisterData();
        $result = $validator->validate([
            'name' => 'Иван',
            'email' => 'ivan@example.com',
            'password' => '123456',
            'password_confirm' => '654321'
        ]);

        $this->assertFalse($result['valid']);
        $this->assertContains('Пароли не совпадают', $result['errors']);
    }

    public function testSanitization(): void
    {
        $validator = new ValidateRegisterData();
        $result = $validator->validate([
            'name' => '<script>alert("xss")</script>Иван',
            'email' => 'ivan@example.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ]);

        $this->assertStringNotContainsString('<script>', $result['sanitized']['name']);
        $this->assertStringContainsString('Иван', $result['sanitized']['name']);
    }
}