<?php
namespace Views;

class AdminTemplate extends BaseTemplate
{
    private static function getStyles(): string
    {
        return '
        <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #7c3aed;
            --success: #198754;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #a78bfa;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 12px rgba(0,0,0,0.08);
            --radius: 12px;
            --radius-lg: 20px;
        }
        body {
            background: #f1f5f9;
            font-family: "Segoe UI", system-ui, sans-serif;
            margin: 0;
            padding: 0;
        }
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            padding: 1.5rem 1rem;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .admin-sidebar::-webkit-scrollbar { width: 6px; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }
        .admin-sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
        .admin-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }
        .admin-logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--info));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(13,110,253,0.3);
        }
        .admin-logo-text { font-weight: 700; font-size: 1.2rem; }
        .admin-nav { list-style: none; padding: 0; margin: 0; }
        .admin-nav-item { margin-bottom: 0.35rem; }
        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1.25rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            border-radius: var(--radius);
            transition: all 0.25s;
            font-weight: 500;
            cursor: pointer;
        }
        .admin-nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(5px);
        }
        .admin-nav-link.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            box-shadow: 0 4px 15px rgba(13,110,253,0.4);
            color: #fff;
        }
        .admin-nav-icon { font-size: 1.25rem; width: 24px; text-align: center; }
        .admin-main { margin-left: 280px; padding: 2rem; min-height: 100vh; }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 2px solid var(--border);
        }
        .admin-title { font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 0; }
        .admin-subtitle { color: var(--gray); font-size: 1rem; margin-top: 0.35rem; }
        .admin-user {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.625rem 1.25rem;
            background: #fff;
            border-radius: 50px;
            box-shadow: var(--shadow);
        }
        .admin-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--info));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }
        .stat-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
        .stat-card.primary { border-top: 5px solid var(--primary); }
        .stat-card.success { border-top: 5px solid var(--success); }
        .stat-card.warning { border-top: 5px solid var(--warning); }
        .stat-card.info { border-top: 5px solid var(--info); }
        .stat-value { font-size: 2.25rem; font-weight: 800; color: #1e293b; }
        .stat-label { color: var(--gray); font-size: 0.95rem; margin-top: 0.35rem; font-weight: 500; }
        .admin-card {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            margin-bottom: 1.75rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .admin-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1.25rem;
            border-bottom: 2px solid var(--border);
            margin-bottom: 1.25rem;
        }
        .admin-card-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0; }
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th {
            background: var(--light);
            padding: 1rem 1.25rem;
            text-align: left;
            font-weight: 700;
            color: #334155;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .admin-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            color: #475569;
            vertical-align: middle;
        }
        .admin-table tr:hover { background: #f8fafc; }
        .btn-admin {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-admin:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(13,110,253,0.4); color: #fff; }
        .btn-admin.sm { padding: 0.5rem 1rem; font-size: 0.875rem; }
        .btn-admin.outline { background: transparent; border: 2px solid var(--primary); color: var(--primary); }
        .btn-admin.outline:hover { background: var(--primary); color: #fff; }
        .btn-admin.danger { background: linear-gradient(135deg, var(--danger), #b91c1c); }
        .btn-admin.success { background: linear-gradient(135deg, var(--success), #059669); }
        .admin-form { max-width: 650px; margin: 0 auto; }
        .form-label { font-weight: 600; color: #334155; margin-bottom: 0.625rem; display: block; }
        .form-control {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: all 0.25s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(13,110,253,0.15); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
        .log-list { max-height: 550px; overflow-y: auto; border: 1px solid var(--border); border-radius: var(--radius); }
        .log-item {
            display: flex;
            gap: 1.25rem;
            padding: 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: all 0.25s;
            cursor: pointer;
        }
        .log-item:hover { background: #f8fafc; }
        .log-item:last-child { border-bottom: none; }
        .log-time { min-width: 150px; color: var(--gray); font-size: 0.875rem; font-family: monospace; }
        .log-user { min-width: 130px; font-weight: 600; color: #334155; }
        .log-action { flex: 1; color: #475569; }
        .log-action strong { color: #1e293b; }
        .log-ip { color: var(--gray); font-size: 0.8rem; font-family: monospace; }
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active { display: flex; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content {
            background: #fff;
            border-radius: var(--radius-lg);
            width: 90%;
            max-width: 750px;
            max-height: 85vh;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            animation: modalSlide 0.35s ease;
        }
        @keyframes modalSlide { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header {
            padding: 1.25rem 1.75rem;
            border-bottom: 2px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--light), #fff);
        }
        .modal-title { font-weight: 700; color: #1e293b; margin: 0; font-size: 1.25rem; }
        .modal-close {
            background: none;
            border: none;
            font-size: 1.75rem;
            color: var(--gray);
            cursor: pointer;
            line-height: 1;
            transition: all 0.2s;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .modal-close:hover { background: var(--danger); color: #fff; }
        .modal-body { padding: 1.75rem; overflow-y: auto; max-height: calc(85vh - 70px); }
        .log-detail {
            font-family: monospace;
            font-size: 0.9rem;
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: var(--radius);
            white-space: pre-wrap;
            border: 1px solid var(--border);
        }
        .badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge.bg-primary { background: rgba(13,110,253,0.15); color: #8b5cf6; border: 1px solid rgba(13,110,253,0.3); }
        .badge.bg-success { background: rgba(25,135,84,0.15); color: #198754; border: 1px solid rgba(25,135,84,0.3); }
        .badge.bg-warning { background: rgba(255,193,7,0.2); color: #997404; border: 1px solid rgba(255,193,7,0.4); }
        .badge.bg-danger { background: rgba(220,53,69,0.15); color: #dc3545; border: 1px solid rgba(220,53,69,0.3); }
        .badge.bg-info { background: rgba(13,202,240,0.15); color: #055160; border: 1px solid rgba(13,202,240,0.3); }
        .badge.bg-secondary { background: rgba(108,117,125,0.15); color: #495057; border: 1px solid rgba(108,117,125,0.3); }
        .pagination { display: flex; gap: 0.375rem; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 0.625rem 1rem;
            border-radius: var(--radius);
            text-decoration: none;
            color: var(--primary);
            border: 2px solid var(--border);
            background: #fff;
            font-weight: 500;
            transition: all 0.2s;
        }
        .pagination a:hover { background: var(--primary); color: #fff; border-color: var(--primary); }
        .pagination .active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .pagination .disabled { color: var(--gray); pointer-events: none; opacity: 0.5; }
        .admin-flash {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            padding: 1.25rem 2rem;
            border-radius: var(--radius);
            color: #fff;
            font-weight: 600;
            z-index: 9999;
            animation: slideIn 0.4s ease;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        .admin-flash.success { background: var(--success); }
        .admin-flash.error { background: var(--danger); }
        @keyframes slideIn { from { opacity: 0; transform: translateX(100px); } to { opacity: 1; transform: translateX(0); } }
        @media (max-width: 992px) {
            .admin-sidebar { width: 80px; padding: 1.5rem 0.5rem; }
            .admin-logo-text, .admin-nav-text { display: none; }
            .admin-nav-link { justify-content: center; padding: 1rem; }
            .admin-main { margin-left: 80px; }
        }
        @media (max-width: 768px) {
            .form-row { grid-template-columns: 1fr; }
            .admin-header { flex-direction: column; gap: 1rem; align-items: flex-start; }
        }
        </style>';
    }

    public static function dashboard(array $products, array $users, array $orders, array $logs, array $stats): string
    {
        $template = parent::getTemplate();
        $title = 'Админ-панель — gym low cortisol';
        
        $productsHtml = '';
        foreach ($products as $product) {
            $productsHtml .= '
            <tr>
                <td><span class="badge bg-primary">'.$product['id'].'</span></td>
                <td><strong>'.htmlspecialchars($product['name']).'</strong></td>
                <td>'.number_format($product['price'], 0, '.', ' ').' ₽</td>
                <td><span class="text-muted small">'.htmlspecialchars($product['period']).'</span></td>
                <td><a href="/admin/product/edit?id='.$product['id'].'" class="btn-admin sm outline">✏️</a></td>
            </tr>';
        }

        $usersHtml = '';
        foreach ($users as $user) {
            $roleBadge = ($user['role'] ?? 'user') === 'admin'
                ? '<span class="badge bg-danger">Админ</span>'
                : '<span class="badge bg-secondary">Пользователь</span>';
            $verified = ($user['verified'] ?? false)
                ? '<span class="badge bg-success">✓</span>'
                : '<span class="badge bg-warning">✗</span>';
            $usersHtml .= '
            <tr>
                <td>'.$user['id'].'</td>
                <td>
                    <strong>'.htmlspecialchars($user['name']).'</strong><br>
                    <small class="text-muted">'.htmlspecialchars($user['email']).'</small>
                </td>
                <td>'.$verified.'</td>
                <td>'.$roleBadge.'</td>
                <td>
                    <form method="POST" action="/admin/role" class="d-inline">
                        <input type="hidden" name="user_id" value="'.$user['id'].'">
                        <select name="role" class="form-control" style="width:auto;padding:0.375rem 0.625rem;font-size:0.875rem" onchange="this.form.submit()">
                            <option value="user" '.(($user['role']??'user')==='user'?'selected':'').'>Пользователь</option>
                            <option value="admin" '.(($user['role']??'user')==='admin'?'selected':'').'>Админ</option>
                        </select>
                    </form>
                </td>
                <td><a href="/admin/user/delete?id='.$user['id'].'" class="btn-admin sm danger" onclick="return confirm(\'Удалить пользователя?\')">🗑️</a></td>
            </tr>';
        }

        $ordersHtml = '';
        foreach (array_slice(array_reverse($orders), 0, 10) as $order) {
            $ordersHtml .= '
            <tr>
                <td><span class="badge bg-info">'.htmlspecialchars($order['order_id'] ?? '—').'</span></td>
                <td>'.htmlspecialchars($order['fio'] ?? '—').'</td>
                <td><strong>'.number_format($order['total'] ?? 0, 0, '.', ' ').' ₽</strong></td>
                <td><small class="text-muted">'.htmlspecialchars($order['created_at'] ?? '—').'</small></td>
            </tr>';
        }

        $logsPreview = '';
        foreach ($logs as $log) {
            $details = !empty($log['details']) ? '<div class="log-details small text-muted">'.htmlspecialchars(json_encode($log['details'], JSON_UNESCAPED_UNICODE)).'</div>' : '';
            $logsPreview .= '
            <div class="log-item" onclick="openLogModal('.htmlspecialchars(json_encode($log, JSON_UNESCAPED_UNICODE)).')">
                <div class="log-time">'.htmlspecialchars($log['timestamp']).'</div>
                <div class="log-user">'.htmlspecialchars($log['user']).'</div>
                <div class="log-action"><strong>'.htmlspecialchars($log['action']).'</strong>'.$details.'</div>
                <div class="log-ip">'.htmlspecialchars($log['ip']).'</div>
            </div>';
        }

        $content = self::getStyles() . '
        <div class="admin-sidebar">
            <div class="admin-logo">
                <div class="admin-logo-icon">⚡</div>
                <div class="admin-logo-text">gym low cortisol</div>
            </div>
            <ul class="admin-nav">
                <li class="admin-nav-item"><a href="/admin" class="admin-nav-link '.(strpos($_SERVER['REQUEST_URI'], '/admin') === 0 && strpos($_SERVER['REQUEST_URI'], '/admin/logs') === false ? 'active' : '').'"><span class="admin-nav-icon">📊</span><span class="admin-nav-text">Панель</span></a></li>
                <li class="admin-nav-item"><a href="#products" class="admin-nav-link"><span class="admin-nav-icon">📦</span><span class="admin-nav-text">Товары</span></a></li>
                <li class="admin-nav-item"><a href="#users" class="admin-nav-link"><span class="admin-nav-icon">👥</span><span class="admin-nav-text">Пользователи</span></a></li>
                <li class="admin-nav-item"><a href="#orders" class="admin-nav-link"><span class="admin-nav-icon">📋</span><span class="admin-nav-text">Заказы</span></a></li>
                <li class="admin-nav-item"><a href="/admin/logs" class="admin-nav-link '.(strpos($_SERVER['REQUEST_URI'], '/admin/logs') !== false ? 'active' : '').'"><span class="admin-nav-icon">📜</span><span class="admin-nav-text">Логи</span></a></li>
                <li class="admin-nav-item" style="margin-top:auto"><a href="/" class="admin-nav-link"><span class="admin-nav-icon">🏠</span><span class="admin-nav-text">На сайт</span></a></li>
            </ul>
        </div>
        <main class="admin-main">
            <div class="admin-header">
                <div>
                    <h1 class="admin-title">📊 Панель управления</h1>
                    <p class="admin-subtitle">Управление товарами, пользователями и заказами</p>
                </div>
                <div class="admin-user">
                    <div class="admin-avatar">'.(isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'A').'</div>
                    <span>'.htmlspecialchars($_SESSION['user_name'] ?? 'Админ').'</span>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-value">'.count($products).'</div>
                    <div class="stat-label">Товаров</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-value">'.count($users).'</div>
                    <div class="stat-label">Пользователей</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value">'.count($orders).'</div>
                    <div class="stat-label">Заказов</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-value">'.$stats['today'].'</div>
                    <div class="stat-label">Событий сегодня</div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="admin-card" id="products">
                        <div class="admin-card-header">
                            <h3 class="admin-card-title">📦 Товары</h3>
                            <a href="/admin/product/add" class="btn-admin sm">+ Добавить</a>
                        </div>
                        <div style="overflow-x:auto">
                            <table class="admin-table">
                                <thead><tr><th>ID</th><th>Название</th><th>Цена</th><th>Период</th><th></th></tr></thead>
                                <tbody>'.$productsHtml.'</tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="admin-card" id="users">
                        <div class="admin-card-header">
                            <h3 class="admin-card-title">👥 Пользователи</h3>
                        </div>
                        <div style="overflow-x:auto">
                            <table class="admin-table">
                                <thead><tr><th>ID</th><th>Пользователь</th><th>Вериф.</th><th>Роль</th><th></th><th></th></tr></thead>
                                <tbody>'.$usersHtml.'</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-card" id="orders">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">📋 Последние заказы</h3>
                </div>
                <div style="overflow-x:auto">
                    <table class="admin-table">
                        <thead><tr><th>Заказ</th><th>Клиент</th><th>Сумма</th><th>Дата</th></tr></thead>
                        <tbody>'.$ordersHtml.'</tbody>
                    </table>
                </div>
            </div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">📜 Активность (последние 20)</h3>
                    <a href="/admin/logs" class="btn-admin sm outline">Все логи →</a>
                </div>
                <div class="log-list">'.$logsPreview.'</div>
            </div>
        </main>
        <div class="modal-overlay" id="logModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">📋 Детали события</h4>
                    <button class="modal-close" onclick="closeLogModal()">&times;</button>
                </div>
                <div class="modal-body" id="logModalBody"></div>
            </div>
        </div>
        <script>
        function openLogModal(log) {
            const body = document.getElementById("logModalBody");
            const details = log.details && Object.keys(log.details).length ? "<div class=\'log-detail\'>"+JSON.stringify(log.details, null, 2)+"</div>" : "";
            body.innerHTML = `
                <div class="mb-3"><strong>Время:</strong> ${log.timestamp}</div>
                <div class="mb-3"><strong>Пользователь:</strong> ${log.user}</div>
                <div class="mb-3"><strong>Действие:</strong> ${log.action}</div>
                <div class="mb-3"><strong>IP:</strong> ${log.ip}</div>
                ${details}
            `;
            document.getElementById("logModal").classList.add("active");
        }
        function closeLogModal() { document.getElementById("logModal").classList.remove("active"); }
        document.getElementById("logModal")?.addEventListener("click", function(e) { if(e.target === this) closeLogModal(); });
        document.addEventListener("keydown", function(e) { if(e.key === "Escape") closeLogModal(); });
        document.querySelectorAll(\'a[href^="#"]\').forEach(anchor => {
            anchor.addEventListener("click", function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute("href"));
                if (target) { target.scrollIntoView({ behavior: "smooth", block: "start" }); }
            });
        });
        </script>';

        return sprintf($template, $title, $content);
    }

    public static function logs(array $logs, array $stats, string $filterAction, string $filterUser, string $filterDate, int $page, int $totalPages, int $totalCount): string
    {
        $template = parent::getTemplate();
        $title = 'Логи — Админ-панель';
        
        $logsHtml = '';
        foreach ($logs as $log) {
            $details = !empty($log['details']) ? '<div class="log-details small text-muted mt-1">'.htmlspecialchars(json_encode($log['details'], JSON_UNESCAPED_UNICODE)).'</div>' : '';
            $logsHtml .= '
            <div class="log-item" onclick="openLogModal('.htmlspecialchars(json_encode($log, JSON_UNESCAPED_UNICODE)).')">
                <div class="log-time">'.htmlspecialchars($log['timestamp']).'</div>
                <div class="log-user">'.htmlspecialchars($log['user']).'</div>
                <div class="log-action"><strong>'.htmlspecialchars($log['action']).'</strong>'.$details.'</div>
                <div class="log-ip">'.htmlspecialchars($log['ip']).'</div>
            </div>';
        }

        if (empty($logs)) {
            $logsHtml = '<div class="text-center py-5 text-muted"><p class="mb-0">Нет записей для отображения</p></div>';
        }

        $pagination = '';
        if ($totalPages > 1) {
            $pagination .= '<div class="pagination">';
            if ($page > 1) {
                $pagination .= '<a href="?page=1'.($filterAction?'&action='.urlencode($filterAction):'').($filterUser?'&user='.urlencode($filterUser):'').($filterDate?'&date_from='.$filterDate:'').'">«</a>';
            }
            for ($i = max(1, $page-2); $i <= min($totalPages, $page+2); $i++) {
                $pagination .= $i === $page
                    ? '<span class="active">'.$i.'</span>'
                    : '<a href="?page='.$i.($filterAction?'&action='.urlencode($filterAction):'').($filterUser?'&user='.urlencode($filterUser):'').($filterDate?'&date_from='.$filterDate:'').'">'.$i.'</a>';
            }
            if ($page < $totalPages) {
                $pagination .= '<a href="?page='.$totalPages.($filterAction?'&action='.urlencode($filterAction):'').($filterUser?'&user='.urlencode($filterUser):'').($filterDate?'&date_from='.$filterDate:'').'">»</a>';
            }
            $pagination .= '</div>';
        }

        $content = self::getStyles() . '
        <div class="admin-sidebar">
            <div class="admin-logo">
                <div class="admin-logo-icon">⚡</div>
                <div class="admin-logo-text">gym low cortisol</div>
            </div>
            <ul class="admin-nav">
                <li class="admin-nav-item"><a href="/admin" class="admin-nav-link"><span class="admin-nav-icon">📊</span><span class="admin-nav-text">Панель</span></a></li>
                <li class="admin-nav-item"><a href="/admin#products" class="admin-nav-link"><span class="admin-nav-icon">📦</span><span class="admin-nav-text">Товары</span></a></li>
                <li class="admin-nav-item"><a href="/admin#users" class="admin-nav-link"><span class="admin-nav-icon">👥</span><span class="admin-nav-text">Пользователи</span></a></li>
                <li class="admin-nav-item"><a href="/admin#orders" class="admin-nav-link"><span class="admin-nav-icon">📋</span><span class="admin-nav-text">Заказы</span></a></li>
                <li class="admin-nav-item"><a href="/admin/logs" class="admin-nav-link active"><span class="admin-nav-icon">📜</span><span class="admin-nav-text">Логи</span></a></li>
                <li class="admin-nav-item" style="margin-top:auto"><a href="/" class="admin-nav-link"><span class="admin-nav-icon">🏠</span><span class="admin-nav-text">На сайт</span></a></li>
            </ul>
        </div>
        <main class="admin-main">
            <div class="admin-header">
                <div>
                    <h1 class="admin-title">📜 Журнал событий</h1>
                    <p class="admin-subtitle">Отслеживание всех действий в системе</p>
                </div>
                <div class="admin-user">
                    <div class="admin-avatar">'.(isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'A').'</div>
                    <span>'.htmlspecialchars($_SESSION['user_name'] ?? 'Админ').'</span>
                </div>
            </div>
            <div class="admin-card">
                <form method="GET" action="/admin/logs" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Действие</label>
                        <input type="text" name="action" class="form-control" value="'.htmlspecialchars($filterAction).'" placeholder="Например: Регистрация">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Пользователь</label>
                        <input type="text" name="user" class="form-control" value="'.htmlspecialchars($filterUser).'" placeholder="Имя или email">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">С даты</label>
                        <input type="date" name="date_from" class="form-control" value="'.htmlspecialchars($filterDate).'">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn-admin w-100">🔍 Фильтр</button>
                    </div>
                </form>
            </div>
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-value">'.$stats['total'].'</div>
                    <div class="stat-label">Всего записей</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-value">'.$stats['today'].'</div>
                    <div class="stat-label">Сегодня</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-value">'.count($stats['by_user']).'</div>
                    <div class="stat-label">Пользователей</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-value">'.count($stats['by_action']).'</div>
                    <div class="stat-label">Типов событий</div>
                </div>
            </div>
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">📋 Все события ('.$totalCount.' записей)</h3>
                    <form method="POST" action="/admin/logs/clear" class="d-inline">
                        <input type="hidden" name="days" value="30">
                        <button type="submit" name="clear_old" class="btn-admin sm outline" onclick="return confirm(\'Удалить логи старше 30 дней?\')">🗑️ Очистить >30 дней</button>
                        <button type="submit" name="clear_all" class="btn-admin sm danger" onclick="return confirm(\'⚠️ Удалить ВСЕ логи?\')">❌ Все</button>
                    </form>
                </div>
                <div class="log-list">'.$logsHtml.'</div>
                '.$pagination.'
            </div>
        </main>
        <div class="modal-overlay" id="logModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">📋 Детали события</h4>
                    <button class="modal-close" onclick="closeLogModal()">&times;</button>
                </div>
                <div class="modal-body" id="logModalBody"></div>
            </div>
        </div>
        <script>
        function openLogModal(log) {
            const body = document.getElementById("logModalBody");
            const details = log.details && Object.keys(log.details).length ? "<div class=\'log-detail\'>"+JSON.stringify(log.details, null, 2)+"</div>" : "";
            body.innerHTML = `
                <div class="mb-3"><strong>Время:</strong> ${log.timestamp}</div>
                <div class="mb-3"><strong>Пользователь:</strong> ${log.user}</div>
                <div class="mb-3"><strong>Действие:</strong> ${log.action}</div>
                <div class="mb-3"><strong>IP:</strong> ${log.ip}</div>
                ${details}
            `;
            document.getElementById("logModal").classList.add("active");
        }
        function closeLogModal() { document.getElementById("logModal").classList.remove("active"); }
        document.getElementById("logModal")?.addEventListener("click", function(e) { if(e.target === this) closeLogModal(); });
        document.addEventListener("keydown", function(e) { if(e.key === "Escape") closeLogModal(); });
        </script>';

        return sprintf($template, $title, $content);
    }

    public static function editProduct(array $product): string
    {
        $template = parent::getTemplate();
        $title = 'Редактирование товара';
        $featuresText = implode("\n", $product['features'] ?? []);

        $content = self::getStyles() . '
        <div class="admin-sidebar">
            <div class="admin-logo">
                <div class="admin-logo-icon">⚡</div>
                <div class="admin-logo-text">gym low cortisol</div>
            </div>
            <ul class="admin-nav">
                <li class="admin-nav-item"><a href="/admin" class="admin-nav-link"><span class="admin-nav-icon">📊</span><span class="admin-nav-text">Панель</span></a></li>
                <li class="admin-nav-item"><a href="/admin#products" class="admin-nav-link active"><span class="admin-nav-icon">📦</span><span class="admin-nav-text">Товары</span></a></li>
            </ul>
        </div>
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">✏️ Редактирование товара</h1>
                <a href="/admin" class="btn-admin outline">← Назад</a>
            </div>
            <div class="admin-card">
                <form method="POST" action="/admin/product/edit" class="admin-form">
                    <input type="hidden" name="id" value="'.$product['id'].'">
                    <div class="form-row">
                        <div>
                            <label class="form-label">Название</label>
                            <input type="text" name="name" class="form-control" value="'.htmlspecialchars($product['name']).'" required>
                        </div>
                        <div>
                            <label class="form-label">Цена (₽)</label>
                            <input type="number" name="price" class="form-control" value="'.$product['price'].'" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="4" required>'.htmlspecialchars($product['description']).'</textarea>
                    </div>
                    <div class="form-row">
                        <div>
                            <label class="form-label">Период</label>
                            <input type="text" name="period" class="form-control" value="'.htmlspecialchars($product['period']).'" required>
                        </div>
                        <div>
                            <label class="form-label">Покрытие</label>
                            <input type="text" name="coverage" class="form-control" value="'.htmlspecialchars($product['coverage']).'">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Изображение (путь)</label>
                        <input type="text" name="image" class="form-control" value="'.htmlspecialchars($product['image']).'">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Преимущества (каждое с новой строки)</label>
                        <textarea name="features" class="form-control" rows="5">'.$featuresText.'</textarea>
                    </div>
                    <button type="submit" class="btn-admin w-100">💾 Сохранить изменения</button>
                </form>
            </div>
        </main>';

        return sprintf($template, $title, $content);
    }

    public static function addProduct(): string
    {
        $template = parent::getTemplate();
        $title = 'Добавить товар';

        $content = self::getStyles() . '
        <div class="admin-sidebar">
            <div class="admin-logo">
                <div class="admin-logo-icon">⚡</div>
                <div class="admin-logo-text">gym low cortisol</div>
            </div>
            <ul class="admin-nav">
                <li class="admin-nav-item"><a href="/admin" class="admin-nav-link"><span class="admin-nav-icon">📊</span><span class="admin-nav-text">Панель</span></a></li>
                <li class="admin-nav-item"><a href="/admin#products" class="admin-nav-link active"><span class="admin-nav-icon">📦</span><span class="admin-nav-text">Товары</span></a></li>
            </ul>
        </div>
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">➕ Новый товар</h1>
                <a href="/admin" class="btn-admin outline">← Назад</a>
            </div>
            <div class="admin-card">
                <form method="POST" action="/admin/product/add" class="admin-form">
                    <div class="form-row">
                        <div>
                            <label class="form-label">Название *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Цена (₽) *</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Описание *</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-row">
                        <div>
                            <label class="form-label">Период *</label>
                            <input type="text" name="period" class="form-control" value="год" required>
                        </div>
                        <div>
                            <label class="form-label">Покрытие</label>
                            <input type="text" name="coverage" class="form-control" value="до 10 млн ₽">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Изображение</label>
                        <input type="text" name="image" class="form-control" value="/assets/images/auto.jpg">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Преимущества (с новой строки)</label>
                        <textarea name="features" class="form-control" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn-admin w-100">✨ Добавить товар</button>
                </form>
            </div>
        </main>';

        return sprintf($template, $title, $content);
    }

    public static function activateAdmin(): string
    {
        $template = parent::getTemplate();
        $title = 'Активация админ-доступа';

        $content = self::getStyles() . '
        <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f1f5f9,#e2e8f0)">
            <div class="admin-card" style="max-width:480px;width:100%;text-align:center">
                <div style="font-size:3.5rem;margin-bottom:1rem">🔐</div>
                <h2 class="admin-card-title mb-3">Активация админ-доступа</h2>
                <p class="text-muted mb-4">Введите код для получения прав администратора</p>
                <form method="POST" action="/admin/activate">
                    <div class="mb-4">
                        <input type="text" name="admin_code" class="form-control" placeholder="Код активации" required style="text-align:center;font-size:1.35rem;letter-spacing:3px">
                    </div>
                    <button type="submit" class="btn-admin w-100">🚀 Активировать</button>
                </form>
                <p class="text-muted small mt-4">
                    <a href="/" style="color:var(--primary);text-decoration:none">← Вернуться на сайт</a>
                </p>
            </div>
        </div>';

        return sprintf($template, $title, $content);
    }
}