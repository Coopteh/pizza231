<?php
namespace App\Views;

class AboutTemplate extends BaseTemplate {
    public static function getTemplate($pageTitle, $pageContent) {
        return <<<HTML
        <section>
        <div style="display: flex; justify-content: center;" width="100%">
            <h1>СТРАНИЦА В РАБОТЕ. НЕ СМОТРЕТЬ!</h1>
        </div>
        <div style="display: flex; justify-content: center;" width="100%">
            <img src="../../assets/img/keep_out.png" class="d-block w-100 h-150" alt="...">
        </div>
        </section>
        HTML;
    }
}