<?php
/**
 * Шаблон страницы корзины
 * Доступные переменные:
 * - $isEmpty - boolean, корзина пуста
 * - $cartRows - HTML строк таблицы
 * - $count - количество товаров
 * - $total - итоговая сумма (число)
 * - $totalFormatted - итоговая сумма (строка)
 * - $cartJson - JSON данные корзины для JS
 * - $texts - массив текстов из storage/templates/cart.json
 */

// Значения по умолчанию
$texts = $texts ?? [];
$emptyText = $texts['empty'] ?? [];
$tableText = $texts['table'] ?? [];
$summaryText = $texts['summary'] ?? [];
$checkoutText = $texts['checkout'] ?? [];
$toastText = $texts['toast'] ?? [];
?>

<?php if ($isEmpty): ?>
<div class="container py-5">
    <div class="text-center py-5">
        <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
        <h3><?= htmlspecialchars($emptyText['title'] ?? 'Ваша корзина пуста 😔') ?></h3>
        <p class="text-muted"><?= htmlspecialchars($emptyText['message'] ?? 'Добавьте товары из каталога') ?></p>
        <a href="/catalog" class="btn btn-primary mt-3"><?= htmlspecialchars($emptyText['link'] ?? 'Перейти в каталог') ?></a>
    </div>
</div>
<?php else: ?>
<div class="container py-5">
    <h1 class="text-center mb-4"><?= htmlspecialchars($texts['title'] ?? 'Ваша корзина') ?></h1>
    
    <div class="card shadow-lg">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th><?= htmlspecialchars($tableText['product'] ?? 'Товар') ?></th>
                            <th><?= htmlspecialchars($tableText['price'] ?? 'Цена') ?></th>
                            <th><?= htmlspecialchars($tableText['quantity'] ?? 'Кол-во') ?></th>
                            <th><?= htmlspecialchars($tableText['sum'] ?? 'Сумма') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cart-items">
                        <?= $cartRows ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div>
                    <span class="text-muted"><?= htmlspecialchars($summaryText['totalItems'] ?? 'Всего товаров:') ?> </span>
                    <span class="fw-bold"><?= $count ?></span>
                </div>
                <div class="text-end">
                    <span class="text-muted d-block"><?= htmlspecialchars($summaryText['total'] ?? 'Итого:') ?></span>
                    <span class="h3 fw-bold text-success mb-0"><?= $totalFormatted ?> ₽</span>
                </div>
            </div>
            
            <div class="d-flex gap-3 justify-content-end mt-4">
                <button class="btn btn-outline-dark" id="clear-cart">
                    <i class="bi bi-trash me-2"></i><?= htmlspecialchars($summaryText['clear'] ?? 'Очистить') ?>
                </button>
                <button class="btn btn-success btn-lg px-4" id="checkout-btn" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                    <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($summaryText['checkout'] ?? 'Оформить заказ') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно оформления заказа -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($checkoutText['title'] ?? 'Оформление заказа') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="checkout-form">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="fio" class="form-label"><?= htmlspecialchars($checkoutText['fio'] ?? 'ФИО') ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fio" name="fio" required placeholder="<?= htmlspecialchars($checkoutText['fioPlaceholder'] ?? 'Иванов Иван Иванович') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label"><?= htmlspecialchars($checkoutText['email'] ?? 'Email') ?> <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="<?= htmlspecialchars($checkoutText['emailPlaceholder'] ?? 'example@mail.ru') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label"><?= htmlspecialchars($checkoutText['phone'] ?? 'Телефон') ?> <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required placeholder="<?= htmlspecialchars($checkoutText['phonePlaceholder'] ?? '+7 (999) 123-45-67') ?>">
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label"><?= htmlspecialchars($checkoutText['address'] ?? 'Адрес доставки') ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" required placeholder="<?= htmlspecialchars($checkoutText['addressPlaceholder'] ?? 'г. Кемерово, ул. Примерная, д. 1, кв. 1') ?>">
                        </div>
                        <div class="col-12">
                            <label for="payment" class="form-label"><?= htmlspecialchars($checkoutText['payment'] ?? 'Способ оплаты') ?> <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment" name="payment" required>
                                <option value=""><?= htmlspecialchars($checkoutText['paymentSelect'] ?? 'Выберите способ оплаты') ?></option>
                                <option value="cash"><?= htmlspecialchars($checkoutText['paymentCash'] ?? 'Наличными при получении') ?></option>
                                <option value="card"><?= htmlspecialchars($checkoutText['paymentCard'] ?? 'Банковской картой') ?></option>
                                <option value="online"><?= htmlspecialchars($checkoutText['paymentOnline'] ?? 'Онлайн-оплата') ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6><?= htmlspecialchars($checkoutText['totalToPay'] ?? 'Итого к оплате:') ?> <span class="text-success fw-bold"><?= $totalFormatted ?> ₽</span></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($checkoutText['cancel'] ?? 'Отмена') ?></button>
                    <button type="submit" class="btn btn-success" id="submit-order">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        <span class="btn-text"><?= htmlspecialchars($checkoutText['submit'] ?? 'Подтвердить заказ') ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast уведомления -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                <span id="toastMessage"><?= htmlspecialchars($toastText['saved'] ?? 'Изменения сохранены!') ?></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- Данные корзины для JS -->
<script>
window.cartData = <?= $cartJson ?>;
window.cartTotal = <?= $total ?>;
</script>
<?php endif; ?>
