<?php
namespace App\Views;

class RegisterTemplate extends BaseTemplate {
    public static function getRegisterTemplate(): string {
        $template = parent::getTemplate();
        $title= 'Регистрация пользователя';
        $content = "<main class='row'>
            <h1 class='mt-3'>$title</h1>";
        $content .= self::formRegister();

        $resultTemplate = sprintf($template, $title, $content);
        return $resultTemplate;
    }

    public static function formRegister(){
        $form = <<<ORDERFORM
        <div class="col-8">
            <form action="/register" method="POST">
                <div class="mb-3">
                    <label for="unameId" class="form-label">Имя пользователя:</label>
                    <input type="text" name="username" class="form-control" id="unameId" placeholder="Иван Иванов" require>
                </div>
                <div class="mb-3">
                    <label for="emailId" class="form-label">Емайл:</label>
                    <input type="email" name="email" class="form-control" id="emailId" placeholder="ivan@mail.ru" require>
                </div>
                <div class="mb-3">
                    <label for="passId" class="form-label">Пароль:</label>
                    <input type="password" name="password" class="form-control" id="passwordId" require>
                </div>
                <div class="mb-3">
                    <label for="confirm_passwordId" class="form-label">Подтвердите пароль:</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirm_passwordId" require>
                </div>
                <div class="mb-3 float-sm-right">
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                </div>
            </form>
        </div>
        ORDERFORM;
        
        return $form;
    }

    public static function getVerifyTemplate(): string {
        $template = parent::getTemplate();
        $title= 'Подтверждение нового пользователя';
        $content = <<<VERY
        <main class="row p-5 justify-content-center align-items-center">
            <div class="col-5 bg-light border">
                <h3 class="mb-5">Успешное завершение регистрации</h3>
        VERY;
        $content .= "Ваш email успешно подтвержден!<br>
        Теперь вы можете войти на сайт";
        $content .= "</div></main>";

        $resultTemplate =  sprintf($template, $title, $content);
        return $resultTemplate;
    }

}