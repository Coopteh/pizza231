<?php

// 🔐 ОБНОВЛЕНО: Добавлена проверка кода при смене пароля

namespace Controllers;

use Lib\User;
use Lib\Auth;
use Lib\Language;
use Views\ProfileTemplate;

class ProfileController
{
    public function index(): string
    {
        if (!User::isLoggedIn()) {
            header('Location: /auth/login');
            exit;
        }
        $user = User::getCurrentUser();
        $orders = User::getUserOrders($user['id']);
        return ProfileTemplate::getProfileTemplate($user, $orders);
    }

    // 🔐 ОБНОВЛЕНО: Добавлена логика с кодом для смены пароля
    public function edit(): string
    {
        if (!User::isLoggedIn()) {
            header('Location: /auth/login');
            exit;
        }

        $user = User::getCurrentUser();
        $errors = [];
        $success = false;
        $showPasswordForm = false;
        $codeSent = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formType = $_POST['form_type'] ?? 'profile';

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'current_password' => $_POST['current_password'] ?? '',
                'new_password' => $_POST['new_password'] ?? '',
                'new_password_confirm' => $_POST['new_password_confirm'] ?? '',
                'verification_code' => trim($_POST['verification_code'] ?? '')
            ];

            if ($formType === 'profile') {
                // Валидация для формы редактирования профиля
                if (empty($data['name'])) {
                    $errors[] = 'name_required';
                }
                if (empty($data['phone'])) {
                    $errors[] = 'phone_required';
                }

                if (empty($errors)) {
                    $updateData = ['name' => $data['name'], 'phone' => $data['phone']];
                    User::updateUser($user['id'], $updateData);
                    $success = true;
                    $user = User::getCurrentUser();
                }
            } elseif ($formType === 'password_request') {
                // 🔐 Запрос кода для смены пароля
                if (!empty($data['current_password'])) {
                    if (password_verify($data['current_password'], $user['password'])) {
                        $code = User::generatePasswordChangeCode($user['id']);
                        if ($code) {
                            Auth::sendPasswordChangeCode($user['email'], $code, $user['name']);
                            $codeSent = true;
                            $showPasswordForm = true;
                        }
                    } else {
                        $errors[] = 'current_password_invalid';
                    }
                } else {
                    $errors[] = 'current_password_required';
                }
            } elseif ($formType === 'password_verify') {
                // 🔐 Проверка кода и смена пароля
                if (empty($data['verification_code'])) {
                    $errors[] = 'code_required';
                } elseif (!User::verifyPasswordChangeCode($user['id'], $data['verification_code'])) {
                    $errors[] = 'code_invalid';
                } elseif (empty($data['new_password'])) {
                    $errors[] = 'new_password_required';
                } elseif (strlen($data['new_password']) < 6) {
                    $errors[] = 'password_short';
                } elseif ($data['new_password'] !== $data['new_password_confirm']) {
                    $errors[] = 'password_mismatch';
                } else {
                    // Все проверки пройдены - меняем пароль
                    User::updatePassword($user['id'], $data['new_password']);
                    User::clearPasswordChangeCode($user['id']);
                    $success = true;
                }
            }
        }

        return ProfileTemplate::getEditTemplate($user, $errors, $success, $showPasswordForm, $codeSent);
    }

    public function orders(): string
    {
        if (!User::isLoggedIn()) {
            header('Location: /auth/login');
            exit;
        }
        $user = User::getCurrentUser();
        $orders = User::getUserOrders($user['id']);
        return ProfileTemplate::getOrdersTemplate($user, $orders);
    }
}
