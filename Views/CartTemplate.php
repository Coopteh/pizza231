<?php
namespace Views;
// 🌐 LANG: Добавлен импорт Language
use Lib\Language;

class CartTemplate extends BaseTemplate
{
    public static function getTemplate(): string
    {
        $template = parent::getTemplate();
        // 🌐 LANG: Заголовок с переводом
        $title = Language::get('cart_title') . ' - ' . Language::get('site_name');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $cartItems = $_SESSION['cart'] ?? [];
        $cartCount = count($cartItems);
        $successMessage = '';
        $errorMessage = '';
        
        // 🌐 LANG: Сообщения об успехе с переводом
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
        
        // 🌐 LANG: Сообщения об ошибках с переводом
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
        
        $customStyles = '
<style>
.cart-container {
background: #fff;
border-radius: 8px;
padding: 2rem;
box-shadow: 0 2px 8px rgba(0,0,0,0.08);
border: 1px solid #e2e8f0;
}
.cart-item {
display: flex;
align-items: center;
padding: 1.5rem;
border: 1px solid #e2e8f0;
border-radius: 8px;
margin-bottom: 1rem;
background: #f7fafc;
}
.cart-item-icon {
font-size: 1.5rem;
font-weight: 700;
width: 60px;
height: 60px;
display: flex;
align-items: center;
justify-content: center;
background: #ebf4ff;
border-radius: 8px;
margin-right: 1.5rem;
color: #2c5282;
}
.cart-item-info {
flex: 1;
}
.cart-item-title {
font-size: 1.1rem;
font-weight: 600;
color: #2d3748;
margin-bottom: 0.5rem;
}
.cart-item-duration {
color: #718096;
font-size: 0.9rem;
}
.cart-item-price {
font-size: 1.3rem;
font-weight: 700;
color: #2c5282;
margin-right: 2rem;
}
.cart-item-remove {
background: #e53e3e;
color: white;
border: none;
padding: 0.5rem 1rem;
border-radius: 6px;
cursor: pointer;
transition: all 0.3s ease;
font-size: 0.9rem;
}
.cart-item-remove:hover {
background: #c53030;
}
.cart-summary {
background: #2c5282;
border-radius: 8px;
padding: 2rem;
color: white;
margin-top: 2rem;
}
.cart-total {
font-size: 2rem;
font-weight: 700;
margin: 1rem 0;
}
.btn-checkout {
background: #fff;
color: #2c5282;
border: none;
padding: 1rem 3rem;
font-size: 1.1rem;
font-weight: 600;
border-radius: 6px;
text-decoration: none;
display: inline-block;
transition: all 0.3s ease;
}
.btn-checkout:hover {
background: #ebf4ff;
color: #2c5282;
text-decoration: none;
}
.cart-empty {
text-align: center;
padding: 4rem 2rem;
}
.alert-success {
background: #c6f6d5;
color: #22543d;
padding: 1rem;
border-radius: 6px;
margin-bottom: 1.5rem;
border: 1px solid #9ae6b4;
}
.alert-error {
background: #fed7d7;
color: #742a2a;
padding: 1rem;
border-radius: 6px;
margin-bottom: 1.5rem;
border: 1px solid #feb2b2;
}
</style>';
        
        $cartItemsHtml = '';
        $totalPrice = 0;
        
        if ($cartCount > 0) {
            foreach ($cartItems as $item) {
                $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
                $totalPrice += $priceNum;
                $cartItemsHtml .= '
<div class="cart-item">
<div class="cart-item-icon">' . strtoupper(substr($item['title'], 0, 2)) . '</div>
<div class="cart-item-info">
<div class="cart-item-title">' . htmlspecialchars($item['title']) . '</div>
<div class="cart-item-duration">' . htmlspecialchars($item['duration']) . '</div>
</div>
<div class="cart-item-price">' . htmlspecialchars($item['price']) . '</div>
<form method="POST" action="/cart/remove" style="display:inline;">
<input type="hidden" name="courseId" value="' . $item['id'] . '">
<button type="submit" class="cart-item-remove">' . Language::get('cart_remove') . '</button>
</form>
</div>';
            }
            
            $confirmClear = Language::get('confirm_clear_cart');
            
            $cartItemsHtml .= '
<div class="cart-summary">
<h3 class="mb-3">' . Language::get('cart_total') . '</h3>
<div class="d-flex justify-content-between align-items-center">
<span>' . Language::get('cart_items_count') . '</span>
<span class="fw-bold">' . $cartCount . ' ' . ($cartCount == 1 ? 'шт.' : 'шт.') . '</span>
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
<h3 class="fw-bold mb-3">' . Language::get('cart_empty') . '</h3>
<p class="text-muted mb-4">' . Language::get('cart_empty_subtitle') . '</p>
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
<!-- 🌐 LANG: Заголовок с переводом -->
<h1 class="display-5 fw-bold text-center mb-4">' . Language::get('cart_title') . '</h1>
' . $alertHtml . '
<div class="cart-container">
' . $cartItemsHtml . '
</div>
</section>';
        
        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}