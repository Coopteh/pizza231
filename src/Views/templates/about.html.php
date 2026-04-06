<?php
/**
 * Шаблон страницы "О нас"
 * Доступные переменные:
 * - $texts - массив текстов из storage/templates/about.json + base.json
 */

// Значения по умолчанию
$texts = $texts ?? [];
$history = $texts['history'] ?? [];
$contacts = $texts['contacts'] ?? [];
$mapId = 'YOUR_CONSTRUCTOR_ID';
?>

<div class="container mt-4">
    <h1 class="text-center mb-4"><?= htmlspecialchars($texts['title'] ?? 'О нашем техникуме') ?></h1>
    
    <div class="row">
        <div class="col-md-8">
            <p class="lead">
                <?= htmlspecialchars($texts['intro'] ?? '') ?>
            </p>
            
            <p>
                <?= htmlspecialchars($history['founded'] ?? 'Техникум был основан в') ?> 
                <strong><?= htmlspecialchars($history['foundedYear'] ?? '1974 году') ?></strong> 
                <?= htmlspecialchars($history['reason'] ?? '') ?>
            </p>
            
            <p>
                <?= htmlspecialchars($history['graduates'] ?? '') ?> 
                <strong><?= htmlspecialchars($history['graduatesCount'] ?? '') ?></strong> 
                <?= htmlspecialchars($history['forText'] ?? '') ?>
            </p>
            
            <p>
                <?= htmlspecialchars($history['teachers'] ?? '') ?>
            </p>
            
            <h4 class="mt-4"><?= htmlspecialchars($contacts['title'] ?? 'Контакты') ?></h4>
            <ul class="list-unstyled">
                <li>📍 <?= htmlspecialchars($contacts['address'] ?? 'Адрес:') ?> <?= htmlspecialchars($contacts['addressValue'] ?? '') ?></li>
                <li>📞 <?= htmlspecialchars($contacts['phone'] ?? 'Телефон:') ?> <?= htmlspecialchars($contacts['phoneValue'] ?? '') ?></li>
                <li>✉️ <?= htmlspecialchars($contacts['email'] ?? 'E-mail:') ?> <?= htmlspecialchars($contacts['emailValue'] ?? '') ?></li>
                <li>👩‍💼 <?= htmlspecialchars($contacts['director'] ?? 'Директор:') ?> <?= htmlspecialchars($contacts['directorName'] ?? '') ?></li>
            </ul>
        </div>
        
        <div class="col-md-4">
            <!-- Карта Яндекс -->
            <div class="card">
                <div class="card-body p-0">
                    <script type="text/javascript" charset="utf-8" 
                            async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A<?= $mapId ?>&amp;width=100%25&amp;height=300&amp;lang=ru_RU&amp;scroll=true">
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>