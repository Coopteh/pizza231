<?php
namespace Views;

class AuthTemplate extends BaseTemplate
{
    public static function registerForm(): string
    {
        $template = parent::getTemplate();
        $title = 'Регистрация — gym low cortisol';
        
        $errors = $_SESSION['auth_errors'] ?? [];
        $old = $_SESSION['auth_old'] ?? [];
        unset($_SESSION['auth_errors'], $_SESSION['auth_old']);
        
        $errorsHtml = '';
        if (!empty($errors)) {
            $errorsHtml = '<div class="alert alert-danger mb-4">' . implode('<br>', array_map('htmlspecialchars', $errors)) . '</div>';
        }
        
        $content = '
        <style>
            .auth-card {
                max-width: 450px;
                margin: 3rem auto;
                border: none;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(13, 110, 253, 0.15);
            }
            .auth-header {
                background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%);
                color: #fff;
                padding: 1.5rem;
                border-radius: 20px 20px 0 0;
                text-align: center;
            }
            .auth-header h2 { margin: 0; font-size: 1.5rem; font-weight: 700; }
            .auth-body { padding: 2rem; }
            .form-label { font-weight: 500; color: #334155; }
            .form-control {
                border-radius: 12px;
                border: 1px solid #cbd5e1;
                padding: 0.75rem 1rem;
            }
            .form-control:focus {
                border-color: #8b5cf6;
                box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12);
            }
            .btn-auth {
                background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%);
                border: none;
                padding: 12px;
                border-radius: 50px;
                color: #fff;
                font-weight: 600;
                width: 100%;
                font-size: 1rem;
            }
            .btn-auth:hover {
                opacity: 0.95;
                color: #fff;
            }
            .auth-link {
                text-align: center;
                margin-top: 1.5rem;
                color: #64748b;
            }
            .auth-link a {
                color: #8b5cf6;
                text-decoration: none;
                font-weight: 500;
            }
            .auth-link a:hover { text-decoration: underline; }
        </style>
        
        <section class="container py-5">
            <div class="card auth-card">
                <div class="auth-header">
                    <h2>Регистрация</h2>
                </div>
                <div class="auth-body">
                    '.$errorsHtml.'
                    <form method="POST" action="/register/process">
                        <div class="mb-3">
                            <label class="form-label">Имя</label>
                            <input type="text" name="name" class="form-control" 
                                   value="'.htmlspecialchars($old['name'] ?? '').'" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="'.htmlspecialchars($old['email'] ?? '').'" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                            <small class="text-muted">Минимум 6 символов</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Подтвердите пароль</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-auth">Зарегистрироваться</button>
                    </form>
                    <div class="auth-link">
                        Уже есть аккаунт? <a href="/login">Войти</a>
                    </div>
                </div>
            </div>
        </section>';
        
        return sprintf($template, $title, $content);
    }
    
    public static function loginForm(): string
    {
        $template = parent::getTemplate();
        $title = 'Вход — gym low cortisol';
        
        $errors = $_SESSION['auth_errors'] ?? [];
        $old = $_SESSION['auth_old'] ?? [];
        $referer = $_SERVER['HTTP_REFERER'] ?? '/products';
        unset($_SESSION['auth_errors'], $_SESSION['auth_old']);
        
        $errorsHtml = '';
        if (!empty($errors)) {
            $errorsHtml = '<div class="alert alert-danger mb-4">' . implode('<br>', array_map('htmlspecialchars', $errors)) . '</div>';
        }
        
        $content = '
        <style>
            .auth-card {
                max-width: 450px;
                margin: 3rem auto;
                border: none;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(13, 110, 253, 0.15);
            }
            .auth-header {
                background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%);
                color: #fff;
                padding: 1.5rem;
                border-radius: 20px 20px 0 0;
                text-align: center;
            }
            .auth-header h2 { margin: 0; font-size: 1.5rem; font-weight: 700; }
            .auth-body { padding: 2rem; }
            .form-label { font-weight: 500; color: #334155; }
            .form-control {
                border-radius: 12px;
                border: 1px solid #cbd5e1;
                padding: 0.75rem 1rem;
            }
            .form-control:focus {
                border-color: #8b5cf6;
                box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12);
            }
            .btn-auth {
                background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%);
                border: none;
                padding: 12px;
                border-radius: 50px;
                color: #fff;
                font-weight: 600;
                width: 100%;
                font-size: 1rem;
            }
            .btn-auth:hover {
                opacity: 0.95;
                color: #fff;
            }
            .auth-link {
                text-align: center;
                margin-top: 1.5rem;
                color: #64748b;
            }
            .auth-link a {
                color: #8b5cf6;
                text-decoration: none;
                font-weight: 500;
            }
            .auth-link a:hover { text-decoration: underline; }
        </style>
        
        <section class="container py-5">
            <div class="card auth-card">
                <div class="auth-header">
                    <h2>Вход в аккаунт</h2>
                </div>
                <div class="auth-body">
                    '.$errorsHtml.'
                    <form method="POST" action="/login/process">
                        <input type="hidden" name="referer" value="'.htmlspecialchars($referer).'">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="'.htmlspecialchars($old['email'] ?? '').'" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-auth">Войти</button>
                    </form>
                    <div class="auth-link">
                        Нет аккаунта? <a href="/register">Зарегистрироваться</a>
                    </div>
                </div>
            </div>
        </section>';
        
        return sprintf($template, $title, $content);
    }
}