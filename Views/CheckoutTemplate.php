<?php
namespace Views;

class CheckoutTemplate extends BaseTemplate
{
    public static function render(array $basket): string
    {
        $template = parent::getTemplate();
        $title = 'Оплата заказа — Чёрный Вантуз';
        
        $errors = $_SESSION['checkout_errors'] ?? [];
        unset($_SESSION['checkout_errors']);
        
        $errorsHtml = '';
        if (!empty($errors)) {
            $errorsHtml = '<div class="alert alert-danger mb-4">' . implode('<br>', array_map('htmlspecialchars', $errors)) . '</div>';
        }
        
        $productModel = new \Models\Product();
        $items = [];
        $total = 0;
        
        foreach ($basket as $productId => $item) {
            $product = $productModel->getById($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'] ?? 1,
                    'subtotal' => $product['price'] * ($item['quantity'] ?? 1)
                ];
                $total += $product['price'] * ($item['quantity'] ?? 1);
            }
        }
        
        $itemsHtml = '';
        foreach ($items as $item) {
            $price = number_format($item['product']['price'], 0, '.', ' ');
            $subtotal = number_format($item['subtotal'], 0, '.', ' ');
            
            $itemsHtml .= '
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="fw-bold mb-1">' . htmlspecialchars($item['product']['name']) . '</h6>
                            <p class="text-muted small mb-0">' . htmlspecialchars($item['product']['description']) . '</p>
                        </div>
                        <div class="col-md-2 text-end">
                            <div class="text-muted small">Количество</div>
                            <div class="fw-semibold">' . $item['quantity'] . ' шт.</div>
                        </div>
                        <div class="col-md-2 text-end">
                            <div class="text-muted small">Сумма</div>
                            <div class="fw-bold text-primary">' . $subtotal . ' ₽</div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        $totalFormatted = number_format($total, 0, '.', ' ');
        
        $content = '
        <style>
            .checkout-header {
                text-align: center;
                margin-bottom: 2rem;
            }
            .checkout-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: #1e293b;
            }
            .checkout-card {
                border: none;
                border-radius: 20px;
                background: #ffffff;
                padding: 2rem;
                margin-bottom: 1.5rem;
                border: 1px solid #e2e8f0;
            }
            .form-label {
                font-weight: 500;
                color: #334155;
                margin-bottom: 0.5rem;
            }
            .form-control {
                border-radius: 12px;
                border: 1px solid #cbd5e1;
                padding: 0.75rem 1rem;
            }
            .form-control:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12);
            }
            .form-text {
                font-size: 0.85rem;
                color: #64748b;
                margin-top: 0.25rem;
            }
            .btn-checkout {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                border: none;
                padding: 14px 32px;
                border-radius: 50px;
                color: #fff;
                font-weight: 600;
                font-size: 1.1rem;
                width: 100%;
            }
            .btn-checkout:hover {
                opacity: 0.95;
                color: #fff;
            }
            .summary-row {
                display: flex;
                justify-content: space-between;
                padding: 0.75rem 0;
                border-bottom: 1px solid #e2e8f0;
            }
            .summary-row:last-child {
                border-bottom: none;
                font-size: 1.25rem;
                font-weight: 700;
                color: #0d6efd;
                padding-top: 1rem;
            }
            .payment-icon {
                font-size: 3rem;
                text-align: center;
                margin-bottom: 1rem;
                color: #0d6efd;
            }
            .secure-badge {
                text-align: center;
                color: #198754;
                font-size: 0.9rem;
                margin-top: 1rem;
            }
        </style>
        
        <section class="container py-5">
            <div class="checkout-header">
                <h1 class="checkout-title">💳 Оформление заказа</h1>
                <p class="text-muted">Заполните данные для оплаты</p>
            </div>
            
            ' . $errorsHtml . '
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="checkout-card">
                        <h4 class="fw-bold mb-4">📦 Товары в заказе</h4>
                        ' . $itemsHtml . '
                    </div>
                    
                    <div class="checkout-card">
                        <h4 class="fw-bold mb-4">💳 Данные карты</h4>
                        <div class="payment-icon">💳</div>
                        <form method="POST" action="/checkout/process">
                            <div class="mb-3">
                                <label class="form-label">Номер карты</label>
                                <input type="text" name="card_number" class="form-control" 
                                       placeholder="0000 0000 0000 0000" 
                                       maxlength="19"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, \'\').replace(/(.{4})/g, \'$1 \').trim()"
                                       required>
                                <small class="form-text">Принимаем Visa, Mastercard, МИР</small>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Срок действия</label>
                                    <input type="text" name="card_expiry" class="form-control" 
                                           placeholder="MM/YY" maxlength="5"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, \'\').replace(/(.{2})/g, \'$1/\').slice(0,5)"
                                           required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">CVC/CVV</label>
                                    <input type="text" name="card_cvc" class="form-control" 
                                           placeholder="123" maxlength="3"
                                           required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Имя держателя карты</label>
                                <input type="text" name="card_name" class="form-control" 
                                       placeholder="IVAN IVANOV" 
                                       style="text-transform: uppercase;"
                                       required>
                            </div>
                            <button type="submit" class="btn btn-checkout">
                                🔐 Оплатить ' . $totalFormatted . ' ₽
                            </button>
                            <div class="secure-badge">
                                🔒 Безопасная оплата через защищённое соединение
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="checkout-card">
                        <h5 class="fw-bold mb-4">📊 Итого</h5>
                        <div class="summary-row">
                            <span class="text-muted">Товаров:</span>
                            <span class="fw-semibold">' . count($items) . '</span>
                        </div>
                        <div class="summary-row">
                            <span class="text-muted">Подытог:</span>
                            <span class="fw-bold">' . $totalFormatted . ' ₽</span>
                        </div>
                        <div class="summary-row">
                            <span class="text-muted">Скидка:</span>
                            <span class="fw-bold text-success">0 ₽</span>
                        </div>
                        <div class="summary-row">
                            <span class="text-muted">К оплате:</span>
                            <span class="fw-bold text-primary">' . $totalFormatted . ' ₽</span>
                        </div>
                    </div>
                    
                    <div class="checkout-card text-center">
                        <p class="text-muted small mb-2">📞 Нужна помощь?</p>
                        <a href="tel:+79999999999" class="fw-bold text-decoration-none">+7 (999) 999-99-99</a>
                    </div>
                </div>
            </div>
        </section>';
        
        return sprintf($template, $title, $content);
    }
    
    public static function success(string $message): string
    {
        $template = parent::getTemplate();
        $title = 'Заказ оформлен — Чёрный Вантуз';
        
        $content = '
        <style>
            .success-card {
                max-width: 600px;
                margin: 3rem auto;
                text-align: center;
                border: none;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(13, 110, 253, 0.15);
            }
            .success-icon {
                font-size: 5rem;
                color: #198754;
                margin-bottom: 1.5rem;
            }
            .success-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 1rem;
            }
            .success-text {
                color: #64748b;
                margin-bottom: 2rem;
            }
            .btn-success-custom {
                background: linear-gradient(135deg, #198754 0%, #20c997 100%);
                border: none;
                padding: 12px 32px;
                border-radius: 50px;
                color: #fff;
                font-weight: 600;
                text-decoration: none;
                display: inline-block;
            }
            .btn-success-custom:hover {
                opacity: 0.95;
                color: #fff;
                text-decoration: none;
            }
        </style>
        
        <section class="container py-5">
            <div class="card success-card p-5">
                <div class="success-icon">✅</div>
                <h1 class="success-title">Заказ успешно оформлен!</h1>
                <p class="success-text">' . htmlspecialchars($message) . '</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="/products" class="btn btn-success-custom">Вернуться в каталог</a>
                    <a href="/profile" class="btn btn-outline-primary rounded-pill px-4">В профиль</a>
                </div>
            </div>
        </section>';
        
        return sprintf($template, $title, $content);
    }
}