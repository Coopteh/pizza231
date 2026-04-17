<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Services\ValidateOrderData;

class ValidateOrderDataTest extends TestCase
{
    public function testValidOrderDataEmailDelivery(): void
    {
        $validator = new ValidateOrderData();
        $result = $validator->validate([
            'fio' => 'Иванов Иван Иванович',
            'phone' => '+7 (999) 123-45-67',
            'delivery_type' => 'email',
            'email' => 'customer@example.com'
        ]);

        $this->assertTrue($result['valid']);
        $this->assertEquals('+79991234567', $result['sanitized']['phone']);
    }

    public function testValidOrderDataAddressDelivery(): void
    {
        $validator = new ValidateOrderData();
        $result = $validator->validate([
            'fio' => 'Петров Петр',
            'phone' => '89001112233',
            'delivery_type' => 'address',
            'address' => 'г. Москва, ул. Ленина, д. 10, кв. 5'
        ]);

        $this->assertTrue($result['valid']);
    }

    public function testShortFio(): void
    {
        $validator = new ValidateOrderData();
        $result = $validator->validate([
            'fio' => 'Ив',
            'phone' => '+79991234567',
            'delivery_type' => 'email',
            'email' => 'test@example.com'
        ]);

        $this->assertFalse($result['valid']);
        $this->assertContains('ФИО должно содержать минимум 3 символа', $result['errors']);
    }

    public function testInvalidAddressLength(): void
    {
        $validator = new ValidateOrderData();
        $result = $validator->validate([
            'fio' => 'Иванов Иван',
            'phone' => '+79991234567',
            'delivery_type' => 'address',
            'address' => 'Коротко'
        ]);

        $this->assertFalse($result['valid']);
        $this->assertContains('Адрес должен содержать от 10 до 200 символов', $result['errors']);
    }

    public function testPhoneSanitization(): void
    {
        $validator = new ValidateOrderData();
        $result = $validator->validate([
            'fio' => 'Сидоров С.',
            'phone' => '+7 (495) 123-45-67 доб. 123',
            'delivery_type' => 'email',
            'email' => 'test@example.com'
        ]);

        $this->assertEquals('+74951234567', $result['sanitized']['phone']);
    }
}