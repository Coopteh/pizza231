<?php
namespace Views;
// 🌐 LANG: Добавлен импорт Language
use Lib\Language;

class OrderTemplate extends BaseTemplate
{
    public static function getCheckoutTemplate(array $cartItems): string
    {
        $template = parent::getTemplate();
        // 🌐 LANG: Заголовок с переводом
        $title = Language::get('checkout_title') . ' - ' . Language::get('site_name');
        
        $errors = $_SESSION['order_errors'] ?? [];
        $data = $_SESSION['order_data'] ?? [];
        unset($_SESSION['order_errors']);
        unset($_SESSION['order_data']);
        
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
            $totalPrice += $priceNum;
        }
        
        $customStyles = '
<style>
.checkout-container {
background: #fff;
border-radius: 8px;
padding: 2.5rem;
box-shadow: 0 2px 8px rgba(0,0,0,0.08);
border: 1px solid #e2e8f0;
}
.checkout-header {
text-align: center;
margin-bottom: 2rem;
}
.order-summary {
background: #f7fafc;
border-radius: 8px;
padding: 1.5rem;
margin-bottom: 2rem;
border: 1px solid #e2e8f0;
}
.order-item {
display: flex;
justify-content: space-between;
padding: 0.75rem 0;
border-bottom: 1px solid #e2e8f0;
}
.order-item:last-child {
border-bottom: none;
}
.form-section {
background: #f7fafc;
border-radius: 8px;
padding: 1.5rem;
margin-bottom: 1.5rem;
border: 1px solid #e2e8f0;
}
.form-section-title {
font-size: 1.1rem;
font-weight: 600;
color: #2d3748;
margin-bottom: 1rem;
}
.form-control-lg {
border-radius: 6px;
border: 1px solid #e2e8f0;
padding: 0.75rem 1rem;
}
.form-control-lg:focus {
border-color: #2c5282;
box-shadow: 0 0 0 3px rgba(44,82,130,0.1);
}
.payment-option {
border: 1px solid #e2e8f0;
border-radius: 6px;
padding: 1rem;
margin-bottom: 0.75rem;
cursor: pointer;
transition: all 0.3s ease;
}
.payment-option:hover {
border-color: #2c5282;
background: #ebf4ff;
}
.payment-option.selected {
border-color: #2c5282;
background: #ebf4ff;
}
.btn-checkout-submit {
background: #2c5282;
border: none;
padding: 1rem 3rem;
font-size: 1.1rem;
font-weight: 600;
border-radius: 6px;
color: white;
width: 100%;
}
.btn-checkout-submit:hover {
background: #1a365d;
color: white;
}
.error-message {
background: #fed7d7;
color: #742a2a;
padding: 0.75rem 1rem;
border-radius: 6px;
margin-bottom: 1rem;
border: 1px solid #feb2b2;
}
.security-badge {
text-align: center;
margin-top: 1.5rem;
padding-top: 1.5rem;
border-top: 1px solid #e2e8f0;
color: #718096;
font-size: 0.9rem;
}
</style>';
        
        $orderItemsHtml = '';
        foreach ($cartItems as $item) {
            $orderItemsHtml .= '
<div class="order-item">
<span>' . htmlspecialchars($item['title']) . '</span>
<span class="fw-bold">' . htmlspecialchars($item['price']) . '</span>
</div>';
        }
        
        $errorHtml = '';
        if (!empty($errors)) {
            $errorMessages = [];
            // 🌐 LANG: Сообщения об ошибках с переводом
            if (in_array('name', $errors)) $errorMessages[] = Language::get('checkout_error_name');
            if (in_array('email', $errors)) $errorMessages[] = Language::get('checkout_error_email');
            if (in_array('phone', $errors)) $errorMessages[] = Language::get('checkout_error_phone');
            $errorHtml = '<div class="error-message">' . implode(', ', $errorMessages) . '</div>';
        }
        
        $content = $customStyles . '
<section class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="checkout-container">
<div class="checkout-header">
<!-- 🌐 LANG: Заголовки с переводом -->
<h2 class="display-6 fw-bold mb-2">' . Language::get('checkout_title') . '</h2>
<p class="text-muted">' . Language::get('checkout_subtitle') . '</p>
</div>
' . $errorHtml . '
<div class="order-summary">
<h4 class="fw-bold mb-3">' . Language::get('checkout_your_order') . '</h4>
' . $orderItemsHtml . '
<div class="order-item" style="border-top: 2px solid #2c5282; padding-top: 1rem; margin-top: 0.5rem;">
<span class="fw-bold">' . Language::get('cart_total') . ':</span>
<span class="fw-bold" style="color: #2c5282; font-size: 1.3rem;">' . number_format($totalPrice, 0, '.', ' ') . ' ₽</span>
</div>
</div>
<form method="POST" action="/cart/order" id="orderForm">
<div class="form-section">
<div class="form-section-title">' . Language::get('checkout_contact_info') . '</div>
<div class="mb-3">
<label class="form-label">' . Language::get('checkout_name') . ' *</label>
<input type="text" name="name" class="form-control form-control-lg"
value="' . htmlspecialchars($data['name'] ?? '') . '" required placeholder="Иванов Иван Иванович">
</div>
<div class="mb-3">
<label class="form-label">' . Language::get('checkout_email') . ' *</label>
<input type="email" name="email" class="form-control form-control-lg"
value="' . htmlspecialchars($data['email'] ?? '') . '" required placeholder="example@mail.ru">
</div>
<div class="mb-3">
<label class="form-label">' . Language::get('checkout_phone') . ' *</label>
<input type="tel" name="phone" class="form-control form-control-lg"
value="' . htmlspecialchars($data['phone'] ?? '') . '" required placeholder="+7 (999) 000-00-00">
</div>
</div>
<div class="form-section">
<div class="form-section-title">' . Language::get('checkout_payment_method') . '</div>
<div class="payment-option" onclick="selectPayment(this)">
<label>
<input type="radio" name="payment_method" value="card" checked>
<strong>' . Language::get('checkout_payment_card') . '</strong>
<p class="text-muted small mb-0">Visa, MasterCard, МИР</p>
</label>
</div>
<div class="payment-option" onclick="selectPayment(this)">
<label>
<input type="radio" name="payment_method" value="sbp">
<strong>' . Language::get('checkout_payment_sbp') . '</strong>
<p class="text-muted small mb-0">' . Language::get('checkout_payment_sbp') . '</p>
</label>
</div>
<div class="payment-option" onclick="selectPayment(this)">
<label>
<input type="radio" name="payment_method" value="invoice">
<strong>' . Language::get('checkout_payment_invoice') . '</strong>
<p class="text-muted small mb-0">' . Language::get('checkout_payment_invoice') . '</p>
</label>
</div>
</div>
<div class="form-section">
<div class="form-section-title">' . Language::get('checkout_comment') . '</div>
<textarea name="comment" class="form-control form-control-lg" rows="3"
placeholder="' . Language::get('checkout_comment_placeholder') . '">' . htmlspecialchars($data['comment'] ?? '') . '</textarea>
</div>
<button type="submit" class="btn-checkout-submit">
' . Language::get('checkout_submit') . '
</button>
<div class="security-badge">
' . Language::get('checkout_security') . '
</div>
</form>
</div>
</div>
</div>
</section>
<script>
function selectPayment(element) {
document.querySelectorAll(".payment-option").forEach(el => el.classList.remove("selected"));
element.classList.add("selected");
element.querySelector("input[type=\'radio\']").checked = true;
}
document.querySelector("input[name=\'phone\']").addEventListener("input", function(e) {
let x = e.target.value.replace(/\D/g, "").match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
if (!x[2]) {
e.target.value = x[1] ? "+7" : "";
} else {
e.target.value = !x[3] ? "+7 (" + x[2] : "+7 (" + x[2] + ") " + x[3] + (x[4] ? "-" + x[4] : "") + (x[5] ? "-" + x[5] : "");
}
});
</script>';
        
        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
    
    public static function getSuccessTemplate(string $orderId): string
    {
        $template = parent::getTemplate();
        // 🌐 LANG: Заголовок с переводом
        $title = Language::get('order_success_title') . ' - ' . Language::get('site_name');
        
        $customStyles = '
<style>
.success-container {
text-align: center;
padding: 4rem 2rem;
background: #fff;
border-radius: 8px;
box-shadow: 0 2px 8px rgba(0,0,0,0.08);
border: 1px solid #e2e8f0;
}
.success-icon {
font-size: 4rem;
margin-bottom: 1rem;
color: #38a169;
}
.order-number {
background: #2c5282;
color: white;
padding: 1rem 2rem;
border-radius: 6px;
display: inline-block;
font-size: 1.3rem;
font-weight: 600;
margin: 1.5rem 0;
}
.success-steps {
text-align: left;
background: #f7fafc;
border-radius: 8px;
padding: 2rem;
margin: 2rem 0;
border: 1px solid #e2e8f0;
}
.step {
display: flex;
align-items: flex-start;
margin-bottom: 1rem;
}
.step-number {
background: #2c5282;
color: white;
width: 30px;
height: 30px;
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
font-weight: 600;
margin-right: 1rem;
flex-shrink: 0;
}
</style>';
        
        $content = $customStyles . '
<section class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="success-container">
<div class="success-icon">✓</div>
<!-- 🌐 LANG: Тексты с переводом -->
<h2 class="display-5 fw-bold mb-3">' . Language::get('order_success_title') . '</h2>
<p class="lead text-muted">' . Language::get('order_success_message') . '</p>
<div class="order-number">' . Language::get('order_number') . ' ' . htmlspecialchars($orderId) . '</div>
<div class="success-steps">
<h4 class="fw-bold mb-3">' . Language::get('order_next_steps') . '</h4>
<div class="step">
<div class="step-number">1</div>
<div>
<strong>' . Language::get('order_step1_title') . '</strong>
<p class="text-muted small mb-0">' . Language::get('order_step1_desc') . '</p>
</div>
</div>
<div class="step">
<div class="step-number">2</div>
<div>
<strong>' . Language::get('order_step2_title') . '</strong>
<p class="text-muted small mb-0">' . Language::get('order_step2_desc') . '</p>
</div>
</div>
<div class="step">
<div class="step-number">3</div>
<div>
<strong>' . Language::get('order_step3_title') . '</strong>
<p class="text-muted small mb-0">' . Language::get('order_step3_desc') . '</p>
</div>
</div>
</div>
<div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
<a href="/courses" class="btn btn-primary btn-lg px-4">' . Language::get('order_continue_shopping') . '</a>
<a href="mailto:info@kemt.ru" class="btn btn-outline-primary btn-lg px-4">' . Language::get('order_contact_us') . '</a>
</div>
</div>
</div>
</div>
</section>';
        
        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}