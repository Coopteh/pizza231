<?php
// Lib/RegistrationValidator.php
namespace Lib;

class RegistrationValidator
{
    public static function validate(array $data): array
    {
        $errors = [];
        $sanitized = [];

        // 1. Проверка имени
        if (empty($data['name'])) {
            $errors[] = "Имя пользователя обязательно";
        } else {
            $sanitized['name'] = self::sanitize($data['name']);
        }

        // 2. Проверка email
        if (empty($data['email'])) {
            $errors[] = "Email обязателен";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Некорректный формат Email";
        } else {
            $email = self::sanitize($data['email']);
            $sanitized['email'] = $email;
            
            // Проверка на уникальность
            if (User::getUserByEmail($email)) {
                $errors[] = "Этот Email уже зарегистрирован";
            }
        }

        // 3. Проверка пароля
        if (empty($data['password'])) {
            $errors[] = "Пароль обязателен";
        } elseif (strlen($data['password']) < 6) {
            $errors[] = "Пароль должен быть не менее 6 символов";
        }

        // 4. Проверка совпадения паролей
        if (empty($data['password_confirm']) || $data['password'] !== $data['password_confirm']) {
            $errors[] = "Пароли не совпадают";
        }

        return [
            'errors' => $errors,
            'data' => $sanitized
        ];
    }

    private static function sanitize(string $str): string
    {
        // strip_tags -> trim -> htmlspecialchars
        return htmlspecialchars(trim(strip_tags($str)), ENT_QUOTES, 'UTF-8');
    }
}