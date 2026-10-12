<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer(): void
    {
        $this->mail->SMTPDebug = 0;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.mail.ru';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'coopteh231@mail.ru';
        $this->mail->Password = 'oBdxSwM2AWnco7ALXUk5'; // ⚠️ Смени пароль!
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = 465;
        $this->mail->setFrom('coopteh231@mail.ru', 'По мультивселенной');
    }

    /**
     * Отправка письма с кнопкой подтверждения.
     * Метод теперь принимает только токен, а не ссылку.
     */
    public function sendMailUserConfirmation(string $email, string $username, string $token): bool
    {
        // Ссылка для кнопки (ваш существующий обработчик)
        // Важно: путь должен совпадать с тем, что прописан в роутере (обычно /verify)
        $verificationUrl = "https://{$_SERVER['HTTP_HOST']}/verify?token={$token}";

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($email, $username);
            $this->mail->addReplyTo('coopteh231@mail.ru', 'Поддержка');
            
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Подтверждение регистрации';
            
            // HTML-тело письма с кнопкой
            $this->mail->Body = "
                <html><body style='font-family:Arial,sans-serif;line-height:1.6;color:#333'>
                <div style='background:#0A3D62;color:white;padding:20px;text-align:center'>
                    <h2>По мультивселенной</h2>
                </div>
                <div style='padding:20px'>
                    <p>Здравствуйте, <b>{$username}</b>!</p>
                    <p>Спасибо за регистрацию. Пожалуйста, подтвердите ваш email, нажав на кнопку:</p>
                    <p style='text-align:center;margin:20px 0'>
                        <a href='{$verificationUrl}' 
                           style='background:#0A3D62;color:white;padding:12px 24px;text-decoration:none;border-radius:5px;display:inline-block;font-weight:bold'>
                            Подтвердить email
                        </a>
                    </p>
                    <p style='font-size:0.9em;color:#666'>Если вы не регистрировались, просто проигнорируйте это письмо.</p>
                    <hr>
                    <p style='text-align:center;color:#999;font-size:0.8em'>© 2025 Кемеровский кооперативный техникум</p>
                </div>
                </body></html>";
            
            // Текстовая версия письма (ссылка на случай, если кнопка не сработает)
            $this->mail->AltBody = "Здравствуйте, {$username}!\n\n"
                                 . "Пожалуйста, подтвердите ваш email, перейдя по этой ссылке:\n"
                                 . "{$verificationUrl}\n\n"
                                 . "Если вы не регистрировались, проигнорируйте это письмо.";
            
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Mailer error: " . $this->mail->ErrorInfo . " | " . $e->getMessage());
            return false;
        }
    }
}