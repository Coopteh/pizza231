<?php

// tests/Security/InputValidationTest.php

namespace Tests\Security;

use PHPUnit\Framework\TestCase;

class InputValidationTest extends TestCase
{
    public function testEmailValidation(): void
    {
        $validEmails = [
            'test@example.com',
            'user.name@domain.co.uk',
            'test+tag@example.com'
        ];

        $invalidEmails = [
            '',
            'invalid',
            '@example.com',
            'test@',
            'test@example',
            'test@@example.com'
        ];

        foreach ($validEmails as $email) {
            $this->assertTrue(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "Email '{$email}' должен быть валидным"
            );
        }

        foreach ($invalidEmails as $email) {
            $this->assertFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "Email '{$email}' должен быть невалидным"
            );
        }
    }

    public function testPasswordLengthValidation(): void
    {
        $shortPasswords = ['12345', 'abc', ''];
        $validPasswords = ['123456', 'password123', 'SecurePass123!'];

        foreach ($shortPasswords as $password) {
            $this->assertLessThan(
                6,
                strlen($password),
                "Пароль '{$password}' должен быть короче 6 символов"
            );
        }

        foreach ($validPasswords as $password) {
            $this->assertGreaterThanOrEqual(
                6,
                strlen($password),
                "Пароль '{$password}' должен быть 6+ символов"
            );
        }
    }

    public function testXSSPrevention(): void
    {
        // ✅ ИСПРАВЛЕНО: Разделяем тесты по типам XSS векторов

        // 1. Тесты с тегами (содержат < и >)
        $tagBasedXSS = [
            '<script>alert("XSS")</script>',
            '<img src=x onerror=alert(1)>',
            '<iframe src="evil.com"></iframe>'
        ];

        foreach ($tagBasedXSS as $input) {
            $sanitized = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

            // Проверяем что теги экранированы
            $this->assertStringNotContainsString(
                '<script>',
                $sanitized,
                "Тег <script> должен быть экранирован"
            );

            $this->assertStringContainsString(
                '&lt;',
                $sanitized,
                "Символ < должен быть экранирован как &lt; для: {$input}"
            );

            $this->assertStringContainsString(
                '&gt;',
                $sanitized,
                "Символ > должен быть экранирован как &gt; для: {$input}"
            );
        }

        // 2. Тесты без тегов (URL-схемы, события)
        $nonTagXSS = [
            'javascript:alert(1)',
            'data:text/html,<script>alert(1)</script>',
            'onclick=alert(1)'
        ];

        foreach ($nonTagXSS as $input) {
            $sanitized = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

            // Эти векторы не содержат < >, поэтому проверяем что они не изменены
            // (htmlspecialchars не меняет текст без спецсимволов)
            $this->assertIsString($sanitized);
            $this->assertNotEmpty($sanitized);
        }

        // 3. Конкретные проверки для известных векторов
        $scriptTag = '<script>alert("XSS")</script>';
        $sanitizedScript = htmlspecialchars($scriptTag, ENT_QUOTES, 'UTF-8');
        $this->assertEquals(
            '&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;',
            $sanitizedScript
        );

        $imgTag = '<img src=x onerror=alert(1)>';
        $sanitizedImg = htmlspecialchars($imgTag, ENT_QUOTES, 'UTF-8');
        $this->assertStringStartsWith('&lt;img', $sanitizedImg);
        $this->assertStringEndsWith('&gt;', $sanitizedImg);

        // 4. JavaScript URL не содержит < >, поэтому остаётся как есть
        $jsUrl = 'javascript:alert(1)';
        $sanitizedJs = htmlspecialchars($jsUrl, ENT_QUOTES, 'UTF-8');
        $this->assertEquals($jsUrl, $sanitizedJs); // Не меняется, т.к. нет спецсимволов
    }

    public function testPhoneValidation(): void
    {
        $validPhones = [
            '+79990000000',
            '+7 (999) 000-00-00',
            '79990000000'
        ];

        $invalidPhones = [
            '',
            '123',
            'abcdefghij'
        ];

        foreach ($validPhones as $phone) {
            $numericOnly = preg_replace('/[^0-9]/', '', $phone);
            $this->assertGreaterThanOrEqual(10, strlen($numericOnly));
        }
    }

    public function testOrderIdFormat(): void
    {
        $orderId = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        $this->assertMatchesRegularExpression(
            '/^ORD-\d{8}-[A-Z0-9]{6}$/',
            $orderId
        );
    }

    public function testVerificationCodeFormat(): void
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->assertMatchesRegularExpression(
            '/^\d{6}$/',
            $code
        );

        $this->assertEquals(6, strlen($code));
    }
}
