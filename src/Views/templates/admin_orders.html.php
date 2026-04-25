<?php
/**
 * Шаблон страницы заказов админ-панели
 * Доступные переменные:
 * - $orders - массив заказов
 * - $activeCount - количество активных заказов
 * - $completedCount - количество завершённых заказов
 * - $texts - массив текстов из storage/templates/admin.json
 */

$texts = $texts ?? [];
$ordersText = $texts['orders'] ?? [];

$activeOrders = array_filter($orders, fn($o) => in_array($o['status'] ?? 'new', ['new', 'processing']));
$completedOrders = array_filter($orders, fn($o) => in_array($o['status'] ?? '', ['completed', 'cancelled']));
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><?= htmlspecialchars($ordersText['title'] ?? 'Заказы') ?></h1>
        <a href="/admin" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?= htmlspecialchars($ordersText['back'] ?? 'Назад') ?>
        </a>
    </div>

    <!-- Вкладки -->
    <ul class="nav nav-tabs mb-4" id="ordersTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-orders" type="button" role="tab">
                <i class="bi bi-clock-history me-1"></i><?= htmlspecialchars($ordersText['active'] ?? 'Активные') ?>
                <span class="badge bg-danger ms-2"><?= count($activeOrders) ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-orders" type="button" role="tab">
                <i class="bi bi-check2-circle me-1"></i><?= htmlspecialchars($ordersText['completed'] ?? 'Завершённые') ?>
                <span class="badge bg-secondary ms-2"><?= count($completedOrders) ?></span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="ordersTabsContent">
        <!-- Активные заказы -->
        <div class="tab-pane fade show active" id="active-orders" role="tabpanel">
            <?= $activeOrdersHtml ?? '' ?>
        </div>

        <!-- Завершённые заказы -->
        <div class="tab-pane fade" id="completed-orders" role="tabpanel">
            <?= $completedOrdersHtml ?? '' ?>
        </div>
    </div>
</div>

<!-- Модальное окно просмотра заказа -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-receipt me-2"></i>Заказ #<span id="orderModalId"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-person me-1"></i><?= htmlspecialchars($ordersText['customer'] ?? 'Клиент') ?>
                                </h6>
                                <p class="mb-2"><strong><?= htmlspecialchars($ordersText['fio'] ?? 'ФИО:') ?></strong> <span id="orderModalFio"></span></p>
                                <p class="mb-2"><strong><?= htmlspecialchars($ordersText['email'] ?? 'Email:') ?></strong> <span id="orderModalEmail"></span></p>
                                <p class="mb-2"><strong><?= htmlspecialchars($ordersText['phone'] ?? 'Телефон:') ?></strong> <span id="orderModalPhone"></span></p>
                                <p class="mb-0"><strong><?= htmlspecialchars($ordersText['address'] ?? 'Адрес:') ?></strong> <span id="orderModalAddress"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-info-circle me-1"></i><?= htmlspecialchars($ordersText['details'] ?? 'Детали') ?>
                                </h6>
                                <p class="mb-2"><strong><?= htmlspecialchars($ordersText['date'] ?? 'Дата:') ?></strong> <span id="orderModalDate"></span></p>
                                <p class="mb-2"><strong><?= htmlspecialchars($ordersText['status'] ?? 'Статус:') ?></strong> <span id="orderModalStatus"></span></p>
                                <p class="mb-0"><strong><?= htmlspecialchars($ordersText['payment'] ?? 'Оплата:') ?></strong> <span id="orderModalPayment"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="text-muted mb-3">
                    <i class="bi bi-box-seam me-1"></i><?= htmlspecialchars($ordersText['items'] ?? 'Товары') ?>
                </h6>
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50%"><?= htmlspecialchars($ordersText['item'] ?? 'Товар') ?></th>
                                    <th><?= htmlspecialchars($ordersText['price'] ?? 'Цена') ?></th>
                                    <th><?= htmlspecialchars($ordersText['quantity'] ?? 'Кол-во') ?></th>
                                    <th class="text-end"><?= htmlspecialchars($ordersText['sum'] ?? 'Сумма') ?></th>
                                </tr>
                            </thead>
                            <tbody id="orderModalItems">
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end"><?= htmlspecialchars($ordersText['total'] ?? 'Итого:') ?></th>
                                    <th class="text-end text-success" id="orderModalTotal"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i><?= htmlspecialchars($ordersText['close'] ?? 'Закрыть') ?>
                </button>
                <button type="button" class="btn btn-success" id="btnCompleteOrder">
                    <i class="bi bi-check-lg me-1"></i><?= htmlspecialchars($ordersText['complete'] ?? 'Завершить') ?>
                </button>
                <button type="button" class="btn btn-outline-danger" id="btnCancelOrder">
                    <i class="bi bi-x-lg me-1"></i><?= htmlspecialchars($ordersText['cancel'] ?? 'Отменить') ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let currentOrderId = null;
    let currentOrderStatus = null;
    const orders = <?= json_encode($orders, JSON_UNESCAPED_UNICODE) ?>;

    // Функция для получения даты
    function getOrderDate(order) {
        return order.created_at || order.date || null;
    }

    // Функция форматирования даты
    function formatDate(dateStr) {
        if (!dateStr) return "—";
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return "—";
        return date.toLocaleString("ru-RU", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit"
        });
    }

    // Открытие модального окна
    document.querySelectorAll(".btn-view-order").forEach(btn => {
        btn.addEventListener("click", function() {
            const orderId = this.dataset.id;
            const order = orders.find(o => o.id === orderId);
            if (!order) return;

            currentOrderId = orderId;
            currentOrderStatus = order.status;

            document.getElementById("orderModalId").textContent = orderId;
            document.getElementById("orderModalFio").textContent = order.fio || "—";
            document.getElementById("orderModalEmail").textContent = order.email || "—";
            document.getElementById("orderModalPhone").textContent = order.phone || "—";
            document.getElementById("orderModalAddress").textContent = order.address || "—";
            document.getElementById("orderModalDate").textContent = formatDate(getOrderDate(order));
            document.getElementById("orderModalPayment").textContent = order.payment === "cash" ? "<?= htmlspecialchars($ordersText['paymentCash'] ?? 'Наличными') ?>" : "<?= htmlspecialchars($ordersText['paymentCard'] ?? 'Картой') ?>";

            const statusMap = {
                "new": {text: "<?= htmlspecialchars($ordersText['statusNew'] ?? 'Новый') ?>", class: "bg-primary"},
                "processing": {text: "<?= htmlspecialchars($ordersText['statusProcessing'] ?? 'В обработке') ?>", class: "bg-warning text-dark"},
                "completed": {text: "<?= htmlspecialchars($ordersText['statusCompleted'] ?? 'Выполнен') ?>", class: "bg-success"},
                "cancelled": {text: "<?= htmlspecialchars($ordersText['statusCancelled'] ?? 'Отменён') ?>", class: "bg-danger"}
            };
            const status = statusMap[order.status] || {text: order.status, class: "bg-secondary"};
            document.getElementById("orderModalStatus").innerHTML = `<span class="badge ${status.class}">${status.text}</span>`;

            // Товары
            const itemsHtml = order.items.map(item => `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${item.image || "/assets/img/no-image.jpg"}" alt="" width="40" height="40" class="rounded me-2" style="object-fit: cover;">
                            <span>${item.name}</span>
                        </div>
                    </td>
                    <td>${new Intl.NumberFormat("ru-RU").format(item.price)} ₽</td>
                    <td>× ${item.quantity}</td>
                    <td class="text-end">${new Intl.NumberFormat("ru-RU").format(item.price * item.quantity)} ₽</td>
                </tr>
            `).join("");
            document.getElementById("orderModalItems").innerHTML = itemsHtml;
            document.getElementById("orderModalTotal").textContent = new Intl.NumberFormat("ru-RU").format(order.total) + " ₽";

            // Показать/скрыть кнопки действий
            const btnComplete = document.getElementById("btnCompleteOrder");
            const btnCancel = document.getElementById("btnCancelOrder");

            if (order.status === "completed" || order.status === "cancelled") {
                btnComplete.style.display = "none";
                btnCancel.style.display = "none";
            } else {
                btnComplete.style.display = "";
                btnCancel.style.display = "";
            }

            const modal = new bootstrap.Modal(document.getElementById("orderModal"));
            modal.show();
        });
    });

    // Завершение заказа
    document.getElementById("btnCompleteOrder").addEventListener("click", function() {
        if (!currentOrderId) return;
        updateOrderStatus(currentOrderId, "completed");
    });

    // Отмена заказа
    document.getElementById("btnCancelOrder").addEventListener("click", function() {
        if (!currentOrderId) return;
        if (confirm("Вы уверены, что хотите отменить этот заказ?")) {
            updateOrderStatus(currentOrderId, "cancelled");
        }
    });

    // Кнопки в таблице
    document.querySelectorAll(".btn-complete-order").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.stopPropagation();
            updateOrderStatus(this.dataset.id, "completed");
        });
    });

    document.querySelectorAll(".btn-cancel-order").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.stopPropagation();
            if (confirm("Вы уверены, что хотите отменить этот заказ?")) {
                updateOrderStatus(this.dataset.id, "cancelled");
            }
        });
    });

    async function updateOrderStatus(orderId, status) {
        try {
            const response = await fetch("/api/cart/update-status", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({id: orderId, status: status})
            });
            const result = await response.json();

            if (result.success) {
                location.reload();
            } else {
                alert(result.error || "Ошибка");
            }
        } catch (e) {
            alert("Ошибка соединения");
        }
    }
});
</script>
