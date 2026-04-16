<?php
namespace App\Services;

use App\Config\Config;
use App\Services\UserDBStorage;

class ValidateRegisterData extends ValidateBase {  

    public static function validate(array &$data): bool 
    {
        // санитизация значений
        self::sanitationArray($data);
// не проходит  <p>"Ivan"<br></p>

        // проверки значений
        if (empty($data['username'])) {
            $_SESSION['flash']= "Имя пользователя обязательно";
            return false;
        }
        if (empty($data['email']))
        {
            $_SESSION['flash']= "Email обязателен";
            return false;
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            $_SESSION['flash']= "Некорректный email";
            return false;
        }
        if (empty($data['password'])) {
            $_SESSION['flash']= "Пароль обязателен";
            return false;
        }
        if (strlen($data['password']) < 6) {
            $_SESSION['flash']= "Пароль должен быть не менее 6 символов";
            return false;
        }
        if ($data['password'] !== $data['confirm_password']) {
            $_SESSION['flash']= "Пароли не совпадают";
            return false;
        }
        // Проверка на уникальность емайл
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceDB = new UserDBStorage();
            if (!$serviceDB->uniqueEmail($data['email'] )) {
                $_SESSION['flash'] = "Указанный email уже зарегистрирован";
                return false;
            }
        }
        
        return true;
    }
}