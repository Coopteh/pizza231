<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Lib\Auth;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testSendVerificationEmailFormat(): void
    {
        $reflection = new \ReflectionClass(Auth::class);
        $method = $reflection->getMethod('buildVerificationEmailWithCode');
        $method->setAccessible(true);

        $name = 'Тестовый Пользователь';
        $code = '123456';

        $emailBody = $method->invoke(null, $name, $code);

        $this->assertIsString($emailBody);
        $this->assertStringContainsString($name, $emailBody);
        $this->assertStringContainsString($code, $emailBody);
        $this->assertStringContainsString('15 минут', $emailBody);
        $this->assertStringContainsString('DOCTYPE html', $emailBody);
    }

    public function testSendPasswordResetEmailFormat(): void
    {
        $reflection = new \ReflectionClass(Auth::class);
        $method = $reflection->getMethod('buildResetEmail');
        $method->setAccessible(true);

        $name = 'Тест';
        $token = bin2hex(random_bytes(32));

        $emailBody = $method->invoke(null, $name, $token);

        $this->assertIsString($emailBody);
        $this->assertStringContainsString($name, $emailBody);
        $this->assertStringContainsString($token, $emailBody);
        $this->assertStringContainsString('Сброс пароля', $emailBody);
        $this->assertStringContainsString('1 час', $emailBody);
    }

    public function testSendPasswordChangeCodeFormat(): void
    {
        $reflection = new \ReflectionClass(Auth::class);
        $method = $reflection->getMethod('buildPasswordChangeEmail');
        $method->setAccessible(true);

        $name = 'Тест';
        $code = '654321';

        $emailBody = $method->invoke(null, $name, $code);

        $this->assertIsString($emailBody);
        $this->assertStringContainsString($name, $emailBody);
        $this->assertStringContainsString($code, $emailBody);
        $this->assertStringContainsString('10 минут', $emailBody);
    }

    public function testEmailHeaders(): void
    {
        $reflection = new \ReflectionClass(Auth::class);
        $method = $reflection->getMethod('getHeaders');
        $method->setAccessible(true);

        $headers = $method->invoke(null);

        $this->assertIsString($headers);
        $this->assertStringContainsString('MIME-Version: 1.0', $headers);
        $this->assertStringContainsString('Content-type: text/html', $headers);
        $this->assertStringContainsString('From:', $headers);
        $this->assertStringContainsString('Reply-To:', $headers);
    }

    public function testVerificationCodeLength(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $this->assertEquals(6, strlen($code));
            $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
        }
    }

    public function testResetTokenLength(): void
    {
        $token = bin2hex(random_bytes(32));
        $this->assertEquals(64, strlen($token));
    }

    public function testResendCodeEmailFormat(): void
    {
        $reflection = new \ReflectionClass(Auth::class);
        $method = $reflection->getMethod('buildResendCodeEmail');
        $method->setAccessible(true);

        $name = 'Тест';
        $code = '111222';

        $emailBody = $method->invoke(null, $name, $code);

        $this->assertIsString($emailBody);
        $this->assertStringContainsString('Новый код подтверждения', $emailBody);
        $this->assertStringContainsString($code, $emailBody);
    }

    public function testEmailXSSProtection(): void
    {
        $reflection = new \ReflectionClass(Auth::class);
        $method = $reflection->getMethod('buildVerificationEmailWithCode');
        $method->setAccessible(true);

        $maliciousName = '<script>alert("XSS")</script>';
        $code = '123456';

        $emailBody = $method->invoke(null, $maliciousName, $code);

        $this->assertStringNotContainsString('<script>', $emailBody);
        $this->assertStringContainsString('&lt;script&gt;', $emailBody);
    }
}
