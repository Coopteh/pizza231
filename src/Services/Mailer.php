<?php 
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Config\Config;

class Mailer {
    //  временный емайл с https://24.email
    public static function sendOrderMail($email):bool {
        $mail = new PHPMailer();
        if (isset($email) && !empty($email)) {
            try {
                $mail->SMTPDebug = 2;
                $mail->CharSet = 'UTF-8';
                $mail->SetFrom("coopteh231@mail.ru","PIZZA-231");
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'ssl://smtp.mail.ru';                   //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'coopteh231@mail.ru';                     //SMTP username
                $mail->Password   = 'oBdxSwM2AWnco7ALXUk5';
                $mail->Port       = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Subject = 'Заявка с сайта: PIZZA-231';
                $mail->Body = "Информационное сообщение c сайта PIZZA-231 <br><br>
                ------------------------------------------<br><br>
                Спасибо!<br><br>
                Ваш заказ успешно создан и передан службе доставки.<br><br>
                Сообщение сгенерировано автоматически.";
                if ($mail->send()) {
                    return true;
                } else {
                    throw new Exception('Ошибка с отправкой письма');
                }
            } catch (Exception $error) {
                $message = $error->getMessage();
                var_dump($message);
                exit();
            }
        }
        return false;
    }

    // Отправка email с подтверждением
    public static function sendMailUserConfirmation(
        string $email, 
        string $verification_token,
        string $username
    ): bool 
    {
        $mail = new PHPMailer();
        if (isset($email) && !empty($email)) {
            try {
                $mail->SMTPDebug = 2;
                $mail->CharSet = 'UTF-8';
                $mail->SetFrom("coopteh231@mail.ru","PIZZA-231");
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'ssl://smtp.mail.ru';                   //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'coopteh231@mail.ru';                     //SMTP username
                $mail->Password   = 'oBdxSwM2AWnco7ALXUk5';
                $mail->Port       = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

$verification_link = Config::SITE_URL . "/verify/" . $verification_token;

                $mail->Subject = "Подтверждение регистрации с сайта: PIZZA-231";
                $mail->Body = "Информационное сообщение c сайта PIZZA-231 <br><br>
                ------------------------------------------<br><br>
                Здравствуйте, $username!<br><br>
                Пожалуйста, подтвердите ваш email, перейдя по ссылке:<br>
                <a href='$verification_link'>$verification_link</a> <br><br>
                Сообщение сгенерировано автоматически.";
                
                if ($mail->send()) {
                    return true;
                } else {
                    throw new Exception('Ошибка с отправкой письма');
                }
            } catch (Exception $error) {
                $message = $error->getMessage();
                var_dump($message);
                exit();
            }
        }
        return false;
    }
}