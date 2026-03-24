<?php
namespace Controllers;

use Models\User;
use Views\AuthTemplate;

class AuthController
{
    public function register(): string
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        return AuthTemplate::registerForm();
    }
    
    public function processRegister(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        $errors = [];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        // Валидация
        if (empty($name)) $errors[] = 'Введите имя';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Введите корректный email';
        if (strlen($password) < 6) $errors[] = 'Пароль должен содержать минимум 6 символов';
        if ($password !== $passwordConfirm) $errors[] = 'Пароли не совпадают';
        
        // Проверка на существующий email
        if (empty($errors)) {
            $userModel = new User();
            if ($userModel->findByEmail($email)) {
                $errors[] = 'Пользователь с таким email уже существует';
            }
        }
        
        // Регистрация
        if (empty($errors)) {
            $userModel = new User();
            $userId = $userModel->create($name, $email, password_hash($password, PASSWORD_DEFAULT));
            
            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                header('Location: /products');
                exit;
            } else {
                $errors[] = 'Ошибка при регистрации. Попробуйте позже.';
            }
        }
        
        // Если есть ошибки — показываем форму с сообщениями
        $_SESSION['auth_errors'] = $errors;
        $_SESSION['auth_old'] = ['name' => $name, 'email' => $email];
        header('Location: /register');
        exit;
    }
    
    public function login(): string
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        return AuthTemplate::loginForm();
    }
    
    public function processLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        $errors = [];
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email';
        }
        if (empty($password)) {
            $errors[] = 'Введите пароль';
        }
        
        if (empty($errors)) {
            $userModel = new User();
            $user = $userModel->findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                // Перенаправление на страницу, с которой пришли, или в каталог
                $referer = $_POST['referer'] ?? '/products';
                header('Location: ' . $referer);
                exit;
            } else {
                $errors[] = 'Неверный email или пароль';
            }
        }
        
        $_SESSION['auth_errors'] = $errors;
        $_SESSION['auth_old'] = ['email' => $email];
        header('Location: /login');
        exit;
    }
    
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }
    
    // Проверка авторизации (для использования в других контроллерах)
    public static function checkAuth(): bool
    {
        return isset($_SESSION['user_id']);
    }
    
    // Получение данных текущего пользователя
    public static function getCurrentUser(): ?array
    {
        if (isset($_SESSION['user_id'])) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? ''
            ];
        }
        return null;
    }
}