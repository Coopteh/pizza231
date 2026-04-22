<?php
namespace Views;
use Lib\Language;
class OrderTemplate extends BaseTemplate
{
    public static function getCheckoutTemplate(array $cartItems): string
    {
        $template = parent::getTemplate();
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
        .checkout-container { background: var(--surface); border-radius: 8px; padding: 2.5rem; box-shadow: var(--shadow); border: 1px solid var(--border); transition: all 0.3s ease; }
        .checkout-header { text-align: center; margin-bottom: 2rem; }
        .checkout-header h2 { color: var(--text) !important; }
        .checkout-header p { color: var(--text-muted) !important; }
        .order-summary { background: var(--surface-hover); border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--border); transition: all 0.3s ease; }
        .order-item { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--border); transition: border-color 0.3s ease; color: var(--text) !important; }
        .order-item:last-child { border-bottom: none; }
        .form-section { background: var(--surface-hover); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid var(--border); transition: all 0.3s ease; }
        .form-section-title { font-size: 1.1rem; font-weight: 600; color: var(--text) !important; margin-bottom: 1rem; }
        .form-label { color: var(--text) !important; font-weight: 500; margin-bottom: 0.5rem; display: block; }
        .form-control-lg { border-radius: 6px; border: 1px solid var(--border); padding: 0.75rem 1rem; background: var(--surface); color: var(--text) !important; transition: all 0.3s ease; width: 100%; }
        .form-control-lg:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2); background: var(--surface); color: var(--text) !important; outline: none; }
        .payment-option { border: 1px solid var(--border); border-radius: 6px; padding: 1rem; margin-bottom: 0.75rem; cursor: pointer; transition: all 0.3s ease; background: var(--surface); }
        .payment-option:hover { border-color: var(--primary); background: var(--badge-bg); }
        .payment-option.selected { border-color: var(--primary); background: var(--badge-bg); }
        .payment-option label { color: var(--text) !important; font-weight: 500; margin: 0; cursor: pointer; display: block; }
        .btn-checkout-submit { background: var(--primary); border: none; padding: 1rem 3rem; font-size: 1.1rem; font-weight: 600; border-radius: 6px; color: white !important; width: 100%; transition: background 0.3s ease; min-height: 48px; cursor: pointer; }
        .btn-checkout-submit:hover { background: var(--primary-dark); color: white !important; }
        .error-message { background: rgba(229, 62, 62, 0.15); color: var(--danger); padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; border: 1px solid var(--danger); }
        .security-badge { text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); color: var(--text-muted); font-size: 0.9rem; }
        [data-theme="dark"] .checkout-container { background: #2d3748; border-color: #4a5568; }
        [data-theme="dark"] .order-summary { background: #4a5568; border-color: #4a5568; }
        [data-theme="dark"] .form-section { background: #4a5568; border-color: #4a5568; }
        @media (max-width: 768px) { .checkout-container { padding: 1.5rem; } }
        </style>';

        $orderItemsHtml = '';
        foreach ($cartItems as $item) {
            $title = is_array($item['title']) ? '' : (string)$item['title'];
            $price = is_array($item['price']) ? '' : (string)$item['price'];
            $orderItemsHtml .= '<div class="order-item"><span>' . htmlspecialchars($title) . '</span><span class="fw-bold">' . htmlspecialchars($price) . '</span></div>';
        }

        // 🟢 ИСПРАВЛЕНИЕ: Прямой вывод сообщений от валидатора
        $errorHtml = '';
        if (!empty($errors)) {
            $errorHtml = '<div class="error-message">' . implode('<br>', $errors) . '</div>';
        }

        $nameValue = isset($data['name']) && !is_array($data['name']) ? htmlspecialchars((string)$data['name']) : '';
        $emailValue = isset($data['email']) && !is_array($data['email']) ? htmlspecialchars((string)$data['email']) : '';
        $phoneValue = isset($data['phone']) && !is_array($data['phone']) ? htmlspecialchars((string)$data['phone']) : '';
        $commentValue = isset($data['comment']) && !is_array($data['comment']) ? htmlspecialchars((string)$data['comment']) : '';

        $content = $customStyles . '
        <section class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="checkout-container">
                        <div class="checkout-header">
                            <h2 class="display-6 fw-bold mb-2">' . Language::get('checkout_title') . '</h2>
                            <p class="mb-0">' . Language::get('checkout_subtitle') . '</p>
                        </div>
                        ' . $errorHtml . '
                        <div class="order-summary">
                            <h4 class="fw-bold mb-3">' . Language::get('checkout_your_order') . '</h4>
                            ' . $orderItemsHtml . '
                            <div class="order-item" style="border-top: 2px solid var(--primary); padding-top: 1rem; margin-top: 0.5rem;">
                                <span class="fw-bold">' . Language::get('cart_total') . ':</span>
                                <span class="fw-bold" style="color: var(--primary); font-size: 1.3rem;">' . number_format($totalPrice, 0, '.', ' ') . ' ₽</span>
                            </div>
                        </div>
                        <form method="POST" action="/cart/order" id="orderForm">
                            <div class="form-section">
                                <div class="form-section-title">' . Language::get('checkout_contact_info') . '</div>
                                <div class="mb-3">
                                    <label class="form-label">' . Language::get('checkout_name') . ' *</label>
                                    <input type="text" name="name" class="form-control form-control-lg" value="' . $nameValue . '" required placeholder="Иванов Иван Иванович">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">' . Language::get('checkout_email') . ' *</label>
                                    <input type="email" name="email" class="form-control form-control-lg" value="' . $emailValue . '" required placeholder="example@mail.ru">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">' . Language::get('checkout_phone') . ' *</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg" value="' . $phoneValue . '" required placeholder="+7 (999) 000-00-00">
                                </div>
                            </div>
                            <div class="form-section">
                                <div class="form-section-title">' . Language::get('checkout_payment_method') . '</div>
                                <div class="payment-option" onclick="selectPayment(this)">
                                    <label><input type="radio" name="payment_method" value="card" checked><strong>' . Language::get('checkout_payment_card') . '</strong></label>
                                </div>
                                <div class="payment-option" onclick="selectPayment(this)">
                                    <label><input type="radio" name="payment_method" value="sbp"><strong>' . Language::get('checkout_payment_sbp') . '</strong></label>
                                </div>
                            </div>
                            <button type="submit" class="btn-checkout-submit">' . Language::get('checkout_submit') . '</button>
                            <div class="security-badge">' . Language::get('checkout_security') . '</div>
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
            if (!x[2]) { e.target.value = x[1] ? "+7" : ""; }
            else { e.target.value = !x[3] ? "+7 (" + x[2] : "+7 (" + x[2] + ") " + x[3] + (x[4] ? "-" + x[4] : "") + (x[5] ? "-" + x[5] : ""); }
        });
        </script>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    // ... (остальные методы класса остаются без изменений) ...
    public static function getSuccessTemplate(string $orderId): string {
        return (require __DIR__ . '/OrderTemplate.php')['getSuccessTemplate'] ?? '';
    }
}