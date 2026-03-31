<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Views/ProfileTemplate.php';

use App\Models\User;
use App\Views\ProfileTemplate;

class ProfileController
{
    private User $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Проверить авторизацию
     */
    private function requireAuth(): array
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email']
        ];
    }
    
    /**
     * Страница профиля
     */
    public function get(): void
    {
        $user = $this->requireAuth();
        
        // Получить полный профиль
        $profile = $this->userModel->getFullProfile($user['id']);
        
        if (!$profile) {
            echo "Ошибка загрузки профиля";
            return;
        }
        
        // Получить историю заказов
        $orders = $this->userModel->getOrders($user['id']);
        
        echo ProfileTemplate::render($profile, $orders);
    }
    
    /**
     * API: обновить профиль
     */
    public function apiUpdate(): string
    {
        header('Content-Type: application/json');
        
        $user = $this->requireAuth();
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Валидация имени
        if (isset($input['name']) && empty(trim($input['name']))) {
            http_response_code(400);
            return json_encode(['error' => 'Имя не может быть пустым'], JSON_UNESCAPED_UNICODE);
        }
        
        // Обновляем профиль
        $result = $this->userModel->updateProfile($user['id'], $input);
        
        if ($result) {
            // Обновить сессию если изменилось имя
            if (isset($input['name'])) {
                $_SESSION['user_name'] = trim($input['name']);
            }
            
            // Получить обновлённый профиль
            $profile = $this->userModel->getFullProfile($user['id']);
            
            return json_encode([
                'success' => true,
                'profile' => $profile
            ], JSON_UNESCAPED_UNICODE);
        }
        
        http_response_code(500);
        return json_encode(['error' => 'Ошибка сохранения профиля'], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * API: загрузить аватар
     */
    public function apiUploadAvatar(): string
    {
        header('Content-Type: application/json');
        
        $user = $this->requireAuth();
        
        if (!isset($_FILES['avatar'])) {
            http_response_code(400);
            return json_encode(['error' => 'Файл не загружен'], JSON_UNESCAPED_UNICODE);
        }
        
        $file = $_FILES['avatar'];
        
        // Проверка на ошибки
        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            return json_encode(['error' => 'Ошибка загрузки файла'], JSON_UNESCAPED_UNICODE);
        }
        
        // Проверка типа файла
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            http_response_code(400);
            return json_encode(['error' => 'Разрешены только изображения (JPEG, PNG, GIF, WebP)'], JSON_UNESCAPED_UNICODE);
        }
        
        // Проверка размера (макс. 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            http_response_code(400);
            return json_encode(['error' => 'Максимальный размер файла - 2MB'], JSON_UNESCAPED_UNICODE);
        }
        
        // Создать папку для аватарок
        $uploadDir = __DIR__ . '/../../storage/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Генерировать уникальное имя файла
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = 'avatar_' . $user['id'] . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $newFilename;
        
        // Удалить старый аватар
        $profile = $this->userModel->getFullProfile($user['id']);
        if (!empty($profile['avatar'])) {
            $oldFile = $uploadDir . basename($profile['avatar']);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        
        // Переместить файл
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Сохранить путь к аватару в профиле
            $avatarPath = '/storage/avatars/' . $newFilename;
            $this->userModel->updateProfile($user['id'], ['avatar' => $avatarPath]);
            
            return json_encode([
                'success' => true,
                'avatar' => $avatarPath
            ], JSON_UNESCAPED_UNICODE);
        }
        
        http_response_code(500);
        return json_encode(['error' => 'Не удалось сохранить файл'], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * API: получить профиль
     */
    public function apiGet(): string
    {
        header('Content-Type: application/json');
        
        $user = $this->requireAuth();
        
        $profile = $this->userModel->getFullProfile($user['id']);
        
        if ($profile) {
            return json_encode(['profile' => $profile], JSON_UNESCAPED_UNICODE);
        }
        
        return json_encode(['profile' => null], JSON_UNESCAPED_UNICODE);
    }
}
