<?php
/**
 * Шаблон главной страницы
 * Доступные переменные:
 * - $productsHtml - HTML карточек товаров
 * - $showCatalog - показывать ли каталог на главной
 * - $texts - массив текстов из storage/templates/home.json
 */

// Значения по умолчанию, если файл не загружен
$texts = $texts ?? [];
$hero = $texts['hero'] ?? [];
$features = $texts['features'] ?? [];
$catalog = $texts['catalog'] ?? [];
$image = $texts['image'] ?? [];
?>

<!-- Герой-блок -->
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold"><?= htmlspecialchars($hero['title'] ?? 'Добро пожаловать на сайт запчастей для всех марок авто!') ?></h1>
        <p class="lead"><?= htmlspecialchars($hero['subtitle'] ?? 'Запчасти разных марок в наличии и под заказ.') ?></p>
        <a href="/catalog" class="btn btn-dark btn-lg mt-3"><?= htmlspecialchars($hero['catalogButton'] ?? 'Каталог') ?></a>
    </div>
</div>

<div class="container">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="mb-3"><?= htmlspecialchars($features['title'] ?? 'Почему выбирают нас?') ?></h2>
            <ul class="list-group list-group-flush">
                <?php foreach ($features['items'] ?? ['Оригинальные запчасти', 'Наличие большого количества деталей', 'Доступные цены'] as $item): ?>
                <li class="list-group-item"><i class="bi bi-check-circle-fill text-success"></i> <?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <img src="/assets/img/keep_out.png" 
                 alt="<?= htmlspecialchars($image['alt'] ?? 'Запчасти') ?>" 
                 class="img-fluid rounded shadow-lg"
                 onerror="this.src='/assets/img/error.jpg';">
        </div>
    </div>
    
    <?php if ($showCatalog): ?>
    <!-- Секция с товарами -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4"><?= htmlspecialchars($catalog['title'] ?? 'Каталог') ?></h2>
            <?= $productsHtml ?>
        </div>
    </div>
    <?php endif; ?>
</div>
