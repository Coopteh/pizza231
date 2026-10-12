<?php

namespace App\Models;

use App\Services\UserDBStorage;
use App\Services\ValidateRegisterData;
use App\Services\Mailer;

class User
{
    private UserDBStorage $storage;
    private ValidateRegisterData $validator;
    private Mailer $mailer;

    public function __construct(
        UserDBStorage $storage,
        ValidateRegisterData $validator,
        Mailer $mailer
    ) {
        $this->storage = $storage;
        $this->validator = $validator;
        $this->mailer = $mailer;
    }

    /**
     * Регистрация нового пользователя
     */
    public function register(array $data): array
    {
        // 1. Валидация
        $validation = $this->validator->validate($data);

        // ✅ ИСПРАВЛЕНИЕ: Проверяем флаг 'valid', а не пустоту массива
        if (!$validation['valid']) {
            return ['success' => false, 'errors' => $validation];
        }

        // 2. Подготовка данных (берём из результата валидатора)
        $sanitized = $validation['data'];
        $verificationToken = bin2hex(random_bytes(32));
        $hashedPassword = password_hash($sanitized['password'], PASSWORD_DEFAULT);

        $userData = [
            'username' => $sanitized['username'],
            'email'    => $sanitized['email'],
            'password' => $hashedPassword,
            'verification_token' => $verificationToken,
            'is_verified' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // 3. Сохранение в БД
        try {
            $saved = $this->storage->saveData('users', $userData);
            if (!$saved) {
                return ['success' => false, 'errors' => ['Ошибка сохранения в базу данных. Возможно, email уже занят.']];
            }
        } catch (\Throwable $e) {
            error_log("User registration DB error: " . $e->getMessage());
            return ['success' => false, 'errors' => ['Внутренняя ошибка сервера. Попробуйте позже.']];
        }

        // 4. Отправка письма (не прерываем успех, если SMTP не отвечает)
        try {
            $this->mailer->sendMailUserConfirmation(
                $userData['email'],
                $userData['username'],
                $verificationToken
            );
        } catch (\Throwable $e) {
            error_log("User registration mail error: " . $e->getMessage());
        }

        return [
            'success' => true,
            'message' => "Регистрация успешна! Проверьте email для подтверждения."
        ];
    }
}