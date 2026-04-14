<?php
namespace App\Controllers;

use App\Services\ValidateRegisterData;
use App\Views\RegisterTemplate;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Config\Config;
use App\Services\UserDBStorage;
use App\Services\Mailer;

class RegisterController {
    public function get(): string {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "POST")
            return $this->create();

        return RegisterTemplate::getRegisterTemplate();
    }

    public function create() {
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceStorage = new UserDBStorage();
            $model = new User($serviceStorage, Config::TABLE_USERS);
        }
        $data= [];
        $data['username'] = $_POST['username'];
        $data['email'] = $_POST['email'];
        $data['password'] = $_POST['password'];
        $data['confirm_password'] = $_POST['confirm_password'];
        $data['confirm_password'] = $_POST['confirm_password'];
        // валидации
        if (ValidateRegisterData::validate($data) == false) {
            header("Location: /register");
            return;
        }
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $verification_token = bin2hex(random_bytes(32));

        $data['password'] = $hashed_password;
        $data['token'] = $verification_token;

        // сохранение пользователя
        $model->saveData($data);

        Mailer::sendMailUserConfirmation(
            $data['email'], 
            $verification_token,
            $data['username']
        );
        // сообщение для пользователя
        $_SESSION['flash'] = "Спасибо за регистрацию! На ваш емайл отправлено письмо для подтверждения регистрации.";
        
        // переадресация на Главную
	    header("Location: /");
	    return '';
    }

    public function verify($id): string {
        if (!isset($id))
            $_SESSION['flash'] = "Ваш токен неверен";

        // Запись верификации (is_verified=1) для указанного токена
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceDB = new UserDBStorage();
            if ($serviceDB->saveVerified($id)) {
                return RegisterTemplate::getVerifyTemplate();
            } else {
                $_SESSION['flash'] = "Ваш токен ненайден";
            }
        }
        // переадресация на Главную
	    header("Location: /");
        return "";
    }

}