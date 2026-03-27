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
                case 'updated': $successMessage = '✅ Количество обновлено'; break;
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
.quantity-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-right: 1.5rem;
}
.quantity-btn {
    width: 36px;
    height: 36px;
    border: 2px solid #ff6b35;
    background: white;
    color: #ff6b35;
    border-radius: 10px;
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}
.quantity-btn:hover {
    background: #ff6b35;
    color: white;
}
.quantity-input {
    width: 50px;
    text-align: center;
    border: 2px solid #ff6b35;
    border-radius: 10px;
    padding: 0.3rem;
    font-weight: 700;
    font-size: 1.1rem;
    color: #1a1a2e;
}
.item-price {
    font-size: 1.8rem;
    font-weight: 900;
    color: #ff6b35;
    margin-right: 2rem;
    min-width: 120px;
    text-align: right;
}
.item-price .price-per-unit {
    font-size: 0.9rem;
    color: #999;
    font-weight: 400;
    display: block;
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
.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    font-size: 1.1rem;
}
.total-row.final {
    border-top: 2px solid rgba(255,255,255,0.3);
    margin-top: 1rem;
    padding-top: 1rem;
    font-size: 1.5rem;
    font-weight: 900;
}
.total-amount {
    font-size: 2.5rem;
    font-weight: 900;
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
    margin-top: 1rem;
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
.saving-badge {
    background: #22c55e;
    color: white;
    padding: 0.3rem 1rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 0.5rem;
    display: inline-block;
}
</style>';

        $cartItemsHtml = '';
        $totalPrice = 0;
        $totalItems = 0;

        if ($cartCount > 0) {
            foreach ($cartItems as $item) {
                $priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
                $quantity = $item['quantity'] ?? 1;
                $itemTotal = $priceNum * $quantity;
                $totalPrice += $itemTotal;
                $totalItems += $quantity;
                
                $cartItemsHtml .= '
<div class="cart-item-row" data-item-id="' . $item['id'] . '" data-price="' . $priceNum . '">
    <div class="item-emoji">' . $item['icon'] . '</div>
    <div class="item-details">
        <div class="item-name">' . htmlspecialchars($item['title']) . '</div>
        <div class="item-info">📦 ' . htmlspecialchars($item['duration']) . '</div>
    </div>
    <div class="quantity-control">
        <button type="button" class="quantity-btn btn-minus" onclick="changeQuantity(' . $item['id'] . ', -1)">−</button>
        <input type="number" class="quantity-input" value="' . $quantity . '" min="1" max="99" 
               onchange="updateQuantity(' . $item['id'] . ', this.value)" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
        <button type="button" class="quantity-btn btn-plus" onclick="changeQuantity(' . $item['id'] . ', 1)">+</button>
    </div>
    <div class="item-price">
        <span class="item-total-price">' . number_format($itemTotal, 0, '.', ' ') . ' ₽</span>
        <span class="price-per-unit">по ' . number_format($priceNum, 0, '.', ' ') . ' ₽/шт</span>
    </div>
    <form method="POST" action="/cart/remove" style="display:inline;">
        <input type="hidden" name="courseId" value="' . $item['id'] . '">
        <button type="submit" class="btn-remove" title="Удалить товар">🗑️</button>
    </form>
</div>';
            }

            // Рассчитываем скидку при заказе от 5000₽
            $discount = 0;
            $finalTotal = $totalPrice;
            if ($totalPrice >= 5000) {
                $discount = round($totalPrice * 0.05);
                $finalTotal = $totalPrice - $discount;
            }

            $cartItemsHtml .= '
<div class="cart-total-box">
    <h3 class="mb-3">📊 Итого к оплате</h3>
    <div class="total-row">
        <span>Товаров в корзине:</span>
        <span class="fw-bold">' . $totalItems . ' шт.</span>
    </div>
    <div class="total-row">
        <span>Сумма:</span>
        <span>' . number_format($totalPrice, 0, '.', ' ') . ' ₽</span>
    </div>';
            
            if ($discount > 0) {
                $cartItemsHtml .= '
    <div class="total-row" style="color: #22c55e;">
        <span>Скидка 5%:</span>
        <span>−' . number_format($discount, 0, '.', ' ') . ' ₽</span>
    </div>
    <div class="saving-badge">🎉 Вы экономите ' . number_format($discount, 0, '.', ' ') . ' ₽!</div>';
            }
            
            $cartItemsHtml .= '
    <div class="total-row final">
        <span>К оплате:</span>
        <span class="total-amount">' . number_format($finalTotal, 0, '.', ' ') . ' ₽</span>
    </div>
    
    <!-- ✅ ИСПРАВЛЕННАЯ КНОПКА (ведет на форму заказа) -->
    <a href="/order/checkout" class="btn-checkout">
        ✅ Оформить заказ
    </a>
    
    <p class="small mt-3" style="opacity: 0.9;">🚚 Доставка: бесплатно при заказе от 3000 ₽</p>
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
</section>

<script>
// Изменение количества через кнопки +/-
function changeQuantity(itemId, change) {
    const row = document.querySelector(\'.cart-item-row[data-item-id="\' + itemId + \'"]\');
    if (!row) return;
    
    const input = row.querySelector(\'.quantity-input\');
    let quantity = parseInt(input.value) || 1;
    quantity = Math.max(1, Math.min(99, quantity + change));
    
    input.value = quantity;
    updateItemTotal(row, quantity);
    updateCartTotals();
    saveQuantityToSession(itemId, quantity);
}

// Обновление через input
function updateQuantity(itemId, value) {
    let quantity = parseInt(value) || 1;
    quantity = Math.max(1, Math.min(99, quantity));
    
    const row = document.querySelector(\'.cart-item-row[data-item-id="\' + itemId + \'"]\');
    if (!row) return;
    
    row.querySelector(\'.quantity-input\').value = quantity;
    updateItemTotal(row, quantity);
    updateCartTotals();
    saveQuantityToSession(itemId, quantity);
}

// Пересчёт суммы для одного товара
function updateItemTotal(row, quantity) {
    const price = parseInt(row.dataset.price) || 0;
    const total = price * quantity;
    
    const totalSpan = row.querySelector(\'.item-total-price\');
    if (totalSpan) {
        totalSpan.textContent = new Intl.NumberFormat(\'ru-RU\').format(total) + \' ₽\';
    }
}

// Пересчёт общей суммы корзины
function updateCartTotals() {
    let totalItems = 0;
    let totalPrice = 0;
    
    document.querySelectorAll(\'.cart-item-row\').forEach(row => {
        const quantity = parseInt(row.querySelector(\'.quantity-input\').value) || 1;
        const price = parseInt(row.dataset.price) || 0;
        
        totalItems += quantity;
        totalPrice += price * quantity;
    });
    
    // Обновляем строки итогов
    const itemsCountEl = document.querySelector(\'.cart-total-box .total-row:first-child .fw-bold\');
    if (itemsCountEl) itemsCountEl.textContent = totalItems + \' шт.\';
    
    const sumEl = document.querySelector(\'.cart-total-box .total-row:nth-child(2) span:last-child\');
    if (sumEl) sumEl.textContent = new Intl.NumberFormat(\'ru-RU\').format(totalPrice) + \' ₽\';
    
    // Скидка
    let discount = 0;
    let finalTotal = totalPrice;
    if (totalPrice >= 5000) {
        discount = Math.round(totalPrice * 0.05);
        finalTotal = totalPrice - discount;
    }
    
    const discountRow = document.querySelector(\'.cart-total-box .total-row[style*="22c55e"]\');
    if (discount > 0 && discountRow) {
        discountRow.querySelector(\'span:last-child\').textContent = \'−\' + new Intl.NumberFormat(\'ru-RU\').format(discount) + \' ₽\';
    }
    
    const savingBadge = document.querySelector(\'.saving-badge\');
    if (discount > 0 && savingBadge) {
        savingBadge.textContent = \'🎉 Вы экономите \' + new Intl.NumberFormat(\'ru-RU\').format(discount) + \' ₽!\';
    }
    
    const finalEl = document.querySelector(\'.total-amount\');
    if (finalEl) finalEl.textContent = new Intl.NumberFormat(\'ru-RU\').format(finalTotal) + \' ₽\';
}

// Сохранение количества в сессию (AJAX)
function saveQuantityToSession(itemId, quantity) {
    fetch(\'/cart/update\', {
        method: \'POST\',
        headers: {
            \'Content-Type\': \'application/x-www-form-urlencoded\',
        },
        body: \'courseId=\' + itemId + \'&quantity=\' + quantity
    })
    .then(response => {
        if (response.ok) {
            showNotification(\'✅ Количество обновлено\');
        }
    })
    .catch(error => {
        console.log(\'Ошибка сохранения:\', error);
    });
}

// Инициализация при загрузке
document.addEventListener(\'DOMContentLoaded\', function() {
    updateCartTotals();
});
</script>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}