<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/EmailSender.php';
require_once __DIR__ . '/../Views/AuthTemplate.php';

use App\Models\User;
use App\Models\EmailSender;
use App\Views\AuthTemplate;

class AuthController
{
    private User $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
        
        // Запуск сессии
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Страница регистрации
     */
    public function register(): void
    {
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $name = trim($_POST['name'] ?? '');
            
            // Валидация
            if (empty($email) || empty($password) || empty($name)) {
                $error = 'Заполните все поля';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Введите корректный email';
            } elseif (strlen($password) < 6) {
                $error = 'Пароль должен быть не менее 6 символов';
            } elseif ($password !== $passwordConfirm) {
                $error = 'Пароли не совпадают';
            } else {
                $result = $this->userModel->create($email, $password, $name);
                
                if ($result) {
                    // Отправляем код подтверждения
                    EmailSender::sendVerificationCode(
                        $result['email'],
                        $result['verification_code'],
                        $result['name']
                    );
                    
                    // Сразу входим в аккаунт
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['user_name'] = $result['name'];
                    $_SESSION['user_email'] = $result['email'];
                    $_SESSION['is_admin'] = false;
                    $_SESSION['is_verified'] = false; // Ещё не подтверждён
                    $_SESSION['pending_verification_email'] = $email;
                    
                    session_write_close();
                    
                    // Перенаправляем на страницу подтверждения
                    header('Location: /verify');
                    exit;
                } else {
                    $error = 'Пользователь с таким email уже существует и подтверждён';
                }
            }
        }
        
        echo AuthTemplate::renderRegister($error, $success);
    }
    
    /**
     * Страница подтверждения email
     */
    public function verify(): void
    {
        $error = '';
        $success = '';
        $email = $_SESSION['pending_verification_email'] ?? '';
        
        // Если нет email в сессии, редирект на регистрацию
        if (empty($email)) {
            header('Location: /register');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            
            if (empty($code)) {
                $error = 'Введите код подтверждения';
            } elseif (strlen($code) !== 6 || !ctype_digit($code)) {
                $error = 'Код должен состоять из 6 цифр';
            } else {
                // Проверяем код
                if ($this->userModel->verifyEmail($email, $code)) {
                    $success = 'Email подтверждён!';
                    $_SESSION['is_verified'] = true;
                    unset($_SESSION['pending_verification_email']);
                    
                    // Редирект на главную через 2 секунды
                    header('Refresh: 2; URL=/');
                } else {
                    $error = 'Неверный или истёкший код подтверждения';
                }
            }
        }
        
        echo AuthTemplate::renderVerify($email, $error, $success);
    }
    
    /**
     * Повторная отправка кода подтверждения
     */
    public function resendCode(): void
    {
        header('Content-Type: application/json');
        
        $email = $_SESSION['pending_verification_email'] ?? '';
        
        if (empty($email)) {
            http_response_code(400);
            echo json_encode(['error' => 'Сессия истекла. Зарегистрируйтесь снова.'], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        $code = $this->userModel->regenerateVerificationCode($email);
        
        if ($code === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Email уже подтверждён'], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user && EmailSender::sendVerificationCode($email, $code, $user['name'])) {
            echo json_encode(['success' => true, 'message' => 'Код отправлен повторно'], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Ошибка отправки письма'], JSON_UNESCAPED_UNICODE);
        }
    }
    
    /**
     * Страница входа
     */
    public function login(): void
    {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = 'Заполните все поля';
            } else {
                $user = $this->userModel->verifyPassword($email, $password);
                
                if ($user) {
                    // Проверяем, подтверждён ли email
                    $isVerified = $this->userModel->isVerified($email);
                    
                    // Установка сессии
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['is_admin'] = $user['is_admin'] ?? false;
                    $_SESSION['is_verified'] = $isVerified;
                    
                    // Если email не подтверждён - перенаправляем на подтверждение
                    if (!$isVerified) {
                        $_SESSION['pending_verification_email'] = $email;
                        session_write_close();
                        header('Location: /verify');
                        exit;
                    }
                    
                    // Записать сессию
                    session_write_close();
                    
                    // Перенаправление
                    header('Location: /');
                    exit;
                } else {
                    $error = 'Неверный email или пароль';
                }
            }
        }
        
        echo AuthTemplate::renderLogin($error);
    }
    
    /**
     * Выход
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
        header('Location: /');
        exit;
    }
    
    /**
     * Получить текущего пользователя
     */
    public static function getCurrentUser(): ?array
    {
        // Не пытаемся запустить сессию если она уже активна
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            $isAdmin = $_SESSION['is_admin'] ?? false;
            $isVerified = $_SESSION['is_verified'] ?? false;
            
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'is_admin' => $isAdmin,
                'is_verified' => $isVerified
            ];
        }
        
        return null;
    }
    
    /**
     * API: регистрация
     */
    public function apiRegister(): string
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $name = trim($input['name'] ?? '');
        
        if (empty($email) || empty($password) || empty($name)) {
            http_response_code(400);
            return json_encode(['error' => 'Заполните все поля'], JSON_UNESCAPED_UNICODE);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return json_encode(['error' => 'Введите корректный email'], JSON_UNESCAPED_UNICODE);
        }
        
        if (strlen($password) < 6) {
            http_response_code(400);
            return json_encode(['error' => 'Пароль должен быть не менее 6 символов'], JSON_UNESCAPED_UNICODE);
        }
        
        $result = $this->userModel->create($email, $password, $name);
        
        if ($result) {
            // Отправляем код подтверждения
            EmailSender::sendVerificationCode(
                $result['email'],
                $result['verification_code'],
                $result['name']
            );
            
            // Сразу входим в аккаунт
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['user_name'] = $result['name'];
            $_SESSION['user_email'] = $result['email'];
            $_SESSION['is_admin'] = false;
            $_SESSION['is_verified'] = false;
            $_SESSION['pending_verification_email'] = $email;
            
            session_write_close();
            
            return json_encode([
                'success' => true, 
                'message' => 'На ваш email отправлен код подтверждения',
                'user' => [
                    'id' => $result['id'],
                    'name' => $result['name'],
                    'email' => $result['email'],
                    'is_verified' => false
                ],
                'needs_verification' => true
            ], JSON_UNESCAPED_UNICODE);
        }
        
        http_response_code(400);
        return json_encode(['error' => 'Пользователь с таким email уже существует и подтверждён'], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * API: вход
     */
    public function apiLogin(): string
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            http_response_code(400);
            return json_encode(['error' => 'Заполните все поля'], JSON_UNESCAPED_UNICODE);
        }
        
        $user = $this->userModel->verifyPassword($email, $password);
        
        if ($user) {
            // Проверяем, подтверждён ли email
            $isVerified = $this->userModel->isVerified($email);
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'] ?? false;
            $_SESSION['is_verified'] = $isVerified;
            
            // Если email не подтверждён - возвращаем информацию об этом
            if (!$isVerified) {
                $_SESSION['pending_verification_email'] = $email;
                session_write_close();
                
                return json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'is_admin' => $user['is_admin'] ?? false,
                        'is_verified' => false
                    ],
                    'needs_verification' => true
                ], JSON_UNESCAPED_UNICODE);
            }
            
            session_write_close();
            
            return json_encode([
                'success' => true, 
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'is_admin' => $user['is_admin'] ?? false,
                    'is_verified' => true
                ]
            ], JSON_UNESCAPED_UNICODE);
        }
        
        http_response_code(401);
        return json_encode(['error' => 'Неверный email или пароль'], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * API: подтверждение email
     */
    public function apiVerify(): string
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $code = trim($input['code'] ?? '');
        $email = $_SESSION['pending_verification_email'] ?? '';
        
        if (empty($email)) {
            http_response_code(400);
            return json_encode(['error' => 'Сессия истекла. Зарегистрируйтесь снова.'], JSON_UNESCAPED_UNICODE);
        }
        
        if (empty($code)) {
            http_response_code(400);
            return json_encode(['error' => 'Введите код подтверждения'], JSON_UNESCAPED_UNICODE);
        }
        
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            http_response_code(400);
            return json_encode(['error' => 'Код должен состоять из 6 цифр'], JSON_UNESCAPED_UNICODE);
        }
        
        if ($this->userModel->verifyEmail($email, $code)) {
            $_SESSION['is_verified'] = true;
            unset($_SESSION['pending_verification_email']);
            
            return json_encode([
                'success' => true, 
                'message' => 'Email подтверждён!',
                'user' => [
                    'email' => $email,
                    'is_verified' => true
                ]
            ], JSON_UNESCAPED_UNICODE);
        }
        
        http_response_code(400);
        return json_encode(['error' => 'Неверный или истёкший код подтверждения'], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * API: повторная отправка кода
     */
    public function apiResend(): string
    {
        header('Content-Type: application/json');
        
        $email = $_SESSION['pending_verification_email'] ?? '';
        
        if (empty($email)) {
            http_response_code(400);
            return json_encode(['error' => 'Сессия истекла. Зарегистрируйтесь снова.'], JSON_UNESCAPED_UNICODE);
        }
        
        $code = $this->userModel->regenerateVerificationCode($email);
        
        if ($code === null) {
            http_response_code(400);
            return json_encode(['error' => 'Email уже подтверждён'], JSON_UNESCAPED_UNICODE);
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user && EmailSender::sendVerificationCode($email, $code, $user['name'])) {
            return json_encode(['success' => true, 'message' => 'Код отправлен повторно'], JSON_UNESCAPED_UNICODE);
        }
        
        http_response_code(500);
        return json_encode(['error' => 'Ошибка отправки письма'], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * API: выход
     */
    public function apiLogout(): string
    {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
        
        return json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * API: получить текущего пользователя
     */
    public function apiGetCurrent(): string
    {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $user = self::getCurrentUser();
        
        // Если есть user, но нет is_admin - проверить через модель
        if ($user) {
            $isAdmin = $this->userModel->isAdmin($user['id']);
            $user['is_admin'] = $isAdmin;
            
            // Обновить сессию
            $_SESSION['is_admin'] = $isAdmin;
            
            // Проверить актуальный статус верификации
            $isVerified = $this->userModel->isVerified($user['email']);
            $user['is_verified'] = $isVerified;
            $_SESSION['is_verified'] = $isVerified;
        }
        
        if ($user) {
            return json_encode(['user' => $user], JSON_UNESCAPED_UNICODE);
        }
        
        return json_encode(['user' => null], JSON_UNESCAPED_UNICODE);
    }
}
