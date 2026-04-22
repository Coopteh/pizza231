<?php

namespace Views;

use Lib\Language;

class CartTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        $title = Language::get('cart_title') . ' - ' . Language::get('site_name');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cartItems = $_SESSION['cart'] ?? [];
        $cartCount = count($cartItems);
        $lang = Language::getCurrentLang();

        // Вспомогательная функция для получения текста на нужном языке
        $getText = function ($field, $default = '') use ($lang) {
            if (is_array($field)) {
                return $field[$lang] ?? $field['ru'] ?? $default;
            }
            return $field;
        };

        $successMessage = '';
        $errorMessage = '';
        if (isset($_GET['success'])) {
            switch ($_GET['success']) {
                case 'added':
                    $successMessage = Language::get('cart_success_added');
                    break;
                case 'removed':
                    $successMessage = Language::get('cart_success_removed');
                    break;
                case 'cleared':
                    $successMessage = Language::get('cart_success_cleared');
                    break;
            }
        }
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'not_found':
                    $errorMessage = Language::get('cart_error_not_found');
                    break;
                case 'invalid_course':
                    $errorMessage = Language::get('cart_error_invalid');
                    break;
            }
        }

        $customStyles = '<style>
.cart-container {
background: var(--surface);
border-radius: 8px;
padding: 2rem;
box-shadow: var(--shadow);
border: 1px solid var(--border);
transition: all 0.3s ease;
}
.cart-item {
display: flex;
align-items: center;
padding: 1.5rem;
border: 1px solid var(--border);
border-radius: 8px;
margin-bottom: 1rem;
background: var(--surface-hover);
transition: all 0.3s ease;
flex-wrap: wrap;
gap: 1rem;
}
.cart-item-icon {
font-size: 1.5rem;
font-weight: 700;
width: 60px;
height: 60px;
display: flex;
align-items: center;
justify-content: center;
background: var(--badge-bg);
border-radius: 8px;
margin-right: 1.5rem;
color: var(--primary);
transition: all 0.3s ease;
flex-shrink: 0;
}
.cart-item-info {
flex: 1;
min-width: 200px;
}
.cart-item-title {
font-size: 1.1rem;
font-weight: 600;
color: var(--text);
margin-bottom: 0.25rem;
transition: color 0.3s ease;
word-wrap: break-word;
}
.cart-item-duration {
color: var(--text-muted);
font-size: 0.9rem;
transition: color 0.3s ease;
}
.cart-item-price {
font-size: 1.3rem;
font-weight: 700;
color: var(--primary);
margin-right: 2rem;
white-space: nowrap;
transition: color 0.3s ease;
}
.cart-item-remove {
background: var(--danger);
color: white;
border: none;
padding: 0.5rem 1rem;
border-radius: 6px;
cursor: pointer;
transition: all 0.3s ease;
font-size: 0.9rem;
min-height: 44px;
min-width: 44px;
display: inline-flex;
align-items: center;
justify-content: center;
}
.cart-item-remove:hover {
background: #c53030;
}
.cart-summary {
background: var(--primary);
border-radius: 8px;
padding: 2rem;
color: white;
margin-top: 2rem;
transition: background 0.3s ease;
}
.cart-total {
font-size: 2rem;
font-weight: 700;
margin: 1rem 0;
}
.btn-checkout {
background: #fff;
color: var(--primary);
border: none;
padding: 1rem 3rem;
font-size: 1.1rem;
font-weight: 600;
border-radius: 6px;
text-decoration: none;
display: inline-block;
transition: all 0.3s ease;
min-height: 48px;
display: inline-flex;
align-items: center;
justify-content: center;
}
.btn-checkout:hover {
background: var(--badge-bg);
color: var(--primary);
text-decoration: none;
}
.cart-empty {
text-align: center;
padding: 4rem 2rem;
}
.alert-success {
background: rgba(56, 161, 105, 0.15);
color: var(--success);
padding: 1rem;
border-radius: 6px;
margin-bottom: 1.5rem;
border: 1px solid var(--success);
}
.alert-error {
background: rgba(229, 62, 62, 0.15);
color: var(--danger);
padding: 1rem;
border-radius: 6px;
margin-bottom: 1.5rem;
border: 1px solid var(--danger);
}
@media (max-width: 768px) {
.cart-item {
flex-direction: column;
align-items: stretch;
text-align: center;
}
.cart-item-icon {
margin-right: 0;
margin-bottom: 0.5rem;
}
.cart-item-price {
margin-right: 0;
margin-top: 0.5rem;
text-align: center;
}
.cart-item-remove {
width: 100%;
margin-top: 0.5rem;
}
}
@media (max-width: 576px) {
.cart-container {
padding: 1.5rem;
}
.cart-item {
padding: 1.25rem;
}
.cart-item-title {
font-size: 1rem;
}
.cart-item-price {
font-size: 1.1rem;
}
.cart-total {
font-size: 1.5rem;
}
.btn-checkout {
width: 100%;
padding: 0.875rem 1.5rem;
}
.cart-empty {
padding: 2rem 1rem;
}
}
</style>';

        $cartItemsHtml = '';
        $totalPrice = 0;

        if ($cartCount > 0) {
            foreach ($cartItems as $item) {
                $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
                $totalPrice += $priceNum;

                // Получаем название курса на нужном языке
                $courseTitle = $getText($item['title']);
                $courseDuration = $getText($item['duration']);

                $cartItemsHtml .= '
<div class="cart-item">
    <div class="cart-item-icon">' . strtoupper(substr($courseTitle, 0, 2)) . '</div>
    <div class="cart-item-info">
        <div class="cart-item-title">' . htmlspecialchars($courseTitle) . '</div>
        <div class="cart-item-duration">' . htmlspecialchars($courseDuration) . '</div>
    </div>
    <div class="cart-item-price">' . htmlspecialchars($item['price']) . '</div>
    <form method="POST" action="/cart/remove" style="display:inline;">
        <input type="hidden" name="courseId" value="' . $item['id'] . '">
        <button type="submit" class="cart-item-remove">' . Language::get('cart_remove') . '</button>
    </form>
</div>';
            }

            $confirmClear = addslashes(Language::get('confirm_clear_cart'));
            $cartItemsHtml .= '
<div class="cart-summary">
    <h3 class="mb-3">' . Language::get('cart_total') . '</h3>
    <div class="d-flex justify-content-between align-items-center">
        <span>' . Language::get('cart_items_count') . '</span>
        <span class="fw-bold">' . $cartCount . ' шт.</span>
    </div>
    <div class="cart-total">' . number_format($totalPrice, 0, '.', ' ') . ' ₽</div>
    <div class="text-center mt-4">
        <a href="/cart/checkout" class="btn-checkout">
            ' . Language::get('cart_checkout') . '
        </a>
        <p class="small mt-3 opacity-75">' . Language::get('cart_contact_message') . '</p>
    </div>
</div>
<div class="text-center mt-4">
    <form method="POST" action="/cart/clear" style="display:inline;">
        <button type="submit" class="btn btn-outline-danger" onclick="return confirm(\'' . $confirmClear . '\');">
            ' . Language::get('cart_clear') . '
        </button>
    </form>
</div>';
        } else {
            $cartItemsHtml = '
<div class="cart-empty">
    <h3 class="fw-bold mb-3" style="color: var(--text);">' . Language::get('cart_empty') . '</h3>
    <p class="mb-4" style="color: var(--text-muted);">' . Language::get('cart_empty_subtitle') . '</p>
    <a href="/courses" class="btn btn-primary btn-lg px-5">
        ' . Language::get('cart_go_courses') . '
    </a>
</div>';
        }

        $alertHtml = '';
        if ($successMessage) {
            $alertHtml = '<div class="alert-success">' . $successMessage . '</div>';
        }
        if ($errorMessage) {
            $alertHtml = '<div class="alert-error">' . $errorMessage . '</div>';
        }

        $content = $customStyles . '
<section class="container py-5">
    <h1 class="display-5 fw-bold text-center mb-4" style="color: var(--text);">' . Language::get('cart_title') . '</h1>
    ' . $alertHtml . '
    <div class="cart-container">
        ' . $cartItemsHtml . '
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}
