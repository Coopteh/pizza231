<?php
namespace Views;

class AdminOrdersTemplate extends BaseTemplate
{
    public static function getTemplate($orders = null): string
    {
        $template = parent::getTemplate();
        $title = 'Управление заказами';

        if (!is_array($orders)) {
            $orders = [];
        }

        $ordersHtml = '';
        if (empty($orders)) {
            $ordersHtml = '<tr><td colspan="5" class="text-center py-4">Заказов пока нет</td></tr>';
        } else {
            foreach ($orders as $order) {
                $statusColor = $order['status'] === 'new' ? 'warning' : 'success';
                $ordersHtml .= '<tr>';
                $ordersHtml .= '<td>' . htmlspecialchars($order['id']) . '</td>';
                $ordersHtml .= '<td>' . htmlspecialchars($order['customer']['name']) . '</td>';
                $ordersHtml .= '<td>' . number_format($order['total'], 0, '.', ' ') . ' ₽</td>';
                $ordersHtml .= '<td><span class="badge bg-' . $statusColor . '">' . $order['status'] . '</span></td>';
                $ordersHtml .= '<td>' . $order['created_at'] . '</td>';
                $ordersHtml .= '</tr>';
            }
        }

        $content = '
<section class="container py-5">
    <h1 class="mb-4">📦 Заказы</h1>
    <a href="/home" class="btn btn-outline-secondary mb-3">← На сайт</a>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>№ Заказа</th>
                        <th>Клиент</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>' . $ordersHtml . '</tbody>
            </table>
        </div>
    </div>
</section>';

        return str_replace(['{{TITLE}}', '{{CONTENT}}'], [$title, $content], $template);
    }
}