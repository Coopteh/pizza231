<?php
namespace App\Views;

class RegisterTemplate
{
    public static function getRegisterTemplate(array $errors = [], array $old = []): string
    {
        $errorsHtml = '';
        if (!empty($errors)) {
            $errorsHtml = '<div class="alert alert-danger">';
            foreach ($errors as $error) {
                $errorText = is_array($error) ? json_encode($error, JSON_UNESCAPED_UNICODE) : $error;
                $errorsHtml .= "<div>• " . htmlspecialchars($errorText) . "</div>";
            }
            $errorsHtml .= '</div>';
        }

        $username = htmlspecialchars($old['username'] ?? '');
        $email    = htmlspecialchars($old['email'] ?? '');
        // Пароль НЕ подставляется — это стандарт безопасности. Поле всегда пустое.

        $content = <<<HTML
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <h2 class="text-center mb-4">Регистрация</h2>
                            {$errorsHtml}
                            <form method="POST" action="/register">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Имя пользователя *</label>
                                    <input type="text" class="form-control" id="username" name="username" value="{$username}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{$email}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Пароль *</label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="6" autocomplete="new-password">
                                    <div class="form-text">Минимум 6 символов</div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Подтвердите пароль *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required autocomplete="new-password">
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
                            </form>
                            <div class="text-center mt-3">
                                <p class="mb-0">Уже есть аккаунт? <a href="/login">Войти</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;

        $baseTemplate = BaseTemplate::getTemplate();
        return sprintf($baseTemplate, 'Регистрация - По мультивселенной', $content);
    }

    public static function getVerifyTemplate(string $message, bool $success = true): string
    {
        $alertClass = $success ? 'alert-success' : 'alert-danger';
        $icon = $success ? '✅' : '❌';

        $content = <<<HTML
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow text-center">
                        <div class="card-body p-4">
                            <div style="font-size: 48px; margin-bottom: 16px;">{$icon}</div>
                            <h2 class="mb-3">Подтверждение email</h2>
                            <div class="alert {$alertClass}">{$message}</div>
                            <a href="/login" class="btn btn-primary mt-3">Войти в аккаунт</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;

        $baseTemplate = BaseTemplate::getTemplate();
        return sprintf($baseTemplate, 'Подтверждение - По мультивселенной', $content);
    }
}