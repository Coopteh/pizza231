<?php
require_once 'config/env.php';

if (!file_exists('vendor/autoload.php')) {
    die('❌ Ошибка: Не найден vendor/autoload.php. Выполните: composer install');
}

require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

echo '<h2>🔧 Тест отправки через .env</h2><hr>';

echo "SMTP Host: " . env('SMTP_HOST') . "<br>";
echo "SMTP Port: " . env('SMTP_PORT') . "<br>";
echo "Username: " . env('SMTP_USERNAME') . "<br><br>";

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str) { echo htmlspecialchars($str) . "<br>"; };
    
    $mail->isSMTP();
    $mail->Host = env('SMTP_HOST');
    $mail->SMTPAuth = true;
    $mail->Username = env('SMTP_USERNAME');
    $mail->Password = env('SMTP_PASSWORD');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = (int)env('SMTP_PORT');
    
    $mail->setFrom(env('SMTP_FROM_EMAIL'), env('SMTP_FROM_NAME'));
    $mail->addAddress(env('SMTP_USERNAME'));
    
    $mail->Subject = '✅ Тест .env — ' . date('H:i:s');
    $mail->isHTML(true);
    $mail->Body = '<h3>Если вы видите это — .env работает!</h3>';
    
    $mail->send();
    echo '<hr><h3 style="color:green">✅ ПИСЬМО ОТПРАВЛЕНО!</h3>';
} catch (Exception $e) {
    echo '<hr><h3 style="color:red">❌ ОШИБКА: ' . $mail->ErrorInfo . '</h3>';
}
?>