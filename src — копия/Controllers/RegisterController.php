<?php

namespace App\Controllers;

use App\Models\User;
use App\Views\RegisterTemplate;
use App\Services\UserDBStorage;
use App\Services\ValidateRegisterData;
use App\Services\Mailer;

class RegisterController
{
    public function get(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->create();
        }
        return RegisterTemplate::getRegisterTemplate();
    }

    private function create(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $formData = [
            'username' => $_POST['username'] ?? '',
            'email'    => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? ''
        ];

        $storage = new UserDBStorage();
        $validator = new ValidateRegisterData($storage);
        $mailer = new Mailer();
        $userModel = new User($storage, $validator, $mailer);

        $result = $userModel->register($formData);

        if ($result['success']) {
            $_SESSION['flash'] = $result['message'] ?? "Регистрация успешна!";
            header("Location: /login");
            exit;
        }

        // Корректный разбор структуры ошибок из модели
        $validationData = $result['errors'] ?? [];
        $errors = $validationData['errors'] ?? (is_array($validationData) ? $validationData : ['Произошла неизвестная ошибка']);
        $oldData  = $validationData['data'] ?? [];

        // Сохраняем только безопасные поля (пароль не подставляется)
        $safeOldData = [
            'username' => $oldData['username'] ?? $formData['username'],
            'email'    => $oldData['email'] ?? $formData['email']
        ];

        return RegisterTemplate::getRegisterTemplate($errors, $safeOldData);
    }

    public function verify(?string $token = null): string
    {
        if ($token === null) {
            $token = $_GET['token'] ?? '';
        }

        if (empty($token)) {
            return RegisterTemplate::getVerifyTemplate("Токен подтверждения не предоставлен.", false);
        }

        $storage = new UserDBStorage();
        $result = $storage->verifyToken($token);

        return RegisterTemplate::getVerifyTemplate(
            $result['message'],
            $result['success'] ?? false
        );
    }
}