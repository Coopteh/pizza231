<?php include 'src/Views/layouts/header.php'; ?>

<div class="container">
    <h1>Запись на курс</h1>
    
    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="enrollment-form">
        <label>Ваше имя:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Телефон:</label>
        <input type="tel" name="phone" required>

        <label>Выберите курс:</label>
        <select name="course_id" required>
            <option value="">-- Выберите курс --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn">Отправить заявку</button>
    </form>
</div>

<?php include 'src/Views/layouts/footer.php'; ?>