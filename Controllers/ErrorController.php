<?php

namespace Controllers;

class ErrorController
{
    public function get(): string
    {
        return '
        <div class="container text-center my-5">
            <h1 class="display-1 text-danger">404</h1>
            <h2>Страница не найдена</h2>
            <p class="lead">К сожалению, запрашиваемая страница не существует.</p>
            <a href="/" class="btn btn-primary mt-3">Вернуться на главную</a>
        </div>
        ';
    }
}
