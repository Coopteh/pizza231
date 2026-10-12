<?php
namespace App\Controllers;

use App\Views\BaseTemplate;
use App\Services\UserDBStorage;

class ProfileController
{
    public function get(): string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (empty($_SESSION['user_id'])) {
            $_SESSION['flash'] = 'Пожалуйста, войдите в систему';
            header('Location: /login');
            exit;
        }
        
        $storage = new UserDBStorage();
        $user = $storage->findById($_SESSION['user_id']);
        $orders = $storage->getUserOrders($_SESSION['user_id']);
        
        ob_start();
        ?>
        <div class="container mt-5">
            <h2 class="mb-4 animate-slide-in">
                <i class="bi bi-person-badge-fill text-primary me-2"></i>
                Личный кабинет
            </h2>
            
            <?php if ($user): ?>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card shadow-lg border-0 animate-fade-in hover-lift">
                            <div class="card-header bg-gradient-primary text-white py-3">
                                <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Профиль</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="avatar-circle mb-3 mx-auto">
                                    <i class="bi bi-person fs-1"></i>
                                </div>
                                <h5 class="card-title"><?= htmlspecialchars($user['username']) ?></h5>
                                <p class="text-muted small"><?= htmlspecialchars($user['email']) ?></p>
                                
                                <div class="list-group list-group-flush mt-3">
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span><i class="bi bi-calendar-check me-2 text-success"></i>Регистрация</span>
                                        <small class="text-muted"><?= date('d.m.Y', strtotime($user['created_at'])) ?></small>
                                    </div>
                                    <?php if (!empty($user['last_login'])): ?>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span><i class="bi bi-clock-history me-2 text-info"></i>Последний вход</span>
                                        <small class="text-muted"><?= date('d.m.Y H:i', strtotime($user['last_login'])) ?></small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <a href="/settings" class="btn btn-outline-primary btn-sm mt-3 w-100">
                                    <i class="bi bi-gear me-1"></i>Настройки
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-8">
                        <div class="card shadow-lg border-0 animate-fade-in" style="animation-delay: 0.1s">
                            <div class="card-header bg-gradient-success text-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-bag-check-fill me-2"></i>Мои заказы</h5>
                                <span class="badge bg-white text-success"><?= count($orders) ?></span>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($orders)): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($orders as $index => $order): ?>
                                            <div class="list-group-item list-group-item-action animate-slide-in" 
                                                 style="animation-delay: <?= 0.1 + $index * 0.05 ?>s">
                                                <div class="d-flex w-100 justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">
                                                            <i class="bi bi-receipt me-1"></i>
                                                            Заказ #<?= htmlspecialchars(substr($order['order_id'], -6)) ?>
                                                        </h6>
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar3 me-1"></i>
                                                            <?= date('d.m.Y', strtotime($order['created_at'])) ?>
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-<?= $order['status'] === 'completed' ? 'success' : 'warning' ?> 
                                                          animate-pulse">
                                                        <?= $order['status'] === 'completed' ? '✅ Выполнен' : '⏳ В обработке' ?>
                                                    </span>
                                                </div>
                                                
                                                <?php if (!empty($order['items'])): ?>
                                                    <ul class="list-unstyled small text-muted mt-2 mb-2">
                                                        <?php foreach (array_slice($order['items'], 0, 3) as $item): ?>
                                                            <li>• <?= htmlspecialchars($item['product_name']) ?> 
                                                                × <?= (int)$item['quantity'] ?></li>
                                                        <?php endforeach; ?>
                                                        <?php if (count($order['items']) > 3): ?>
                                                            <li class="fst-italic">+ ещё <?= count($order['items']) - 3 ?> товара</li>
                                                        <?php endif; ?>
                                                    </ul>
                                                <?php endif; ?>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong class="text-success fs-5">
                                                        <?= number_format($order['all_sum'], 0, '.', ' ') ?> ₽
                                                    </strong>
                                                    <div>
                                                        <a href="/products" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-arrow-repeat me-1"></i>Повторить
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5 animate-fade-in">
                                        <i class="bi bi-bag-x fs-1 text-muted mb-3 d-block"></i>
                                        <p class="text-muted">У вас пока нет заказов</p>
                                        <a href="/products" class="btn btn-primary">
                                            <i class="bi bi-search me-1"></i>Выбрать тур
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning animate-shake">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Не удалось загрузить данные пользователя
                </div>
            <?php endif; ?>
            
            <a href="/" class="btn btn-outline-secondary mt-4 animate-fade-in" style="animation-delay: 0.3s">
                <i class="bi bi-house-door me-1"></i>На главную
            </a>
        </div>
        
        <style>
            @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
            @keyframes slideIn { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
            @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
            @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
            
            .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; opacity: 0; }
            .animate-slide-in { animation: slideIn 0.4s ease-out forwards; opacity: 0; }
            .animate-pulse { animation: pulse 2s infinite; }
            .animate-shake { animation: shake 0.5s ease-in-out; }
            
            .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
            .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 1rem 3rem rgba(0,0,0,.15)!important; }
            
            .avatar-circle {
                width: 100px; height: 100px;
                border-radius: 50%;
                background: linear-gradient(135deg, #0A3D62, #00A896);
                display: flex; align-items: center; justify-content: center;
                color: white;
            }
            
            .bg-gradient-primary { background: linear-gradient(135deg, #0A3D62, #1a5f8a); }
            .bg-gradient-success { background: linear-gradient(135deg, #00A896, #00d4aa); }
        </style>
        <?php
        $content = ob_get_clean();
        return sprintf(BaseTemplate::getTemplate(), 'Личный кабинет - По мультивселенной', $content);
    }
}