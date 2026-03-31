<?php
/**
 * Шаблон страницы каталога
 * Доступные переменные:
 * - $search - текущий поисковый запрос
 * - $searchInfo - HTML с информацией о результатах поиска
 * - $productsGrid - HTML сетки товаров
 * - $texts - массив текстов из storage/templates/catalog.json
 */

// Значения по умолчанию
$texts = $texts ?? [];
$pageTitle = $texts['pageTitle'] ?? 'Каталог товаров';
$titleText = $texts['title'] ?? 'Каталог товаров';
$searchText = $texts['search'] ?? [];
$toastText = $texts['toast'] ?? [];
$modalText = $texts['modal'] ?? [];
?>

<div class="container py-5">
    <!-- Заголовок + Поиск -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold mb-4"><?= htmlspecialchars($titleText) ?></h1>

            <!-- Форма поиска -->
            <form method="GET" action="/catalog" class="col-md-6 col-lg-4 mx-auto">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control border-start-0 ps-0" 
                        placeholder="<?= htmlspecialchars($searchText['placeholder'] ?? 'Поиск товаров...') ?>"
                        value="<?= htmlspecialchars($search) ?>"
                        aria-label="Поиск">
                    <button class="btn btn-primary px-4" type="submit"><?= htmlspecialchars($searchText['button'] ?? 'Найти') ?></button>
                </div>
            </form>
            
            <!-- Результаты поиска -->
            <?= $searchInfo ?>
        </div>
    </div>
    
    <!-- Сетка товаров -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?= $productsGrid ?>
    </div>
</div>

<!-- Полноэкранный Toast -->
<div id="fullscreenToast" class="fullscreen-toast" aria-hidden="true">
    <button class="toast-close" id="closeFullscreenToast" aria-label="Закрыть">
        <i class="bi bi-x-lg"></i>
    </button>
    
    <video class="toast-video" id="toastVideo" autoplay muted playsinline loop>
        <source src="/assets/img/cart_video.mp4" type="video/mp4">
        Ваш браузер не поддерживает видео.
    </video>
    
    <div class="toast-message" id="fullscreenToastMessage">
        <?= htmlspecialchars($toastText['addedWithEmoji'] ?? '🎉 Товар добавлен в корзину!') ?>
    </div>
</div>

<!-- Стандартный Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="cartToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                <span id="toastMessage"><?= htmlspecialchars($toastText['added'] ?? 'Товар добавлен в корзину!') ?></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toastEl = document.getElementById("cartToast");
    if (toastEl && typeof bootstrap !== "undefined") {
        window.cartToast = new bootstrap.Toast(toastEl, { delay: 3000 });
    }
    
    // Закрытие полноэкранного toast
    document.getElementById('closeFullscreenToast')?.addEventListener('click', function() {
        const toast = document.getElementById('fullscreenToast');
        toast.classList.remove('show');
        toast.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('toast-open');
    });
    
    // Обработчики для кнопок "Подробнее"
    document.querySelectorAll('.btn-product-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.id;
            loadProductModal(productId);
        });
    });
});

// Загрузка данных товара и показ модального окна
async function loadProductModal(productId) {
    const modalEl = document.getElementById('productModal');
    const modalBody = modalEl.querySelector('.modal-body');
    const modal = new bootstrap.Modal(modalEl);
    
    // Показать загрузку
    modalBody.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';
    modal.show();
    
    try {
        const response = await fetch('/api/product/' + productId);
        const data = await response.json();
        
        if (data.notFound) {
            modalBody.innerHTML = '<div class="alert alert-warning">Товар не найден</div>';
            return;
        }
        
        const product = data;
        const priceFormatted = new Intl.NumberFormat('ru-RU').format(product.price);
        const image = product.image || '/assets/img/no-image.jpg';
        const fallbackImage = '/assets/img/error.jpg';
        
        // Проверить, есть ли товар в корзине
        const cart = CartManager ? CartManager.get() : [];
        const cartItem = cart.find(item => item.id === product.id);
        const inCart = !!cartItem;
        const quantity = cartItem ? cartItem.quantity : 1;
        
        const productJson = JSON.stringify({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image
        }).replace(/"/g, '&quot;');
        
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-5 mb-3 mb-md-0">
                    <img src="${image}" 
                         class="img-fluid rounded-3 shadow-sm" 
                         alt="${product.name}"
                         style="width: 100%; max-height: 300px; object-fit: contain;"
                         onerror="this.src='${fallbackImage}'; this.onerror=null;">
                </div>
                <div class="col-md-7">
                    <h5 class="text-uppercase fw-bold ls-1 mb-2 text-muted" style="font-size: 0.8rem;">Наше меню</h5>
                    <h3 class="card-title fw-bold mb-3">${product.name}</h3>
                    <p class="card-text text-muted" style="line-height: 1.6;">${product.description}</p>
                    
                    <div class="d-flex align-items-center mb-3">
                        <span class="h3 fw-bold me-3">${priceFormatted} ₽</span>
                        <span class="badge bg-success px-3 py-2 rounded-pill">В наличии</span>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2">
                        ${inCart ? `
                            <div class="input-group input-group-sm" style="max-width: 130px;">
                                <button class="btn btn-outline-secondary btn-qty-modal" data-action="decrease" data-id="${product.id}">−</button>
                                <input type="number" class="form-control text-center qty-input-modal" 
                                       value="${quantity}" min="1" data-id="${product.id}">
                                <button class="btn btn-outline-secondary btn-qty-modal" data-action="increase" data-id="${product.id}">+</button>
                            </div>
                        ` : `
                            <button type="button" 
                                    class="btn btn-primary btn-add-to-cart"
                                    data-product="${productJson}"
                                    data-id="${product.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus me-1" viewBox="0 0 16 16">
                                    <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                                В корзину
                            </button>
                        `}
                    </div>
                </div>
            </div>
        `;
        
        // Добавить обработчики для кнопок в модальном окне
        initModalCartHandlers(product.id);
        
    } catch (error) {
        modalBody.innerHTML = '<div class="alert alert-danger">Ошибка загрузки товара</div>';
    }
}

// Инициализация обработчиков корзины в модальном окне
function initModalCartHandlers(productId) {
    // Кнопка "В корзину"
    const addBtn = document.querySelector(`#productModal .btn-add-to-cart[data-id="${productId}"]`);
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            const product = JSON.parse(this.dataset.product);
            CartManager.add(product);
            CartManager.showToast('"' + product.name + '" добавлен в корзину!', 'success', false);
            
            // Переключиться на блок управления количеством
            const modalBody = document.querySelector('#productModal .modal-body');
            const cart = CartManager.get();
            const item = cart.find(i => i.id === productId);
            
            if (item) {
                const productJson = JSON.stringify({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image
                }).replace(/"/g, '&quot;');
                
                const controlsHtml = `
                    <div class="input-group input-group-sm" style="max-width: 130px;">
                        <button class="btn btn-outline-secondary btn-qty-modal" data-action="decrease" data-id="${product.id}">−</button>
                        <input type="number" class="form-control text-center qty-input-modal" 
                               value="${item.quantity}" min="1" data-id="${product.id}">
                        <button class="btn btn-outline-secondary btn-qty-modal" data-action="increase" data-id="${product.id}">+</button>
                    </div>
                `;
                
                this.outerHTML = controlsHtml;
                initModalCartHandlers(productId);
            }
        });
    }
    
    // Кнопки +/- в модальном окне
    document.querySelectorAll(`#productModal .btn-qty-modal`).forEach(btn => {
        btn.addEventListener('click', function() {
            const id = parseInt(this.dataset.id);
            const input = document.querySelector(`#productModal .qty-input-modal[data-id="${id}"]`);
            let value = parseInt(input.value) || 1;
            
            if (this.dataset.action === 'increase') {
                value++;
            } else {
                value = Math.max(0, value - 1);
            }
            
            input.value = value;
            
            if (value === 0) {
                CartManager.remove(id);
                CartManager.showToast('Товар удалён', 'success', false);
                // Вернуть кнопку "В корзину"
                loadProductModal(id);
            } else {
                CartManager.updateQuantity(id, value);
            }
        });
    });
    
    // Прямой ввод количества
    document.querySelectorAll(`#productModal .qty-input-modal`).forEach(input => {
        input.addEventListener('change', function() {
            const id = parseInt(this.dataset.id);
            const value = parseInt(this.value) || 1;
            
            if (value <= 0) {
                CartManager.remove(id);
                CartManager.showToast('Товар удалён', 'success', false);
                loadProductModal(id);
            } else {
                CartManager.updateQuantity(id, value);
            }
        });
    });
}
</script>

<!-- Модальное окно товара -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Информация о товаре</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <!-- Загружается через JS -->
            </div>
        </div>
    </div>
</div>
