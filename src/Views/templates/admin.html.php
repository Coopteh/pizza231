<?php
/**
 * Шаблон страницы админ-панели (дашборд)
 * Доступные переменные:
 * - $stats - массив со статистикой
 * - $texts - массив текстов из storage/templates/admin.json
 */

// Значения по умолчанию
$texts = $texts ?? [];
$dashboardText = $texts['dashboard'] ?? [];
$cardsText = $texts['cards'] ?? [];

$revenueFormatted = number_format($stats['total_revenue'] ?? 0, 0, '.', ' ');
?>

<div class="container py-4">
    <h1 class="mb-4"><?= htmlspecialchars($dashboardText['title'] ?? 'Панель администратора') ?></h1>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title"><?= htmlspecialchars($dashboardText['users'] ?? 'Пользователи') ?></h6>
                            <h2 class="mb-0"><?= $stats['total_users'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title"><?= htmlspecialchars($dashboardText['products'] ?? 'Товары') ?></h6>
                            <h2 class="mb-0"><?= $stats['total_products'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-box fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title"><?= htmlspecialchars($dashboardText['orders'] ?? 'Заказы') ?></h6>
                            <h2 class="mb-0"><?= $stats['total_orders'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-cart fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title"><?= htmlspecialchars($dashboardText['revenue'] ?? 'Выручка') ?></h6>
                            <h2 class="mb-0"><?= $revenueFormatted ?> ₽</h2>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i><?= htmlspecialchars($cardsText['catalogTitle'] ?? 'Управление каталогом') ?></h5>
                </div>
                <div class="card-body">
                    <p><?= htmlspecialchars($cardsText['catalogDesc'] ?? 'Добавление, редактирование и удаление товаров') ?></p>
                    <a href="/admin/catalog" class="btn btn-success"><?= htmlspecialchars($cardsText['catalogLink'] ?? 'Перейти к каталогу') ?></a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-cart me-2"></i><?= htmlspecialchars($cardsText['ordersTitle'] ?? 'Управление заказами') ?></h5>
                </div>
                <div class="card-body">
                    <p><?= htmlspecialchars($cardsText['ordersDesc'] ?? 'Просмотр и управление заказами клиентов') ?></p>
                    <a href="/admin/orders" class="btn btn-primary"><?= htmlspecialchars($cardsText['ordersLink'] ?? 'Перейти к заказам') ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i><?= htmlspecialchars($cardsText['usersTitle'] ?? 'Управление пользователями') ?></h5>
                </div>
                <div class="card-body">
                    <p><?= htmlspecialchars($cardsText['usersDesc'] ?? 'Просмотр списка зарегистрированных пользователей') ?></p>
                    <a href="/admin/users" class="btn btn-primary"><?= htmlspecialchars($cardsText['usersLink'] ?? 'Перейти к пользователям') ?></a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($cardsText['logsTitle'] ?? 'Логи ошибок') ?></h5>
                </div>
                <div class="card-body">
                    <p><?= htmlspecialchars($cardsText['logsDesc'] ?? 'Просмотр и управление логами ошибок сайта') ?></p>
                    <a href="/admin/logs" class="btn btn-warning"><?= htmlspecialchars($cardsText['logsLink'] ?? 'Перейти к логам') ?></a>
                </div>
            </div>
        </div>
    </div>

</div>
