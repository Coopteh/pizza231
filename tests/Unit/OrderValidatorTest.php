<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Lib\OrderValidator;

class OrderValidatorTest extends TestCase
{
    public function testValidData()
    {
        $data = [
            'name' => 'Иванов Иван Иванович',
            'email' => 'ivan@example.com',
            'phone' => '+7 (999) 123-45-67',
            'address' => 'г. Москва, ул. Пушкина, д. 10'
        ];
        $result = OrderValidator::validate($data);
        $this->assertEmpty($result['errors']);
    }

    public function testShortFio()
    {
        $data = ['name' => 'Ив', 'email' => 't@t.com', 'phone' => '+79991234567', 'address' => 'Адрес длинный достаточный'];
        $result = OrderValidator::validate($data);
        $this->assertContains("ФИО должно содержать более 3 символов", $result['errors']);
    }

    public function testShortAddress()
    {
        $data = ['name' => 'Иванов', 'email' => 't@t.com', 'phone' => '+79991234567', 'address' => 'Мало'];
        $result = OrderValidator::validate($data);
        $this->assertContains("Адрес должен содержать более 10 символов", $result['errors']);
    }

    public function testLongAddress()
    {
        $data = [
            'name' => 'Иванов', 
            'email' => 't@t.com', 
            'phone' => '+79991234567', 
            'address' => str_repeat('a', 201)
        ];
        $result = OrderValidator::validate($data);
        $this->assertContains("Адрес не должен превышать 200 символов", $result['errors']);
    }

    public function testInvalidPhone()
    {
        // filter_var удалит буквы, останется пусто или мало символов
        $data = ['name' => 'Иванов', 'email' => 't@t.com', 'phone' => 'абв', 'address' => 'Адрес длинный достаточный'];
        $result = OrderValidator::validate($data);
        $this->assertContains("Некорректный номер телефона", $result['errors']);
    }
    
    public function testPhoneSanitization()
    {
        $data = [
            'name' => 'Иванов', 
            'email' => 't@t.com', 
            'phone' => '8 (999) 123-45-67', 
            'address' => 'Адрес длинный достаточный'
        ];
        $result = OrderValidator::validate($data);
        $this->assertEmpty($result['errors']);
        // Проверяем, что остались только цифры (и возможно +)
        // filter_var(SANITIZE_NUMBER_INT) оставляет только цифры и знаки.
        $this->assertEquals('89991234567', $result['data']['phone']);
    }
}