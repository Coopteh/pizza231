<?php
/**
 * Шаблон страницы товара
 * Доступные переменные:
 * - $id          - ID товара
 * - $title       - Название товара
 * - $description - Описание товара
 * - $price       - Цена товара
 * - $priceFormatted - Отформатированная цена
 * - $image       - URL изображения товара
 * - $fallbackImage - URL изображения по умолчанию
 * - $productJson - JSON с данными товара для JavaScript
 * - $notFound    - boolean, флаг что товар не найден
 * - $texts       - массив текстов из storage/templates/product.json
 */

// Значения по умолчанию, если файл не загружен
$texts = $texts ?? [];
$notFoundText = $texts['notFound'] ?? [];
$productText = $texts['product'] ?? [];
$featuresText = $texts['features'] ?? [];
$toastText = $texts['toast'] ?? [];
?>

<?php if ($notFound): ?>
<div class="container mt-5">
    <div class="alert alert-warning text-center shadow-sm" role="alert">
        <h4 class="alert-heading"><?= htmlspecialchars($notFoundText['title'] ?? 'Товар не найден!') ?></h4>
        <p><?= htmlspecialchars($notFoundText['message'] ?? 'К сожалению, товар с таким идентификатором отсутствует в нашем каталоге.') ?></p>
        <hr>
        <a href="/catalog" class="btn btn-outline-warning"><?= htmlspecialchars($notFoundText['backLink'] ?? 'Вернуться в каталог') ?></a>
    </div>
</div>
<?php else: ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <!-- Карточка товара -->
            <div class="card shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0 h-100">
                    
                    <!-- Блок с изображением -->
                    <div class="col-md-5 col-lg-4 d-flex align-items-center justify-content-center p-4 bg-light" style="min-height: 300px;">
                        <img src="<?= $image ?>" 
                             class="img-fluid rounded-3 shadow-sm" 
                             alt="<?= $title ?>" 
                             style="max-height: 350px; width: 100%; object-fit: contain;"
                             onerror="this.src='<?= $fallbackImage ?>'; this.onerror=null;">
                    </div>
                    
                    <!-- Блок с информацией -->
                    <div class="col-md-7 col-lg-8">
                        <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                            
                            <!-- Заголовок секции -->
                            <h5 class="text-uppercase fw-bold ls-1 mb-2 text-muted" style="font-size: 0.9rem;"><?= htmlspecialchars($productText['label'] ?? 'Наше меню') ?></h5>
                            
                            <!-- Название товара -->
                            <h2 class="card-title display-6 fw-bold mb-3"><?= $title ?></h2>
                            
                            <!-- Описание -->
                            <p class="card-text lead mb-4 text-muted" style="line-height: 1.6;">
                                <?= $description ?>
                            </p>
                            
                            <!-- Цена и статус -->
                            <div class="mt-auto">
                                <div class="d-flex align-items-center mb-4">
                                    <span class="display-5 fw-bold me-3"><?= $priceFormatted ?> ₽</span>
                                    <span class="badge bg-success px-3 py-2 rounded-pill"><?= htmlspecialchars($productText['inStock'] ?? 'В наличии') ?></span>
                                </div>
                                
                                <!-- Кнопки -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                    <button type="button" 
                                            class="btn btn-primary btn-lg px-4 me-md-2 fw-bold shadow-sm btn-add-to-cart"
                                            data-product='<?= $productJson ?>'
                                            id="add-to-cart-<?= $id ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cart-plus me-2" viewBox="0 0 16 16">
                                            <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                                            <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                        </svg>
                                        <span class="btn-text"><?= htmlspecialchars($productText['addToCart'] ?? 'В корзину') ?></span>
                                    </button>
                                    <a href="/" class="btn btn-outline-dark btn-lg px-4"><?= htmlspecialchars($productText['goHome'] ?? 'На главную') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Дополнительная информация -->
            <div class="row mt-4 text-center small text-muted">
                <div class="col-4">
                    <i class="bi bi-truck me-1"></i> <?= htmlspecialchars($featuresText['delivery'] ?? 'Быстрая доставка') ?>
                </div>
                <div class="col-4">
                    <i class="bi bi-shield-check me-1"></i> <?= htmlspecialchars($featuresText['warranty'] ?? 'Гарантия качества') ?>
                </div>
                <div class="col-4">
                    <i class="bi bi-heart me-1"></i> <?= htmlspecialchars($featuresText['fresh'] ?? 'Свежие ингредиенты') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast-уведомление -->
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

<!-- Скрипт корзины -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toastEl = document.getElementById("cartToast");
    if (toastEl && typeof bootstrap !== "undefined") {
        window.cartToast = new bootstrap.Toast(toastEl, { delay: 3000 });
    }
});
</script>
<?php endif; ?>
