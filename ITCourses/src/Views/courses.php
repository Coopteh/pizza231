<?php include 'src/Views/layouts/header.php'; ?>

<div class="container">
    <h1>Наши курсы</h1>
    <div class="courses-grid">
        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <h3><?= htmlspecialchars($course['title']) ?></h3>
                <p><?= htmlspecialchars($course['description']) ?></p>
                <p class="price"><?= $course['price'] ?> руб.</p>
                <p class="duration">⏱ <?= $course['duration'] ?> недель</p>
                <a href="<?= BASE_URL ?>enrollment" class="btn">Записаться</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'src/Views/layouts/footer.php'; ?>