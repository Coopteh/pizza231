<?php
/**
 * Шаблон страниц авторизации (регистрация, вход и подтверждение)
 * Доступные переменные:
 * - $error - сообщение об ошибке
 * - $success - сообщение об успехе
 * - $mode - 'register', 'login' или 'verify'
 * - $texts - массив текстов из storage/templates/auth.json
 * - $email - email для подтверждения (для verify)
 */

$texts = $texts ?? [];
$mode = $mode ?? 'login';

if ($mode === 'register') {
    $formText = $texts['register'] ?? [];
} elseif ($mode === 'verify') {
    $formText = $texts['verify'] ?? [];
} else {
    $formText = $texts['login'] ?? [];
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4"><?= htmlspecialchars($formText['title'] ?? ($mode === 'register' ? 'Регистрация' : ($mode === 'verify' ? 'Подтверждение email' : 'Вход'))) ?></h2>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($mode === 'register'): ?>
                        <form method="POST" action="/register">
                            <div class="mb-3">
                                <label for="name" class="form-label"><?= htmlspecialchars($formText['name'] ?? 'Имя') ?></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label"><?= htmlspecialchars($formText['email'] ?? 'Email') ?></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><?= htmlspecialchars($formText['password'] ?? 'Пароль') ?></label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                <div class="form-text"><?= htmlspecialchars($formText['passwordHint'] ?? 'Минимум 6 символов') ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label"><?= htmlspecialchars($formText['passwordConfirm'] ?? 'Подтверждение пароля') ?></label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><?= htmlspecialchars($formText['submit'] ?? 'Зарегистрироваться') ?></button>
                        </form>

                        <div class="text-center mt-3">
                            <span class="text-muted"><?= htmlspecialchars($formText['hasAccount'] ?? 'Уже есть аккаунт?') ?></span>
                            <a href="/login"><?= htmlspecialchars($formText['loginLink'] ?? 'Войти') ?></a>
                        </div>
                    <?php elseif ($mode === 'verify'): ?>
                        <div class="text-center mb-4">
                            <p class="text-muted"><?= htmlspecialchars($formText['subtitle'] ?? 'На ваш email отправлен код подтверждения') ?></p>
                            <strong><?= htmlspecialchars($email ?? '') ?></strong>
                        </div>
                        
                        <form method="POST" action="/verify" id="verifyForm">
                            <div class="mb-3">
                                <label for="code" class="form-label"><?= htmlspecialchars($formText['codeLabel'] ?? 'Код подтверждения') ?></label>
                                <input type="text" class="form-control text-center" id="code" name="code" 
                                       required maxlength="6" pattern="[0-9]{6}" 
                                       placeholder="000000" style="font-size: 24px; letter-spacing: 8px;">
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><?= htmlspecialchars($formText['submitVerify'] ?? 'Подтвердить') ?></button>
                        </form>

                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-link" id="resendBtn">
                                <?= htmlspecialchars($formText['resend'] ?? 'Отправить код повторно') ?>
                            </button>
                            <div id="resendMessage" class="mt-2" style="display: none;"></div>
                        </div>

                        <div class="text-center mt-3">
                            <span class="text-muted"><?= htmlspecialchars($formText['wrongEmail'] ?? 'Неверный email?') ?></span>
                            <a href="/register"><?= htmlspecialchars($formText['registerAgain'] ?? 'Зарегистрироваться заново') ?></a>
                        </div>

                        <script>
                        document.getElementById('verifyForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            const code = document.getElementById('code').value;
                            
                            fetch('/api/auth/verify', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json'},
                                body: JSON.stringify({code: code})
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    // Перенаправляем на главную страницу после подтверждения
                                    window.location.href = '/';
                                } else {
                                    alert(data.error || 'Ошибка подтверждения');
                                }
                            })
                            .catch(() => alert('Ошибка соединения'));
                        });

                        document.getElementById('resendBtn').addEventListener('click', function() {
                            const btn = this;
                            btn.disabled = true;
                            
                            fetch('/api/auth/resend', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json'}
                            })
                            .then(r => r.json())
                            .then(data => {
                                const msg = document.getElementById('resendMessage');
                                msg.style.display = 'block';
                                if (data.success) {
                                    msg.className = 'mt-2 text-success';
                                    msg.textContent = data.message;
                                } else {
                                    msg.className = 'mt-2 text-danger';
                                    msg.textContent = data.error;
                                }
                                btn.disabled = false;
                            })
                            .catch(() => {
                                alert('Ошибка соединения');
                                btn.disabled = false;
                            });
                        });
                        </script>
                    <?php else: ?>
                        <form method="POST" action="/login">
                            <div class="mb-3">
                                <label for="email" class="form-label"><?= htmlspecialchars($formText['email'] ?? 'Email') ?></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><?= htmlspecialchars($formText['password'] ?? 'Пароль') ?></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100"><?= htmlspecialchars($formText['submit'] ?? 'Войти') ?></button>
                        </form>

                        <div class="text-center mt-3">
                            <span class="text-muted"><?= htmlspecialchars($formText['noAccount'] ?? 'Нет аккаунта?') ?></span>
                            <a href="/register"><?= htmlspecialchars($formText['registerLink'] ?? 'Регистрация') ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
