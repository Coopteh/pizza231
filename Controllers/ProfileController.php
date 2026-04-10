<?php
namespace Controllers;
use Models\User;
use Views\ProfileTemplate;

class ProfileController
{
    private function requireAuth(): ?int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        return (int)$_SESSION['user_id'];
    }

    public function get(): string
    {
        $userId = $this->requireAuth();
        $userModel = new User();
        $user = $userModel->findById($userId);
        if (!$user) {
            session_destroy();
            header('Location: /login');
            exit;
        }
        return ProfileTemplate::render($user);
    }

    public function updatePhone(): void
    {
        $userId = $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile');
            exit;
        }
        $phone = trim($_POST['phone'] ?? '');
        if (empty($phone)) {
            $_SESSION['profile_errors'] = ['Введите номер телефона'];
            header('Location: /profile#phone');
            exit;
        }
        $userModel = new User();
        if ($userModel->updatePhone($userId, $phone)) {
            $_SESSION['profile_success'] = '✅ Телефон обновлён';
            $_SESSION['user_phone'] = $phone;
        } else {
            $_SESSION['profile_errors'] = ['Ошибка сохранения'];
        }
        header('Location: /profile#phone');
        exit;
    }

    public function updatePassword(): void
    {
        $userId = $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile');
            exit;
        }
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $errors = [];
        if (empty($currentPassword)) $errors[] = 'Введите текущий пароль';
        if (strlen($newPassword) < 6) $errors[] = 'Новый пароль минимум 6 символов';
        if ($newPassword !== $confirmPassword) $errors[] = 'Пароли не совпадают';
        if (!empty($errors)) {
            $_SESSION['profile_errors'] = $errors;
            header('Location: /profile#password');
            exit;
        }
        $userModel = new User();
        if (!$userModel->verifyPassword($userId, $currentPassword)) {
            $_SESSION['profile_errors'] = ['Неверный текущий пароль'];
            header('Location: /profile#password');
            exit;
        }
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        if ($userModel->updatePassword($userId, $newPasswordHash)) {
            $_SESSION['profile_success'] = '✅ Пароль изменён';
        } else {
            $_SESSION['profile_errors'] = ['Ошибка сохранения'];
        }
        header('Location: /profile#password');
        exit;
    }

    public function updateCard(): void
    {
        $userId = $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile');
            exit;
        }
        $cardNumber = trim($_POST['card_number'] ?? '');
        if (empty($cardNumber)) {
            $_SESSION['profile_errors'] = ['Введите номер карты'];
            header('Location: /profile#card');
            exit;
        }
        $userModel = new User();
        if ($userModel->updateCard($userId, $cardNumber)) {
            $_SESSION['profile_success'] = '✅ Карта привязана';
        } else {
            $_SESSION['profile_errors'] = ['Ошибка сохранения'];
        }
        header('Location: /profile#card');
        exit;
    }
}