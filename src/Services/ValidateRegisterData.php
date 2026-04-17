<?php
namespace Services;

use Models\User;

class ValidateRegisterData
{
    private array $errors = [];
    private array $sanitized = [];

    public function validate(array $data, ?User $userModel = null): array
    {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $passwordConfirm = $data['password_confirm'] ?? '';

        // Санитизация
        $this->sanitized['name'] = $this->sanitize($name);
        $this->sanitized['email'] = $this->sanitize($email);

        // Валидация имени
        if (empty($this->sanitized['name'])) {
            $this->errors[] = 'Введите имя';
        }

        // Валидация email
        if (empty($this->sanitized['email'])) {
            $this->errors[] = 'Введите email';
        } elseif (!filter_var($this->sanitized['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Некорректный email';
        } elseif ($userModel && $userModel->findByEmail($this->sanitized['email'])) {
            $this->errors[] = 'Email уже зарегистрирован';
        }

        // Валидация пароля
        if (strlen($password) < 6) {
            $this->errors[] = 'Пароль должен содержать минимум 6 символов';
        }
        if ($password !== $passwordConfirm) {
            $this->errors[] = 'Пароли не совпадают';
        }

        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'sanitized' => $this->sanitized
        ];
    }

    private function sanitize(string $input): string
    {
        $input = trim($input);
        $input = strip_tags($input);
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}