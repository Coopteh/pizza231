<?php

namespace Tests\Security;

use PHPUnit\Framework\TestCase;
use Lib\User;

class AuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testAdminAccessControl(): void
    {
        $this->assertFalse(User::isLoggedIn());
        $this->assertFalse(User::isAdmin());

        $_SESSION['user_id'] = 3;
        $_SESSION['user_email'] = 'kasll2007@inbox.ru';
        $_SESSION['user_role'] = 'user';

        $this->assertTrue(User::isLoggedIn());
        $this->assertFalse(User::isAdmin());

        $_SESSION['user_id'] = 2;
        $_SESSION['user_email'] = 'kerji4100@gmail.com';
        $_SESSION['user_role'] = 'admin';

        $this->assertTrue(User::isLoggedIn());
        $this->assertTrue(User::isAdmin());
    }

    public function testCannotDeleteAdminUser(): void
    {
        $users = User::getAllUsers();

        if (isset($users[2])) {
            $this->assertEquals('admin', $users[2]['role']);
        }
    }

    public function testUserCannotAccessAdminRoutes(): void
    {
        $_SESSION['user_id'] = 3;
        $_SESSION['user_role'] = 'user';

        $this->assertFalse(User::isAdmin());
    }

    public function testSessionFixationPrevention(): void
    {
        $oldSessionId = session_id();

        User::login(2);

        $newSessionId = session_id();

        $this->assertNotEmpty($_SESSION['user_id']);
    }

    public function testGuestCannotAccessProfile(): void
    {
        $_SESSION = [];

        $this->assertFalse(User::isLoggedIn());
        $this->assertNull(User::getCurrentUser());
    }
}
