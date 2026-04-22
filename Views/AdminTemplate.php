<?php

namespace Views;

use Lib\Language;

class AdminTemplate extends BaseTemplate
{
    // ✅ Дашборд администратора
    public static function getDashboardTemplate(array $stats): string
    {
        $template = parent::getTemplate();
        $title = Language::get('admin_dashboard') . ' - ' . Language::get('site_name');
        $content = '
<style>
/* 🌙 ТЁМНАЯ ТЕМА: Стили для админ-панели */
.admin-container {
max-width: 1400px;
margin: 2rem auto;
padding: 0 1rem;
}
.admin-header {
background: linear-gradient(135deg, var(--primary), var(--primary-dark));
color: #fff;
padding: 2rem;
border-radius: 12px;
margin-bottom: 2rem;
transition: background 0.3s ease;
}
.stats-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
gap: 1rem;
margin-bottom: 2rem;
}
.stat-card {
background: var(--surface);
padding: 1.5rem;
border-radius: 12px;
border: 1px solid var(--border);
text-align: center;
transition: all 0.3s ease;
}
.stat-card:hover {
transform: translateY(-3px);
box-shadow: var(--shadow-lg);
}
.stat-number {
font-size: 2rem;
font-weight: 700;
color: var(--primary);
transition: color 0.3s ease;
}
.stat-label {
color: var(--text-muted);
margin-top: 0.5rem;
font-size: 0.85rem;
transition: color 0.3s ease;
}
.admin-nav {
display: flex;
gap: 0.75rem;
margin-bottom: 2rem;
flex-wrap: wrap;
overflow-x: auto;
padding-bottom: 0.5rem;
}
.admin-nav a {
padding: 0.625rem 1rem;
background: var(--surface);
border: 1px solid var(--border);
border-radius: 6px;
text-decoration: none;
color: var(--text);
font-weight: 500;
white-space: nowrap;
min-height: 44px;
display: inline-flex;
align-items: center;
transition: all 0.2s;
}
.admin-nav a:hover {
border-color: var(--primary);
color: var(--primary);
}
.admin-nav a.active {
background: var(--primary);
color: #fff;
border-color: var(--primary);
}
.recent-orders {
background: var(--surface);
border-radius: 12px;
padding: 1.5rem;
border: 1px solid var(--border);
overflow-x: auto;
transition: all 0.3s ease;
}
.order-row {
display: grid;
grid-template-columns: 1fr 2fr 1fr 1fr 1fr;
gap: 0.75rem;
padding: 0.75rem;
border-bottom: 1px solid var(--border);
align-items: center;
min-width: 600px;
}
.order-row:last-child {
border-bottom: none;
}
.order-row.header {
background: var(--surface-hover);
font-weight: 600;
}
.status-badge {
padding: 0.25rem 0.5rem;
border-radius: 4px;
color: #fff;
font-size: 0.75rem;
}
@media (max-width: 768px) {
.admin-container {
margin: 1rem auto;
}
.admin-header {
padding: 1.25rem;
}
.stats-grid {
grid-template-columns: repeat(2, 1fr);
gap: 0.75rem;
}
.stat-number {
font-size: 1.75rem;
}
.order-row {
grid-template-columns: 1fr 1fr;
gap: 0.5rem;
}
}
@media (max-width: 576px) {
.admin-nav {
flex-wrap: nowrap;
-webkit-overflow-scrolling: touch;
}
.stats-grid {
grid-template-columns: 1fr;
}
.stat-card {
padding: 1.25rem;
}
.order-row {
grid-template-columns: 1fr;
min-width: auto;
}
.order-row > div {
text-align: center;
padding: 0.25rem 0;
}
}
</style>
<section class="container py-5">
<div class="admin-container">
<div class="admin-header">
<h1 class="mb-2">' . Language::get('admin_dashboard') . '</h1>
<p class="mb-0 opacity-75">' . Language::get('admin_orders') . '</p>
</div>
<div class="stats-grid">
<div class="stat-card">
<div class="stat-number">' . $stats['totalOrders'] . '</div>
<div class="stat-label">' . Language::get('admin_total_orders') . '</div>
</div>
<div class="stat-card">
<div class="stat-number">' . number_format($stats['totalRevenue'], 0, '.', ' ') . ' ₽</div>
<div class="stat-label">' . Language::get('admin_total_revenue') . '</div>
</div>
<div class="stat-card">
<div class="stat-number">' . $stats['pendingOrders'] . '</div>
<div class="stat-label">' . Language::get('admin_pending_orders') . '</div>
</div>
<div class="stat-card">
<div class="stat-number">' . $stats['totalUsers'] . '</div>
<div class="stat-label">' . Language::get('admin_total_users') . '</div>
</div>
</div>
<div class="admin-nav">
<a href="/admin" class="active">📊 ' . Language::get('admin_dashboard') . '</a>
<a href="/admin/orders">📦 ' . Language::get('admin_orders') . '</a>
<a href="/admin/users">👥 ' . Language::get('admin_users') . '</a>
<a href="/admin/logs">📋 ' . Language::get('admin_logs') . '</a>
<a href="/">🏠 ' . Language::get('nav_home') . '</a>
</div>
<div class="recent-orders">
<h3 style="margin-bottom: 1.5rem; color: var(--text);">' . Language::get('admin_recent_orders') . '</h3>
<div class="order-row header">
<div>' . Language::get('admin_order_id') . '</div>
<div>' . Language::get('admin_customer') . '</div>
<div>' . Language::get('admin_amount') . '</div>
<div>' . Language::get('admin_status') . '</div>
<div>' . Language::get('admin_date') . '</div>
</div>';
        $statusColors = [
        'pending' => '#ed8936',
        'paid' => '#4299e1',
        'shipped' => '#48bb78',
        'completed' => '#38a169',
        'cancelled' => '#e53e3e'
        ];
        $statusNames = [
        'pending' => Language::get('admin_order_pending'),
        'paid' => Language::get('admin_order_paid'),
        'shipped' => Language::get('admin_order_shipped'),
        'completed' => Language::get('admin_order_completed'),
        'cancelled' => Language::get('admin_order_cancelled')
        ];
        foreach ($stats['recentOrders'] as $order) {
            $content .= '<div class="order-row">
<div><a href="/admin/order?id=' . htmlspecialchars($order['id']) . '" style="color: var(--primary);">' . htmlspecialchars($order['id']) . '</a></div>
<div>' . htmlspecialchars($order['name']) . '</div>
<div>' . number_format($order['total'], 0, '.', ' ') . ' ₽</div>
<div><span class="status-badge" style="background: ' . $statusColors[$order['status']] . '">' . $statusNames[$order['status']] . '</span></div>
<div>' . date('d.m.Y', strtotime($order['created_at'])) . '</div>
</div>';
        }
        $content .= '</div></div></section>';
        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    // ✅ СПИСОК ЗАКАЗОВ
    public static function getOrdersTemplate(array $orders, string $status = 'all', string $search = ''): string
    {
        $template = parent::getTemplate();
        $title = Language::get('admin_orders') . ' - ' . Language::get('site_name');
        $content = '
<style>
.admin-container {
max-width: 1400px;
margin: 2rem auto;
padding: 0 1rem;
}
.admin-header {
background: linear-gradient(135deg, var(--primary), var(--primary-dark));
color: #fff;
padding: 2rem;
border-radius: 12px;
margin-bottom: 2rem;
transition: background 0.3s ease;
}
.admin-nav {
display: flex;
gap: 0.75rem;
margin-bottom: 2rem;
flex-wrap: wrap;
overflow-x: auto;
padding-bottom: 0.5rem;
}
.admin-nav a {
padding: 0.625rem 1rem;
background: var(--surface);
border: 1px solid var(--border);
border-radius: 6px;
text-decoration: none;
color: var(--text);
font-weight: 500;
white-space: nowrap;
min-height: 44px;
display: inline-flex;
align-items: center;
transition: all 0.2s;
}
.admin-nav a.active {
background: var(--primary);
color: #fff;
border-color: var(--primary);
}
.filters {
display: flex;
gap: 1rem;
margin-bottom: 2rem;
flex-wrap: wrap;
}
.filters select,
.filters input {
padding: 0.75rem 1rem;
border: 1px solid var(--border);
border-radius: 6px;
background: var(--surface);
color: var(--text);
}
.filters button {
padding: 0.75rem 1.5rem;
background: var(--primary);
color: #fff;
border: none;
border-radius: 6px;
cursor: pointer;
}
.orders-table {
background: var(--surface);
border-radius: 12px;
overflow: hidden;
border: 1px solid var(--border);
}
.order-row {
display: grid;
grid-template-columns: 1fr 2fr 1fr 1fr 1fr auto;
gap: 1rem;
padding: 1rem;
border-bottom: 1px solid var(--border);
align-items: center;
min-width: 700px;
}
.order-row:last-child {
border-bottom: none;
}
.order-row.header {
background: var(--surface-hover);
font-weight: 600;
}
.status-badge {
padding: 0.25rem 0.75rem;
border-radius: 4px;
color: #fff;
font-size: 0.85rem;
}
.btn-view {
padding: 0.5rem 1rem;
background: var(--primary);
color: #fff;
border-radius: 4px;
text-decoration: none;
font-size: 0.9rem;
}
@media (max-width: 768px) {
.filters {
flex-direction: column;
}
.order-row {
grid-template-columns: 1fr 1fr;
gap: 0.5rem;
}
}
@media (max-width: 576px) {
.admin-nav {
flex-wrap: nowrap;
}
.order-row {
grid-template-columns: 1fr;
min-width: auto;
}
.order-row > div {
text-align: center;
padding: 0.25rem 0;
}
}
</style>
<section class="container py-5">
<div class="admin-container">
<div class="admin-header">
<h1>' . Language::get('admin_orders') . '</h1>
</div>
<div class="admin-nav">
<a href="/admin">📊 ' . Language::get('admin_dashboard') . '</a>
<a href="/admin/orders" class="active">📦 ' . Language::get('admin_orders') . '</a>
<a href="/admin/users">👥 ' . Language::get('admin_users') . '</a>
<a href="/admin/logs">📋 ' . Language::get('admin_logs') . '</a>
<a href="/">🏠 ' . Language::get('nav_home') . '</a>
</div>
<form class="filters" method="GET" action="/admin/orders">
<select name="status">
<option value="all" ' . ($status === 'all' ? 'selected' : '') . '>' . Language::get('admin_all_statuses') . '</option>
<option value="pending" ' . ($status === 'pending' ? 'selected' : '') . '>' . Language::get('admin_order_pending') . '</option>
<option value="paid" ' . ($status === 'paid' ? 'selected' : '') . '>' . Language::get('admin_order_paid') . '</option>
<option value="shipped" ' . ($status === 'shipped' ? 'selected' : '') . '>' . Language::get('admin_order_shipped') . '</option>
<option value="completed" ' . ($status === 'completed' ? 'selected' : '') . '>' . Language::get('admin_order_completed') . '</option>
<option value="cancelled" ' . ($status === 'cancelled' ? 'selected' : '') . '>' . Language::get('admin_order_cancelled') . '</option>
</select>
<input type="text" name="search" placeholder="' . Language::get('admin_search') . '" value="' . htmlspecialchars($search) . '">
<button type="submit">' . Language::get('admin_filter') . '</button>
</form>
<div class="orders-table">
<div class="order-row header">
<div>' . Language::get('admin_order_id') . '</div>
<div>' . Language::get('admin_customer') . '</div>
<div>' . Language::get('admin_amount') . '</div>
<div>' . Language::get('admin_status') . '</div>
<div>' . Language::get('admin_date') . '</div>
<div>' . Language::get('admin_actions') . '</div>
</div>';
        $statusColors = [
        'pending' => '#ed8936',
        'paid' => '#4299e1',
        'shipped' => '#48bb78',
        'completed' => '#38a169',
        'cancelled' => '#e53e3e'
        ];
        $statusNames = [
        'pending' => Language::get('admin_order_pending'),
        'paid' => Language::get('admin_order_paid'),
        'shipped' => Language::get('admin_order_shipped'),
        'completed' => Language::get('admin_order_completed'),
        'cancelled' => Language::get('admin_order_cancelled')
        ];
        foreach ($orders as $order) {
            $content .= '<div class="order-row">
<div>' . htmlspecialchars($order['id']) . '</div>
<div>' . htmlspecialchars($order['name']) . '<br><small>' . htmlspecialchars($order['email']) . '</small></div>
<div>' . number_format($order['total'], 0, '.', ' ') . ' ₽</div>
<div><span class="status-badge" style="background: ' . $statusColors[$order['status']] . '">' . $statusNames[$order['status']] . '</span></div>
<div>' . date('d.m.Y', strtotime($order['created_at'])) . '</div>
<div><a href="/admin/order?id=' . htmlspecialchars($order['id']) . '" class="btn-view">' . Language::get('admin_view') . '</a></div>
</div>';
        }
        $content .= '</div></div></section>';
        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    // ✅ ============================================================================
    // ✅ ДЕТАЛИ ЗАКАЗА (🔧 ИСПРАВЛЕНО: Обработка массивов для многоязычности)
    // ✅ ============================================================================
    public static function getOrderDetailTemplate(array $order): string
    {
        $template = parent::getTemplate();
        $title = Language::get('admin_order_id') . ' ' . $order['id'] . ' - ' . Language::get('site_name');
        $statusOptions = [
        'pending' => Language::get('admin_order_pending'),
        'paid' => Language::get('admin_order_paid'),
        'shipped' => Language::get('admin_order_shipped'),
        'completed' => Language::get('admin_order_completed'),
        'cancelled' => Language::get('admin_order_cancelled')
        ];

        // 🌐 ============================================================================
        // 🔧 ИЗМЕНЕНИЕ #1: Получаем текущий язык для отображения
        // 🌐 ============================================================================
        $lang = Language::getCurrentLang();

        // 🌐 ============================================================================
        // 🔧 ИЗМЕНЕНИЕ #2: Вспомогательная функция для получения текста на нужном языке
        // 🌐 ============================================================================
        $getText = function ($field, $default = '') use ($lang) {
            if (is_array($field)) {
                return $field[$lang] ?? $field['ru'] ?? $default;
            }
            return $field;
        };

        $itemsHtml = '';
        if (!empty($order['items']) && is_array($order['items'])) {
            foreach ($order['items'] as $item) {
                if (is_array($item)) {
                    // 🌐 ============================================================================
                    // 🔧 ИЗМЕНЕНИЕ #3: Получаем название и длительность на нужном языке
                    // 🌐 ============================================================================
                    $courseTitle = $getText($item['title'], '');
                    $courseDuration = $getText($item['duration'], '');
                    $coursePrice = is_array($item['price']) ? '' : (string)$item['price'];

                    $itemsHtml .= '<tr>
                <td>' . htmlspecialchars($courseTitle) . '</td>
                <td>' . htmlspecialchars($courseDuration) . '</td>
                <td>' . htmlspecialchars($coursePrice) . '</td>
            </tr>';
                }
            }
        }

        // 🔧 ============================================================================
        // 🔧 ИЗМЕНЕНИЕ #4: Проверка типов перед htmlspecialchars для всех полей заказа
        // 🔧 ============================================================================
        $orderName = is_string($order['name']) ? $order['name'] : '';
        $orderEmail = is_string($order['email']) ? $order['email'] : '';
        $orderPhone = is_string($order['phone']) ? $order['phone'] : '';
        $orderIp = is_string($order['ip']) ? $order['ip'] : '';
        $orderPaymentMethod = is_string($order['payment_method']) ? $order['payment_method'] : '';
        $orderComment = is_string($order['comment']) ? $order['comment'] : '';
        $orderStatus = is_string($order['status']) ? $order['status'] : 'pending';
        $orderCreatedAt = is_string($order['created_at']) ? $order['created_at'] : '';

        $content = '
<style>
.admin-container {
max-width: 1000px;
margin: 2rem auto;
padding: 0 1rem;
}
.admin-header {
background: linear-gradient(135deg, var(--primary), var(--primary-dark));
color: #fff;
padding: 2rem;
border-radius: 12px;
margin-bottom: 2rem;
}
.admin-nav {
display: flex;
gap: 0.75rem;
margin-bottom: 2rem;
flex-wrap: wrap;
}
.admin-nav a {
padding: 0.625rem 1rem;
background: var(--surface);
border: 1px solid var(--border);
border-radius: 6px;
text-decoration: none;
color: var(--text);
}
.order-detail {
background: var(--surface);
border-radius: 12px;
padding: 2rem;
border: 1px solid var(--border);
}
.detail-section {
margin-bottom: 2rem;
}
.detail-section h3 {
color: var(--text);
margin-bottom: 1rem;
padding-bottom: 0.5rem;
border-bottom: 2px solid var(--border);
}
.info-grid {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 1rem;
}
.info-item {
padding: 0.75rem;
background: var(--surface-hover);
border-radius: 6px;
}
.info-label {
color: var(--text-muted);
font-size: 0.9rem;
}
.info-value {
font-weight: 600;
color: var(--text);
}
.orders-table {
width: 100%;
border-collapse: collapse;
}
.orders-table th,
.orders-table td {
padding: 1rem;
text-align: left;
border-bottom: 1px solid var(--border);
}
.orders-table th {
background: var(--surface-hover);
}
.status-form {
margin-top: 1rem;
}
.status-form select {
padding: 0.75rem 1rem;
border: 1px solid var(--border);
border-radius: 6px;
margin-right: 0.5rem;
background: var(--surface);
color: var(--text);
}
.status-form button {
padding: 0.75rem 1.5rem;
background: var(--primary);
color: #fff;
border: none;
border-radius: 6px;
cursor: pointer;
}
@media (max-width: 768px) {
.info-grid {
grid-template-columns: 1fr;
}
}
</style>
<section class="container py-5">
<div class="admin-container">
<div class="admin-header">
<h1>' . Language::get('admin_order_id') . ' ' . htmlspecialchars($order['id']) . '</h1>
</div>
<div class="admin-nav">
<a href="/admin">📊 ' . Language::get('admin_dashboard') . '</a>
<a href="/admin/orders">📦 ' . Language::get('admin_orders') . '</a>
<a href="/admin/users">👥 ' . Language::get('admin_users') . '</a>
<a href="/admin/logs">📋 ' . Language::get('admin_logs') . '</a>
<a href="/">🏠 ' . Language::get('nav_home') . '</a>
</div>
<div class="order-detail">
<div class="detail-section">
<h3>' . Language::get('checkout_contact_info') . '</h3>
<div class="info-grid">
<div class="info-item">
<div class="info-label">' . Language::get('checkout_name') . '</div>
<div class="info-value">' . htmlspecialchars($orderName) . '</div>
</div>
<div class="info-item">
<div class="info-label">Email</div>
<div class="info-value"><a href="mailto:' . htmlspecialchars($orderEmail) . '">' . htmlspecialchars($orderEmail) . '</a></div>
</div>
<div class="info-item">
<div class="info-label">' . Language::get('checkout_phone') . '</div>
<div class="info-value"><a href="tel:' . htmlspecialchars($orderPhone) . '">' . htmlspecialchars($orderPhone) . '</a></div>
</div>
<div class="info-item">
<div class="info-label">IP</div>
<div class="info-value">' . htmlspecialchars($orderIp) . '</div>
</div>
</div>
</div>
<div class="detail-section">
<h3>' . Language::get('order_email_courses') . '</h3>
<table class="orders-table">
<thead>
<tr>
<th>' . Language::get('order_email_course') . '</th>
<th>' . Language::get('course_duration') . '</th>
<th>' . Language::get('cart_total') . '</th>
</tr>
</thead>
<tbody>' . $itemsHtml . '</tbody>
<tfoot>
<tr>
<td colspan="2" style="text-align:right;font-weight:600">' . Language::get('cart_total') . ':</td>
<td style="font-weight:700;color:var(--primary)">' . number_format($order['total'], 0, '.', ' ') . ' ₽</td>
</tr>
</tfoot>
</table>
</div>
<div class="detail-section">
<h3>' . Language::get('admin_status') . '</h3>
<div class="info-grid">
<div class="info-item">
<div class="info-label">' . Language::get('admin_status') . '</div>
<div class="info-value">' . htmlspecialchars($orderStatus) . '</div>
</div>
<div class="info-item">
<div class="info-label">' . Language::get('admin_date') . '</div>
<div class="info-value">' . htmlspecialchars($orderCreatedAt) . '</div>
</div>
</div>
<form class="status-form" method="POST" action="/admin/order/update">
<input type="hidden" name="order_id" value="' . htmlspecialchars($order['id']) . '">
<select name="status">';
        foreach ($statusOptions as $value => $label) {
            $selected = $orderStatus === $value ? 'selected' : '';
            $content .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
        }
        $content .= '</select><button type="submit">' . Language::get('admin_update_status') . '</button></form></div>';
        if (!empty($orderComment)) {
            $content .= '<div class="detail-section"><h3>' . Language::get('checkout_comment') . '</h3><div class="info-item"><p style="margin:0">' . htmlspecialchars($orderComment) . '</p></div></div>';
        }
        $content .= '</div></div></section>';
        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    // ✅ СПИСОК ПОЛЬЗОВАТЕЛЕЙ
    public static function getUsersTemplate(array $users): string
    {
        $template = parent::getTemplate();
        $title = Language::get('admin_users') . ' - ' . Language::get('site_name');
        $content = '
<style>
.admin-container {
max-width: 1400px;
margin: 2rem auto;
padding: 0 1rem;
}
.admin-header {
background: linear-gradient(135deg, var(--primary), var(--primary-dark));
color: #fff;
padding: 2rem;
border-radius: 12px;
margin-bottom: 2rem;
}
.admin-nav {
display: flex;
gap: 0.75rem;
margin-bottom: 2rem;
flex-wrap: wrap;
}
.admin-nav a {
padding: 0.625rem 1rem;
background: var(--surface);
border: 1px solid var(--border);
border-radius: 6px;
text-decoration: none;
color: var(--text);
}
.admin-nav a.active {
background: var(--primary);
color: #fff;
}
.users-table {
background: var(--surface);
border-radius: 12px;
overflow: hidden;
border: 1px solid var(--border);
}
.user-row {
display: grid;
grid-template-columns: 1fr 2fr 1fr 1fr 1fr auto;
gap: 1rem;
padding: 1rem;
border-bottom: 1px solid var(--border);
align-items: center;
min-width: 700px;
}
.user-row:last-child {
border-bottom: none;
}
.user-row.header {
background: var(--surface-hover);
font-weight: 600;
}
.role-badge {
padding: 0.25rem 0.75rem;
border-radius: 4px;
color: #fff;
font-size: 0.85rem;
}
.role-admin {
background: var(--danger);
}
.role-user {
background: var(--primary);
}
.verified-badge {
color: var(--success);
}
.unverified-badge {
color: var(--warning);
}
.btn-delete {
padding: 0.5rem 1rem;
background: var(--danger);
color: #fff;
border: none;
border-radius: 4px;
cursor: pointer;
}
@media (max-width: 768px) {
.user-row {
grid-template-columns: 1fr 1fr;
gap: 0.5rem;
}
}
@media (max-width: 576px) {
.admin-nav {
flex-wrap: nowrap;
}
.user-row {
grid-template-columns: 1fr;
min-width: auto;
}
.user-row > div {
text-align: center;
padding: 0.25rem 0;
}
}
</style>
<section class="container py-5">
<div class="admin-container">
<div class="admin-header">
<h1>' . Language::get('admin_users') . '</h1>
</div>
<div class="admin-nav">
<a href="/admin">📊 ' . Language::get('admin_dashboard') . '</a>
<a href="/admin/orders">📦 ' . Language::get('admin_orders') . '</a>
<a href="/admin/users" class="active">👥 ' . Language::get('admin_users') . '</a>
<a href="/admin/logs">📋 ' . Language::get('admin_logs') . '</a>
<a href="/">🏠 ' . Language::get('nav_home') . '</a>
</div>
<div class="users-table">
<div class="user-row header">
<div>ID</div>
<div>' . Language::get('admin_customer') . '</div>
<div>' . Language::get('auth_role') . '</div>
<div>' . Language::get('auth_verify_email') . '</div>
<div>' . Language::get('admin_date') . '</div>
<div>' . Language::get('admin_actions') . '</div>
</div>';
        foreach ($users as $user) {
            $roleClass = $user['role'] === 'admin' ? 'role-admin' : 'role-user';
            $roleName = $user['role'] === 'admin' ? Language::get('auth_role_admin') : Language::get('auth_role_user');
            $verifiedBadge = $user['verified']
            ? '<span class="verified-badge">✓ ' . Language::get('admin_verified') . '</span>'
            : '<span class="unverified-badge">○ ' . Language::get('admin_not_verified') . '</span>';
            $deleteButton = $user['id'] == 1 ? '' :
            '<form method="POST" action="/admin/user/delete" style="display:inline;" onsubmit="return confirm(\'Удалить пользователя?\')">' .
            '<input type="hidden" name="user_id" value="' . $user['id'] . '">' .
            '<button type="submit" class="btn-delete">' . Language::get('admin_delete_user') . '</button>' .
            '</form>';
            $content .= '<div class="user-row">
<div>' . $user['id'] . '</div>
<div>' . htmlspecialchars($user['name']) . '<br><small>' . htmlspecialchars($user['email']) . '</small></div>
<div><span class="role-badge ' . $roleClass . '">' . $roleName . '</span></div>
<div>' . $verifiedBadge . '</div>
<div>' . date('d.m.Y', strtotime($user['created_at'])) . '</div>
<div>' . $deleteButton . '</div>
</div>';
        }
        $content .= '</div></div></section>';
        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    // 📝 LOGGER: Просмотр логов
    public static function getLogsTemplate(array $logs, array $stats, int $limit): string
    {
        $template = parent::getTemplate();
        $title = Language::get('admin_logs') . ' - ' . Language::get('site_name');
        $successHtml = isset($_GET['success']) && $_GET['success'] === 'cleared'
        ? '<div class="alert alert-success">' . Language::get('admin_log_cleared') . '</div>'
        : '';
        $logsHtml = '';
        foreach ($logs as $log) {
            $logClass = 'log-entry-default';
            if (strpos($log, '[ERROR]') !== false) {
                $logClass = 'log-entry-error';
            } elseif (strpos($log, '[WARNING]') !== false) {
                $logClass = 'log-entry-warning';
            } elseif (strpos($log, '[CRITICAL]') !== false) {
                $logClass = 'log-entry-critical';
            } elseif (strpos($log, '[INFO]') !== false) {
                $logClass = 'log-entry-info';
            } elseif (strpos($log, '[DEBUG]') !== false) {
                $logClass = 'log-entry-debug';
            }
            $logsHtml .= '<div class="log-entry ' . $logClass . '">'
            . htmlspecialchars($log) . '</div>';
        }
        $clearConfirm = Language::get('admin_log_clear_confirm');
        $content = '
<style>
.admin-container { max-width: 1400px; margin: 2rem auto; padding: 0 1rem; }
.admin-header { background: linear-gradient(135deg, var(--primary), var(--primary-dark));
color: #fff; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; }
.admin-nav { display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; }
.admin-nav a { padding: 0.625rem 1rem; background: var(--surface); border: 1px solid var(--border);
border-radius: 6px; text-decoration: none; color: var(--text); }
.admin-nav a.active { background: var(--primary); color: #fff; }
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
gap: 1rem; margin-bottom: 2rem; }
.stat-card { background: var(--surface); padding: 1.5rem; border-radius: 12px;
border: 1px solid var(--border); text-align: center; }
.stat-number { font-size: 2rem; font-weight: 700; color: var(--primary); }
.stat-label { color: var(--text-muted); font-size: 0.85rem; }
/* 🌙 ТЁМНАЯ ТЕМА: Улучшенные стили для логов */
.logs-container {
background: var(--surface);
border-radius: 12px;
padding: 1.5rem;
border: 1px solid var(--border);
max-height: 600px;
overflow-y: auto;
}
.log-entry {
font-family: "Consolas", "Monaco", "Courier New", monospace;
font-size: 0.85rem;
word-break: break-all;
padding: 0.5rem 0.75rem;
margin-bottom: 0.25rem;
border-radius: 4px;
line-height: 1.5;
border-left: 3px solid transparent;
transition: all 0.2s ease;
}
.log-entry:hover {
background: var(--surface-hover);
}
/* Светлая тема - тёмный текст */
.log-entry-default {
color: #2d3748;
background: #f7fafc;
}
.log-entry-error {
color: #c53030;
background: #fff5f5;
border-left-color: #e53e3e;
font-weight: 600;
}
.log-entry-warning {
color: #c05621;
background: #fffaf0;
border-left-color: #ed8936;
}
.log-entry-critical {
color: #9b2c2c;
background: #fed7d7;
border-left-color: #e53e3e;
font-weight: 700;
}
.log-entry-info {
color: #2b6cb0;
background: #ebf8ff;
border-left-color: #4299e1;
}
.log-entry-debug {
color: #4a5568;
background: #edf2f7;
border-left-color: #718096;
}
/* 🌙 ТЁМНАЯ ТЕМА: Светлый текст для логов */
[data-theme="dark"] .logs-container {
background: #1a202c;
border-color: #4a5568;
}
[data-theme="dark"] .log-entry {
background: #2d3748;
color: #e2e8f0;
}
[data-theme="dark"] .log-entry:hover {
background: #4a5568;
}
[data-theme="dark"] .log-entry-default {
color: #e2e8f0;
background: #2d3748;
}
[data-theme="dark"] .log-entry-error {
color: #fc8181;
background: #742a2a;
border-left-color: #fc8181;
}
[data-theme="dark"] .log-entry-warning {
color: #f6ad55;
background: #7c2d12;
border-left-color: #f6ad55;
}
[data-theme="dark"] .log-entry-critical {
color: #feb2b2;
background: #9b2c2c;
border-left-color: #fc8181;
font-weight: 700;
}
[data-theme="dark"] .log-entry-info {
color: #90cdf4;
background: #2c5282;
border-left-color: #63b3ed;
}
[data-theme="dark"] .log-entry-debug {
color: #a0aec0;
background: #2d3748;
border-left-color: #718096;
}
</style>
<section class="container py-5">
<div class="admin-container">
<div class="admin-header">
<h1>📋 ' . Language::get('admin_logs') . '</h1>
<p class="mb-0 opacity-75">' . Language::get('admin_log_viewer') . '</p>
</div>
<div class="admin-nav">
<a href="/admin">📊 ' . Language::get('admin_dashboard') . '</a>
<a href="/admin/orders">📦 ' . Language::get('admin_orders') . '</a>
<a href="/admin/users">👥 ' . Language::get('admin_users') . '</a>
<a href="/admin/logs" class="active">📋 ' . Language::get('admin_logs') . '</a>
<a href="/">🏠 ' . Language::get('nav_home') . '</a>
</div>
' . $successHtml . '
<div class="stats-grid">
<div class="stat-card">
<div class="stat-number">' . $stats['total'] . '</div>
<div class="stat-label">' . Language::get('admin_log_total') . '</div>
</div>
<div class="stat-card">
<div class="stat-number" style="color: #fc8181">' . $stats['error'] . '</div>
<div class="stat-label">' . Language::get('admin_log_errors') . '</div>
</div>
<div class="stat-card">
<div class="stat-number" style="color: #f6ad55">' . $stats['warning'] . '</div>
<div class="stat-label">' . Language::get('admin_log_warnings') . '</div>
</div>
<div class="stat-card">
<div class="stat-number" style="color: #48bb78">' . $stats['info'] . '</div>
<div class="stat-label">' . Language::get('admin_log_info') . '</div>
</div>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
<h3 style="color: var(--text);">' . Language::get('admin_log_recent') . '</h3>
<form method="POST" action="/admin/logs?action=clear" style="display:inline;"
onsubmit="return confirm(\'' . $clearConfirm . '\')">
<button type="submit" class="btn btn-danger btn-sm">🗑️ ' . Language::get('admin_log_clear') . '</button>
</form>
</div>
<div class="logs-container">
' . ($logsHtml ?: '<p class="text-muted text-center">' . Language::get('admin_log_empty') . '</p>') . '
</div>
</div>
</section>';
        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}
