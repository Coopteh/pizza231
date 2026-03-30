<?php
namespace Controllers;
use Models\User;
use Models\Log;
use Views\AuthTemplate;

class AuthController
{
    public function register()
    {
        echo AuthTemplate::registerForm();
    }

    public function processRegister()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        $errors = [];
        if (empty($name)) $errors[] = 'Введите имя';
        if (empty($email)) $errors[] = 'Введите email';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';
        if (strlen($password) < 6) $errors[] = 'Пароль минимум 6 символов';
        if ($password !== $passwordConfirm) $errors[] = 'Пароли не совпадают';
        
        if (!empty($errors)) {
            $_SESSION['auth_errors'] = $errors;
            $_SESSION['auth_old'] = ['name' => $name, 'email' => $email];
            header('Location: /register');
            exit;
        }
        
        $userModel = new User();
        $existingUser = $userModel->findByEmail($email);
        
        if ($existingUser) {
            $_SESSION['auth_errors'] = ['Email уже зарегистрирован'];
            $_SESSION['auth_old'] = ['name' => $name, 'email' => $email];
            header('Location: /register');
            exit;
        }
        
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
        
        // 🔹 ПЕРЕНАПРАВЛЯЕМ В ПРОФИЛЬ (вместо referer)
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