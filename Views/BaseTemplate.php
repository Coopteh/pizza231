<?php
namespace Views;
use Lib\Language;

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

        // 🎨 СВЕТЛАЯ ТЕМА: Чистый современный дизайн
        return '
        <!DOCTYPE html>
        <html lang="' . $currentLang . '">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{{TITLE}}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
            <style>
            :root {
                --primary: #00c896;
                --primary-dark: #00a87d;
                --primary-light: #e8f7f3;
                --primary-glow: rgba(0, 200, 150, 0.15);
                --bg-main: #ffffff;
                --bg-secondary: #f8f9fa;
                --bg-accent: #f0f4f8;
                --text-primary: #1a1a2e;
                --text-secondary: #4a5568;
                --text-muted: #718096;
                --border: #e2e8f0;
                --border-light: #edf2f7;
                --success: #00c896;
                --danger: #e53e3e;
                --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
                --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.06);
                --shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.08);
                --shadow-hover: 0 12px 40px rgba(0, 200, 150, 0.15);
                --gradient-primary: linear-gradient(135deg, #00c896 0%, #00a87d 100%);
                --gradient-soft: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
                --radius-sm: 8px;
                --radius-md: 12px;
                --radius-lg: 16px;
                --radius-xl: 24px;
            }
            * { box-sizing: border-box; margin: 0; padding: 0; }
            html { scroll-behavior: smooth; }
            body {
                font-family: "Manrope", "Noto Sans JP", system-ui, -apple-system, sans-serif;
                color: var(--text-primary);
                background: var(--bg-main);
                line-height: 1.6;
                overflow-x: hidden;
                font-size: 16px;
            }
            
            /* ===== HEADER ===== */
            .navbar {
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(20px);
                border-bottom: 1px solid var(--border-light);
                padding: 1rem 0;
                position: sticky;
                top: 0;
                z-index: 1000;
                transition: all 0.3s ease;
            }
            .navbar.scrolled {
                box-shadow: var(--shadow-md);
            }
            .navbar-brand {
                font-weight: 800;
                color: var(--text-primary) !important;
                font-size: 1.5rem;
                letter-spacing: -0.5px;
            }
            .navbar-brand span {
                color: var(--primary);
            }
            .nav-link {
                color: var(--text-secondary) !important;
                font-weight: 600;
                margin: 0 0.5rem;
                font-size: 0.95rem;
                padding: 0.5rem 1rem !important;
                border-radius: var(--radius-sm);
                transition: all 0.2s ease;
            }
            .nav-link:hover, .nav-link.active {
                color: var(--primary) !important;
                background: var(--primary-light);
            }
            
            .cart-count {
                background: var(--primary);
                color: white;
                border-radius: 50%;
                padding: 0.2rem 0.5rem;
                font-size: 0.7rem;
                font-weight: 700;
                position: absolute;
                top: -6px;
                right: -8px;
                min-width: 20px;
                text-align: center;
            }
            
            /* ===== LANGUAGE SWITCHER ===== */
            .lang-switcher {
                display: flex;
                align-items: center;
                gap: 0.4rem;
                margin-left: 1rem;
            }
            .lang-switcher a {
                padding: 0.35rem 0.7rem;
                border-radius: var(--radius-sm);
                text-decoration: none;
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--text-muted);
                transition: all 0.2s;
                border: 1px solid transparent;
            }
            .lang-switcher a:hover {
                color: var(--primary);
                background: var(--primary-light);
            }
            .lang-switcher a.active {
                color: var(--primary);
                background: var(--primary-light);
                border-color: var(--primary);
                font-weight: 700;
            }
            
            /* ===== BUTTONS ===== */
            .btn-primary {
                background: var(--gradient-primary);
                border: none;
                color: white;
                font-weight: 700;
                padding: 0.875rem 2.25rem;
                border-radius: var(--radius-md);
                transition: all 0.3s ease;
                text-transform: none;
                font-size: 0.95rem;
                letter-spacing: 0;
                box-shadow: 0 4px 15px var(--primary-glow);
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-hover);
                color: white;
            }
            .btn-outline-primary {
                background: transparent;
                border: 2px solid var(--primary);
                color: var(--primary);
                font-weight: 600;
                padding: 0.75rem 2rem;
                border-radius: var(--radius-md);
                transition: all 0.3s ease;
            }
            .btn-outline-primary:hover {
                background: var(--primary);
                color: white;
                transform: translateY(-2px);
            }
            .btn-cta {
                background: var(--gradient-primary);
                border: none;
                color: white;
                font-weight: 700;
                padding: 1rem 2.75rem;
                border-radius: var(--radius-lg);
                transition: all 0.3s ease;
                font-size: 1rem;
                box-shadow: 0 6px 20px var(--primary-glow);
                display: inline-block;
                text-decoration: none;
            }
            .btn-cta:hover {
                transform: translateY(-3px);
                box-shadow: var(--shadow-hover);
                color: white;
                text-decoration: none;
            }
            
            /* ===== CARDS ===== */
            .card {
                background: var(--bg-main);
                border: 1px solid var(--border-light);
                border-radius: var(--radius-lg);
                transition: all 0.3s ease;
                box-shadow: var(--shadow-sm);
            }
            .card:hover {
                border-color: var(--primary);
                box-shadow: var(--shadow-hover);
                transform: translateY(-4px);
            }
            
            /* ===== TOAST ===== */
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
            }
            .toast-notification {
                background: var(--bg-main);
                color: var(--text-primary);
                border: 2px solid var(--primary);
                border-radius: var(--radius-md);
                padding: 1rem 1.5rem;
                box-shadow: var(--shadow-lg);
                display: none;
                animation: slideIn 0.3s ease;
                font-weight: 600;
            }
            .toast-notification.show {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            .toast-close {
                background: none;
                border: none;
                color: var(--text-muted);
                font-size: 1.5rem;
                cursor: pointer;
                margin-left: 0.5rem;
                transition: color 0.2s;
            }
            .toast-close:hover {
                color: var(--primary);
            }
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            /* ===== FOOTER ===== */
            footer {
                background: var(--bg-secondary);
                color: var(--text-secondary);
                padding: 3.5rem 0 2rem;
                margin-top: 5rem;
                border-top: 1px solid var(--border-light);
            }
            footer h5, footer h6 {
                color: var(--text-primary);
                font-weight: 700;
            }
            footer a {
                color: var(--text-secondary);
                text-decoration: none;
                transition: color 0.2s;
            }
            footer a:hover {
                color: var(--primary);
            }
            
            /* ===== UTILITIES ===== */
            .text-gradient {
                background: var(--gradient-primary);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .bg-gradient-primary {
                background: var(--gradient-primary);
            }
            .border-primary {
                border-color: var(--primary) !important;
            }
            .container {
                max-width: 1200px;
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            
            /* ===== HERO SECTION ===== */
            .hero-section {
                background: var(--gradient-soft);
                border-radius: 0 0 var(--radius-xl) var(--radius-xl);
                padding: 5rem 0;
                margin-bottom: 3rem;
                position: relative;
                overflow: hidden;
            }
            .hero-section::before {
                content: "";
                position: absolute;
                top: -30%;
                right: -10%;
                width: 500px;
                height: 500px;
                background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
                opacity: 0.5;
                pointer-events: none;
            }
            
            /* ===== FORMS ===== */
            .form-control {
                background: var(--bg-main);
                border: 1.5px solid var(--border);
                color: var(--text-primary);
                border-radius: var(--radius-md);
                padding: 0.875rem 1.25rem;
                font-size: 0.95rem;
                transition: all 0.2s ease;
            }
            .form-control:focus {
                background: var(--bg-main);
                border-color: var(--primary);
                color: var(--text-primary);
                box-shadow: 0 0 0 4px var(--primary-glow);
                outline: none;
            }
            .form-control::placeholder {
                color: var(--text-muted);
            }
            .form-label {
                color: var(--text-primary);
                font-weight: 600;
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }
            
            /* ===== BADGES ===== */
            .badge-hot {
                background: var(--gradient-primary);
                color: white;
                padding: 0.4rem 1rem;
                border-radius: 50px;
                font-weight: 700;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .badge-soft {
                background: var(--primary-light);
                color: var(--primary-dark);
                padding: 0.35rem 0.85rem;
                border-radius: 50px;
                font-weight: 600;
                font-size: 0.8rem;
            }
            
            /* ===== SECTIONS ===== */
            .section-title {
                font-size: 2.25rem;
                font-weight: 800;
                color: var(--text-primary);
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
            }
            .section-subtitle {
                font-size: 1.1rem;
                color: var(--text-muted);
                max-width: 600px;
                margin: 0 auto;
            }
            .section-divider {
                width: 60px;
                height: 4px;
                background: var(--gradient-primary);
                margin: 1rem auto 2rem auto;
                border-radius: 2px;
            }
            
            /* ===== RESPONSIVE ===== */
            @media (max-width: 991px) {
                .navbar-collapse {
                    background: var(--bg-main);
                    padding: 1rem;
                    border-radius: var(--radius-md);
                    margin-top: 1rem;
                    box-shadow: var(--shadow-md);
                }
                .hero-section {
                    padding: 3rem 0;
                }
                .section-title {
                    font-size: 1.75rem;
                }
            }
            @media (max-width: 767px) {
                .btn-cta {
                    padding: 0.875rem 2rem;
                    font-size: 0.95rem;
                }
            }
            </style>
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
                <a class="navbar-brand" href="/"><span>GYM</span> low cortisol</a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link" href="/home">' . $navHome . '</a></li>
                        <li class="nav-item"><a class="nav-link" href="/courses">' . $navCourses . '</a></li>
                        <li class="nav-item"><a class="nav-link" href="/about">' . $navAbout . '</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cart" style="position: relative;">
                                ' . $navCart . ' ' . $cartBadge . '
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="lang-switcher">' . $langSwitcher . '</div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <main>{{CONTENT}}</main>
        
        <footer>
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <h5 class="mb-3">' . $footerSiteName . '</h5>
                        <p class="small mb-0" style="color: var(--text-muted);">' . $footerDescription . '</p>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="mb-3">' . $footerContactTitle . '</h6>
                        <p class="small mb-1" style="color: var(--text-muted);">650000, г. Кемерово, пр. Советский, 15</p>
                        <p class="small mb-1">
                            <a href="tel:+79991234567" style="color: var(--primary); font-weight: 600;">
                                +7 (999) 123-45-67
                            </a>
                        </p>
                        <p class="small mb-0">
                            <a href="mailto:info@gymlowcortisol.ru">info@gymlowcortisol.ru</a>
                        </p>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="mb-3">' . $footerScheduleTitle . '</h6>
                        <p class="small mb-0" style="color: var(--text-muted);">' . $footerScheduleValue . '</p>
                    </div>
                </div>
                <hr style="border-color: var(--border-light); margin: 2.5rem 0;">
                <p class="small text-center mb-0" style="color: var(--text-muted);">
                    &copy; ' . date('Y') . ' ' . $footerSiteName . '. ' . $footerCopyright . '
                </p>
            </div>
        </footer>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        // Toast notifications
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
        
        // Cart count update
        function updateCartCount(count) {
            var badge = document.querySelector(".cart-count");
            var cartLink = document.querySelector("a[href=\'/cart\']");
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
        
        // Navbar scroll effect
        window.addEventListener("scroll", function() {
            var navbar = document.querySelector(".navbar");
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
        
        // Form handlers
        document.addEventListener("DOMContentLoaded", function() {
            var forms = document.querySelectorAll("form[action=\'/cart/add\']");
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
                            fetch("/cart/count").then(function(r) { return r.json(); })
                                .then(function(data) { updateCartCount(data.count); });
                        } else {
                            showToast("' . $toastError . '");
                        }
                    }).catch(function() {
                        showToast("' . $toastNetwork . '");
                    });
                });
            });
        });
        </script>
        </body>
        </html>';
    }
    
    private static function getLanguageSwitcher(string $currentLang): string
    {
        $langs = ['ru', 'en', 'ja'];
        $html = '';
        foreach ($langs as $lang) {
            $activeClass = ($lang === $currentLang) ? 'active' : '';
            $langName = Language::getLangName($lang);
            $html .= '<a href="?lang=' . $lang . '" class="' . $activeClass . '">' . $langName . '</a>';
            if ($lang !== end($langs)) {
                $html .= '<span style="color: var(--border);">|</span>';
            }
        }
        return $html;
    }
}