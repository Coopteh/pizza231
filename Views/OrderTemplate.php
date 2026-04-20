<?php
namespace Views;

class OrderTemplate extends BaseTemplate
{
    public static function render(array $basketItems = [], float $total = 0): string
    {
        $template = parent::getTemplate();
        $title = 'Оформление карты — Фитнес-клуб «gym low cortisol»';
        $flashHtml = '';
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!empty($_SESSION['flash'])) {
            $flashMessage = $_SESSION['flash'];
            $flashType = $_SESSION['flash_type'] ?? 'success';
            unset($_SESSION['flash'], $_SESSION['flash_type']);
            $alertClass = $flashType === 'error' ? 'alert-danger' : 'alert-success';
            $flashHtml = '<div class="alert ' . $alertClass . ' alert-dismissible fade show mb-4" role="alert">' . $flashMessage . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }

        $customStyles = '
        <style>
        .order-form { max-width: 600px; margin: 0 auto; background: #fff; padding: 2.5rem; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .order-form h2 { text-align: center; margin-bottom: 2rem; color: #1e293b; font-weight: 700; }
        .form-summary { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); padding: 1.5rem; border-radius: 16px; margin-bottom: 2rem; border-left: 4px solid #8b5cf6; }
        .form-summary .summary-item { display: flex; justify-content: space-between; margin: 0.5rem 0; font-size: 1rem; }
        .form-summary .total { font-weight: 700; color: #8b5cf6; font-size: 1.25rem; border-top: 2px dashed #8b5cf6; padding-top: 1rem; margin-top: 1rem; }
        .form-control-custom { border-radius: 12px; border: 2px solid #e2e8f0; padding: 0.9rem 1.2rem; transition: all 0.25s ease; width: 100%; box-sizing: border-box; font-size: 1rem; }
        .form-control-custom:focus { border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(13,110,253,0.12); outline: none; }
        .btn-submit { background: linear-gradient(135deg, #8b5cf6 0%, #0dcaf0 100%); border: none; padding: 18px; border-radius: 50px; color: #fff; font-weight: 600; width: 100%; font-size: 1.15rem; transition: all 0.3s ease; cursor: pointer; }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(13,110,253,0.35); }
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .delivery-options { display: flex; gap: 1rem; margin: 1rem 0; }
        .delivery-option { flex: 1; text-align: center; padding: 1.2rem 1rem; border: 2px solid #e2e8f0; border-radius: 16px; cursor: pointer; transition: all 0.25s ease; user-select: none; font-weight: 500; }
        .delivery-option:hover, .delivery-option.active { border-color: #8b5cf6; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); box-shadow: 0 4px 12px rgba(13,110,253,0.1); }
        .delivery-option input { margin-right: 0.5rem; accent-color: #8b5cf6; }
        .form-label { font-weight: 600; color: #334155; margin-bottom: 0.6rem; display: block; font-size: 0.95rem; }
        .field-wrapper { position: relative; overflow: hidden; min-height: 100px; }
        .field-slide { transition: transform 0.35s ease, opacity 0.35s ease; }
        .field-slide.hidden { position: absolute; opacity: 0; pointer-events: none; transform: translateY(15px); }
        .field-slide.visible { position: relative; opacity: 1; pointer-events: all; transform: translateY(0); }
        .field-hint { font-size: 0.85rem; color: #64748b; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.3rem; }
        .push-notification { position: fixed; top: 24px; right: 24px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); padding: 1.5rem; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); border: 1px solid rgba(25,135,84,0.3); border-left: 5px solid #198754; z-index: 9999; display: flex; align-items: flex-start; gap: 1rem; min-width: 340px; max-width: 420px; transform: translateX(450px); opacity: 0; transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .push-notification.show { transform: translateX(0); opacity: 1; }
        .push-notification .icon-wrapper { width: 50px; height: 50px; border-radius: 14px; background: linear-gradient(135deg, #198754 0%, #20c997 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 8px 20px rgba(25,135,84,0.35); }
        .push-notification .icon { color: #fff; font-size: 1.6rem; font-weight: bold; }
        .push-notification .content { flex: 1; }
        .push-notification .title { font-weight: 700; font-size: 1.1rem; color: #1e293b; margin-bottom: 0.4rem; }
        .push-notification .message { font-size: 0.95rem; color: #475569; line-height: 1.4; }
        .push-notification .close-btn { background: rgba(100,116,139,0.12); border: none; color: #64748b; width: 32px; height: 32px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; font-size: 1.2rem; }
        .push-notification .close-btn:hover { background: rgba(100,116,139,0.25); color: #334155; transform: rotate(90deg); }
        @media (max-width: 576px) { .push-notification { top: 16px; right: 16px; left: 16px; min-width: auto; max-width: none; } .delivery-options { flex-direction: column; } .order-form { padding: 1.5rem; } }
        </style>';

        $summaryHtml = '';
        if (!empty($basketItems)) {
            foreach ($basketItems as $item) {
                $subtotal = $item['product']['price'] * $item['quantity'];
                $summaryHtml .= '<div class="summary-item"><span>' . htmlspecialchars($item['product']['name']) . ' × ' . $item['quantity'] . '</span><span>' . number_format($subtotal, 0, '.', ' ') . ' ₽</span></div>';
            }
        }
        $summaryHtml .= '<div class="summary-item total"><span>Итого:</span><span>' . number_format($total, 0, '.', ' ') . ' ₽</span></div>';

        $content = $customStyles . $flashHtml . '
        <div id="pushNotification" class="push-notification" role="alert">
            <div class="icon-wrapper">✓</div>
            <div class="content">
                <div class="title">Заявка успешно создана!</div>
                <div class="message" id="pushMessage">Ваша карта готовится к активации. Менеджер свяжется с вами.</div>
            </div>
            <button class="close-btn" onclick="hidePushNotification()">×</button>
        </div>
        <section class="container py-5">
            <div class="order-form">
                <h2>✨ Оформление карты</h2>
                <div class="form-summary"><small class="text-muted d-block mb-2 fw-semibold">📋 Ваш выбор:</small>' . $summaryHtml . '</div>
                <form action="/order" method="POST" id="orderForm">
                    <div class="mb-3"><label for="fio" class="form-label">Ваше ФИО *</label><input type="text" class="form-control form-control-custom" id="fio" name="fio" placeholder="Иванов Иван Иванович" required></div>
                    <div class="mb-3 field-wrapper">
                        <label class="form-label" id="contactLabel">Email для карты *</label>
                        <div id="addressField" class="field-slide hidden"><input type="text" class="form-control form-control-custom" id="address" name="address" placeholder="г. Москва, ул. Примерная, д. 1, кв. 1"><small class="field-hint">🚚 Адрес для доставки пластиковой карты</small></div>
                        <div id="emailField" class="field-slide visible"><input type="email" class="form-control form-control-custom" id="email" name="email" placeholder="example@mail.ru" required><small class="field-hint">📧 Куда отправить электронную карту</small></div>
                    </div>
                    <div class="mb-3"><label for="phone" class="form-label">Телефон *</label><input type="tel" class="form-control form-control-custom" id="phone" name="phone" placeholder="+7 (999) 123-45-67" required></div>
                    <div class="mb-4">
                        <label class="form-label">📬 Способ получения карты:</label>
                        <div class="delivery-options">
                            <label class="delivery-option active" data-type="email"><input type="radio" name="delivery_type" value="email" checked>📧 Email</label>
                            <label class="delivery-option" data-type="courier"><input type="radio" name="delivery_type" value="courier">🚚 Курьер</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-submit" id="submitBtn">✨ Оформить карту</button>
                </form>
            </div>
        </section>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const emailRadio = document.querySelector(\'input[name="delivery_type"][value="email"]\');
            const courierRadio = document.querySelector(\'input[name="delivery_type"][value="courier"]\');
            const addressField = document.getElementById("addressField");
            const emailField = document.getElementById("emailField");
            const contactLabel = document.getElementById("contactLabel");
            const addressInput = document.getElementById("address");
            const emailInput = document.getElementById("email");
            const form = document.getElementById("orderForm");
            const submitBtn = document.getElementById("submitBtn");
            function toggleContactField() {
                if (emailRadio.checked) {
                    contactLabel.textContent = "Email для карты *";
                    addressField.classList.remove("visible"); addressField.classList.add("hidden");
                    emailField.classList.remove("hidden"); emailField.classList.add("visible");
                    addressInput.required = false; emailInput.required = true;
                } else {
                    contactLabel.textContent = "Адрес доставки *";
                    emailField.classList.remove("visible"); emailField.classList.add("hidden");
                    addressField.classList.remove("hidden"); addressField.classList.add("visible");
                    emailInput.required = false; addressInput.required = true;
                }
            }
            emailRadio.addEventListener("change", toggleContactField);
            courierRadio.addEventListener("change", toggleContactField);
            document.querySelectorAll(".delivery-option").forEach(option => {
                option.addEventListener("click", function() {
                    document.querySelectorAll(".delivery-option").forEach(o => o.classList.remove("active"));
                    this.classList.add("active");
                    this.querySelector("input").checked = true;
                    toggleContactField();
                });
            });
            toggleContactField();
            form.addEventListener("submit", function(e) { submitBtn.disabled = true; submitBtn.innerHTML = " Обработка..."; });
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get("success") === "1") { showPushNotification(); clearForm(); window.history.replaceState({}, document.title, window.location.pathname); }
        });
        function showPushNotification() { const notification = document.getElementById("pushNotification"); if (notification) { notification.classList.add("show"); setTimeout(hidePushNotification, 6000); } }
        function hidePushNotification() { const notification = document.getElementById("pushNotification"); if (notification) { notification.classList.remove("show"); } }
        function clearForm() {
            const form = document.getElementById("orderForm");
            if (form) {
                form.reset();
                document.querySelector(\'input[name="delivery_type"][value="email"]\').checked = true;
                document.querySelectorAll(".delivery-option").forEach((o, i) => { o.classList.toggle("active", i === 0); });
                const contactLabel = document.getElementById("contactLabel");
                const addressField = document.getElementById("addressField");
                const emailField = document.getElementById("emailField");
                contactLabel.textContent = "Email для карты *";
                addressField.classList.remove("visible"); addressField.classList.add("hidden");
                emailField.classList.remove("hidden"); emailField.classList.add("visible");
            }
        }
        </script>';

        return sprintf($template, $title, $content);
    }
}