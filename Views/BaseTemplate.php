<?php

namespace Views;

use Lib\Language;
use Lib\User;

class BaseTemplate
{
    public static function getTemplate(): string
    {
        Language::init();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cartCount = count($_SESSION['cart'] ?? []);
        $cartBadge = $cartCount > 0 ? '<span class="cart-count">' . $cartCount . '</span>' : '';
        $currentLang = Language::getCurrentLang();
        $langSwitcher = self::getLanguageSwitcher($currentLang);
        $isLoggedIn = User::isLoggedIn();
        $currentUser = $isLoggedIn ? User::getCurrentUser() : null;
        $isAdmin = $isLoggedIn && User::isAdmin();

        $userName = '';
        $userInitial = '?';
        if ($currentUser !== null) {
            if (is_array($currentUser) && isset($currentUser['name'])) {
                $userName = $currentUser['name'];
            } elseif (is_object($currentUser) && isset($currentUser->name)) {
                $userName = $currentUser->name;
            } elseif (is_array($currentUser) && isset($currentUser['email'])) {
                $userName = explode('@', $currentUser['email'])[0];
            }
        }
        if ($userName !== '') {
            $userInitial = strtoupper(mb_substr($userName, 0, 1, 'UTF-8'));
        }

        // 🌙 ТЁМНАЯ ТЕМА: Определение текущей темы
        $defaultTheme = 'light';
        if (isset($_SESSION['theme'])) {
            $defaultTheme = $_SESSION['theme'];
        }

        $toastAdded = Language::get('toast_added');
        $toastError = Language::get('toast_error');
        $toastNetwork = Language::get('toast_network');
        $navHome = Language::get('nav_home');
        $navCourses = Language::get('nav_courses');
        $navAbout = Language::get('nav_about');
        $navCart = Language::get('nav_cart');
        $siteBrand = Language::get('site_brand');
        $footerSiteName = Language::get('site_name');
        $footerDescription = Language::get('site_description');
        $footerContactTitle = Language::get('contact_title');
        $footerScheduleTitle = Language::get('contact_schedule');
        $footerScheduleValue = Language::get('contact_schedule_value');
        $footerCopyright = Language::get('copyright');
        $authLogin = Language::get('auth_login');
        $authRegister = Language::get('auth_register');
        $authProfile = Language::get('auth_profile');
        $authLogout = Language::get('auth_logout');
        $authAdmin = Language::get('auth_admin_panel');
        $themeToggle = Language::get('theme_toggle');
        $themeLight = Language::get('theme_light');
        $themeDark = Language::get('theme_dark');

        return '<!DOCTYPE html>
<html lang="' . $currentLang . '" data-theme="' . $defaultTheme . '">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{TITLE}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* 🌙 ТЁМНАЯ ТЕМА: Отключение переходов при загрузке */
        :root, body, .navbar, .card, .alert-success, .alert-error,
        .cart-container, .cart-item, .cart-summary, .auth-container,
        .profile-container, .admin-container, .checkout-container,
        .success-container, .feature-box, .course-card, .cta-section,
        .order-detail, .users-table, .orders-table, .logs-container,
        .form-control, .form-select, .btn, footer {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease !important;
        }
        .no-transitions, .no-transitions *, .no-transitions *:before, .no-transitions *:after {
            transition: none !important;
            animation: none !important;
        }
        body.loaded .no-transitions, body.loaded .no-transitions *,
        body.loaded .no-transitions *:before, body.loaded .no-transitions *:after {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease !important;
            animation: initial !important;
        }
        /* 🌙 ТЁМНАЯ ТЕМА: CSS переменные для светлой и тёмной темы */
        :root {
            --primary: #2c5282;
            --primary-dark: #1a365d;
            --primary-light: #4299e1;
            --secondary: #4a5568;
            --background: #f7fafc;
            --surface: #ffffff;
            --surface-hover: #f7fafc;
            --text: #2d3748;
            --text-muted: #718096;
            --text-inverse: #ffffff;
            --border: #e2e8f0;
            --success: #38a169;
            --danger: #e53e3e;
            --warning: #ed8936;
            --info: #4299e1;
            --shadow: 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg: 0 4px 20px rgba(0,0,0,0.08);
            --badge-bg: #ebf4ff;
            --badge-text: #2c5282;
        }
        [data-theme="dark"] {
            --primary: #4299e1;
            --primary-dark: #2c5282;
            --primary-light: #63b3ed;
            --secondary: #a0aec0;
            --background: #1a202c;
            --surface: #2d3748;
            --surface-hover: #4a5568;
            --text: #e2e8f0;
            --text-muted: #a0aec0;
            --text-inverse: #1a202c;
            --border: #4a5568;
            --success: #48bb78;
            --danger: #fc8181;
            --warning: #f6ad55;
            --info: #63b3ed;
            --shadow: 0 2px 4px rgba(0,0,0,0.2);
            --shadow-lg: 0 4px 20px rgba(0,0,0,0.3);
            --badge-bg: #2c5282;
            --badge-text: #90cdf4;
        }
        body {
            font-family: "Inter", system-ui, -apple-system, sans-serif;
            color: var(--text);
            background: var(--background);
            line-height: 1.6;
        }
        .navbar {
            background: var(--surface) !important;
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 0;
            box-shadow: var(--shadow);
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--text) !important;
            font-size: 1.25rem;
        }
        .nav-link {
            color: var(--text) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0.25rem 0;
            transition: color 0.2s, background-color 0.2s;
            border-radius: 6px;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
            background: var(--surface-hover);
        }
        .cart-link {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        .cart-count {
            background: var(--danger);
            color: white;
            border-radius: 50%;
            padding: 0.15rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            position: absolute;
            top: -8px;
            right: -10px;
            min-width: 20px;
            text-align: center;
            line-height: 1.4;
        }
        .navbar-collapse {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--surface);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
        }
        .navbar-nav {
            flex-direction: column;
            gap: 0.5rem;
        }
        .nav-item { width: 100%; }
        .lang-switcher-mobile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: var(--surface-hover);
            border-radius: 8px;
            margin: 1rem 0;
        }
        .lang-switcher-mobile a {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: all 0.2s;
        }
        .lang-switcher-mobile a:hover {
            color: var(--primary);
            background: var(--surface);
        }
        .lang-switcher-mobile a.active {
            color: var(--primary);
            background: var(--surface);
            font-weight: 600;
            box-shadow: var(--shadow);
        }
        .lang-divider { color: var(--border); }
        /* 🌙 ТЁМНАЯ ТЕМА: Переключатель темы */
        .theme-switcher {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 1rem;
            padding-left: 1rem;
            border-left: 1px solid var(--border);
        }
        .theme-toggle-btn {
            background: var(--surface-hover);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 0.4rem 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
        }
        .theme-toggle-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .theme-toggle-btn svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }
        .theme-toggle-mobile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: var(--surface-hover);
            border-radius: 8px;
            margin: 1rem 0;
            color: var(--text);
            text-decoration: none;
            cursor: pointer;
            border: 1px solid var(--border);
        }
        .theme-toggle-mobile:hover {
            background: var(--primary);
            color: white;
        }
        .user-mobile-section {
            border-top: 1px solid var(--border);
            padding-top: 1rem;
            margin-top: 1rem;
        }
        .user-mobile-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--surface-hover);
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .user-mobile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .user-mobile-name {
            flex: 1;
            font-weight: 500;
            color: var(--text);
            font-size: 0.95rem;
        }
        .user-mobile-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .user-mobile-buttons a {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-mobile-profile {
            background: var(--surface-hover);
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-mobile-profile:hover {
            background: var(--border);
            color: var(--text);
        }
        .btn-mobile-admin {
            background: var(--danger);
            color: white;
        }
        .btn-mobile-admin:hover {
            background: #c53030;
            color: white;
        }
        .btn-mobile-logout {
            background: transparent;
            color: var(--danger);
            border: 1px solid var(--danger);
        }
        .btn-mobile-logout:hover {
            background: var(--danger);
            color: white;
        }
        .btn-mobile-login {
            background: var(--primary);
            color: white;
        }
        .btn-mobile-login:hover {
            background: var(--primary-dark);
            color: white;
        }
        .btn-mobile-register {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        .btn-mobile-register:hover {
            background: var(--primary);
            color: white;
        }
        .lang-switcher-desktop {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 1.5rem;
            padding-left: 1.5rem;
            border-left: 1px solid var(--border);
        }
        .lang-switcher-desktop a {
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: all 0.2s;
        }
        .lang-switcher-desktop a:hover {
            color: var(--primary);
            background: var(--surface-hover);
        }
        .lang-switcher-desktop a.active {
            color: var(--primary);
            background: var(--badge-bg);
            font-weight: 600;
        }
        .user-menu-desktop {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: 1.5rem;
            padding-left: 1.5rem;
            border-left: 1px solid var(--border);
        }
        .user-avatar-desktop {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .user-menu-desktop a {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast-notification {
            background: var(--success);
            color: white;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            box-shadow: var(--shadow-lg);
            display: none;
            animation: slideIn 0.3s ease;
            font-weight: 500;
        }
        .toast-notification.show {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .toast-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            opacity: 0.9;
            margin-left: 0.5rem;
        }
        .toast-close:hover { opacity: 1; }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        footer {
            background: var(--primary-dark);
            color: rgba(255,255,255,0.85);
            padding: 2.5rem 0 1.5rem;
            margin-top: 4rem;
        }
        footer a { color: rgba(255,255,255,0.9); text-decoration: none; }
        footer a:hover { color: white; }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
            padding: 0.625rem 1.5rem;
            border-radius: 6px;
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: white;
        }
        .btn-outline-primary {
            background: transparent;
            border-color: var(--primary);
            color: var(--primary);
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
        }
        .btn-outline-danger {
            background: transparent;
            border-color: var(--danger);
            color: var(--danger);
        }
        .btn-outline-danger:hover {
            background: var(--danger);
            color: white;
        }
        .container { max-width: 1200px; }
        @media (max-width: 991px) {
            .navbar-toggler {
                border: 1px solid var(--border);
                padding: 0.5rem 0.75rem;
            }
            .lang-switcher-desktop,
            .user-menu-desktop,
            .theme-switcher {
                display: none !important;
            }
        }
        @media (min-width: 992px) {
            .lang-switcher-mobile,
            .user-mobile-section,
            .theme-toggle-mobile {
                display: none !important;
            }
        }
        .form-control, .form-select {
            background: var(--surface);
            border-color: var(--border);
            color: var(--text);
        }
        .form-control:focus, .form-select:focus {
            background: var(--surface);
            border-color: var(--primary);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        }
        .card {
            background: var(--surface);
            border-color: var(--border);
        }
        .alert-success {
            background: rgba(56, 161, 105, 0.15);
            color: var(--success);
            border: 1px solid var(--success);
        }
        .alert-error {
            background: rgba(229, 62, 62, 0.15);
            color: var(--danger);
            border: 1px solid var(--danger);
        }
    </style>
    <!-- 🌙 ТЁМНАЯ ТЕМА: Мгновенное применение темы до рендеринга -->
    <script>
    (function() {
        var savedTheme = localStorage.getItem("theme");
        if (savedTheme) {
            document.documentElement.setAttribute("data-theme", savedTheme);
            document.documentElement.classList.add("no-transitions");
        }
    })();
    </script>
</head>
<body>
    <div class="toast-container">
        <div class="toast-notification" id="cartToast">
            <span id="toastMessage">' . $toastAdded . '</span>
            <button class="toast-close" onclick="hideToast()">×</button>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">' . $siteBrand . '</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/home">' . $navHome . '</a></li>
                    <li class="nav-item"><a class="nav-link" href="/courses">' . $navCourses . '</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">' . $navAbout . '</a></li>
                    <li class="nav-item">
                        <a class="nav-link cart-link" href="/cart">
                            ' . $navCart . '
                            ' . $cartBadge . '
                        </a>
                    </li>
                </ul>
                <div class="lang-switcher-desktop">
                    ' . $langSwitcher . '
                </div>
                <div class="theme-switcher">
                    <button class="theme-toggle-btn" onclick="toggleTheme()" aria-label="' . $themeToggle . '">
                        <svg class="sun-icon" viewBox="0 0 24 24" style="display: ' . ($defaultTheme === 'light' ? 'block' : 'none') . ';">
                            <path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
                        </svg>
                        <svg class="moon-icon" viewBox="0 0 24 24" style="display: ' . ($defaultTheme === 'dark' ? 'block' : 'none') . ';">
                            <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/>
                        </svg>
                    </button>
                </div>
                <div class="user-menu-desktop">
                    ' . ($isLoggedIn ? '
                        <span class="user-avatar-desktop">' . $userInitial . '</span>
                        <a href="/profile" class="btn-profile">' . htmlspecialchars($userName) . '</a>
                        ' . ($isAdmin ? '<a href="/admin" class="btn-admin" style="background:#e53e3e;color:white;padding:0.5rem 1rem;border-radius:6px">' . $authAdmin . '</a>' : '') . '
                        <a href="/auth/logout" class="btn-logout" style="color:var(--danger);border:1px solid var(--danger);padding:0.5rem 1rem;border-radius:6px">' . $authLogout . '</a>
                    ' : '
                        <a href="/auth/login" class="btn-login" style="color:var(--primary);border:1px solid var(--primary);padding:0.5rem 1rem;border-radius:6px">' . $authLogin . '</a>
                        <a href="/auth/register" class="btn-register" style="background:var(--primary);color:white;padding:0.5rem 1rem;border-radius:6px">' . $authRegister . '</a>
                    ') . '
                </div>
                <div class="lang-switcher-mobile">
                    ' . $langSwitcher . '
                </div>
                <div class="theme-toggle-mobile" onclick="toggleTheme()">
                    <svg class="sun-icon" viewBox="0 0 24 24" style="width:20px;height:20px;display: ' . ($defaultTheme === 'light' ? 'block' : 'none') . ';">
                        <path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
                    </svg>
                    <svg class="moon-icon" viewBox="0 0 24 24" style="width:20px;height:20px;display: ' . ($defaultTheme === 'dark' ? 'block' : 'none') . ';">
                        <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/>
                    </svg>
                    <span style="margin-left:0.5rem">' . ($defaultTheme === 'light' ? $themeDark : $themeLight) . '</span>
                </div>
                ' . ($isLoggedIn ? '
                    <div class="user-mobile-section">
                        <div class="user-mobile-info">
                            <span class="user-mobile-avatar">' . $userInitial . '</span>
                            <span class="user-mobile-name">' . htmlspecialchars($userName) . '</span>
                        </div>
                        <div class="user-mobile-buttons">
                            ' . ($isAdmin ? '<a href="/admin" class="btn-mobile-admin">🔧 ' . $authAdmin . '</a>' : '') . '
                            <a href="/profile" class="btn-mobile-profile">👤 ' . $authProfile . '</a>
                            <a href="/auth/logout" class="btn-mobile-logout">🚪 ' . $authLogout . '</a>
                        </div>
                    </div>
                ' : '
                    <div class="user-mobile-section">
                        <div class="user-mobile-buttons">
                            <a href="/auth/login" class="btn-mobile-login">🔐 ' . $authLogin . '</a>
                            <a href="/auth/register" class="btn-mobile-register">📝 ' . $authRegister . '</a>
                        </div>
                    </div>
                ') . '
            </div>
        </div>
    </nav>
    <main>{{CONTENT}}</main>
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold text-white mb-3">' . $footerSiteName . '</h5>
                    <p class="small mb-0">' . $footerDescription . '</p>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold text-white mb-3">' . $footerContactTitle . '</h6>
                    <p class="small mb-1">650066, г. Кемерово, ул. Тухачевского, 32а</p>
                    <p class="small mb-1">
                        <a href="tel:+73842396000">+7 (3842) 39-60-00</a>
                    </p>
                    <p class="small mb-0">
                        <a href="mailto:info@kemt.ru">info@kemt.ru</a>
                    </p>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold text-white mb-3">' . $footerScheduleTitle . '</h6>
                    <p class="small mb-0">' . $footerScheduleValue . '</p>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.15);">
            <p class="small text-center mb-0">
                &copy; ' . date('Y') . ' ' . $footerSiteName . '. ' . $footerCopyright . '
            </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 🌙 ТЁМНАЯ ТЕМА: Функция переключения темы
        function toggleTheme() {
            var html = document.documentElement;
            var currentTheme = html.getAttribute("data-theme");
            var newTheme = currentTheme === "dark" ? "light" : "dark";
            html.setAttribute("data-theme", newTheme);
            localStorage.setItem("theme", newTheme);
            document.querySelectorAll(".sun-icon").forEach(function(icon) {
                icon.style.display = newTheme === "light" ? "block" : "none";
            });
            document.querySelectorAll(".moon-icon").forEach(function(icon) {
                icon.style.display = newTheme === "dark" ? "block" : "none";
            });
            var mobileText = document.querySelector(".theme-toggle-mobile span");
            if (mobileText) {
                mobileText.textContent = newTheme === "light" ? "' . $themeDark . '" : "' . $themeLight . '";
            }
        }
        // 🌙 ТЁМНАЯ ТЕМА: Применение сохранённой темы при загрузке
        document.addEventListener("DOMContentLoaded", function() {
            var savedTheme = localStorage.getItem("theme");
            if (savedTheme) {
                document.documentElement.setAttribute("data-theme", savedTheme);
                document.querySelectorAll(".sun-icon").forEach(function(icon) {
                    icon.style.display = savedTheme === "light" ? "block" : "none";
                });
                document.querySelectorAll(".moon-icon").forEach(function(icon) {
                    icon.style.display = savedTheme === "dark" ? "block" : "none";
                });
                var mobileText = document.querySelector(".theme-toggle-mobile span");
                if (mobileText) {
                    mobileText.textContent = savedTheme === "light" ? "' . $themeDark . '" : "' . $themeLight . '";
                }
            }
            // ✅ Включаем переходы после загрузки страницы
            setTimeout(function() {
                document.documentElement.classList.remove("no-transitions");
                document.body.classList.add("loaded");
            }, 100);
        });
        function showToast(message, duration) {
            duration = duration || 3000;
            var toast = document.getElementById("cartToast");
            var toastMessage = document.getElementById("toastMessage");
            toastMessage.textContent = message;
            toast.classList.add("show");
            setTimeout(function() { hideToast(); }, duration);
        }
        function hideToast() {
            document.getElementById("cartToast").classList.remove("show");
        }
        function updateCartCount(count) {
            var badge = document.querySelector(".cart-count");
            var cartLink = document.querySelector(".cart-link");
            if (count > 0) {
                if (!badge) {
                    var newBadge = document.createElement("span");
                    newBadge.className = "cart-count";
                    newBadge.textContent = count;
                    cartLink.appendChild(newBadge);
                } else {
                    badge.textContent = count;
                }
            } else if (badge) {
                badge.remove();
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            var forms = document.querySelectorAll("form[action=\"/cart/add\"]");
            forms.forEach(function(form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    fetch("/cart/add", {
                        method: "POST",
                        body: formData,
                        headers: { "X-Requested-With": "XMLHttpRequest" }
                    })
                    .then(function(response) {
                        if (response.ok) {
                            showToast("' . $toastAdded . '");
                            fetch("/cart/count").then(function(r) { return r.json(); }).then(function(data) {
                                updateCartCount(data.count);
                            });
                        } else {
                            showToast("' . $toastError . '");
                        }
                    }).catch(function() { showToast("' . $toastNetwork . '"); });
                });
            });
        });
    </script>
</body>
</html>';
    }

    private static function getLanguageSwitcher(string $currentLang): string
    {
        $langs = ['ru', 'en'];
        $html = '';
        foreach ($langs as $lang) {
            $activeClass = ($lang === $currentLang) ? 'active' : '';
            $langName = Language::getLangName($lang);
            $html .= '<a href="?lang=' . $lang . '" class="' . $activeClass . '">' . $langName . '</a>';
            if ($lang !== end($langs)) {
                $html .= '<span class="lang-divider">|</span>';
            }
        }
        return $html;
    }
}
