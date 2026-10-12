<?php

namespace App\Controllers;

use App\Views\BaseTemplate;
use App\Views\RegisterTemplate;
use App\Config\Config;
use PDO;
use PDOException;

class AuthController
{
    /**
     * Показывает страницу регистрации (GET /register)
     */
    public function register(): string
    {
        return RegisterTemplate::getRegisterTemplate();
    }

    /**
     * Обрабатывает отправку формы регистрации (POST /register)
     */
    public function registerPost(): string
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $errors = [];

        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $errors[] = 'Все поля обязательны для заполнения.';
        }
        if ($password !== $confirm_password) {
            $errors[] = 'Пароли не совпадают.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Пароль должен содержать не менее 6 символов.';
        }

        if (!empty($errors)) {
            $oldData = ['username' => $username, 'email' => $email];
            return RegisterTemplate::getRegisterTemplate($errors, $oldData);
        }
        
        // 🔹 Логика регистрации в БД
        try {
            $pdo = new PDO(
                Config::MYSQL_DSN,
                Config::MYSQL_USER,
                Config::MYSQL_PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Проверка на дубликат email
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Пользователь с таким email уже зарегистрирован';
                return RegisterTemplate::getRegisterTemplate($errors, ['username' => $username, 'email' => $email]);
            }
            
            // Хэширование пароля и генерация токена
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(32));
            
            // Вставка в БД
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, verification_token, is_verified, created_at)
                VALUES (?, ?, ?, ?, 0, NOW())
            ");
            $stmt->execute([$username, $email, $passwordHash, $token]);
            
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $errors[] = 'Ошибка при сохранении в базу данных';
            return RegisterTemplate::getRegisterTemplate($errors, ['username' => $username, 'email' => $email]);
        }
        
        return RegisterTemplate::getVerifyTemplate(
            'Регистрация прошла успешно! Пожалуйста, войдите в систему.', 
            true
        );
    }

    /**
     * Показывает страницу входа (GET /login)
     */
    public function login(): string
    {
        ob_start();
        ?>
        <div class="container mt-5">
            <h2>Вход</h2>
            <form method="POST" action="/login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Войти</button>
            </form>
            <p class="mt-3">Нет аккаунта? <a href="/register">Зарегистрируйтесь</a></p>
        </div>
        <?php
        $content = ob_get_clean();
        
        $template = BaseTemplate::getTemplate();
        return sprintf($template, 'Вход - По мультивселенной', $content);
    }

    /**
     * Обрабатывает отправку формы входа (POST /login)
     */
    public function loginPost(): string
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];
        
        if (!empty($email) && !empty($password)) {
            try {
                $pdo = new PDO(
                    Config::MYSQL_DSN,
                    Config::MYSQL_USER,
                    Config::MYSQL_PASSWORD,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if ($user && password_verify($password, $user['password'])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['username'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    // Обновление last_login
                    $upd = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                    $upd->execute([$user['id']]);
                    
                    $_SESSION['flash'] = 'Добро пожаловать, ' . htmlspecialchars($user['username']) . '!';
                    header('Location: /');
                    exit;
                } else {
                    $errors[] = 'Неверный email или пароль';
                }
            } catch (PDOException $e) {
                error_log("Login error: " . $e->getMessage());
                $errors[] = 'Ошибка подключения к базе данных';
            }
        } else {
            $errors[] = 'Заполните все поля';
        }
        
        ob_start();
        ?>
        <div class="container mt-5">
            <h2>Вход</h2>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err): ?>
                        <div>• <?= htmlspecialchars($err) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="/login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Войти</button>
            </form>
            <p class="mt-3">Нет аккаунта? <a href="/register">Зарегистрируйтесь</a></p>
        </div>
        <?php
        $content = ob_get_clean();
        
        $template = BaseTemplate::getTemplate();
        return sprintf($template, 'Вход - По мультивселенной', $content);
    }

    /**
     * Выход из системы (GET /logout)
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        
        $_SESSION['flash'] = 'Вы вышли из своего аккаунта.';

        header('Location: /login');
        exit();
    }
}