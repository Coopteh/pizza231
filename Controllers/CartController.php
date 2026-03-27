<?php
namespace Controllers;

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

        $productId = isset($_POST['courseId']) ? (int)$_POST['courseId'] : 0;

        if ($productId < 1 || $productId > 6) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'invalid_product']);
                exit;
            }
            header('Location: /products?error=invalid_product');
            exit;
        }

        $products = \Views\ProductTemplate::$courses;
        if (!isset($products[$productId])) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'product_not_found']);
                exit;
            }
            header('Location: /products?error=product_not_found');
            exit;
        }

        $product = $products[$productId];
        $existing = false;

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] === $productId) {
                $existing = true;
                // Если товар уже есть, увеличиваем количество
                $item['quantity'] = ($item['quantity'] ?? 1) + 1;
                break;
            }
        }

        if (!$existing) {
            $_SESSION['cart'][] = [
                'id' => $productId,
                'title' => $product['title'],
                'price' => $product['price_from'],
                'icon' => $product['icon'],
                'duration' => $product['duration'],
                'quantity' => 1,
                'added_at' => time()
            ];
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
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

    public function remove(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $productId = isset($_POST['courseId']) ? (int)$_POST['courseId'] : 0;

        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] === $productId) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'count' => count($_SESSION['cart'])]);
                    exit;
                }
                header('Location: /cart?success=removed');
                exit;
            }
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
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

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            http_response_code(200);
            echo json_encode(['success' => true, 'count' => 0]);
            exit;
        }

        header('Location: /cart?success=cleared');
        exit;
    }

    // ✅ НОВЫЙ МЕТОД: Обновление количества
    public function update(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $productId = isset($_POST['courseId']) ? (int)$_POST['courseId'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        $quantity = max(1, min(99, $quantity));

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] === $productId) {
                $item['quantity'] = $quantity;

                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'quantity' => $quantity]);
                    exit;
                }

                header('Location: /cart?success=updated');
                exit;
            }
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'not_found']);
            exit;
        }

        header('Location: /cart?error=not_found');
        exit;
    }
}