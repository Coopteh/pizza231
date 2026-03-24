<?php
namespace Controllers;

use Views\CheckoutTemplate;

class CheckoutController
{
    public function get(): string
    {
        if (!AuthController::checkAuth()) {
            $_SESSION['login_redirect'] = '/checkout';
            header('Location: /login');
            exit;
        }
        
        $basket = $_SESSION['basket'] ?? [];
        
        if (empty($basket)) {
            header('Location: /products');
            exit;
        }
        
        return CheckoutTemplate::render($basket);
    }
    
    public function process(): void
    {
        if (!AuthController::checkAuth()) {
            header('Location: /login');
            exit;
        }
        
        $errors = [];
        
        $cardNumber = trim($_POST['card_number'] ?? '');
        $cardExpiry = trim($_POST['card_expiry'] ?? '');
        $cardCvc = trim($_POST['card_cvc'] ?? '');
        
        if (empty($cardNumber) || strlen(preg_replace('/\D/', '', $cardNumber)) < 16) {
            $errors[] = 'Введите корректный номер карты';
        }
        if (empty($cardExpiry)) {
            $errors[] = 'Введите срок действия карты';
        }
        if (empty($cardCvc) || strlen($cardCvc) < 3) {
            $errors[] = 'Введите CVC код';
        }
        
        if (empty($errors)) {
            $_SESSION['basket'] = [];
            $_SESSION['order_success'] = 'Заказ успешно оформлен! Менеджер свяжется с вами в течение 15 минут.';
            header('Location: /checkout/success');
            exit;
        }
        
        $_SESSION['checkout_errors'] = $errors;
        header('Location: /checkout');
        exit;
    }
    
    public function success(): string
    {
        if (!AuthController::checkAuth()) {
            header('Location: /login');
            exit;
        }
        
        $success = $_SESSION['order_success'] ?? 'Заказ оформлен';
        unset($_SESSION['order_success']);
        
        return CheckoutTemplate::success($success);
    }
}