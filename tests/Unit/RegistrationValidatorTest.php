<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Lib\RegistrationValidator;

// Мокируем User, если нужно проверять уникальность без БД,
// но здесь мы тестируем логику валидатора.
// Для теста уникальности лучше было бы внедрить зависимость, 
// но следуя заданию просто тестируем валидатор "как есть".

class RegistrationValidatorTest extends TestCase
{
    public function testValidData()
    {
        $data = [
            'name' => 'Ivan',
            'email' => 'ivan@example.com',
            'password' => 'password123',
            'password_confirm' => 'password123'
        ];
        $result = RegistrationValidator::validate($data);
        $this->assertEmpty($result['errors']);
        $this->assertEquals('Ivan', $result['data']['name']);
    }

    public function testMissingName()
    {
        $data = ['name' => '', 'email' => 'test@test.com', 'password' => '123456', 'password_confirm' => '123456'];
        $result = RegistrationValidator::validate($data);
        $this->assertNotEmpty($result['errors']);
        $this->assertContains("Имя пользователя обязательно", $result['errors']);
    }

    public function testInvalidEmail()
    {
        $data = ['name' => 'Ivan', 'email' => 'invalid-email', 'password' => '123456', 'password_confirm' => '123456'];
        $result = RegistrationValidator::validate($data);
        $this->assertContains("Некорректный формат Email", $result['errors']);
    }

    public function testShortPassword()
    {
        $data = ['name' => 'Ivan', 'email' => 'ivan@test.com', 'password' => '123', 'password_confirm' => '123'];
        $result = RegistrationValidator::validate($data);
        $this->assertContains("Пароль должен быть не менее 6 символов", $result['errors']);
    }

    public function testPasswordMismatch()
    {
        $data = ['name' => 'Ivan', 'email' => 'ivan@test.com', 'password' => '123456', 'password_confirm' => '654321'];
        $result = RegistrationValidator::validate($data);
        $this->assertContains("Пароли не совпадают", $result['errors']);
    }

    public function testSanitization()
    {
        // Тест на инъекцию
        $data = [
            'name' => '<script>alert("hack")</script> Ivan ',
            'email' => 'ivan@test.com',
            'password' => '123456',
            'password_confirm' => '123456'
        ];
        $result = RegistrationValidator::validate($data);
        $this->assertEmpty($result['errors']);
        // Проверяем, что теги удалены и пробелы убраны
        $this->assertEquals('Ivan', $result['data']['name']); 
        // htmlspecialchars применяется, но strip_tags удалит скрипт.
        // Результат: Ivan (trim уберет пробелы)
    }
}