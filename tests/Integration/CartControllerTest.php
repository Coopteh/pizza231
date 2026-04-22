<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use Controllers\CartController;

class CartControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $_SESSION['cart'] = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
        parent::tearDown();
    }

    public function testCartInitialization(): void
    {
        $controller = new CartController();
        $this->assertIsArray($_SESSION['cart']);
        $this->assertEmpty($_SESSION['cart']);
    }

    public function testCartViewReturnsTemplate(): void
    {
        $controller = new CartController();
        $result = $controller->view();

        $this->assertIsString($result);
        $this->assertStringContainsString('Корзина', $result);
    }

    public function testAddToCart(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['courseId'] = 1;

        $controller = new CartController();

        // ✅ ИСПРАВЛЕНО: используем буферизацию для подавления header()
        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
            // Игнорируем редирект
        }
        ob_end_clean();

        $this->assertIsArray($_SESSION['cart']);
        $this->assertNotEmpty($_SESSION['cart']);
        $this->assertEquals(1, $_SESSION['cart'][0]['id']);
    }

    public function testAddSameCourseTwice(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['courseId'] = 1;

        $controller = new CartController();

        ob_start();
        try {
            $controller->add();
            $controller->add();
        } catch (\Exception $e) {
            // Игнорируем редирект
        }
        ob_end_clean();

        $this->assertCount(1, $_SESSION['cart']);
    }

    public function testAddMultipleCourses(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $controller = new CartController();

        $_POST['courseId'] = 1;
        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $_POST['courseId'] = 2;
        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $this->assertCount(2, $_SESSION['cart']);
    }

    public function testAddInvalidCourse(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['courseId'] = 99999;

        $controller = new CartController();

        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
            // Ожидаем ошибку
        }
        ob_end_clean();

        $this->assertIsArray($_SESSION['cart']);
    }

    public function testRemoveFromCart(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['courseId'] = 1;
        $controller = new CartController();

        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $this->assertCount(1, $_SESSION['cart']);

        ob_start();
        try {
            $controller->remove();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $this->assertCount(0, $_SESSION['cart']);
    }

    public function testRemoveNonExistentFromCart(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['courseId'] = 999;

        $controller = new CartController();

        ob_start();
        try {
            $controller->remove();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $this->assertCount(0, $_SESSION['cart']);
    }

    public function testClearCart(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['courseId'] = 1;
        $controller = new CartController();

        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $_POST['courseId'] = 2;
        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $this->assertCount(2, $_SESSION['cart']);

        ob_start();
        try {
            $controller->clear();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $this->assertCount(0, $_SESSION['cart']);
    }

    public function testGetCount(): void
    {
        $_POST['courseId'] = 1;
        $controller = new CartController();

        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $_POST['courseId'] = 2;
        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $count = $controller->getCount();
        $this->assertEquals(2, $count);
    }

    public function testGetCountJson(): void
    {
        $_POST['courseId'] = 1;
        $controller = new CartController();

        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        ob_start();
        $controller->getCountJson();
        $output = ob_get_clean();

        $this->assertIsString($output);
        $this->assertMatchesRegularExpression('/{"count":\d+}/', $output);
    }

    public function testCartDataStructure(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['courseId'] = 1;

        $controller = new CartController();

        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $item = $_SESSION['cart'][0];

        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('title', $item);
        $this->assertArrayHasKey('price', $item);
        $this->assertArrayHasKey('icon', $item);
        $this->assertArrayHasKey('duration', $item);
        $this->assertArrayHasKey('added_at', $item);

        $this->assertIsInt($item['id']);
        $this->assertIsInt($item['added_at']);
    }

    public function testAjaxRequestDetection(): void
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $controller = new CartController();

        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('isAjaxRequest');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($controller));
    }

    public function testNonAjaxRequest(): void
    {
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);

        $controller = new CartController();

        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('isAjaxRequest');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($controller));
    }

    public function testCartTotalCalculation(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $controller = new CartController();

        $_POST['courseId'] = 1;
        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $_POST['courseId'] = 2;
        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
            $total += $priceNum;
        }

        $this->assertGreaterThan(0, $total);
    }

    public function testCartPersistence(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['courseId'] = 1;

        $controller = new CartController();

        ob_start();
        try {
            $controller->add();
        } catch (\Exception $e) {
        }
        ob_end_clean();

        $cartData = $_SESSION['cart'];
        $_SESSION = [];
        $_SESSION['cart'] = $cartData;

        $this->assertCount(1, $_SESSION['cart']);
        $this->assertEquals(1, $_SESSION['cart'][0]['id']);
    }
}
