<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Lib\User;

class OrderControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $_SESSION['cart'] = [];
    }

    public function testOrderCreation(): void
    {
        $ordersFile = __DIR__ . '/../../data/orders.json';

        if (file_exists($ordersFile)) {
            $orders = json_decode(file_get_contents($ordersFile), true) ?? [];

            if (!empty($orders)) {
                $lastOrder = end($orders);

                $this->assertArrayHasKey('id', $lastOrder);
                $this->assertArrayHasKey('email', $lastOrder);
                $this->assertArrayHasKey('total', $lastOrder);
                $this->assertArrayHasKey('status', $lastOrder);
                $this->assertArrayHasKey('created_at', $lastOrder);

                $this->assertMatchesRegularExpression(
                    '/^ORD-\d{8}-[A-Z0-9]{6}$/',
                    $lastOrder['id']
                );

                $this->assertContains(
                    $lastOrder['status'],
                    ['pending', 'paid', 'shipped', 'completed', 'cancelled']
                );
            }
        }
    }

    public function testOrderStatusValues(): void
    {
        $validStatuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];

        $ordersFile = __DIR__ . '/../../data/orders.json';

        if (file_exists($ordersFile)) {
            $orders = json_decode(file_get_contents($ordersFile), true) ?? [];

            foreach ($orders as $order) {
                $this->assertContains(
                    $order['status'],
                    $validStatuses,
                    "Статус '{$order['status']}' недопустим"
                );
            }
        }
    }

    public function testOrderTotalCalculation(): void
    {
        $ordersFile = __DIR__ . '/../../data/orders.json';

        if (file_exists($ordersFile)) {
            $orders = json_decode(file_get_contents($ordersFile), true) ?? [];

            foreach ($orders as $order) {
                $calculatedTotal = 0;

                if (!empty($order['items'])) {
                    foreach ($order['items'] as $item) {
                        $price = (int)preg_replace('/[^0-9]/', '', $item['price']);
                        $calculatedTotal += $price;
                    }
                }

                $this->assertEquals(
                    $calculatedTotal,
                    $order['total'],
                    "Сумма заказа {$order['id']} не совпадает"
                );
            }
        }
    }

    public function testOrderUserData(): void
    {
        $ordersFile = __DIR__ . '/../../data/orders.json';

        if (file_exists($ordersFile)) {
            $orders = json_decode(file_get_contents($ordersFile), true) ?? [];

            foreach ($orders as $order) {
                $this->assertNotEmpty($order['email']);
                $this->assertNotEmpty($order['name']);
                $this->assertNotEmpty($order['phone']);

                $this->assertTrue(
                    filter_var($order['email'], FILTER_VALIDATE_EMAIL) !== false,
                    "Email в заказе {$order['id']} невалиден"
                );
            }
        }
    }

    public function testOrderTimestamp(): void
    {
        $ordersFile = __DIR__ . '/../../data/orders.json';

        if (file_exists($ordersFile)) {
            $orders = json_decode(file_get_contents($ordersFile), true) ?? [];

            foreach ($orders as $order) {
                $this->assertArrayHasKey('created_at', $order);

                $timestamp = strtotime($order['created_at']);
                $this->assertIsInt($timestamp);
                $this->assertLessThanOrEqual(time(), $timestamp);
            }
        }
    }
}
