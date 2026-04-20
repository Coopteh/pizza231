<?php 
namespace App\Views;

use App\Views\BaseTemplate;
use App\Configs\Config;

class UserTemplate extends BaseTemplate
{
    /*
        Формирование страница "Регистрация"
    */
    public static function getUserTemplate(): string {
        // $template = parent::getTemplate($content="");
        $title= 'Вход пользователя';
        $content = <<<CORUSEL
        <main class="row p-5 justify-content-center align-items-center">
            <div class="col-5 bg-light border">
                <h3 class="mb-5">Вход пользователя</h3>
        CORUSEL;
        $content .= self::getFormLogin();
        $content .= "</div></main>";

        $resultTemplate =  sprintf($title, $content);
        return parent::getTemplate($resultTemplate);
    }

    /* 
        Форма входа (логин, пароль)
    */
    public static function getFormLogin(): string {
        $html= <<<FORMA
                <form action="/login" method="POST">
                    <div class="mb-3">
                        <label for="nameInput" class="form-label">Логин (имя или емайл):</label>
                        <input type="text" name="username" class="form-control" id="nameInput" required>
                    </div>
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Пароль:</label>
                        <input type="password" name="password" class="form-control" id="passwordInput">
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Войти</button>
                </form>
        FORMA;
        return $html;
    }
    public static function getProfileTemplate(?array $data): string {
        $title= 'Профиль пользователя';
        $content = <<<CORUSEL
        <main class="row p-5 justify-content-center align-items-center">
            <div class="col-8 bg-light border">
                <h3 class="mb-5">Профиль пользователя</h3>
        CORUSEL;
        $content .= self::getFormProfile($data);
        $content .= "</div></main>";

        $resultTemplate =  sprintf($title, $content);
        return parent::getTemplate($resultTemplate);
    }

    /* 
        Форма входа (логин, пароль)
    */
    public static function getFormProfile(?array $data): string {

        $fio = (isset($data) && $data["fio"]) ? $data["fio"] : "";
        $email = (isset($data) && $data["email"]) ? $data["email"] : "";
        $address = (isset($data) && $data["address"])? $data["address"] : "";
        $phone = (isset($data) && $data["phone"])? $data["phone"] : "";

        $html= <<<FORMA
                <form action="/profile" method="POST">
                    <div class="mb-3">
                        <label for="fioInput" class="form-label">Ваше ФИО:</label>
                        <input type="text" name="fio" class="form-control" id="fioInput" value="$fio">
                    </div>
                    <div class="mb-3">
                        <label for="emailInput" class="form-label">Емайл:</label>
                        <input type="email" name="email" class="form-control" id="emailInput" disabled value="$email">
                    </div>
                    <div class="mb-3">
                        <label for="addressInput" class="form-label">Адрес доставки:</label>
                        <input type="text" name="address" class="form-control" id="addressInput" value="$address">
                    </div>
                    <div class="mb-3">
                        <label for="phoneInput" class="form-label">Телефон:</label>
                        <input type="text" name="phone" class="form-control" id="phoneInput" value="$phone">
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Обновить</button>
                </form>
        FORMA;
        return $html;
    }

    /*
        Формирование страница "История заказов"
    */
    public static function getHistoryTemplate(?array $data): string {
        $title= 'История  заказов';
        $content = <<<CORUSEL
        <main class="row p-5 justify-content-center align-items-center">
            <div class="col-8 bg-light border">
                <h3 class="mb-5">История заказов</h3>
        CORUSEL;
        $content .= <<<TABLE
            <table class="table table-striped">
            <tr>    
                <th>Номер заказа</th>
                <th>Дата</th>
                <th>Сумма</th>
            </tr>
        TABLE;
        
        $sum =0;
        if (isset($data)) {
            foreach($data as $row) {
                $orderDate = date("d-m-Y H:s", strtotime($row['created']));
                $content .= <<<TABLE
                <tr>    
                    <td>Заказ #{$row['id']}</td>
                    <td>{$orderDate}</td>
                    <td>{$row['all_sum']} ₽</td>
                </tr>
                TABLE;
                $sum += $row['all_sum'];
            }
        }
        $content .= '</table>';
        $content .= "<div>Всего заказов на сумму: <strong>{$sum} ₽</strong></div>";
        $content .= "</div></main>";

        $resultTemplate =  sprintf($title, $content);
        return parent::getTemplate($resultTemplate);
    }
}