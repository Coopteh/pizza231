<?php
namespace Controllers;
use Models\Product;
use Models\User;
use Models\Log;
use Views\AdminTemplate;

class AdminController
{
    private function isAdmin(): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    private function requireAdmin()
    {
        if (!$this->isAdmin()) {
            header('Location: /');
            exit;
        }
    }

    private function log(string $action, array $details = []): void
    {
        $logModel = new Log();
        $user = $_SESSION['user_name'] ?? 'Система';
        $logModel->add($action, $user, $details);
    }

    public function dashboard()
    {
        $this->requireAdmin();
        $productModel = new Product();
        $products = $productModel->loadData();
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $orders = $this->getOrders();
        $logModel = new Log();
        $logs = $logModel->getAll(20);
        $stats = $logModel->getStats();
        echo AdminTemplate::dashboard($products, $users, $orders, $logs, $stats);
    }

    public function logs()
    {
        $this->requireAdmin();
        $logModel = new Log();
        $action = $_GET['action'] ?? '';
        $user = $_GET['user'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 50;
        $allLogs = $logModel->filter($action, $user, $dateFrom, 1000);
        $totalPages = ceil(count($allLogs) / $perPage);
        $logs = array_slice($allLogs, ($page - 1) * $perPage, $perPage);
        $stats = $logModel->getStats();
        echo AdminTemplate::logs($logs, $stats, $action, $user, $dateFrom, $page, $totalPages, count($allLogs));
    }

    public function clearLogs()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $logModel = new Log();
            if (isset($_POST['clear_all'])) {
                $logModel->clearAll();
                $this->log('Полная очистка логов', ['method' => 'clear_all']);
                $_SESSION['flash'] = "✅ Все логи удалены";
            } else {
                $days = (int)($_POST['days'] ?? 30);
                $deleted = $logModel->clearOld($days);
                $this->log('Очистка старых логов', ['days' => $days, 'deleted' => $deleted]);
                $_SESSION['flash'] = "✅ Удалено {$deleted} записей";
            }
            $_SESSION['flash_type'] = 'success';
        }
        header('Location: /admin/logs');
        exit;
    }

    public function editProduct()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (int)($_POST['price'] ?? 0),
                'period' => trim($_POST['period'] ?? 'год'),
                'image' => trim($_POST['image'] ?? '/assets/images/auto.jpg'),
                'coverage' => trim($_POST['coverage'] ?? 'до 10 млн ₽'),
                'features' => array_filter(explode("\n", $_POST['features'] ?? ''))
            ];
            $productModel = new Product();
            $oldProduct = $productModel->getById($id);
            $productModel->updateProduct($data);
            $this->log('Редактирование товара', ['product_id' => $id, 'name' => $data['name'], 'old_price' => $oldProduct['price'] ?? 0, 'new_price' => $data['price']]);
            $_SESSION['flash'] = '✅ Товар обновлён';
            $_SESSION['flash_type'] = 'success';
            header('Location: /admin');
            exit;
        }
        $id = (int)($_GET['id'] ?? 0);
        $productModel = new Product();
        $product = $productModel->getById($id);
        if (!$product) { header('Location: /admin'); exit; }
        echo AdminTemplate::editProduct($product);
    }

    public function addProduct()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new Product();
            $products = $productModel->loadData();
            $newId = max(array_column($products, 'id')) + 1;
            $data = [
                'id' => $newId,
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (int)($_POST['price'] ?? 0),
                'period' => trim($_POST['period'] ?? 'год'),
                'image' => trim($_POST['image'] ?? '/assets/images/auto.jpg'),
                'coverage' => trim($_POST['coverage'] ?? 'до 10 млн ₽'),
                'features' => array_filter(explode("\n", $_POST['features'] ?? ''))
            ];
            $productModel->addProduct($data);
            $this->log('Добавление товара', ['product_id' => $newId, 'name' => $data['name'], 'price' => $data['price']]);
            $_SESSION['flash'] = '✅ Товар добавлен';
            $_SESSION['flash_type'] = 'success';
            header('Location: /admin');
            exit;
        }
        echo AdminTemplate::addProduct();
    }

    public function deleteUser()
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $userModel = new User();
        $user = $userModel->findById($id);
        if ($user) {
            $userModel->deleteUser($id);
            $this->log('Удаление пользователя', ['user_id' => $id, 'email' => $user['email'] ?? '']);
            $_SESSION['flash'] = '✅ Пользователь удалён';
            $_SESSION['flash_type'] = 'success';
        }
        header('Location: /admin');
        exit;
    }

    public function setRole()
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)($_POST['user_id'] ?? 0);
            $role = $_POST['role'] ?? 'user';
            $userModel = new User();
            $user = $userModel->findById($userId);
            if ($user) {
                $userModel->setRole($userId, $role);
                $this->log('Изменение роли', ['user_id' => $userId, 'email' => $user['email'], 'new_role' => $role]);
                $_SESSION['flash'] = '✅ Роль обновлена';
                $_SESSION['flash_type'] = 'success';
            }
            header('Location: /admin');
            exit;
        }
    }

    public function activate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['admin_code'] ?? '';
            $envCode = getenv('ADMIN_CODE') ?: '123123';
            if ($code === $envCode) {
                $userId = $_SESSION['user_id'] ?? 0;
                if ($userId) {
                    $userModel = new User();
                    $userModel->setRole($userId, 'admin');
                    $_SESSION['user_role'] = 'admin';
                    $logModel = new Log();
                    $logModel->add('Активация админ-доступа', $_SESSION['user_name'] ?? 'Новый пользователь', ['user_id' => $userId]);
                    $_SESSION['flash'] = '✅ Админ-доступ активирован';
                    header('Location: /admin');
                    exit;
                }
            }
            $_SESSION['flash'] = '❌ Неверный код';
            $_SESSION['flash_type'] = 'error';
        }
        echo AdminTemplate::activateAdmin();
    }

    private function getOrders(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM orders ORDER BY created_at DESC");
            $orders = $stmt->fetchAll();
            
            foreach ($orders as &$order) {
                if (isset($order['products']) && is_string($order['products'])) {
                    $order['products'] = json_decode($order['products'], true) ?? [];
                }
            }
            
            return $orders;
        } catch (\PDOException $e) {
            return [];
        }
    }
}