<?php
namespace Controllers;

class ErrorController
{
    public function get(): string
    {
        return '
<div class="error-page text-center py-5">
    <div class="error-icon">😕</div>
    <h1 class="display-1 fw-bold">404</h1>
    <h2 class="mb-3">Страница не найдена</h2>
    <p class="lead text-muted mb-4">К сожалению, запрашиваемая страница не существует.</p>
    <a href="/" class="btn btn-primary btn-lg rounded-pill px-4">🏠 На главную</a>
</div>';
    }
}