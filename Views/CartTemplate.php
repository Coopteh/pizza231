<?php
namespace Views;

class CartTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = 'Корзина - Продукты24';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cartItems = $_SESSION['cart'] ?? [];
        $cartCount = count($cartItems);
        $successMessage = '';
        $errorMessage = '';

        if (isset($_GET['success'])) {
            switch ($_GET['success']) {
                case 'added': $successMessage = '✅ Товар добавлен'; break;
                case 'removed': $successMessage = '✅ Товар удалён'; break;
                case 'cleared': $successMessage = '✅ Корзина очищена'; break;
            }
        }

        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'not_found': $errorMessage = '❌ Товар не найден'; break;
                case 'invalid_product': $errorMessage = '❌ Неверный ID'; break;
            }
        }

        $customStyles = '
<style>
.cart-wrapper {
    background: white;
    border-radius: 30px;
    padding: 2.5rem;
    box-shadow: 0 15px 50px rgba(255,107,53,0.15);
    border: 2px solid #ffe5d9;
}
.cart-item-row {
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #fff5f0, #ffffff);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 2px solid #ff6b35;
    transition: all 0.3s ease;
}
.cart-item-row:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 30px rgba(255,107,53,0.2);
}
.item-emoji {
    font-size: 3.5rem;
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 50%;
    margin-right: 1.5rem;
    border: 3px solid #ff6b35;
}
.item-details { flex: 1; }
.item-name {
    font-size: 1.3rem;
    font-weight: 900;
    color: #1a1a2e;
    margin-bottom: 0.3rem;
}
.item-info {
    color: #666;
    font-size: 0.95rem;
}
.item-price {
    font-size: 1.8rem;
    font-weight: 900;
    color: #ff6b35;
    margin-right: 2rem;
}
.btn-remove {
    background: #ff4757;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-remove:hover {
    background: #ff3838;
    transform: scale(1.05);
}
.cart-total-box {
    background: linear-gradient(135deg, #ff6b35, #f7931e);
    border-radius: 25px;
    padding: 2rem;
    color: white;
    margin-top: 2rem;
    text-align: center;
}
.total-amount {
    font-size: 3rem;
    font-weight: 900;
    margin: 1rem 0;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}
.btn-checkout {
    background: white;
    color: #ff6b35;
    border: none;
    padding: 1rem 3rem;
    font-size: 1.3rem;
    font-weight: 900;
    border-radius: 50px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}
.btn-checkout:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    color: #ff6b35;
    text-decoration: none;
}
.empty-cart {
    text-align: center;
    padding: 5rem 2rem;
}
.empty-icon {
    font-size: 6rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}
.alert-custom {
    border-radius: 15px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
}
.alert-success-custom {
    background: #d4edda;
    color: #155724;
    border: 2px solid #28a745;
}
.alert-error-custom {
    background: #f8d7da;
    color: #721c24;
    border: 2px solid #dc3545;
}
</style>';

        $cartItemsHtml = '';
        $totalPrice = 0;

        if ($cartCount > 0) {
            foreach ($cartItems as $item) {
                $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
                $totalPrice += $priceNum;
                $cartItemsHtml .= '
<div class="cart-item-row">
    <div class="item-emoji">' . $item['icon'] . '</div>
    <div class="item-details">
        <div class="item-name">' . htmlspecialchars($item['title']) . '</div>
        <div class="item-info">📦 ' . htmlspecialchars($item['duration']) . '</div>
    </div>
    <div class="item-price">' . htmlspecialchars($item['price']) . '</div>
    <form method="POST" action="/cart/remove" style="display:inline;">
        <input type="hidden" name="courseId" value="' . $item['id'] . '">
        <button type="submit" class="btn-remove">🗑️</button>
    </form>
</div>';
            }

            $cartItemsHtml .= '
<div class="cart-total-box">
    <h3 class="mb-2">📊 Итого к оплате</h3>
    <div class="total-amount">' . number_format($totalPrice, 0, '.', ' ') . ' ₽</div>
    <p class="mb-4">Товаров: ' . $cartCount . ' шт.</p>
    <a href="mailto:info@produkty24.ru?subject=Заказ" class="btn-checkout">
        ✅ Оформить заказ
    </a>
    <p class="small mt-3" style="opacity: 0.9;">Менеджер перезвонит через 10 минут</p>
</div>
<div class="text-center mt-4">
    <form method="POST" action="/cart/clear" style="display:inline;">
        <button type="submit" class="btn btn-outline-danger rounded-pill px-4" onclick="return confirm(\'Очистить корзину?\');">
            🗑️ Очистить всё
        </button>
    </form>
</div>';
        } else {
            $cartItemsHtml = '
<div class="empty-cart">
    <div class="empty-icon">🛒</div>
    <h3 class="fw-bold mb-3">Корзина пуста</h3>
    <p class="text-muted mb-4">Добавьте товары для оформления заказа</p>
    <a href="/products" class="btn btn-primary btn-lg rounded-pill px-5">
        🛍️ В каталог
    </a>
</div>';
        }

        $alertHtml = '';
        if ($successMessage) {
            $alertHtml = '<div class="alert-custom alert-success-custom">' . $successMessage . '</div>';
        }
        if ($errorMessage) {
            $alertHtml = '<div class="alert-custom alert-error-custom">' . $errorMessage . '</div>';
        }

        $content = $customStyles . '
<section class="container py-5">
    <h1 class="display-4 fw-bold text-center mb-4" style="color: #ff6b35;">🛒 Ваша корзина</h1>
    ' . $alertHtml . '
    <div class="cart-wrapper">' . $cartItemsHtml . '</div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}