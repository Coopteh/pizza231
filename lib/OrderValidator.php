<?php
namespace Lib;

class OrderValidator
{
    public static function validate(array $data): array
    {
        $errors = [];
        $sanitized = [];

        // 1. Проверка ФИО
        if (empty($data['name'])) {
            $errors[] = "ФИО обязательно для заполнения";
        } elseif (strlen(trim($data['name'])) <= 3) {
            $errors[] = "ФИО должно содержать более 3 символов";
        } else {
            $sanitized['name'] = self::sanitize($data['name']);
        }

        // 2. Проверка телефона
        if (empty($data['phone'])) {
            $errors[] = "Телефон обязателен";
        } else {
            // Удаляем все символы, кроме цифр и +, согласно заданию
            $cleanPhone = filter_var($data['phone'], FILTER_SANITIZE_NUMBER_INT);
            $cleanPhone = str_replace('-', '', $cleanPhone); // Убираем оставшиеся минусы
            
            if (empty($cleanPhone) || strlen($cleanPhone) < 6) {
                $errors[] = "Некорректный номер телефона";
            } else {
                $sanitized['phone'] = $cleanPhone;
            }
        }

        // 3. Проверка Email
        if (empty($data['email'])) {
            $errors[] = "Email обязателен";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Некорректный Email";
        } else {
            $sanitized['email'] = self::sanitize($data['email']);
        }

        return [
            'errors' => $errors,
            'data' => $sanitized
        ];
    }

    private static function sanitize(string $str): string
    {
        return htmlspecialchars(trim(strip_tags($str)), ENT_QUOTES, 'UTF-8');
    }
}