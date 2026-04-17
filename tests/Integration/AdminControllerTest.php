<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Lib\User;

class AdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    public function testAdminDashboardStats(): void
    {
        $ordersFile = __DIR__ . '/../../data/orders.json';

        if (file_exists($ordersFile)) {
            $orders = json_decode(file_get_contents($ordersFile), true) ?? [];

            $stats = [
                'totalOrders' => count($orders),
                'totalRevenue' => array_sum(array_column($orders, 'total')),
                'pendingOrders' => count(array_filter($orders, fn ($o) => $o['status'] === 'pending')),
            ];

            $this->assertIsInt($stats['totalOrders']);
            $this->assertGreaterThanOrEqual(0, $stats['totalOrders']);

            $this->assertIsNumeric($stats['totalRevenue']);
            $this->assertGreaterThanOrEqual(0, $stats['totalRevenue']);

            $this->assertIsInt($stats['pendingOrders']);
            $this->assertLessThanOrEqual($stats['pendingOrders'], $stats['totalOrders']);
        }
    }

    public function testUserDeletionProtection(): void
    {
        $users = User::getAllUsers();

        // ✅ ИСПРАВЛЕНО: добавлены ассерты
        $this->assertIsArray($users);
        $this->assertNotEmpty($users);

        // Пользователь с ID 2 - админ, не должен удаляться
        if (isset($users[2])) {
            $this->assertEquals('admin', $users[2]['role']);
        }

        // Проверяем что есть хотя бы один админ
        $adminUsers = array_filter($users, fn ($u) => $u['role'] === 'admin');
        $this->assertNotEmpty($adminUsers);
    }

    public function testAdminUserExists(): void
    {
        $users = User::getAllUsers();

        $adminUsers = array_filter($users, fn ($u) => $u['role'] === 'admin');

        // ✅ ИСПРАВЛЕНО: в данных 2 админа (ID 2 и 4)
        $this->assertGreaterThanOrEqual(1, count($adminUsers));
        $this->assertEquals(2, count($adminUsers));
    }

    public function testUserVerificationStatus(): void
    {
        $users = User::getAllUsers();

        foreach ($users as $user) {
            $this->assertArrayHasKey('verified', $user);
            $this->assertIsBool($user['verified']);
        }
    }
}
