<?php

namespace Views;

use Lib\Language;

class ProfileTemplate extends BaseTemplate
{
    public static function getProfileTemplate(array $user, array $orders): string
    {
        $template = parent::getTemplate();
        $title = Language::get('auth_profile') . ' - ' . Language::get('site_name');

        $recentOrders = array_slice($orders, 0, 5);
        $ordersHtml = '';

        if (!empty($recentOrders)) {
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

            foreach ($recentOrders as $order) {
                $ordersHtml .= '
                <div class="order-item">
                    <div>
                        <strong>' . htmlspecialchars($order['id']) . '</strong>
                        <span class="order-status" style="background:' . $statusColors[$order['status']] . '">' . $statusNames[$order['status']] . '</span>
                    </div>
                    <div>' . number_format($order['total'], 0, '.', ' ') . ' ₽</div>
                    <div>' . date('d.m.Y', strtotime($order['created_at'])) . '</div>
                    <a href="/profile/orders" class="btn-sm">' . Language::get('auth_order_details') . '</a>
                </div>';
            }
        } else {
            $ordersHtml = '<p class="text-muted">' . Language::get('auth_no_orders') . '</p>';
        }

        $content = '
        <style>
            /* 🌙 ТЁМНАЯ ТЕМА: Стили для профиля */
            .profile-container {
                max-width: 900px;
                margin: 3rem auto;
            }
            
            .profile-header {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: #fff;
                padding: 2rem;
                border-radius: 12px;
                margin-bottom: 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: background 0.3s ease;
            }
            
            .profile-info {
                background: var(--surface);
                padding: 2rem;
                border-radius: 12px;
                border: 1px solid var(--border);
                margin-bottom: 2rem;
                transition: all 0.3s ease;
            }
            
            .profile-section-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--text);
                margin-bottom: 1.5rem;
                padding-bottom: 0.75rem;
                border-bottom: 2px solid var(--primary);
                transition: color 0.3s ease;
            }
            
            .info-row {
                display: flex;
                padding: 1rem 0;
                border-bottom: 1px solid var(--border);
                transition: border-color 0.3s ease;
            }
            
            .info-label {
                width: 150px;
                color: var(--text-muted);
                font-weight: 500;
                transition: color 0.3s ease;
            }
            
            .info-value {
                flex: 1;
                color: var(--text);
                transition: color 0.3s ease;
            }
            
            .btn-profile {
                padding: 0.75rem 1.5rem;
                border-radius: 6px;
                text-decoration: none;
                font-weight: 500;
                display: inline-block;
                transition: all 0.2s;
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-primary {
                background: var(--primary);
                color: #fff;
            }
            
            .btn-outline {
                border: 2px solid var(--primary);
                color: var(--primary);
            }
            
            .order-item {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr auto;
                gap: 1rem;
                padding: 1rem;
                background: var(--surface-hover);
                border-radius: 8px;
                margin-bottom: 0.75rem;
                align-items: center;
                transition: all 0.3s ease;
            }
            
            .order-item:hover {
                box-shadow: var(--shadow);
            }
            
            .order-status {
                padding: 0.25rem 0.75rem;
                border-radius: 4px;
                color: #fff;
                font-size: 0.85rem;
                margin-left: 0.5rem;
            }
            
            .btn-sm {
                padding: 0.5rem 1rem;
                background: var(--primary);
                color: #fff;
                border-radius: 4px;
                text-decoration: none;
                font-size: 0.9rem;
                transition: background 0.2s;
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-sm:hover {
                background: var(--primary-dark);
                color: #fff;
                text-decoration: none;
            }
            
            @media (max-width: 768px) {
                .profile-header {
                    flex-direction: column;
                    gap: 1rem;
                    text-align: center;
                }
                .order-item {
                    grid-template-columns: 1fr;
                    gap: 0.5rem;
                }
            }
        </style>
        <section class="container py-5">
            <div class="profile-container">
                <div class="profile-header">
                    <div>
                        <h1 class="mb-2">' . htmlspecialchars($user['name']) . '</h1>
                        <p class="mb-0 opacity-75">' . htmlspecialchars($user['email']) . '</p>
                    </div>
                    <div>
                        <a href="/profile/edit" class="btn-profile btn-outline" style="background:#fff;color:var(--primary)">' . Language::get('auth_edit_profile') . '</a>
                        <a href="/auth/logout" class="btn-profile" style="background:var(--danger);color:#fff;margin-left:0.5rem">' . Language::get('auth_logout') . '</a>
                    </div>
                </div>
                <div class="profile-info">
                    <h2 class="profile-section-title">' . Language::get('auth_personal_info') . '</h2>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">' . htmlspecialchars($user['email']) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">' . Language::get('checkout_phone') . ':</span>
                        <span class="info-value">' . htmlspecialchars($user['phone']) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">' . Language::get('auth_role') . ':</span>
                        <span class="info-value">' . ($user['role'] === 'admin' ? Language::get('auth_role_admin') : Language::get('auth_role_user')) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">' . Language::get('auth_registration_date') . ':</span>
                        <span class="info-value">' . date('d.m.Y', strtotime($user['created_at'])) . '</span>
                    </div>
                </div>
                <div class="profile-info">
                    <h2 class="profile-section-title">' . Language::get('auth_order_history') . ' <a href="/profile/orders" class="btn-sm" style="float:right">' . Language::get('course_back') . '</a></h2>
                    ' . $ordersHtml . '
                </div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    public static function getEditTemplate(array $user, array $errors = [], bool $success = false, bool $showPasswordForm = false, bool $codeSent = false): string
    {
        $template = parent::getTemplate();
        $title = Language::get('auth_edit_profile') . ' - ' . Language::get('site_name');

        $errorMessages = [
            'name_required' => Language::get('auth_name_required'),
            'phone_required' => Language::get('auth_phone_required'),
            'current_password_invalid' => 'Неверный текущий пароль',
            'current_password_required' => 'Введите текущий пароль',
            'password_short' => Language::get('auth_password_short'),
            'password_mismatch' => Language::get('auth_password_mismatch'),
            'new_password_required' => 'Введите новый пароль',
            'code_required' => 'Введите код из email',
            'code_invalid' => 'Неверный код или истёк срок действия'
        ];

        $errorHtml = '';
        foreach ($errors as $error) {
            $errorHtml .= '<div class="alert-error">' . ($errorMessages[$error] ?? $error) . '</div>';
        }

        $successHtml = $success ? '<div class="alert-success">' . Language::get('auth_save_changes') . '!</div>' : '';
        $codeSentHtml = $codeSent ? '<div class="alert-success">Код отправлен на email ' . htmlspecialchars($user['email']) . '</div>' : '';

        $content = '
        <style>
            .profile-container {
                max-width: 600px;
                margin: 3rem auto;
            }
            
            .profile-form {
                background: var(--surface);
                padding: 2.5rem;
                border-radius: 12px;
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .form-title {
                text-align: center;
                margin-bottom: 2rem;
                color: var(--primary);
                transition: color 0.3s ease;
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 500;
                color: var(--text);
                transition: color 0.3s ease;
            }
            
            .form-control {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid var(--border);
                border-radius: 6px;
                background: var(--surface);
                color: var(--text);
                transition: all 0.3s ease;
            }
            
            .form-control:focus {
                border-color: var(--primary);
                outline: none;
                box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            }
            
            .btn-save {
                width: 100%;
                padding: 1rem;
                background: var(--primary);
                color: #fff;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.3s;
                min-height: 48px;
            }
            
            .btn-save:hover {
                background: var(--primary-dark);
            }
            
            .alert-error {
                background: rgba(229, 62, 62, 0.15);
                color: var(--danger);
                padding: 0.75rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--danger);
            }
            
            .alert-success {
                background: rgba(56, 161, 105, 0.15);
                color: var(--success);
                padding: 0.75rem;
                border-radius: 6px;
                margin-bottom: 1rem;
                border: 1px solid var(--success);
            }
            
            .password-section {
                background: var(--surface-hover);
                padding: 1.5rem;
                border-radius: 8px;
                margin-top: 2rem;
                transition: all 0.3s ease;
            }
            
            .code-section {
                background: rgba(229, 62, 62, 0.05);
                padding: 1.5rem;
                border-radius: 8px;
                margin-top: 1rem;
                border: 1px solid var(--danger);
            }
            
            .code-input {
                text-align: center;
                font-size: 1.5rem;
                letter-spacing: 8px;
                font-weight: 700;
            }
        </style>
        <section class="container py-5">
            <div class="profile-container">
                <div class="profile-form">
                    <h1 class="form-title">' . Language::get('auth_edit_profile') . '</h1>
                    ' . $successHtml . $errorHtml . $codeSentHtml . '
                    <form method="POST" action="/profile/edit">
                        <input type="hidden" name="form_type" value="profile">
                        <div class="form-group">
                            <label>' . Language::get('checkout_name') . '</label>
                            <input type="text" name="name" class="form-control" value="' . htmlspecialchars($user['name']) . '" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="' . htmlspecialchars($user['email']) . '" disabled>
                        </div>
                        <div class="form-group">
                            <label>' . Language::get('checkout_phone') . '</label>
                            <input type="tel" name="phone" class="form-control" value="' . htmlspecialchars($user['phone']) . '" required>
                        </div>
                        <button type="submit" class="btn-save">' . Language::get('auth_save_changes') . '</button>
                    </form>
                    <div class="password-section">
                        <h3 style="margin-bottom:1rem;color:var(--text)">' . Language::get('auth_new_password') . '</h3>
                        ' . ($showPasswordForm ? '
                        <form method="POST" action="/profile/edit">
                            <input type="hidden" name="form_type" value="password_verify">
                            <div class="code-section">
                                <div class="form-group">
                                    <label>📧 Код из email</label>
                                    <input type="text" name="verification_code" class="form-control code-input" maxlength="6" placeholder="000000" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, \'\'">
                                    <small style="color:var(--text-muted);margin-top:0.5rem;display:block">Введите 6-значный код, отправленный на ' . htmlspecialchars($user['email']) . '</small>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:1rem">
                                <label>' . Language::get('auth_new_password') . '</label>
                                <input type="password" name="new_password" class="form-control" minlength="6" required>
                            </div>
                            <div class="form-group">
                                <label>' . Language::get('auth_confirm_password') . '</label>
                                <input type="password" name="new_password_confirm" class="form-control" required>
                            </div>
                            <button type="submit" class="btn-save">Сменить пароль</button>
                        </form>
                        ' : '
                        <form method="POST" action="/profile/edit">
                            <input type="hidden" name="form_type" value="password_request">
                            <div class="form-group">
                                <label>' . Language::get('auth_current_password') . '</label>
                                <input type="password" name="current_password" class="form-control" required>
                                <small style="color:var(--text-muted);margin-top:0.5rem;display:block">Для безопасности на email будет отправлен код подтверждения</small>
                            </div>
                            <button type="submit" class="btn-save">Отправить код на email</button>
                        </form>
                        ') . '
                    </div>
                    <div style="text-align:center;margin-top:1.5rem">
                        <a href="/profile" style="color:var(--primary)">← ' . Language::get('auth_profile') . '</a>
                    </div>
                </div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }

    public static function getOrdersTemplate(array $user, array $orders): string
    {
        $template = parent::getTemplate();
        $title = Language::get('auth_order_history') . ' - ' . Language::get('site_name');

        $ordersHtml = '';
        if (!empty($orders)) {
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
                $itemsHtml = '';
                foreach ($order['items'] as $item) {
                    $itemsHtml .= '<li>' . htmlspecialchars($item['title']) . ' — ' . htmlspecialchars($item['price']) . '</li>';
                }

                $ordersHtml .= '
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <strong>' . Language::get('order_number') . htmlspecialchars($order['id']) . '</strong>
                            <span class="order-status" style="background:' . $statusColors[$order['status']] . '">' . $statusNames[$order['status']] . '</span>
                        </div>
                        <div class="order-date">' . date('d.m.Y H:i', strtotime($order['created_at'])) . '</div>
                    </div>
                    <div class="order-items">
                        <ul>' . $itemsHtml . '</ul>
                    </div>
                    <div class="order-footer">
                        <span class="order-total">' . Language::get('cart_total') . ': ' . number_format($order['total'], 0, '.', ' ') . ' ₽</span>
                        <span class="order-payment">' . htmlspecialchars($order['payment_method']) . '</span>
                    </div>
                </div>';
            }
        } else {
            $ordersHtml = '
            <div class="empty-state">
                <p>' . Language::get('auth_no_orders') . '</p>
                <a href="/courses" class="btn-primary">' . Language::get('nav_courses') . '</a>
            </div>';
        }

        $content = '
        <style>
            .orders-container {
                max-width: 900px;
                margin: 3rem auto;
            }
            
            .orders-title {
                text-align: center;
                margin-bottom: 2rem;
                color: var(--primary);
                transition: color 0.3s ease;
            }
            
            .order-card {
                background: var(--surface);
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .order-card:hover {
                box-shadow: var(--shadow);
            }
            
            .order-header {
                display: flex;
                justify-content: space-between;
                margin-bottom: 1rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid var(--border);
            }
            
            .order-status {
                padding: 0.25rem 0.75rem;
                border-radius: 4px;
                color: #fff;
                font-size: 0.85rem;
                margin-left: 0.5rem;
            }
            
            .order-date {
                color: var(--text-muted);
                transition: color 0.3s ease;
            }
            
            .order-items ul {
                margin: 1rem 0;
                padding-left: 1.5rem;
            }
            
            .order-items li {
                padding: 0.5rem 0;
                color: var(--text);
                transition: color 0.3s ease;
            }
            
            .order-footer {
                display: flex;
                justify-content: space-between;
                padding-top: 1rem;
                border-top: 1px solid var(--border);
            }
            
            .order-total {
                font-weight: 700;
                color: var(--primary);
                transition: color 0.3s ease;
            }
            
            .order-payment {
                color: var(--text-muted);
                transition: color 0.3s ease;
            }
            
            .empty-state {
                text-align: center;
                padding: 3rem;
                background: var(--surface);
                border-radius: 12px;
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }
            
            .btn-primary {
                display: inline-block;
                padding: 1rem 2rem;
                background: var(--primary);
                color: #fff;
                border-radius: 6px;
                text-decoration: none;
                margin-top: 1rem;
                transition: background 0.3s;
                min-height: 48px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .btn-primary:hover {
                background: var(--primary-dark);
                color: #fff;
                text-decoration: none;
            }
            
            @media (max-width: 768px) {
                .order-header {
                    flex-direction: column;
                    gap: 0.5rem;
                }
                .order-footer {
                    flex-direction: column;
                    gap: 0.5rem;
                }
            }
        </style>
        <section class="container py-5">
            <div class="orders-container">
                <h1 class="orders-title">' . Language::get('auth_order_history') . '</h1>
                ' . $ordersHtml . '
                <div style="text-align:center;margin-top:2rem">
                    <a href="/profile" style="color:var(--primary)">← ' . Language::get('auth_profile') . '</a>
                </div>
            </div>
        </section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}
