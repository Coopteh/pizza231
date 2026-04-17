<?php

// 🔐 ОБНОВЛЕНО: Добавлена отправка кода для смены пароля

namespace Lib;

class Auth
{
    private const FROM_EMAIL = 'noreply@kemt.local';
    private const FROM_NAME = 'Geek LegendS';
    private const REPLY_TO = 'info@kemt.ru';

    public static function sendVerificationEmail(string $email, string $code, string $name): bool
    {
        $to = $email;
        $subject = 'Код подтверждения регистрации - ' . self::FROM_NAME;
        $message = self::buildVerificationEmailWithCode($name, $code);
        $headers = self::getHeaders();

        $result = @mail($to, $subject, $message, $headers);
        error_log("Verification email to {$email}: " . ($result ? 'SENT' : 'FAILED'));
        return $result;
    }

    public static function resendVerificationCode(string $email, string $code, string $name): bool
    {
        $to = $email;
        $subject = 'Новый код подтверждения - ' . self::FROM_NAME;
        $message = self::buildResendCodeEmail($name, $code);
        $headers = self::getHeaders();

        $result = @mail($to, $subject, $message, $headers);
        error_log("Resend code email to {$email}: " . ($result ? 'SENT' : 'FAILED'));
        return $result;
    }

    // 🔐 ДОБАВЛЕНО: Отправка кода для смены пароля
    public static function sendPasswordChangeCode(string $email, string $code, string $name): bool
    {
        $to = $email;
        $subject = 'Код подтверждения смены пароля - ' . self::FROM_NAME;
        $message = self::buildPasswordChangeEmail($name, $code);
        $headers = self::getHeaders();

        $result = @mail($to, $subject, $message, $headers);
        error_log("Password change code to {$email}: " . ($result ? 'SENT' : 'FAILED'));
        return $result;
    }

    public static function sendPasswordResetEmail(string $email, string $token, string $name): bool
    {
        $to = $email;
        $subject = 'Сброс пароля - ' . self::FROM_NAME;
        $link = 'https://kemt.local/auth/reset-password?token=' . $token;
        $message = self::buildResetEmail($name, $link);
        $headers = self::getHeaders();

        $result = @mail($to, $subject, $message, $headers);
        error_log("Password reset email to {$email}: " . ($result ? 'SENT' : 'FAILED'));
        return $result;
    }

    private static function getHeaders(): string
    {
        return "MIME-Version: 1.0\r\n" .
               "Content-type: text/html; charset=UTF-8\r\n" .
               "From: " . self::FROM_NAME . " <" . self::FROM_EMAIL . ">\r\n" .
               "Reply-To: " . self::REPLY_TO . "\r\n" .
               "X-Mailer: PHP/" . phpversion() . "\r\n";
    }

    private static function buildVerificationEmailWithCode(string $name, string $code): string
    {
        return '<!DOCTYPE html>
<html lang="ru">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f7fafc">
<table role="presentation" style="width:100%;border-collapse:collapse">
<tr><td align="center" style="padding:40px 20px">
<table role="presentation" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1)">
<tr><td style="background:linear-gradient(135deg,#2c5282,#1a365d);padding:40px 30px;text-align:center">
<h1 style="margin:0;color:#fff;font-size:28px">Подтверждение регистрации</h1></td></tr>
<tr><td style="padding:30px">
<p style="font-size:16px;color:#2d3748">Здравствуйте, <strong>'.htmlspecialchars($name).'</strong>!</p>
<p style="font-size:16px;color:#2d3748">Спасибо за регистрацию на Geek Legends. Для активации аккаунта введите этот код на сайте:</p>
<table role="presentation" style="margin:30px auto"><tr><td align="center">
<div style="background:#ebf4ff;padding:20px 40px;border-radius:8px;border:2px dashed #2c5282">
<span style="font-size:36px;font-weight:700;color:#2c5282;letter-spacing:8px">'.$code.'</span>
</div>
</td></tr></table>
<p style="font-size:14px;color:#718096;text-align:center">Код действителен в течение <strong>15 минут</strong></p>
<p style="font-size:14px;color:#718096;margin-top:30px">Если вы не регистрировались, проигнорируйте это письмо.</p>
</td></tr>
<tr><td style="background:#1a365d;padding:25px 30px;text-align:center">
<p style="margin:0;color:rgba(255,255,255,0.8);font-size:14px">© '.date('Y').' '.self::FROM_NAME.'</p>
</td></tr>
</table></td></tr></table></body></html>';
    }

    private static function buildResendCodeEmail(string $name, string $code): string
    {
        return '<!DOCTYPE html>
<html lang="ru">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f7fafc">
<table role="presentation" style="width:100%;border-collapse:collapse">
<tr><td align="center" style="padding:40px 20px">
<table role="presentation" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden">
<tr><td style="background:linear-gradient(135deg,#ed8936,#dd6b20);padding:40px 30px;text-align:center">
<h1 style="margin:0;color:#fff;font-size:28px">Новый код подтверждения</h1></td></tr>
<tr><td style="padding:30px">
<p style="font-size:16px;color:#2d3748">Здравствуйте, <strong>'.htmlspecialchars($name).'</strong>!</p>
<p style="font-size:16px;color:#2d3748">Вы запросили новый код подтверждения. Ваш код:</p>
<table role="presentation" style="margin:30px auto"><tr><td align="center">
<div style="background:#fffaf0;padding:20px 40px;border-radius:8px;border:2px dashed #ed8936">
<span style="font-size:36px;font-weight:700;color:#ed8936;letter-spacing:8px">'.$code.'</span>
</div>
</td></tr></table>
<p style="font-size:14px;color:#718096;text-align:center">Код действителен в течение <strong>15 минут</strong></p>
</td></tr>
<tr><td style="background:#1a365d;padding:25px 30px;text-align:center">
<p style="margin:0;color:rgba(255,255,255,0.8);font-size:14px">© '.date('Y').' '.self::FROM_NAME.'</p>
</td></tr>
</table></td></tr></table></body></html>';
    }

    // 🔐 ДОБАВЛЕНО: Шаблон письма для смены пароля
    private static function buildPasswordChangeEmail(string $name, string $code): string
    {
        return '<!DOCTYPE html>
<html lang="ru">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f7fafc">
<table role="presentation" style="width:100%;border-collapse:collapse">
<tr><td align="center" style="padding:40px 20px">
<table role="presentation" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1)">
<tr><td style="background:linear-gradient(135deg,#e53e3e,#c53030);padding:40px 30px;text-align:center">
<h1 style="margin:0;color:#fff;font-size:28px">Смена пароля</h1></td></tr>
<tr><td style="padding:30px">
<p style="font-size:16px;color:#2d3748">Здравствуйте, <strong>'.htmlspecialchars($name).'</strong>!</p>
<p style="font-size:16px;color:#2d3748">Вы запросили смену пароля. Для подтверждения введите этот код на сайте:</p>
<table role="presentation" style="margin:30px auto"><tr><td align="center">
<div style="background:#fff5f5;padding:20px 40px;border-radius:8px;border:2px dashed #e53e3e">
<span style="font-size:36px;font-weight:700;color:#e53e3e;letter-spacing:8px">'.$code.'</span>
</div>
</td></tr></table>
<p style="font-size:14px;color:#718096;text-align:center">Код действителен в течение <strong>10 минут</strong></p>
<p style="font-size:14px;color:#718096;margin-top:30px">Если вы не запрашивали смену пароля, проигнорируйте это письмо или обратитесь в поддержку.</p>
</td></tr>
<tr><td style="background:#1a365d;padding:25px 30px;text-align:center">
<p style="margin:0;color:rgba(255,255,255,0.8);font-size:14px">© '.date('Y').' '.self::FROM_NAME.'</p>
</td></tr>
</table></td></tr></table></body></html>';
    }

    private static function buildResetEmail(string $name, string $link): string
    {
        return '<!DOCTYPE html>
<html lang="ru">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f7fafc">
<table role="presentation" style="width:100%;border-collapse:collapse">
<tr><td align="center" style="padding:40px 20px">
<table role="presentation" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden">
<tr><td style="background:linear-gradient(135deg,#e53e3e,#c53030);padding:40px 30px;text-align:center">
<h1 style="margin:0;color:#fff;font-size:28px">Сброс пароля</h1></td></tr>
<tr><td style="padding:30px">
<p style="font-size:16px;color:#2d3748">Здравствуйте, <strong>'.htmlspecialchars($name).'</strong>!</p>
<p style="font-size:16px;color:#2d3748">Вы запросили сброс пароля. Нажмите кнопку для создания нового:</p>
<table role="presentation" style="margin:30px 0"><tr><td align="center">
<a href="'.$link.'" style="display:inline-block;background:#e53e3e;color:#fff;padding:14px 40px;text-decoration:none;border-radius:8px;font-weight:600">Сбросить пароль</a>
</td></tr></table>
<p style="font-size:14px;color:#718096">Ссылка действительна 1 час.</p>
<p style="font-size:14px;color:#718096;margin-top:30px">Если не запрашивали сброс, проигнорируйте письмо.</p>
</td></tr></table></td></tr></table></body></html>';
    }
}
