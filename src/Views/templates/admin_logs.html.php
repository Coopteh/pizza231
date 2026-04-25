<?php
/**
 * Шаблон страницы логов ошибок
 * Доступные переменные:
 * - $logs - массив логов из Logger::getErrors()
 * - $texts - массив текстов из storage/templates/admin.json
 */

// Значения по умолчанию
$texts = $texts ?? [];
$logsText = $texts['logs'] ?? [];

$logCount = count($logs ?? []);
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><?= htmlspecialchars($logsText['title'] ?? 'Логи ошибок') ?></h1>
        <div>
            <button id="refreshLogsBtn" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-clockwise"></i> Обновить
            </button>
            <button id="clearLogsBtn" class="btn btn-outline-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                <i class="bi bi-trash"></i> Очистить логи
            </button>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="bi bi-info-circle fs-4 me-2"></i>
                <div>
                    <strong>Информация:</strong> Здесь отображаются последние ошибки и предупреждения сайта. Всего записей: <strong><?= $logCount ?></strong>
                </div>
            </div>

            <?php if (empty($logs)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-check-circle-fill fs-1 text-success mb-3 d-block"></i>
                    <h5 class="text-muted"><?= htmlspecialchars($logsText['noLogs'] ?? 'Ошибок не найдено') ?></h5>
                    <p class="text-muted">Система работает стабильно, ошибок не обнаружено.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="150">Время</th>
                                <th width="100">Уровень</th>
                                <th>Сообщение</th>
                                <th width="100">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars($log['timestamp'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $level = $log['level'] ?? 'UNKNOWN';
                                        $badgeClass = match($level) {
                                            'ERROR' => 'bg-danger',
                                            'INFO' => 'bg-info',
                                            'WARNING' => 'bg-warning',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($level) ?></span>
                                    </td>
                                    <td>
                                        <div class="log-message"><?= htmlspecialchars($log['message'] ?? '') ?></div>
                                        <?php if (isset($log['context']) && !empty($log['context'])): ?>
                                            <small class="text-muted d-block mt-1">
                                                <button class="btn btn-sm btn-link p-0 text-decoration-none" type="button" 
                                                        data-bs-toggle="collapse" data-bs-target="#context-<?= md5($log['timestamp'] . $log['message']) ?>">
                                                    <i class="bi bi-chevron-down"></i> Контекст
                                                </button>
                                            </small>
                                            <div class="collapse mt-2" id="context-<?= md5($log['timestamp'] . $log['message']) ?>">
                                                <pre class="bg-light p-2 rounded small"><code><?= htmlspecialchars(json_encode($log['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></code></pre>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary copy-log-btn" 
                                                data-message="<?= htmlspecialchars($log['message'] ?? '') ?>"
                                                title="Копировать сообщение">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Показано последних <?= $logCount ?> записей
                    </small>
                    <div>
                        <a href="/api/admin/logs?limit=100" class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="bi bi-download"></i> JSON
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Модальное окно очистки логов -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($logsText['clearTitle'] ?? 'Очистить логи ошибок') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?= htmlspecialchars($logsText['clearWarning'] ?? 'Вы уверены, что хотите очистить все логи ошибок? Это действие невозможно отменить.') ?></p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= htmlspecialchars($logsText['clearNote'] ?? 'Рекомендуется сохранить логи перед очисткой, нажав кнопку "JSON" выше.') ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= htmlspecialchars($logsText['cancel'] ?? 'Отмена') ?></button>
                <button type="button" class="btn btn-danger" id="confirmClearLogsBtn"><?= htmlspecialchars($logsText['clear'] ?? 'Очистить логи') ?></button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Копирование сообщения лога
    document.querySelectorAll('.copy-log-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const message = this.getAttribute('data-message');
            navigator.clipboard.writeText(message).then(() => {
                const icon = this.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'bi bi-check';
                setTimeout(() => {
                    icon.className = originalClass;
                }, 1000);
            });
        });
    });
    
    // Обновление логов
    document.getElementById('refreshLogsBtn').addEventListener('click', function() {
        window.location.reload();
    });
    
    // Очистка логов
    document.getElementById('confirmClearLogsBtn').addEventListener('click', function() {
        fetch('/api/admin/clear-logs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Ошибка при очистке логов: ' + (data.error || 'Неизвестная ошибка'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при очистке логов');
        })
        .finally(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('clearLogsModal'));
            modal.hide();
        });
    });
});
</script>