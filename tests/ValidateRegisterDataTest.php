<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use App\Services\ValidateRegisterData;

class ValidateRegisterDataTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Инициализируем сессию для тестов
        if (session_status() === PHP_SESSION_NONE) {
            // Для CLI-тестов эмулируем сессию через массив
            $_SESSION = [];
        } else {
            // Очищаем сессию перед каждым тестом
            $_SESSION = [];
        }
    }

    protected function tearDown(): void
    {
        // Очищаем flash-сообщения после каждого теста
        unset($_SESSION['flash']);
        parent::tearDown();
    }

    public function testSanitationArray()
    {
        $data = [
            'username' => '<p>Ivan<br></p>',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123'
        ];
        ValidateRegisterData::sanitationArray($data);
        $this->assertEquals("Ivan", $data["username"]);   
    }
    /**
     * Вспомогательный метод для получения сообщения из сессии
     */
    private function getFlashMessage(): ?string
    {
        return $_SESSION['flash'] ?? null;
    }

    // ==================== Тесты на обязательные поля ====================

    public function testValidateFailsWhenUsernameIsEmpty(): void
    {
        $data = [
            'username' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123'
        ];

        $result = ValidateRegisterData::validate($data);

        $this->assertFalse($result);
        $this->assertEquals("Имя пользователя обязательно", $this->getFlashMessage());
    }

    public function testValidateFailsWhenUsernameIsMissing(): void
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123'
        ];

        $result = ValidateRegisterData::validate($data);

        $this->assertFalse($result);
        $this->assertEquals("Имя пользователя обязательно", $this->getFlashMessage());
    }

    public function testValidateFailsWhenEmailIsEmpty(): void
    {
        $data = [
            'username' => 'testuser',
            'email' => '',
            'password' => 'password123',
            'confirm_password' => 'password123'
        ];

        $result = ValidateRegisterData::validate($data);

        $this->assertFalse($result);
        $this->assertEquals("Email обязателен", $this->getFlashMessage());
    }

    public function testValidateFailsWhenEmailIsInvalid(): void
    {
        $data = [
            'username' => 'testuser',
            'email' => 'invalid-email',
            'password' => 'password123',
            'confirm_password' => 'password123'
        ];

        $result = ValidateRegisterData::validate($data);

        $this->assertFalse($result);
        $this->assertEquals("Некорректный email", $this->getFlashMessage());
    }

    public function testValidateFailsWhenPasswordIsEmpty(): void
    {
        $data = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => '',
            'confirm_password' => ''
        ];

        $result = ValidateRegisterData::validate($data);

        $this->assertFalse($result);
        $this->assertEquals("Пароль обязателен", $this->getFlashMessage());
    }

    public function testValidateFailsWhenPasswordIsTooShort(): void
    {
        $data = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => '12345', // 5 символов
            'confirm_password' => '12345'
        ];

        $result = ValidateRegisterData::validate($data);

        $this->assertFalse($result);
        $this->assertEquals("Пароль должен быть не менее 6 символов", $this->getFlashMessage());
    }

    public function testValidateFailsWhenPasswordsDoNotMatch(): void
    {
        $data = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm_password' => 'different_password'
        ];

        $result = ValidateRegisterData::validate($data);

        $this->assertFalse($result);
        $this->assertEquals("Пароли не совпадают", $this->getFlashMessage());
    }

// ==================== Тесты успешной валидации ====================

    public function testValidateSucceedsWithValidDataInNonDbMode(): void
    {       
        $data = [
            'username' => 'validuser',
            'email' => 'valid@example.com',
            'password' => 'securepass123',
            'confirm_password' => 'securepass123'
        ];

        $result = ValidateRegisterData::validate($data);
        $this->assertTrue($result);
    }

    public function testValidateSucceedsWithMinimalValidPassword(): void
    {
        $data = [
            'username' => 'user',
            'email' => 'u@ex.com',
            'password' => '123456', // Ровно 6 символов
            'confirm_password' => '123456'
        ];

        $result = ValidateRegisterData::validate($data);

        $this->assertTrue($result);
    }
}