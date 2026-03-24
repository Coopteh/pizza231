<?php
namespace Controllers;

use Models\Product;
use Views\BasketTemplate;

class BasketController
{
    private function getStorageType(): string { return $_COOKIE['cart_storage'] ?? 'session'; }
    
    private function getCart(): array
    {
        $type = $this->getStorageType();
        if ($type === 'session') {
            return $_SESSION['basket'] ?? [];
        } else {
            return json_decode($_COOKIE['basket'] ?? '[]', true) ?? [];
        }
    }
    
    private function saveCart(array $cart): void
    {
        $type = $this->getStorageType();
        if ($type === 'session') {
            $_SESSION['basket'] = $cart;
        } else {
            setcookie('basket', json_encode($cart), time() + 86400 * 30, '/');
        }
    }
    
    public function get(): string
    {
        // 🔐 Проверка авторизации для просмотра корзины (опционально)
        // if (!AuthController::checkAuth()) { header('Location: /login'); exit; }
        
        $basket = $this->getCart();
        $productModel = new Product();
        $items = []; $total = 0;
        
        foreach ($basket as $productId => $item) {
            $product = $productModel->getById($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'] ?? 1,
                    'subtotal' => $product['price'] * ($item['quantity'] ?? 1)
                ];
                $total += $product['price'] * ($item['quantity'] ?? 1);
            }
        }
        return BasketTemplate::render($items, $total, $this->getStorageType());
    }
    
    public function add(): void
    {
        // 🔐 Проверка авторизации перед добавлением в корзину
        if (!AuthController::checkAuth()) {
            // Перенаправляем на страницу входа с возвратом на текущую
            $referer = $_SERVER['HTTP_REFERER'] ?? '/products';
            $_SESSION['login_redirect'] = $referer;
            header('Location: /login');
            exit;
        }
        
        if (isset($_POST['id'])) {
            $productId = (int)$_POST['id'];
            $cart = $this->getCart();
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = ($cart[$productId]['quantity'] ?? 0) + 1;
            } else {
                $cart[$productId] = ['quantity' => 1];
            }
            $this->saveCart($cart);
        }
        
        $referer = $_SESSION['login_redirect'] ?? ($_SERVER['HTTP_REFERER'] ?? '/products');
        unset($_SESSION['login_redirect']);
        header('Location: ' . $referer);
        exit;
    }
    
    public function remove(): void
    {
        if (!AuthController::checkAuth()) { header('Location: /login'); exit; }
        
        if (isset($_GET['id'])) {
            $productId = (int)$_GET['id'];
            $cart = $this->getCart();
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                $this->saveCart($cart);
            }
        }
        header('Location: /cart'); exit;
    }
    
    public function clear(): void
    {
        if (!AuthController::checkAuth()) { header('Location: /login'); exit; }
        $this->saveCart([]);
        header('Location: /cart'); exit;
    }
    
    public function update(): void
    {
        if (!AuthController::checkAuth()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'auth_required']);
            exit;
        }
        
        header('Content-Type: application/json');
        if (isset($_POST['id'], $_POST['quantity'])) {
            $productId = (int)$_POST['id'];
            $quantity = max(1, (int)$_POST['quantity']);
            $cart = $this->getCart();
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
                $this->saveCart($cart);
                echo json_encode(['success' => true]);
                return;
            }
        }
        echo json_encode(['success' => false]);
    }
    
    public function setStorage(): void
    {
        if (isset($_POST['type']) && in_array($_POST['type'], ['session', 'cookie'])) {
            setcookie('cart_storage', $_POST['type'], time() + 86400 * 365, '/');
            $_COOKIE['cart_storage'] = $_POST['type'];
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? '/cart';
        header('Location: ' . $referer); exit;
    }
}