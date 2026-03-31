<?php
namespace Views;

class OrderTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Оформление заказа';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cartItems = $_SESSION['cart'] ?? [];
        $totalPrice = 0;
        $itemsHtml = '';

        foreach ($cartItems as $item) {
            $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
            $totalPrice += $priceNum;
            $itemsHtml .= '<div class="mb-2">' . htmlspecialchars($item['title']) . ' - ' . number_format($priceNum, 0, '.', ' ') . ' ₽</div>';
        }

        $errorHtml = '';
        if (isset($_GET['error'])) {
            $errorHtml = '<div class="alert alert-danger">Заполните все поля!</div>';
        }

        $content = '
<style>
.checkout-box {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: 0 auto;
}
.btn-submit {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    border: none;
    padding: 1rem;
    font-size: 1.1rem;
    font-weight: 700;
    border-radius: 50px;
    color: white;
    width: 100%;
}
</style>
<section class="container py-5">
    <h1 class="text-center mb-4">📝 Оформление заказа</h1>
    <div class="checkout-box">
        ' . $errorHtml . '
        <form method="POST" action="/order/submit">
            <div class="mb-3">
                <label class="form-label">ФИО</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Телефон</label>
                <input type="tel" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="p-3 bg-light rounded mb-3">
                <strong>Товары:</strong>
                ' . $itemsHtml . '
                <hr>
                <strong>Итого: ' . number_format($totalPrice, 0, '.', ' ') . ' ₽</strong>
            </div>
            <button type="submit" class="btn-submit">✅ Подтвердить заказ</button>
        </form>
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}