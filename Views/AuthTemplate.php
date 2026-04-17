<?php

namespace Views;

use Lib\Language;

class AuthTemplate extends BaseTemplate
{
    public static function getRegisterTemplate(array $errors = [], array $data = []): string
    {
        $template = parent::getTemplate();
        $title = Language::get('auth_register') . ' - ' . Language::get('site_name');

        $errorMessages = [
            'name_required' => Language::get('auth_name_required'),
            'email_invalid' => Language::get('checkout_error_email'),
            'email_exists' => Language::get('auth_email_exists'),
            'phone_required' => Language::get('auth_phone_required'),
            'password_short' => Language::get('auth_password_short'),
            'password_mismatch' => Language::get('auth_password_mismatch')
        ];

        $errorHtml = '';
        foreach ($errors as $error) {
            $errorHtml .= '<div class="alert-error">' . ($errorMessages[$error] ?? $error) . '</div>';
        }

        $content = '
        <style>
            /* 🌙 ТЁМНАЯ ТЕМА: Стили для регистрации */
            .auth-container {
                max-width: 500px;
                margin: 2rem auto;
                background: var(--surface);
                padding: 2rem;
                border-radius: 12px;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .auth-title {
                text-align: center;
                margin-bottom: 1.5rem;
                color: var(--primary);
                font-size: 1.75rem;
                transition: color 0.3s ease;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: var(--text);
                font-size: 0.95rem;
                transition: color 0.3s ease;
            }
            
            .form-control {
                width: 100%;
                padding: 0.875rem 1rem;
                border: 1px solid var(--border);
                border-radius: 6px;
                font-size: 1rem;
                font-size: 16px;
                min-height: 48px;
                background: var(--surface);
                color: var(--text);
                transition: all 0.3s ease;
            }
            
            .form-control:focus {
                border-color: var(--primary);
                outline: none;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            }
            
            .btn-auth {
                width: 100%;
                padding: 1rem;
                background: var(--primary);
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-auth:hover {
                background: var(--primary-dark);
            }
            
            .auth-link {
                text-align: center;
                margin-top: 1.5rem;
                color: var(--text-muted);
                font-size: 0.9rem;
                transition: color 0.3s ease;
            }
            
            .auth-link a {
                color: var(--primary);
                text-decoration: none;
                font-weight: 600;
            }
            
            .alert-error {
                background: rgba(229, 62, 62, 0.15);
                color: var(--danger);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--danger);
                font-size: 0.9rem;
            }
            
            .alert-success {
                background: rgba(56, 161, 105, 0.15);
                color: var(--success);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--success);
                font-size: 0.9rem;
            }
            
            @media (max-width: 576px) {
                .auth-container {
                    margin: 1rem;
                    padding: 1.5rem;
                }
                .auth-title {
                    font-size: 1.5rem;
                }
                .form-group {
                    margin-bottom: 1rem;
                }
                .form-control {
                    padding: 0.75rem 1rem;
                }
                .btn-auth {
                    padding: 0.875rem;
                }
            }
        </style>
        <section class="container py-4 py-md-5">
            <div class="auth-container">
                <h1 class="auth-title">' . Language::get('auth_register') . '</h1>
                ' . $errorHtml . '
                <form method="POST" action="/auth/register">
                    <div class="form-group">
                        <label>' . Language::get('checkout_name') . ' *</label>
                        <input type="text" name="name" class="form-control" value="' . htmlspecialchars($data['name'] ?? '') . '" required autocomplete="name">
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" value="' . htmlspecialchars($data['email'] ?? '') . '" required autocomplete="email">
                    </div>
                    <div class="form-group">
                        <label>' . Language::get('checkout_phone') . ' *</label>
                        <input type="tel" name="phone" class="form-control" value="' . htmlspecialchars($data['phone'] ?? '') . '" required placeholder="+7 (___) ___-__-__" autocomplete="tel">
                    </div>
                    <div class="form-group">
                        <label>' . Language::get('auth_new_password') . ' *</label>
                        <input type="password" name="password" class="form-control" required minlength="6" autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label>' . Language::get('auth_confirm_password') . ' *</label>
                        <input type="password" name="password_confirm" class="form-control" required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn-auth">' . Language::get('auth_register') . '</button>
                </form>
                <div class="auth-link">' . Language::get('auth_login') . '? <a href="/auth/login">' . Language::get('auth_login') . '</a></div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    public static function getLoginTemplate(array $errors = [], bool $registered = false, bool $verified = false, bool $resetSuccess = false): string
    {
        $template = parent::getTemplate();
        $title = Language::get('auth_login') . ' - ' . Language::get('site_name');

        $errorMessages = [
            'fields_required' => 'Введите email и пароль',
            'invalid_credentials' => Language::get('auth_invalid_credentials'),
            'email_not_verified' => Language::get('auth_email_not_verified')
        ];

        $errorHtml = '';
        foreach ($errors as $error) {
            $errorHtml .= '<div class="alert-error">' . ($errorMessages[$error] ?? $error) . '</div>';
        }

        $successHtml = '';
        if ($registered) {
            $successHtml = '<div class="alert-success">' . Language::get('auth_registration_success') . '</div>';
        }
        if ($verified) {
            $successHtml = '<div class="alert-success">' . Language::get('auth_email_verified') . '</div>';
        }
        if ($resetSuccess) {
            $successHtml = '<div class="alert-success">' . Language::get('auth_password_changed') . '</div>';
        }

        $content = '
        <style>
            /* 🌙 ТЁМНАЯ ТЕМА: Стили для входа */
            .auth-container {
                max-width: 500px;
                margin: 2rem auto;
                background: var(--surface);
                padding: 2rem;
                border-radius: 12px;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .auth-title {
                text-align: center;
                margin-bottom: 1.5rem;
                color: var(--primary);
                font-size: 1.75rem;
                transition: color 0.3s ease;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: var(--text);
                font-size: 0.95rem;
                transition: color 0.3s ease;
            }
            
            .form-control {
                width: 100%;
                padding: 0.875rem 1rem;
                border: 1px solid var(--border);
                border-radius: 6px;
                font-size: 1rem;
                font-size: 16px;
                min-height: 48px;
                background: var(--surface);
                color: var(--text);
                transition: all 0.3s ease;
            }
            
            .form-control:focus {
                border-color: var(--primary);
                outline: none;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            }
            
            .btn-auth {
                width: 100%;
                padding: 1rem;
                background: var(--primary);
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-auth:hover {
                background: var(--primary-dark);
            }
            
            .auth-link {
                text-align: center;
                margin-top: 1.5rem;
                color: var(--text-muted);
                font-size: 0.9rem;
                transition: color 0.3s ease;
            }
            
            .auth-link a {
                color: var(--primary);
                text-decoration: none;
                font-weight: 600;
            }
            
            .alert-error {
                background: rgba(229, 62, 62, 0.15);
                color: var(--danger);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--danger);
                font-size: 0.9rem;
            }
            
            .alert-success {
                background: rgba(56, 161, 105, 0.15);
                color: var(--success);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--success);
                font-size: 0.9rem;
            }
            
            @media (max-width: 576px) {
                .auth-container {
                    margin: 1rem;
                    padding: 1.5rem;
                }
                .auth-title {
                    font-size: 1.5rem;
                }
            }
        </style>
        <section class="container py-4 py-md-5">
            <div class="auth-container">
                <h1 class="auth-title">' . Language::get('auth_login') . '</h1>
                ' . $successHtml . $errorHtml . '
                <form method="POST" action="/auth/login">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" required autocomplete="email">
                    </div>
                    <div class="form-group">
                        <label>' . Language::get('auth_new_password') . ' *</label>
                        <input type="password" name="password" class="form-control" required autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn-auth">' . Language::get('auth_login') . '</button>
                </form>
                <div class="auth-link"><a href="/auth/forgot-password">' . Language::get('auth_forgot_password') . '</a></div>
                <div class="auth-link">' . Language::get('auth_register') . '? <a href="/auth/register">' . Language::get('auth_register') . '</a></div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    public static function getVerifyCodeTemplate(string $email, string $name, array $errors = [], bool $loginAttempt = false): string
    {
        $template = parent::getTemplate();
        $title = Language::get('auth_verify_email') . ' - ' . Language::get('site_name');

        $errorMessages = [
            'code_invalid' => 'Код должен содержать 6 цифр',
            'code_wrong' => 'Неверный код',
            'no_email' => 'Email не найден',
            'already_verified' => 'Email уже подтверждён',
            'resend_failed' => 'Ошибка отправки кода'
        ];

        $errorHtml = '';
        foreach ($errors as $error) {
            $errorHtml .= '<div class="alert-error">' . ($errorMessages[$error] ?? $error) . '</div>';
        }

        $resent = isset($_GET['resent']);
        $successHtml = $resent ? '<div class="alert-success">Новый код отправлен на ' . htmlspecialchars($email) . '</div>' : '';

        $content = '
        <style>
            .auth-container {
                max-width: 500px;
                margin: 2rem auto;
                background: var(--surface);
                padding: 2rem;
                border-radius: 12px;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .auth-title {
                text-align: center;
                margin-bottom: 1.5rem;
                color: var(--primary);
                font-size: 1.75rem;
                transition: color 0.3s ease;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: var(--text);
                transition: color 0.3s ease;
            }
            
            .form-control {
                width: 100%;
                padding: 0.875rem 1rem;
                border: 1px solid var(--border);
                border-radius: 6px;
                font-size: 1.5rem;
                text-align: center;
                letter-spacing: 8px;
                font-weight: 700;
                background: var(--surface);
                color: var(--text);
                transition: all 0.3s ease;
            }
            
            .form-control:focus {
                border-color: var(--primary);
                outline: none;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            }
            
            .btn-auth {
                width: 100%;
                padding: 1rem;
                background: var(--primary);
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
                min-height: 48px;
            }
            
            .btn-auth:hover {
                background: var(--primary-dark);
            }
            
            .alert-error {
                background: rgba(229, 62, 62, 0.15);
                color: var(--danger);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--danger);
            }
            
            .alert-success {
                background: rgba(56, 161, 105, 0.15);
                color: var(--success);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--success);
            }
            
            .resend-link {
                text-align: center;
                margin-top: 1rem;
            }
            
            .resend-link a {
                color: var(--primary);
                text-decoration: none;
            }
            
            @media (max-width: 576px) {
                .auth-container {
                    margin: 1rem;
                    padding: 1.5rem;
                }
            }
        </style>
        <section class="container py-4 py-md-5">
            <div class="auth-container">
                <h1 class="auth-title">' . Language::get('auth_verify_email') . '</h1>
                <p style="text-align:center;color:var(--text-muted);margin-bottom:1.5rem">
                    Здравствуйте, <strong>' . htmlspecialchars($name) . '</strong>! 
                    Код отправлен на <strong>' . htmlspecialchars($email) . '</strong>
                </p>
                ' . $successHtml . $errorHtml . '
                <form method="POST" action="/auth/verify-code">
                    <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">
                    <div class="form-group">
                        <label>Введите 6-значный код</label>
                        <input type="text" name="code" class="form-control" maxlength="6" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, \'\'">
                    </div>
                    <button type="submit" class="btn-auth">Подтвердить</button>
                </form>
                <div class="resend-link"><a href="/auth/resend-code">Отправить код повторно</a></div>
                ' . ($loginAttempt ? '<div class="auth-link" style="margin-top:1.5rem"><a href="/auth/login">← Вернуться ко входу</a></div>' : '') . '
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    public static function getForgotPasswordTemplate(array $errors = [], bool $success = false): string
    {
        $template = parent::getTemplate();
        $title = Language::get('auth_forgot_password') . ' - ' . Language::get('site_name');

        $errorHtml = '';
        foreach ($errors as $error) {
            $errorHtml .= '<div class="alert-error">' . Language::get('checkout_error_email') . '</div>';
        }

        $successHtml = $success ? '<div class="alert-success">Если email зарегистрирован, мы отправили инструкцию по сбросу пароля</div>' : '';

        $content = '
        <style>
            .auth-container {
                max-width: 500px;
                margin: 2rem auto;
                background: var(--surface);
                padding: 2rem;
                border-radius: 12px;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .auth-title {
                text-align: center;
                margin-bottom: 1.5rem;
                color: var(--primary);
                font-size: 1.75rem;
                transition: color 0.3s ease;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: var(--text);
                transition: color 0.3s ease;
            }
            
            .form-control {
                width: 100%;
                padding: 0.875rem 1rem;
                border: 1px solid var(--border);
                border-radius: 6px;
                background: var(--surface);
                color: var(--text);
                transition: all 0.3s ease;
            }
            
            .form-control:focus {
                border-color: var(--primary);
                outline: none;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            }
            
            .btn-auth {
                width: 100%;
                padding: 1rem;
                background: var(--primary);
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
                min-height: 48px;
            }
            
            .btn-auth:hover {
                background: var(--primary-dark);
            }
            
            .alert-error {
                background: rgba(229, 62, 62, 0.15);
                color: var(--danger);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--danger);
            }
            
            .alert-success {
                background: rgba(56, 161, 105, 0.15);
                color: var(--success);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--success);
            }
            
            .auth-link {
                text-align: center;
                margin-top: 1.5rem;
                color: var(--text-muted);
                transition: color 0.3s ease;
            }
            
            .auth-link a {
                color: var(--primary);
                text-decoration: none;
            }
        </style>
        <section class="container py-4 py-md-5">
            <div class="auth-container">
                <h1 class="auth-title">' . Language::get('auth_forgot_password') . '</h1>
                <p style="text-align:center;color:var(--text-muted);margin-bottom:1.5rem">Введите email для получения инструкции по сбросу пароля</p>
                ' . $successHtml . $errorHtml . '
                <form method="POST" action="/auth/forgot-password">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-auth">Отправить</button>
                </form>
                <div class="auth-link"><a href="/auth/login">← Вернуться ко входу</a></div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    public static function getResetPasswordTemplate(array $errors = [], bool $validToken = false, string $token = ''): string
    {
        $template = parent::getTemplate();
        $title = Language::get('auth_reset_password') . ' - ' . Language::get('site_name');

        $errorMessages = [
            'password_short' => Language::get('auth_password_short'),
            'password_mismatch' => Language::get('auth_password_mismatch')
        ];

        $errorHtml = '';
        foreach ($errors as $error) {
            $errorHtml .= '<div class="alert-error">' . ($errorMessages[$error] ?? $error) . '</div>';
        }

        if (!$validToken) {
            $content = '<section class="container py-5"><div class="auth-container"><h1 class="auth-title">Ошибка</h1><p style="text-align:center;color:var(--text-muted)">Ссылка недействительна или истекла</p><div class="auth-link"><a href="/auth/forgot-password">Запросить сброс пароля</a></div></div></section>';
            return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
        }

        $content = '
        <style>
            .auth-container {
                max-width: 500px;
                margin: 2rem auto;
                background: var(--surface);
                padding: 2rem;
                border-radius: 12px;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .auth-title {
                text-align: center;
                margin-bottom: 1.5rem;
                color: var(--primary);
                font-size: 1.75rem;
                transition: color 0.3s ease;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: var(--text);
                transition: color 0.3s ease;
            }
            
            .form-control {
                width: 100%;
                padding: 0.875rem 1rem;
                border: 1px solid var(--border);
                border-radius: 6px;
                background: var(--surface);
                color: var(--text);
                transition: all 0.3s ease;
            }
            
            .form-control:focus {
                border-color: var(--primary);
                outline: none;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            }
            
            .btn-auth {
                width: 100%;
                padding: 1rem;
                background: var(--primary);
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
                min-height: 48px;
            }
            
            .btn-auth:hover {
                background: var(--primary-dark);
            }
            
            .alert-error {
                background: rgba(229, 62, 62, 0.15);
                color: var(--danger);
                padding: 0.875rem 1rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--danger);
            }
        </style>
        <section class="container py-4 py-md-5">
            <div class="auth-container">
                <h1 class="auth-title">' . Language::get('auth_reset_password') . '</h1>
                ' . $errorHtml . '
                <form method="POST" action="/auth/reset-password?token=' . htmlspecialchars($token) . '">
                    <div class="form-group">
                        <label>' . Language::get('auth_new_password') . '</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>' . Language::get('auth_confirm_password') . '</label>
                        <input type="password" name="password_confirm" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-auth">Сменить пароль</button>
                </form>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}
