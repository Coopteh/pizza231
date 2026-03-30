<?php
// debug-log.php — Показать лог последнего заказа
session_start();

echo "<h1>🐛 Лог отладки заказа</h1>";

if (empty($_SESSION['debug_log'])) {
    echo "<p style='color:orange'>⚠️ Лог пуст. Оформите заказ на /order</p>";
} else {
    echo "<pre style='background:#f5f5f5;padding:20px;border-radius:8px'>";
    foreach ($_SESSION['debug_log'] as $line) {
        echo htmlspecialchars($line) . "\n";
    }
    echo "</pre>";
}

echo "<hr>";
echo "<p><a href='/order'>⬅️ Вернуться к оформлению заказа</a></p>";
echo "<p><a href='/emails/index.html'>📧 Открыть письма</a></p>";