<?php

// 🔐 НОВЫЙ ФАЙЛ: Controllers/AdminController.php
// Контроллер админ-панели

namespace Controllers;

use Lib\User;
use Lib\Language;
use Lib\Logger; // 📝 LOGGER: Добавлен импорт
use Views\AdminTemplate;

class AdminController
{
    // 🔐 ДОБАВЛЕНО: Дашборд администратора
    public function index(): string
    {
        if (!User::isAdmin()) {
            header('Location: /');
            exit;
        }

        $ordersFile = __DIR__ . '/../data/orders.json';
        $orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) ?? [] : [];

        $stats = [
            'totalOrders' => count($orders),
            'totalRevenue' => array_sum(array_column($orders, 'total')),
            'pendingOrders' => count(array_filter($orders, fn ($o) => $o['status'] === 'pending')),
            'totalUsers' => count(User::getAllUsers()),
            'verifiedUsers' => count(array_filter(User::getAllUsers(), fn ($u) => $u['verified'])),
            'recentOrders' => array_slice(array_values(array_reverse($orders)), 0, 10)
        ];

        // 📝 LOGGER: Логирование доступа к админ-панели
        Logger::info("Доступ к админ-панели", [
            'user_id' => $_SESSION['user_id'] ?? 'unknown'
        ]);

        return AdminTemplate::getDashboardTemplate($stats);
    }

    // 🔐 ДОБАВЛЕНО: Список заказов
    public function orders(): string
    {
        if (!User::isAdmin()) {
            header('Location: /');
            exit;
        }

        $ordersFile = __DIR__ . '/../data/orders.json';
        $orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) ?? [] : [];

        $status = $_GET['status'] ?? 'all';
        if ($status !== 'all') {
            $orders = array_filter($orders, fn ($o) => $o['status'] === $status);
        }

        $search = $_GET['search'] ?? '';
        if (!empty($search)) {
            $orders = array_filter(
                $orders,
                fn ($o) =>
                stripos($o['id'], $search) !== false ||
                stripos($o['name'], $search) !== false ||
                stripos($o['email'], $search) !== false
            );
        }

        usort($orders, fn ($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));

        return AdminTemplate::getOrdersTemplate($orders, $status, $search);
    }

    // 🔐 ДОБАВЛЕНО: Детали заказа
    public function orderDetail(): string
    {
        if (!User::isAdmin()) {
            header('Location: /');
            exit;
        }

        $orderId = $_GET['id'] ?? '';
        $ordersFile = __DIR__ . '/../data/orders.json';
        $orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) ?? [] : [];

        $order = null;
        foreach ($orders as $o) {
            if ($o['id'] === $orderId) {
                $order = $o;
                break;
            }
        }

        if (!$order) {
            header('Location: /admin/orders?error=not_found');
            exit;
        }

        return AdminTemplate::getOrderDetailTemplate($order);
    }

    // 🔐 ДОБАВЛЕНО: Обновление статуса заказа
    public function updateOrderStatus(): void
    {
        if (!User::isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            exit;
        }

        $orderId = $_POST['order_id'] ?? '';
        $status = $_POST['status'] ?? '';
        $validStatuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            header('Location: /admin/orders?error=invalid_status');
            exit;
        }

        $ordersFile = __DIR__ . '/../data/orders.json';
        $orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) ?? [] : [];

        $oldStatus = null;
        foreach ($orders as $key => $order) {
            if ($order['id'] === $orderId) {
                $oldStatus = $orders[$key]['status'];
                $orders[$key]['status'] = $status;
                $orders[$key]['updated_at'] = date('Y-m-d H:i:s');
                break;
            }
        }

        file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // 📝 LOGGER: Логирование обновления статуса заказа
        Logger::info("Статус заказа обновлён", [
            'order_id' => $orderId,
            'old_status' => $oldStatus,
            'new_status' => $status,
            'admin_id' => $_SESSION['user_id'] ?? 'unknown'
        ]);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }

        header('Location: /admin/orders?success=status_updated');
        exit;
    }

    // 🔐 ДОБАВЛЕНО: Список пользователей
    public function users(): string
    {
        if (!User::isAdmin()) {
            header('Location: /');
            exit;
        }

        return AdminTemplate::getUsersTemplate(User::getAllUsers());
    }

    // 🔐 ДОБАВЛЕНО: Удаление пользователя
    public function deleteUser(): void
    {
        if (!User::isAdmin()) {
            http_response_code(403);
            exit;
        }

        $userId = $_POST['user_id'] ?? 0;

        if ($userId == 1) {
            header('Location: /admin/users?error=cannot_delete_admin');
            exit;
        }

        $users = User::getAllUsers();

        if (isset($users[$userId])) {
            $deletedUser = $users[$userId];
            unset($users[$userId]);

            $content = "<?php\n// data/users.php\nreturn " . var_export($users, true) . ";";
            file_put_contents(__DIR__ . '/../data/users.php', $content);

            // 📝 LOGGER: Логирование удаления пользователя
            Logger::warning("Пользователь удалён", [
                'deleted_user_id' => $userId,
                'deleted_user_email' => $deletedUser['email'] ?? 'unknown',
                'admin_id' => $_SESSION['user_id'] ?? 'unknown'
            ]);
        }

        header('Location: /admin/users?success=deleted');
        exit;
    }

    public function logs(): string
    {
        if (!User::isAdmin()) {
            header('Location: /');
            exit;
        }

        $action = $_GET['action'] ?? 'view';
        $limit = (int)($_GET['limit'] ?? 100);

        if ($action === 'clear' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            Logger::clearLogs();

            // 📝 LOGGER: Логирование очистки логов
            Logger::info("Логи очищены администратором", [
                'admin_id' => $_SESSION['user_id'] ?? 'unknown'
            ]);

            header('Location: /admin/logs?success=cleared');
            exit;
        }

        $logs = Logger::getLogs($limit);
        $stats = Logger::getStats();

        return AdminTemplate::getLogsTemplate($logs, $stats, $limit);
    }
}
