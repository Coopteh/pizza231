<?php
namespace App\Controllers;

use App\Views\BaseTemplate;
use App\Config\Config;
use PDO;

class SettingsController
{
    public function get(): string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) {
            $_SESSION['flash'] = 'Доступ только для авторизованных пользователей';
            header('Location: /login');
            exit;
        }

        $settings = $this->loadSettings();
        
        ob_start();
        ?>
        <div class="container mt-5">
            <h2 class="mb-4">⚙️ Настройки аккаунта</h2>
            
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100 animate-fade-in">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-person-circle me-2"></i>Личные данные
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/settings/profile">
                                <div class="mb-3">
                                    <label class="form-label">Имя пользователя</label>
                                    <input type="text" class="form-control" name="username" 
                                           value="<?= htmlspecialchars($settings['username']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?= htmlspecialchars($settings['email']) ?>" readonly>
                                    <small class="text-muted">Email нельзя изменить</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Телефон</label>
                                    <input type="tel" class="form-control" name="phone" 
                                           value="<?= htmlspecialchars($settings['phone'] ?? '') ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Сохранить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100 animate-fade-in" style="animation-delay: 0.1s">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-sliders me-2"></i>Предпочтения
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/settings/preferences">
                                <div class="mb-3">
                                    <label class="form-label">Интересующие направления</label>
                                    <select class="form-select" name="preferred_destinations[]" multiple size="4">
                                        <option value="mountains" <?= in_array('mountains', $settings['preferences'] ?? []) ? 'selected' : '' ?>>🏔️ Горы</option>
                                        <option value="sea" <?= in_array('sea', $settings['preferences'] ?? []) ? 'selected' : '' ?>>🏖️ Море</option>
                                        <option value="cities" <?= in_array('cities', $settings['preferences'] ?? []) ? 'selected' : '' ?>>🏙️ Города</option>
                                        <option value="nature" <?= in_array('nature', $settings['preferences'] ?? []) ? 'selected' : '' ?>>🌲 Природа</option>
                                    </select>
                                    <small class="text-muted">Удерживайте Ctrl/Cmd для выбора нескольких</small>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="email_notifications" 
                                           id="emailNotif" <?= $settings['email_notifications'] ?? true ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="emailNotif">
                                        Получать уведомления о скидках
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Применить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="/profile" class="btn btn-outline-secondary mt-4">
                <i class="bi bi-arrow-left me-1"></i>Вернуться в кабинет
            </a>
        </div>
        <?php
        $content = ob_get_clean();
        return sprintf(BaseTemplate::getTemplate(), 'Настройки - По мультивселенной', $content);
    }
    
    public function updateProfile(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        try {
            $pdo = new PDO(Config::MYSQL_DSN, Config::MYSQL_USER, Config::MYSQL_PASSWORD, 
                          [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            
            $stmt = $pdo->prepare("UPDATE users SET username = ?, phone = ? WHERE id = ?");
            $stmt->execute([
                trim($_POST['username'] ?? ''),
                trim($_POST['phone'] ?? ''),
                $_SESSION['user_id']
            ]);
            
            $_SESSION['flash'] = '✅ Данные профиля обновлены!';
            $_SESSION['user_name'] = trim($_POST['username'] ?? '');
        } catch (\PDOException $e) {
            error_log("Settings update error: " . $e->getMessage());
            $_SESSION['flash'] = '❌ Ошибка при сохранении';
        }
        
        header('Location: /settings');
        exit;
    }
    
    private function loadSettings(): array
    {
        try {
            $pdo = new PDO(Config::MYSQL_DSN, Config::MYSQL_USER, Config::MYSQL_PASSWORD);
            $stmt = $pdo->prepare("SELECT username, email, phone, preferences, email_notifications FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return [
                'username' => $user['username'] ?? '',
                'email' => $user['email'] ?? '',
                'phone' => $user['phone'] ?? '',
                'preferences' => $user['preferences'] ? json_decode($user['preferences'], true) : [],
                'email_notifications' => (bool)($user['email_notifications'] ?? true)
            ];
        } catch (\PDOException $e) {
            return ['username' => '', 'email' => '', 'phone' => '', 'preferences' => [], 'email_notifications' => true];
        }
    }
}