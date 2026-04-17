<?php
namespace Views;

class CatalogTemplate extends BaseTemplate
{
    public static function render(array $products): string
    {
        $template = parent::getTemplate();
        $title = 'Каталог услуг — Страховая компания «Чёрный Вантуз»';
        $itemsHtml = '';
        
        foreach ($products as $product) {
            $priceDisplay = $product['price'] > 0 
                ? number_format($product['price'], 0, '.', ' ') . ' ₽/' . htmlspecialchars($product['period']) 
                : 'По запросу';
            
            $isAuth = isset($_SESSION['user_id']);
            
            // 🔐 Кнопка "В корзину" с данными для JS-уведомлений
            if ($isAuth && $product['price'] > 0) {
                // Добавляем data-атрибуты для обработки через JS (AJAX)
                $addToCartBtn = '<form method="POST" action="/cart/add" class="d-inline add-to-cart-form" data-product-id="'.$product['id'].'" data-product-name="'.htmlspecialchars($product['name']).'"><input type="hidden" name="id" value="'.$product['id'].'"><button type="submit" class="btn btn-primary rounded-pill px-4">В корзину</button></form>';
            } elseif ($product['price'] > 0) {
                // Кнопка для неавторизованных с триггером уведомления
                $addToCartBtn = '<button type="button" class="btn btn-outline-secondary rounded-pill px-4 btn-need-auth" data-product-name="'.htmlspecialchars($product['name']).'">В корзину</button>';
            } else {
                $addToCartBtn = '';
            }
            
            $itemsHtml .= '
            <div class="card border-0 shadow-sm mb-4 hover-shadow transition">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="'.htmlspecialchars($product['image']).'" 
                             class="img-fluid h-100 w-100" 
                             style="object-fit:cover;min-height:200px" 
                             alt="'.htmlspecialchars($product['name']).'" 
                             onerror="this.src=\'https://placehold.co/400x300/0d6efd/ffffff?text='.urlencode($product['name']).'\'">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body p-4">
                            <h3 class="h4 fw-bold mb-2">
                                <a href="/product/'.$product['id'].'" class="text-decoration-none text-dark hover-primary">
                                    '.htmlspecialchars($product['name']).'
                                </a>
                            </h3>
                            <p class="text-muted mb-3">'.htmlspecialchars($product['description']).'</p>
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-3">'.$priceDisplay.'</span>
                            <div class="d-flex gap-2">
                                <a href="/product/'.$product['id'].'" class="btn btn-outline-primary rounded-pill px-4">Подробнее</a>
                                '.$addToCartBtn.'
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        // 🔐 Модальное окно авторизации (оставляем как резервный вариант)
        $authModal = '
        <div class="modal fade" id="authModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">🔐 Требуется авторизация</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-4">Чтобы добавить товар в корзину, пожалуйста, войдите в аккаунт или зарегистрируйтесь.</p>
                        <div class="d-grid gap-2">
                            <a href="/login" class="btn btn-primary rounded-pill">Войти</a>
                            <a href="/register" class="btn btn-outline-primary rounded-pill">Зарегистрироваться</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

        // 🔔 Контейнер для Toast-уведомлений (только для каталога)
        $toastContainer = '
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
            <!-- Динамические уведомления будут добавляться здесь -->
        </div>';
        
        // 🎨 Стили и Скрипты для уведомлений (встроены, чтобы не менять другие файлы)
        $toastAssets = '
        <style>
            /* Профессиональные стили для уведомлений */
            .toast-custom {
                border: none;
                border-radius: 12px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.15);
                background: #fff;
                overflow: hidden;
                min-width: 320px;
                max-width: 400px;
                animation: slideIn 0.3s ease-out;
            }
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            .toast-custom.success { border-left: 5px solid #198754; }
            .toast-custom.error { border-left: 5px solid #dc3545; }
            .toast-custom.warning { border-left: 5px solid #ffc107; }
            
            .toast-custom .toast-header {
                background: transparent;
                border-bottom: none;
                padding: 12px 16px 8px;
            }
            .toast-custom .toast-body {
                padding: 8px 16px 16px;
                font-size: 0.95rem;
                color: #333;
            }
            .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; transition: box-shadow 0.2s; }
            .transition { transition: all 0.2s ease-in-out; }
            a.hover-primary:hover { color: #0d6efd !important; }
        </style>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Функция создания и показа уведомления
            window.showCatalogToast = function(title, message, type = "success") {
                const container = document.querySelector(".toast-container");
                const toastId = "toast-" + Date.now();
                
                const icons = { success: "✅", error: "⚠️", warning: "ℹ️" };
                const icon = icons[type] || "ℹ️";
                
                const toastHTML = `
                    <div id="${toastId}" class="toast toast-custom ${type} hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                        <div class="toast-header">
                            <strong class="me-auto fw-bold">${icon} ${title}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">${message}</div>
                    </div>`;
                
                container.insertAdjacentHTML("beforeend", toastHTML);
                const toastEl = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                
                toastEl.addEventListener("hidden.bs.toast", () => toastEl.remove());
                toast.show();
            };

            // 1. Обработка добавления в корзину (AJAX)
            document.querySelectorAll(".add-to-cart-form").forEach(form => {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    const btn = this.querySelector("button");
                    const originalText = btn.innerHTML;
                    const productName = this.dataset.productName || "Товар";
                    
                    // Визуальная обратная связь на кнопке
                    btn.disabled = true;
                    btn.innerHTML = "<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> Добавление...";
                    
                    fetch(this.action, { method: "POST", body: new FormData(this) })
                    .then(response => {
                        if (response.ok || response.redirected) {
                            showCatalogToast("Успешно!", `\`${productName}\` добавлен в корзину`, "success");
                            // Обновляем счетчик в меню, если он есть
                            const badge = document.querySelector(".navbar-nav .badge");
                            if(badge) badge.textContent = parseInt(badge.textContent || 0) + 1;
                            return true;
                        }
                        throw new Error("Ошибка сети");
                    })
                    .catch(err => {
                        console.error(err);
                        showCatalogToast("Ошибка", "Не удалось добавить товар. Попробуйте позже.", "error");
                        // Если AJAX не сработал, отправляем форму классически
                        this.submit(); 
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
                });
            });

            // 2. Обработка клика без авторизации
            document.querySelectorAll(".btn-need-auth").forEach(btn => {
                btn.addEventListener("click", function() {
                    const name = this.dataset.productName || "Этот товар";
                    showCatalogToast("Вход в аккаунт", `Войдите, чтобы добавить \`${name}\` в корзину`, "warning");
                    // Опционально: открыть модальное окно
                    // var modal = new bootstrap.Modal(document.getElementById("authModal")); modal.show();
                });
            });
        });
        </script>';
        
        // Собираем контент: товары + модалка + уведомления + стили/скрипты
        $content = '<section class="container py-5">
                        <h2 class="text-center mb-4">Каталог услуг</h2>
                        '.$itemsHtml.'
                        '.$authModal.'
                        '.$toastContainer.'
                        '.$toastAssets.'
                    </section>';
        
        return sprintf($template, $title, $content);
    }
}