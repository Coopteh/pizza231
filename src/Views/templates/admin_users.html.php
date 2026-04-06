<?php
/**
 * Шаблон страницы пользователей админ-панели
 * Доступные переменные:
 * - $users - массив пользователей
 * - $texts - массив текстов из storage/templates/admin.json
 */

$texts = $texts ?? [];
$usersText = $texts['users'] ?? [];
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><?= htmlspecialchars($usersText['title'] ?? 'Пользователи') ?></h1>
        <a href="/admin" class="btn btn-outline-secondary"><?= htmlspecialchars($usersText['back'] ?? 'Назад в панель') ?></a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?= htmlspecialchars($usersText['id'] ?? 'ID') ?></th>
                            <th><?= htmlspecialchars($usersText['name'] ?? 'Имя') ?></th>
                            <th><?= htmlspecialchars($usersText['email'] ?? 'Email') ?></th>
                            <th><?= htmlspecialchars($usersText['registered'] ?? 'Дата регистрации') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $usersHtml ?? '' ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
