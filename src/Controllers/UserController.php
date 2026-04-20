<?php
namespace App\Controllers;

use App\Services\UserDBStorage;
use App\Config\Config;
use App\Services\ValidateBase;
use App\Views\UserTemplate;

class UserController {

    /* Форма входа на сайт */
    public function get(): string {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "POST")
            return $this->login();

        return UserTemplate::getUserTemplate();
    }
    
    public function login():string {      

        $arr = [];
        $arr['username'] =  strip_tags($_POST['username']);
        $arr['password'] = strip_tags($_POST['password']);

        // проверка логина и пароля
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceDB = new UserDBStorage();
            if (!$serviceDB->loginUser($arr['username'], $arr['password'])) {
                $_SESSION['flash'] = "Ошибка ввода логина или пароля";
                return UserTemplate::getUserTemplate();
            }
        }
        // переадресация на Главную
	    header("Location: /");
        return "";
    }

    public function logout(){
        // очистка сессионных переменных аутентификации
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        // переадресация на Главную
	    header("Location: /");
        return "";
    }

    /* Форма профиля пользователя */
    public function profile(): string {
        global $user_id;

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "POST")
            return $this->updateProfile();
        
        $data = null;
        // проверка логина и пароля
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $id=$user_id;
            $serviceDB = new UserDBStorage();
            $data = $serviceDB->getUserData($id);
            if (! $data) {
                $_SESSION['flash'] = "Ошибка получения данных пользователя";
            }
        }
        return UserTemplate::getProfileTemplate($data);
    }

    public function updateProfile(): string {
        $arr = [];
        $arr['fio'] = $_POST['fio'];
        $arr['address'] = $_POST['address'];
        $arr['phone'] = $_POST['phone'];
        // санитизация значений
        ValidateBase::sanitationArray($arr);

        // сохранение в БД
        if (Config::STORAGE_TYPE == Config::TYPE_DB) {
            $serviceDB = new UserDBStorage();
            if (!$serviceDB->updateProfile($arr)) {
                $_SESSION['flash'] = "Ошибка сохранения данных";
            }
        }

        $_SESSION['flash'] = "Данные профиля обновлены";
        // переадресация на Главную
	    header("Location: /pizza221/");
        return "";
    }

    public function history(): string 
    {
        global $user_id;
        
        $data = null;
        // получение данных по заказам для юзера
        if ($user_id > 0)
            if (Config::STORAGE_TYPE == Config::TYPE_DB) {
                $serviceDB = new UserDBStorage();
                $data = $serviceDB->getDataHistory($user_id);
                if (!$data) {
                    $_SESSION['flash'] = "Нет заказов";
                }
            }

        return UserTemplate::getHistoryTemplate($data);
    }

}