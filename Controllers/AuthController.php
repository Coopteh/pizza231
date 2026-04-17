<?php
namespace Controllers;

use Models\User;
use Models\Log;
use Views\AuthTemplate;
use Services\ValidateRegisterData; // Подключаем наш сервис

class AuthController
{
    public function register()
    {
        echo AuthTemplate::registerForm();
    }

    public function processRegister()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Инициализируем сервис валидации
        $validator = new ValidateRegisterData();
        $userModel = new User();

        // Запускаем валидацию (передаем данные и модель для проверки уникальности)
        $result = $validator->validate($_POST, $userModel);

        // Если есть ошибки — возвращаем пользователя назад
        if (!$result['valid']) {
            $_SESSION['auth_errors'] = $result['errors'];
            $_SESSION['auth_old'] = $result['sanitized']; // Возвращаем очищенные данные
            header('Location: /register');
            exit;
        }

        // Если валидация прошла успешно, используем очищенные данные
        $name = $result['sanitized']['name'];
        $email = $result['sanitized']['email'];
        $password = $_POST['password']; // Пароль берем из исходных данных (он не санитизится перед хешированием)

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $userModel->create($name, $email, $passwordHash);

        if (!$userId) {
            $_SESSION['auth_errors'] = ['Ошибка регистрации'];
            header('Location: /register');
            exit;
        }

        // 🔔 Логирование регистрации
        $logModel = new Log();
        $logModel->add('Регистрация пользователя', $name, ['email' => $email]);

        // 🔐 ОТПРАВЛЯЕМ НА ПОДТВЕРЖДЕНИЕ EMAIL
        $_SESSION['pending_email'] = $email;
        header('Location: /verify');
        exit;
    }

    public function login()
    {
        echo AuthTemplate::loginForm();
    }

    public function processLogin()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        // Базовая проверка для входа (можно тоже вынести в сервис, но оставим тут для краткости)
        if (empty($email)) $errors[] = 'Введите email';
        if (empty($password)) $errors[] = 'Введите пароль';

        if (!empty($errors)) {
            $_SESSION['auth_errors'] = $errors;
            $_SESSION['auth_old'] = ['email' => $email];
            header('Location: /login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['auth_errors'] = ['Неверный email или пароль'];
            $_SESSION['auth_old'] = ['email' => $email];
            header('Location: /login');
            exit;
        }

        // ✅ Устанавливаем сессию
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'] ?? 'user';

        // 🔔 Логирование входа
        $logModel = new Log();
        $logModel->add('Вход в систему', $user['name'], [
            'email' => $user['email'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ]);

        header('Location: /profile');
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
}