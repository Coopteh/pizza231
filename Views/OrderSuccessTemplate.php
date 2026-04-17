<?php
namespace Views;

class OrderSuccessTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Заказ оформлен';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $orderId = $_SESSION['last_order_id'] ?? 'N/A';
        unset($_SESSION['last_order_id']);

        $content = '
<section class="container py-5">
    <div class="text-center" style="background: white; border-radius: 30px; padding: 4rem; max-width: 600px; margin: 0 auto;">
        <div style="font-size: 5rem; margin-bottom: 1rem;">✅</div>
        <h1 class="mb-3">Заказ успешно оформлен!</h1>
        <p class="lead text-muted">Номер заказа: <strong>' . htmlspecialchars($orderId) . '</strong></p>
        <p class="text-muted">Менеджер свяжется с вами в течение 15 минут.</p>
        <a href="/home" class="btn btn-primary btn-lg rounded-pill mt-3">🏠 На главную</a>
        <a href="/admin/orders" class="btn btn-outline-secondary btn-lg rounded-pill mt-2">🔐 Админ-панель</a>
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}