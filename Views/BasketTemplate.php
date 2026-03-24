<?php
namespace Views;

class BasketTemplate extends BaseTemplate
{
    public static function render(array $items, float $total, string $storageType): string
    {
        $template = parent::getTemplate();
        $title = 'Корзина — Страховая компания «Чёрный Вантуз»';
        
        $customStyles = '
        <style>
            .basket-header { text-align: center; margin-bottom: 2.5rem; }
            .basket-title { font-size: 2rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; }
            .basket-subtitle { color: #64748b; font-size: 1.05rem; }
            .basket-card {
                border: none; border-radius: 20px; background: #ffffff;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                margin-bottom: 1rem;
            }
            .basket-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 30px rgba(13, 110, 253, 0.12);
            }
            .basket-img {
                object-fit: cover; height: 140px; width: 100%;
                border-radius: 16px 0 0 16px;
            }
            @media (max-width: 768px) {
                .basket-img { border-radius: 16px 16px 0 0; height: 180px; }
            }
            .qty-control {
                display: inline-flex; align-items: center; gap: 0.5rem;
                background: #f8fafc; border-radius: 50px;
                padding: 0.25rem 0.75rem;
            }
            .qty-btn {
                width: 28px; height: 28px; border: none;
                background: #0d6efd; color: #fff; border-radius: 50%;
                font-weight: 600; cursor: pointer;
                display: flex; align-items: center; justify-content: center;
                transition: background 0.2s;
            }
            .qty-btn:hover { background: #0b5ed7; }
            .qty-value { font-weight: 600; min-width: 20px; text-align: center; }
            .price-label { font-size: 0.85rem; color: #64748b; margin-bottom: 0.25rem; }
            .price-value { font-weight: 600; color: #1e293b; }
            .total-value { font-size: 1.25rem; font-weight: 700; color: #0d6efd; }
            .basket-summary {
                background: linear-gradient(135deg, #f8fafc 0%, #f0f9ff 100%);
                border-radius: 20px; padding: 1.75rem; border: 1px solid #e2e8f0;
            }
            .summary-row {
                display: flex; justify-content: space-between;
                padding: 0.6rem 0; border-bottom: 1px solid #e2e8f0;
            }
            .summary-row:last-child {
                border-bottom: none; padding-top: 1rem;
                font-size: 1.25rem; font-weight: 700; color: #0d6efd;
            }
            .btn-custom {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                border: none; padding: 12px 32px; border-radius: 50px;
                color: #fff; font-weight: 600; text-decoration: none;
                display: inline-block; transition: all 0.3s ease;
                width: 100%; text-align: center;
            }
            .btn-custom:hover {
                opacity: 0.95; color: #fff; transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
            }
            .btn-outline-custom {
                border: 2px solid #0d6efd; color: #0d6efd;
                padding: 10px 24px; border-radius: 50px; font-weight: 600;
                background: transparent; width: 100%; text-align: center;
                transition: all 0.3s ease;
            }
            .btn-outline-custom:hover { background: #0d6efd; color: #fff; }
            .basket-actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
            @media (max-width: 768px) { .basket-actions { flex-direction: column; } }
            .basket-empty { text-align: center; padding: 4rem 2rem; }
            .basket-empty-icon { font-size: 4rem; margin-bottom: 1.5rem; opacity: 0.6; }
            .basket-empty h3 { font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: #1e293b; }
            .basket-empty p { color: #64748b; margin-bottom: 2rem; }
            .remove-link { color: #ef4444; font-size: 0.9rem; text-decoration: none; font-weight: 500; }
            .remove-link:hover { text-decoration: underline; }
            .storage-toggle {
                background: #f8fafc; border-radius: 12px; padding: 1rem;
                margin-bottom: 2rem; text-align: center;
            }
            .storage-toggle label { margin: 0 1rem; cursor: pointer; }
            .storage-toggle input { margin-right: 0.5rem; }
        </style>';
        
        if (empty($items)) {
            $content = $customStyles . '
            <section class="container py-5">
                <div class="basket-empty">
                    <div class="basket-empty-icon">🛒</div>
                    <h3>Корзина пуста</h3>
                    <p>Добавьте страховые продукты для оформления</p>
                    <a href="/products" class="btn btn-custom" style="max-width:250px">Перейти в каталог</a>
                </div>
            </section>';
        } else {
            $itemsHtml = '';
            foreach ($items as $item) {
                $price = number_format($item['product']['price'], 0, '.', ' ');
                $subtotal = number_format($item['subtotal'], 0, '.', ' ');
                
                $itemsHtml .= '
                <div class="card basket-card">
                    <div class="row g-0">
                        <div class="col-md-3">
                            <img src="'.htmlspecialchars($item['product']['image']).'" 
                                 class="basket-img" 
                                 alt="'.htmlspecialchars($item['product']['name']).'"
                                 onerror="this.src=\'https://placehold.co/300x200/0d6efd/ffffff?text='.urlencode($item['product']['name']).'\'">
                        </div>
                        <div class="col-md-6">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-2">
                                    <a href="/product/'.$item['product']['id'].'" class="text-decoration-none text-dark">
                                        '.htmlspecialchars($item['product']['name']).'
                                    </a>
                                </h5>
                                <p class="text-muted small mb-3">'.htmlspecialchars($item['product']['description']).'</p>
                                <span class="badge bg-primary bg-opacity-10 text-primary">'.htmlspecialchars($item['product']['coverage']).'</span>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex flex-column justify-content-center p-4">
                            <div class="price-label">Цена</div>
                            <div class="price-value mb-3">'.$price.' ₽</div>
                            <div class="price-label">Количество</div>
                            <div class="qty-control mb-3">
                                <button class="qty-btn" data-id="'.$item['product']['id'].'" data-action="dec">−</button>
                                <span class="qty-value">'.$item['quantity'].'</span>
                                <button class="qty-btn" data-id="'.$item['product']['id'].'" data-action="inc">+</button>
                            </div>
                            <div class="price-label">Итого</div>
                            <div class="total-value mb-3">'.$subtotal.' ₽</div>
                            <a href="/cart/remove?id='.$item['product']['id'].'" class="remove-link" onclick="return confirm(\'Удалить из корзины?\')">Удалить</a>
                        </div>
                    </div>
                </div>';
            }
            
            $totalFormatted = number_format($total, 0, '.', ' ');
            $storageLabel = $storageType === 'session' ? 'Сессия (до закрытия браузера)' : 'Куки (30 дней)';
            
            $content = $customStyles . '
            <section class="container py-5">
                <div class="basket-header">
                    <h1 class="basket-title">Корзина</h1>
                    <p class="basket-subtitle">Оформление страховых продуктов</p>
                </div>
                
                <div class="storage-toggle">
                    <small class="text-muted d-block mb-2">Хранение корзины:</small>
                    <form method="POST" action="/cart/setStorage" class="d-inline">
                        <label>
                            <input type="radio" name="type" value="session" '.($storageType==='session'?'checked':'').'>
                            Сессия
                        </label>
                        <label>
                            <input type="radio" name="type" value="cookie" '.($storageType==='cookie'?'checked':'').'>
                            Куки
                        </label>
                        <button type="submit" class="btn btn-sm btn-outline-primary ms-2">Применить</button>
                    </form>
                    <small class="text-muted d-block mt-2">'.$storageLabel.'</small>
                </div>
                
                <div class="row g-4">
                    <div class="col-lg-8">'.$itemsHtml.'</div>
                    <div class="col-lg-4">
                        <div class="basket-summary">
                            <h5 class="fw-bold mb-4">Итого</h5>
                            <div class="summary-row">
                                <span class="text-muted">Товаров:</span>
                                <span class="fw-semibold">'.count($items).'</span>
                            </div>
                            <div class="summary-row">
                                <span class="text-muted">Общая сумма:</span>
                                <span class="fw-bold text-primary">'.$totalFormatted.' ₽</span>
                            </div>
                            <div class="basket-actions">
                                <a href="/cart/clear" class="btn btn-outline-custom" onclick="return confirm(\'Очистить корзину?\')">Очистить</a>
                                <a href="/checkout" class="btn btn-custom">Оформить заказ</a>
                            </div>
                            <p class="text-muted small mt-3 mb-0 text-center">
                                * Менеджер свяжется для уточнения деталей
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            
            <script>
            document.querySelectorAll(".qty-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    const id = this.dataset.id;
                    const action = this.dataset.action;
                    const qtySpan = this.closest(".qty-control").querySelector(".qty-value");
                    let qty = parseInt(qtySpan.textContent) || 1;
                    
                    if (action === "inc") { qty++; } 
                    else if (action === "dec" && qty > 1) { qty--; }
                    
                    qtySpan.textContent = qty;
                    
                    fetch("/cart/update", {
                        method: "POST",
                        headers: {"Content-Type": "application/x-www-form-urlencoded"},
                        body: "id=" + id + "&quantity=" + qty
                    }).then(() => location.reload());
                });
            });
            </script>';
        }
        
        return sprintf($template, $title, $content);
    }
}