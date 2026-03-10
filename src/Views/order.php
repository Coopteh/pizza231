<?php /** @var array $pizzas */ ?>
<div class="order-form">
    <h2>🍕 Оформить заказ</h2>
    
    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    
    <form method="POST" action="/Pizza221/order">
        <div class="form-group">
            <label>Выберите пиццу:</label>
            <select name="pizza_id" class="form-control" required>
                <option value="">-- Выберите --</option>
                <?php foreach ($pizzas as $pizza): ?>
                    <option value="<?= $pizza['id'] ?>" 
                            <?= (($_SESSION['old_input']['pizza_id'] ?? '') == $pizza['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($pizza['name']) ?> - $<?= $pizza['price'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Количество:</label>
            <input type="number" name="quantity" min="1" value="<?= $_SESSION['old_input']['quantity'] ?? 1 ?>" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Ваше имя:</label>
            <input type="text" name="customer_name" value="<?= $_SESSION['old_input']['customer_name'] ?? '' ?>" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Телефон:</label>
            <input type="tel" name="customer_phone" value="<?= $_SESSION['old_input']['customer_phone'] ?? '' ?>" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Заказать 🚀</button>
    </form>
</div>

<?php unset($_SESSION['old_input']); ?>