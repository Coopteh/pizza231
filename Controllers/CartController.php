<?php

namespace Controllers;

use Lib\DataLoader;

class CartController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function view(): string
    {
        return \Views\CartTemplate::getTemplate();
    }

    public function add(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $courseId = isset($_POST['courseId']) ? (int)$_POST['courseId'] : 0;
        $courses = DataLoader::loadCourses();

        if (!isset($courses[$courseId])) {
            if ($this->isAjaxRequest()) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'course_not_found']);
                exit;
            }
            header('Location: /courses?error=course_not_found');
            exit;
        }

        $course = $courses[$courseId];
        $existing = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] === $courseId) {
                $existing = true;
                break;
            }
        }

        if (!$existing) {
            $_SESSION['cart'][] = [
                'id' => $courseId,
                'title' => $course['title'],
                'price' => $course['price_from'],
                'icon' => $course['icon'],
                'duration' => $course['duration'],
                'added_at' => time()
            ];
        }

        if ($this->isAjaxRequest()) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'count' => count($_SESSION['cart'])
            ]);
            exit;
        }

        header('Location: /cart?success=added');
        exit;
    }

    public function remove(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $courseId = isset($_POST['courseId']) ? (int)$_POST['courseId'] : 0;

        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] === $courseId) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);

                if ($this->isAjaxRequest()) {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'count' => count($_SESSION['cart'])]);
                    exit;
                }

                header('Location: /cart?success=removed');
                exit;
            }
        }

        if ($this->isAjaxRequest()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'not_found']);
            exit;
        }

        header('Location: /cart?error=not_found');
        exit;
    }

    public function clear(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['cart'] = [];

        if ($this->isAjaxRequest()) {
            http_response_code(200);
            echo json_encode(['success' => true, 'count' => 0]);
            exit;
        }

        header('Location: /cart?success=cleared');
        exit;
    }

    public function getCount(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return count($_SESSION['cart'] ?? []);
    }

    public function getCountJson(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');
        echo json_encode(['count' => count($_SESSION['cart'] ?? [])]);
    }

    private function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function checkout(): void
    {
        $controller = new \Controllers\OrderController();
        echo $controller->checkout();
        exit;
    }

    public function order(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            exit;
        }
        $controller = new \Controllers\OrderController();
        $controller->process();
        exit;
    }

    public function success(): void
    {
        $controller = new \Controllers\OrderController();
        echo $controller->success();
        exit;
    }
}
