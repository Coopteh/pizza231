<?php

namespace Tests\Security;

use PHPUnit\Framework\TestCase;
use Lib\User;
use Lib\Auth;

class AuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testSessionManagement(): void
    {
        User::login(2);

        $this->assertArrayHasKey('user_id', $_SESSION);
        $this->assertArrayHasKey('user_email', $_SESSION);
        $this->assertArrayHasKey('user_role', $_SESSION);
        $this->assertEquals(2, $_SESSION['user_id']);
        $this->assertEquals('admin', $_SESSION['user_role']);
    }

    public function testLogoutClearsSession(): void
    {
        User::login(2);
        $this->assertTrue(User::isLoggedIn());

        User::logout();

        $this->assertFalse(User::isLoggedIn());
        $this->assertEmpty($_SESSION);
    }

    public function testVerificationCodeExpiry(): void
    {
        $userData = [
            'name' => 'Expiry Test',
            'email' => 'expiry' . time() . '@test.com',
            'phone' => '+79990000011',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);
        $user = User::getUserById($userId);

        $this->assertNotNull($user['code_expires']);

        $expiryTime = strtotime($user['code_expires']);
        $currentTime = time();

        $this->assertGreaterThan($currentTime, $expiryTime);
        $this->assertLessThanOrEqual($currentTime + 900, $expiryTime);
    }

    public function testPasswordResetTokenGeneration(): void
    {
        $userData = [
            'name' => 'Reset Token Test',
            'email' => 'reset' . time() . '@test.com',
            'phone' => '+79990000012',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);
        $token = User::generateResetToken($userId);

        $this->assertIsString($token);
        $this->assertEquals(64, strlen($token));
    }

    public function testPasswordResetTokenValidation(): void
    {
        $userData = [
            'name' => 'Token Validate Test',
            'email' => 'tokenval' . time() . '@test.com',
            'phone' => '+79990000013',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);
        $token = User::generateResetToken($userId);

        $validatedId = User::validateResetToken($token);

        $this->assertEquals($userId, $validatedId);
    }

    public function testInvalidResetToken(): void
    {
        $validatedId = User::validateResetToken('invalid_token_here');

        $this->assertNull($validatedId);
    }

    public function testResendVerificationCode(): void
    {
        $userData = [
            'name' => 'Resend Test',
            'email' => 'resend' . time() . '@test.com',
            'phone' => '+79990000014',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);
        $originalCode = User::getUserById($userId)['verification_code'];

        $newCode = User::resendVerificationCode('resend' . time() . '@test.com');

        $this->assertIsString($newCode);
        $this->assertNotEquals($originalCode, $newCode);
        $this->assertEquals(6, strlen($newCode));
    }

    public function testResendCodeForVerifiedUser(): void
    {
        $userData = [
            'name' => 'Verified Resend',
            'email' => 'verifiedresend' . time() . '@test.com',
            'phone' => '+79990000015',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);
        $code = User::getUserById($userId)['verification_code'];
        User::verifyUserByCode($userData['email'], $code);

        $newCode = User::resendVerificationCode($userData['email']);

        $this->assertNull($newCode);
    }
}
