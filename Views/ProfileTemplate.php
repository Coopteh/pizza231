<?php
namespace Views;

class ProfileTemplate extends BaseTemplate
{
    public static function render(array $user): string
    {
        $template = parent::getTemplate();
        
        // Получаем данные безопасно
        $userName = isset($user['name']) ? $user['name'] : 'Пользователь';
        $userEmail = isset($user['email']) ? $user['email'] : '';
        $userPhone = isset($user['phone']) ? $user['phone'] : '';
        $userCard = isset($user['card_last4']) ? $user['card_last4'] : '';
        $userCreated = isset($user['created_at']) ? $user['created_at'] : date('Y-m-d H:i:s');
        
        $title = 'Профиль — ' . htmlspecialchars($userName);
        
        $errors = isset($_SESSION['profile_errors']) ? $_SESSION['profile_errors'] : [];
        $success = isset($_SESSION['profile_success']) ? $_SESSION['profile_success'] : '';
        unset($_SESSION['profile_errors'], $_SESSION['profile_success']);
        
        // Блок уведомлений
        $alertsHtml = '';
        if (!empty($errors)) {
            $alertsHtml .= '<div class="alert alert-danger">' . implode('<br>', array_map('htmlspecialchars', $errors)) . '</div>';
        }
        if (!empty($success)) {
            $alertsHtml .= '<div class="alert alert-success">' . htmlspecialchars($success) . '</div>';
        }
        
        // Отображение телефона
        if (!empty($userPhone)) {
            $phoneDisplay = self::formatPhone($userPhone);
        } else {
            $phoneDisplay = 'Не привязан';
        }
        
        // Отображение карты
        if (!empty($userCard)) {
            $cardDisplay = '**** **** **** ' . $userCard;
            $cardAlertBlock = '<div class="alert alert-info mt-3"><strong>Текущая карта:</strong> **** **** **** ' . htmlspecialchars($userCard) . '<br><small>Чтобы изменить карту, просто введите новые данные выше</small></div>';
        } else {
            $cardDisplay = 'Не привязана';
            $cardAlertBlock = '';
        }
        
        $content = '
        <style>
            .profile-header {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                border-radius: 20px;
                padding: 2rem;
                color: #fff;
                margin-bottom: 2rem;
            }
            .profile-avatar {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: rgba(255,255,255,0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            .profile-name { font-size: 1.5rem; font-weight: 700; margin: 0; }
            .profile-email { opacity: 0.9; margin: 0.25rem 0 0; }
            .profile-nav {
                display: flex;
                gap: 0.5rem;
                margin-bottom: 2rem;
                flex-wrap: wrap;
            }
            .profile-nav a {
                padding: 0.5rem 1.25rem;
                border-radius: 50px;
                text-decoration: none;
                color: #64748b;
                font-weight: 500;
                transition: all 0.2s;
                border: 1px solid transparent;
                cursor: pointer;
            }
            .profile-nav a:hover, .profile-nav a.active {
                background: #0d6efd;
                color: #fff;
            }
            .profile-section {
                background: #fff;
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 1.5rem;
                border: 1px solid #e2e8f0;
                display: none;
            }
            .profile-section.active { display: block; }
            .profile-section h4 {
                font-size: 1.25rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
                color: #1e293b;
            }
            .form-label { font-weight: 500; color: #334155; margin-bottom: 0.5rem; }
            .form-control {
                border-radius: 12px;
                border: 1px solid #cbd5e1;
                padding: 0.75rem 1rem;
            }
            .form-control:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.12);
            }
            .form-text { font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; }
            .btn-profile {
                background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
                border: none;
                padding: 10px 24px;
                border-radius: 50px;
                color: #fff;
                font-weight: 600;
            }
            .btn-profile:hover { opacity: 0.95; color: #fff; }
            .info-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem 0;
                border-bottom: 1px solid #e2e8f0;
            }
            .info-row:last-child { border-bottom: none; }
            .info-label { color: #64748b; }
            .info-value { font-weight: 600; color: #1e293b; }
            .card-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                color: #0d6efd;
            }
            @media (max-width: 768px) {
                .profile-nav { justify-content: center; }
            }
        </style>
        
        <section class="container py-5">
            <div class="profile-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="profile-avatar">👤</div>
                    <div>
                        <h1 class="profile-name">' . htmlspecialchars($userName) . '</h1>
                        <p class="profile-email">' . htmlspecialchars($userEmail) . '</p>
                    </div>
                </div>
            </div>
            
            <div class="profile-nav">
                <a href="#general" class="active" onclick="showSection(\'general\', this); return false;">📋 Общая информация</a>
                <a href="#phone" onclick="showSection(\'phone\', this); return false;">📱 Телефон</a>
                <a href="#password" onclick="showSection(\'password\', this); return false;">🔐 Пароль</a>
                <a href="#card" onclick="showSection(\'card\', this); return false;">💳 Карта</a>
            </div>
            
            ' . $alertsHtml . '
            
            <div id="general" class="profile-section active">
                <h4>📋 Общая информация</h4>
                <div class="info-row">
                    <span class="info-label">Имя</span>
                    <span class="info-value">' . htmlspecialchars($userName) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">' . htmlspecialchars($userEmail) . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Телефон</span>
                    <span class="info-value">' . $phoneDisplay . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Карта</span>
                    <span class="info-value">' . $cardDisplay . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Дата регистрации</span>
                    <span class="info-value">' . date('d.m.Y', strtotime($userCreated)) . '</span>
                </div>
            </div>
            
            <div id="phone" class="profile-section">
                <h4>📱 Номер телефона</h4>
                <form method="POST" action="/profile/phone">
                    <div class="mb-3">
                        <label class="form-label">Номер телефона</label>
                        <input type="tel" name="phone" class="form-control" 
                               value="' . htmlspecialchars($userPhone) . '" 
                               placeholder="+7 (999) 999-99-99" required>
                        <small class="form-text">В формате +7 (999) 999-99-99</small>
                    </div>
                    <button type="submit" class="btn btn-profile">Сохранить</button>
                </form>
            </div>
            
            <div id="password" class="profile-section">
                <h4>🔐 Смена пароля</h4>
                <form method="POST" action="/profile/password">
                    <div class="mb-3">
                        <label class="form-label">Текущий пароль</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Новый пароль</label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                        <small class="form-text">Минимум 6 символов</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Подтвердите новый пароль</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-profile">Изменить пароль</button>
                </form>
            </div>
            
            <div id="card" class="profile-section">
                <h4>💳 Привязка карты</h4>
                <div class="text-center mb-4">
                    <div class="card-icon">💳</div>
                    <p class="text-muted">Привяжите карту для быстрой оплаты страховых полисов</p>
                </div>
                <form method="POST" action="/profile/card">
                    <div class="mb-3">
                        <label class="form-label">Номер карты</label>
                        <input type="text" name="card_number" class="form-control" 
                               value="" 
                               placeholder="0000 0000 0000 0000" 
                               maxlength="19"
                               oninput="this.value = this.value.replace(/[^0-9]/g, \'\').replace(/(.{4})/g, \'$1 \').trim()">
                        <small class="form-text">Мы сохраняем только последние 4 цифры для идентификации</small>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label">Срок действия</label>
                            <input type="text" class="form-control" placeholder="MM/YY" maxlength="5"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, \'\').replace(/(.{2})/g, \'$1/\').slice(0,5)">
                        </div>
                        <div class="col-6">
                            <label class="form-label">CVC</label>
                            <input type="text" class="form-control" placeholder="123" maxlength="3">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-profile">Привязать карту</button>
                </form>
                ' . $cardAlertBlock . '
            </div>
        </section>
        
        <script>
        function showSection(id, link) {
            document.querySelectorAll(".profile-section").forEach(function(sec) {
                sec.classList.remove("active");
            });
            document.querySelectorAll(".profile-nav a").forEach(function(a) {
                a.classList.remove("active");
            });
            document.getElementById(id).classList.add("active");
            link.classList.add("active");
            location.hash = id;
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            const hash = location.hash.slice(1);
            if (hash && document.getElementById(hash)) {
                const link = document.querySelector(\'.profile-nav a[href="#\' + hash + \'"]\');
                if (link) showSection(hash, link);
            }
        });
        
        const phoneInput = document.querySelector(\'input[name="phone"]\');
        if (phoneInput) {
            phoneInput.addEventListener("input", function() {
                let val = this.value.replace(/\\D/g, "");
                if (val.startsWith("8")) val = "7" + val.slice(1);
                if (val.length <= 11) {
                    val = val.replace(/(\\d{1})(\\d{3})(\\d{3})(\\d{2})(\\d{2})/, "+$1 ($2) $3-$4-$5");
                }
                this.value = val;
            });
        }
        </script>';
        
        return sprintf($template, $title, $content);
    }
    
    private static function formatPhone(string $phone): string
    {
        $clean = preg_replace('/\D/', '', $phone);
        if (strlen($clean) === 11 && $clean[0] === '7') {
            return '+7 (' . substr($clean, 1, 3) . ') ' . substr($clean, 4, 3) . '-' . substr($clean, 7, 2) . '-' . substr($clean, 9, 2);
        }
        return htmlspecialchars($phone);
    }
}