<?php
namespace Controllers;

use Models\User;
use Views\ProfileTemplate;

class ProfileController
{
    public function get(): string
    {
        // 🔐 Проверка авторизации
        if (!AuthController::checkAuth()) {
            $_SESSION['login_redirect'] = '/profile';
            header('Location: /login');
            exit;
        }
        
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        if (!$user) {
            session_unset();
            session_destroy();
            header('Location: /');
            exit;
        }
        
        return ProfileTemplate::render($user);
    }
    
    public function updatePhone(): void
    {
        if (!AuthController::checkAuth()) {
            header('Location: /login');
            exit;
        }
        
        $phone = trim($_POST['phone'] ?? '');
        $errors = [];
        
        // Валидация телефона
        $phoneClean = preg_replace('/[^\d+]/', '', $phone);
        if (strlen($phoneClean) < 10) {
            $errors[] = 'Введите корректный номер телефона';
        }
        
        if (empty($errors)) {
            $userModel = new User();
            if ($userModel->updatePhone($_SESSION['user_id'], $phoneClean)) {
                $_SESSION['profile_success'] = 'Номер телефона обновлён';
            } else {
                $errors[] = 'Ошибка при обновлении';
            }
        }
        
        $_SESSION['profile_errors'] = $errors;
        header('Location: /profile#phone');
        exit;
    }
    
    public function updatePassword(): void
    {
        if (!AuthController::checkAuth()) {
            header('Location: /login');
            exit;
        }
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $errors = [];
        
        // Проверка текущего пароля
        $userModel = new User();
        if (!$userModel->verifyPassword($_SESSION['user_id'], $currentPassword)) {
            $errors[] = 'Неверный текущий пароль';
        }
        
        // Валидация нового пароля
        if (strlen($newPassword) < 6) {
            $errors[] = 'Новый пароль должен содержать минимум 6 символов';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Пароли не совпадают';
        }
        
        // Обновление
        if (empty($errors)) {
            if ($userModel->updatePassword($_SESSION['user_id'], password_hash($newPassword, PASSWORD_DEFAULT))) {
                $_SESSION['profile_success'] = 'Пароль успешно изменён';
            } else {
                $errors[] = 'Ошибка при смене пароля';
            }
        }
        
        $_SESSION['profile_errors'] = $errors;
        header('Location: /profile#password');
        exit;
    }
    
    public function updateCard(): void
    {
        if (!AuthController::checkAuth()) {
            header('Location: /login');
            exit;
        }
        
        $cardNumber = trim($_POST['card_number'] ?? '');
        $errors = [];
        
        // Валидация карты
        $cardClean = preg_replace('/\D/', '', $cardNumber);
        if (strlen($cardClean) < 16) {
            $errors[] = 'Введите корректный номер карты (16 цифр)';
        }
        
        if (empty($errors)) {
            $userModel = new User();
            if ($userModel->updateCard($_SESSION['user_id'], $cardClean)) {
                $_SESSION['profile_success'] = 'Карта привязана';
            } else {
                $errors[] = 'Ошибка при привязке карты';
            }
        }
        
        $_SESSION['profile_errors'] = $errors;
        header('Location: /profile#card');
        exit;
    }
}