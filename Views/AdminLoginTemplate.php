<?php
namespace Views;

class AdminLoginTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Вход для администратора';

        $errorHtml = '';
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!empty($_SESSION['admin_error'])) {
            $errorHtml = '<div class="alert alert-danger">' . $_SESSION['admin_error'] . '</div>';
            unset($_SESSION['admin_error']);
        }

        $content = '
<section class="container py-5">
    <div class="card shadow-lg rounded-4" style="max-width: 400px; margin: 0 auto;">
        <div class="card-body p-5">
            <h3 class="text-center mb-4">🔐 Админ-панель</h3>
            ' . $errorHtml . '
            <form method="POST" action="/admin/orders">
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="admin_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Войти</button>
            </form>
        </div>
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}