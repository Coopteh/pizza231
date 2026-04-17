<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Lib\User;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        User::init();
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        parent::tearDown();
    }

    public function testCreateUser(): void
    {
        $userData = [
            'name' => 'Тестовый Пользователь',
            'email' => 'test' . time() . '@example.com',
            'phone' => '+79990000000',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);

        $this->assertIsInt($userId);
        $this->assertGreaterThan(0, $userId);

        $user = User::getUserById($userId);
        $this->assertNotNull($user);
        $this->assertEquals($userData['email'], $user['email']);
        $this->assertFalse($user['verified']);
        $this->assertNotNull($user['verification_code']);
    }

    public function testPasswordHashing(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'hash' . time() . '@test.com',
            'phone' => '+79990000002',
            'password' => 'securePassword123'
        ];

        $userId = User::createUser($userData);
        $user = User::getUserById($userId);

        $this->assertStringStartsWith('$2y$', $user['password']);
        $this->assertTrue(password_verify('securePassword123', $user['password']));
        $this->assertFalse(password_verify('wrongPassword', $user['password']));
    }

    public function testVerifyUserByCode(): void
    {
        $userData = [
            'name' => 'Verify Test',
            'email' => 'verify' . time() . '@test.com',
            'phone' => '+79990000003',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);
        $user = User::getUserById($userId);
        $code = $user['verification_code'];

        $result = User::verifyUserByCode($userData['email'], $code);

        $this->assertTrue($result);

        $updatedUser = User::getUserById($userId);
        $this->assertTrue($updatedUser['verified']);
        $this->assertNull($updatedUser['verification_code']);
    }

    public function testValidateLogin(): void
    {
        $userData = [
            'name' => 'Login Test',
            'email' => 'login' . time() . '@test.com',
            'phone' => '+79990000005',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);
        User::verifyUserByCode(
            $userData['email'],
            User::getUserById($userId)['verification_code']
        );

        $result = User::validateLogin($userData['email'], 'password123');

        $this->assertNotNull($result);
        $this->assertEquals($userId, $result['id']);
        $this->assertArrayNotHasKey('error', $result);
    }

    public function testIsAdmin(): void
    {
        $_SESSION['user_id'] = 2;
        $_SESSION['user_email'] = 'kerji4100@gmail.com';

        $this->assertTrue(User::isAdmin());

        $_SESSION['user_id'] = 3;
        $_SESSION['user_email'] = 'kasll2007@inbox.ru';

        $this->assertFalse(User::isAdmin());
    }

    public function testGetUserOrders(): void
    {
        $orders = User::getUserOrders(4);

        $this->assertIsArray($orders);

        foreach ($orders as $order) {
            $this->assertEquals(4, $order['user_id']);
            $this->assertArrayHasKey('id', $order);
            $this->assertArrayHasKey('total', $order);
        }
    }

    public function testGeneratePasswordChangeCode(): void
    {
        $userData = [
            'name' => 'Password Change Test',
            'email' => 'passchange' . time() . '@test.com',
            'phone' => '+79990000008',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);

        $code = User::generatePasswordChangeCode($userId);

        $this->assertIsString($code);
        $this->assertEquals(6, strlen($code));  // ✅ ИСПРАВЛЕНО: было setLength()
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
    }

    public function testVerifyPasswordChangeCode(): void
    {
        $userData = [
            'name' => 'Verify Pass Change',
            'email' => 'verifypass' . time() . '@test.com',
            'phone' => '+79990000009',
            'password' => 'password123'
        ];

        $userId = User::createUser($userData);
        $code = User::generatePasswordChangeCode($userId);

        $result = User::verifyPasswordChangeCode($userId, $code);

        $this->assertTrue($result);
    }

    public function testUpdatePassword(): void
    {
        $userData = [
            'name' => 'Update Pass Test',
            'email' => 'updatepass' . time() . '@test.com',
            'phone' => '+79990000010',
            'password' => 'oldpassword'
        ];

        $userId = User::createUser($userData);

        $result = User::updatePassword($userId, 'newpassword123');

        $this->assertTrue($result);

        $user = User::getUserById($userId);
        $this->assertTrue(password_verify('newpassword123', $user['password']));
        $this->assertFalse(password_verify('oldpassword', $user['password']));
    }

    public function testCreateUserWithExistingEmail(): void
    {
        $email = 'duplicate' . time() . '@example.com';

        $userData1 = [
            'name' => 'Пользователь 1',
            'email' => $email,
            'phone' => '+79990000001',
            'password' => 'password123'
        ];

        $userId1 = User::createUser($userData1);
        $this->assertIsInt($userId1);

        $existingUser = User::getUserByEmail($email);
        $this->assertNotNull($existingUser);
        $this->assertEquals($email, $existingUser['email']);
    }

    public function testValidateLoginWithWrongPassword(): void
    {
        $userData = [
            'name' => 'Wrong Pass Test',
            'email' => 'wrongpass' . time() . '@test.com',
            'phone' => '+79990000006',
            'password' => 'password123'
        ];

        User::createUser($userData);

        $result = User::validateLogin($userData['email'], 'wrongpassword');

        $this->assertNull($result);
    }

    public function testValidateLoginWithEmailNotVerified(): void
    {
        $userData = [
            'name' => 'Not Verified Test',
            'email' => 'notverified' . time() . '@test.com',
            'phone' => '+79990000007',
            'password' => 'password123'
        ];

        User::createUser($userData);

        $result = User::validateLogin($userData['email'], 'password123');

        $this->assertNotNull($result);
        $this->assertEquals('email_not_verified', $result['error']);
    }

    public function testVerifyUserWithWrongCode(): void
    {
        $userData = [
            'name' => 'Wrong Code Test',
            'email' => 'wrongcode' . time() . '@test.com',
            'phone' => '+79990000004',
            'password' => 'password123'
        ];

        User::createUser($userData);

        $result = User::verifyUserByCode($userData['email'], '999999');

        $this->assertFalse($result);
    }
}
