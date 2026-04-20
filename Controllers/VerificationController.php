<?php
namespace Controllers;
use Models\User;
use Models\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class VerificationController
{
    public function sendCode()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');
        $email = $_SESSION['pending_email'] ?? '';
        if (empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Email не найден в сессии']);
            exit;
        }
        $code = sprintf('%06d', mt_rand(0, 999999));
        $_SESSION['verification_code'] = $code;
        $_SESSION['verification_expire'] = time() + 600;
        $this->saveCodeToFile($email, $code);
        if ($this->sendVerificationEmail($email, $code)) {
            echo json_encode(['success' => true, 'message' => 'Код отправлен на ' . $email]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка отправки. Код сохранён в файле для отладки.',
                'debug_code' => $code
            ]);
        }
        exit;
    }

    public function verify()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $storedCode = $_SESSION['verification_code'] ?? '';
            $expire = $_SESSION['verification_expire'] ?? 0;
            if (time() > $expire) {
                $_SESSION['auth_errors'] = ['Код истёк. Запросите новый'];
                header('Location: /verify');
                exit;
            }
            if ($code === $storedCode) {
                $userModel = new User();
                $email = $_SESSION['pending_email'] ?? '';
                $user = $userModel->findByEmail($email);
                if ($user) {
                    $userModel->verifyUser($user['id']);
                    unset($_SESSION['verification_code']);
                    unset($_SESSION['verification_expire']);
                    unset($_SESSION['pending_email']);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'] ?? 'user';
                    
                    // 🔔 Логирование верификации
                    $logModel = new Log();
                    $logModel->add('Подтверждение email', $user['name'], ['email' => $user['email']]);
                    
                    header('Location: /profile?verified=1');
                    exit;
                }
            }
            $_SESSION['auth_errors'] = ['Неверный код'];
            header('Location: /verify');
            exit;
        }
        $this->showVerifyPage();
    }

    private function showVerifyPage()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $email = $_SESSION['pending_email'] ?? '';
        $errors = $_SESSION['auth_errors'] ?? [];
        unset($_SESSION['auth_errors']);
        $errorsHtml = '';
        if (!empty($errors)) {
            $errorsHtml = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
        }
        $content = '
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Подтверждение Email</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
            .verify-card { max-width: 450px; margin: 3rem auto; border: none; border-radius: 20px; box-shadow: 0 20px 60px rgba(13,110,253,0.15); }
            .verify-header { background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%); color: #fff; padding: 1.5rem; border-radius: 20px 20px 0 0; text-align: center; }
            .verify-body { padding: 2rem; }
            .code-input { text-align: center; font-size: 1.5rem; letter-spacing: 0.5rem; }
            .btn-verify { background: linear-gradient(135deg, #8b5cf6, #0dcaf0); border: none; padding: 12px; border-radius: 50px; color: #fff; font-weight: 600; width: 100%; }
            </style>
        </head>
        <body>
        <section class="container py-5">
            <div class="card verify-card">
                <div class="verify-header"><h2>📧 Подтверждение Email</h2></div>
                <div class="verify-body">
                    '.$errorsHtml.'
                    <p class="text-center mb-4">Код отправлен на <strong>'.htmlspecialchars($email).'</strong></p>
                    <form method="POST" action="/verify">
                        <div class="mb-3">
                            <input type="text" name="code" class="form-control code-input" maxlength="6" placeholder="000000" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-verify">Подтвердить</button>
                    </form>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-link" onclick="resendCode()">Отправить повторно</button>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        function resendCode() {
            fetch("/verification/send", { method: "POST" })
                .then(r => r.json())
                .then(d => alert(d.message));
        }
        </script>
        </body>
        </html>';
        echo $content;
        exit;
    }

    private function sendVerificationEmail(string $email, string $code): bool
    {
        try {
            $envFile = 'C:/xampp/htdocs/.env';
            $env = [];
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    if (strpos($line, '=') === false) continue;
                    [$key, $value] = explode('=', $line, 2);
                    $env[trim($key)] = trim(trim($value), '"\'');
                }
            }
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $env['SMTP_HOST'] ?? 'smtp.yandex.ru';
            $mail->SMTPAuth = true;
            $mail->Username = $env['SMTP_USER'];
            $mail->Password = $env['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = (int)($env['SMTP_PORT'] ?? 465);
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($env['SMTP_USER'], 'gym low cortisol');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = '🔐 Код подтверждения';
            $mail->Body = "<h2>Ваш код: {$code}</h2><p>Действителен 10 минут</p>";
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Verification email error: {$e->getMessage()}");
            return false;
        }
    }

    private function saveCodeToFile(string $email, string $code): void
    {
        $dir = 'C:/xampp/htdocs/storage/codes';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $filename = $dir . '/codes_' . date('Y-m-d') . '.txt';
        $line = date('Y-m-d H:i:s') . " | {$email} | {$code}\n";
        file_put_contents($filename, $line, FILE_APPEND);
    }
}