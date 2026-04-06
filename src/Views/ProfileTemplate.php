<?php
namespace App\Views;

require_once __DIR__ . '/BaseTemplate.php';

class ProfileTemplate extends BaseTemplate
{
    /**
     * Путь к файлу с текстами
     */
    private const TEXTS_PATH = __DIR__ . '/../../storage/templates/profile.json';
    
    /**
     * Загрузить тексты из JSON
     */
    private static function loadTexts(): array
    {
        $file = self::TEXTS_PATH;
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return [];
    }
    
    /**
     * Страница профиля
     */
    public static function render(array $profile, array $orders = []): void
    {
        $texts = self::loadTexts();
        
        $name = htmlspecialchars($profile['name'] ?? '');
        $email = htmlspecialchars($profile['email'] ?? '');
        $phone = htmlspecialchars($profile['phone'] ?? '');
        $address = htmlspecialchars($profile['address'] ?? '');
        $avatar = $profile['avatar'] ?? '';
        
        // Если аватар пустой - показываем заглушку
        $avatarHtml = '';
        if (!empty($avatar)) {
            $avatarHtml = '<img id="avatar-preview" src="' . htmlspecialchars($avatar) . '" alt="Аватар" class="rounded-circle profile-avatar">';
        } else {
            $avatarHtml = '<div id="avatar-preview" class="profile-avatar-placeholder rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-person-fill fs-1"></i>
            </div>';
        }
        
        // Извлекаем тексты для использования в heredoc
        $pageTitle = $texts['pageTitle'] ?? 'Настройки профиля';
        $avatarTitle = $texts['avatar']['title'] ?? 'Аватар';
        $avatarChange = $texts['avatar']['change'] ?? 'Изменить аватар';
        $avatarFormats = $texts['avatar']['formats'] ?? 'JPEG, PNG, GIF, WebP (макс. 2MB)';
        $formTitle = $texts['form']['title'] ?? 'Информация';
        $nameLabel = $texts['form']['name'] ?? 'Имя';
        $namePlaceholder = $texts['form']['namePlaceholder'] ?? 'Ваше имя';
        $emailLabel = $texts['form']['email'] ?? 'Email';
        $phoneLabel = $texts['form']['phone'] ?? 'Телефон';
        $addressLabel = $texts['form']['address'] ?? 'Адрес доставки';
        $addressPlaceholder = $texts['form']['addressPlaceholder'] ?? 'Улица, дом, квартира, индекс';
        $saveBtnText = $texts['form']['save'] ?? 'Сохранить изменения';
        $emailNote = $texts['info']['emailNote'] ?? 'Email нельзя изменить';
        $phoneNote = $texts['info']['phoneNote'] ?? 'Телефон нельзя изменить';
        
        // Тексты для истории заказов
        $ordersTitle = $texts['orders']['title'] ?? 'История заказов';
        $noOrdersText = $texts['orders']['noOrders'] ?? 'У вас пока нет заказов';
        $orderNumberText = $texts['orders']['orderNumber'] ?? 'Заказ';
        $dateText = $texts['orders']['date'] ?? 'Дата';
        $statusText = $texts['orders']['status'] ?? 'Статус';
        $totalText = $texts['orders']['total'] ?? 'Сумма';
        $detailsText = $texts['orders']['details'] ?? 'Детали';
        $itemsText = $texts['orders']['items'] ?? 'Товаров';
        $viewDetailsText = $texts['orders']['viewDetails'] ?? 'Показать детали';
        $hideDetailsText = $texts['orders']['hideDetails'] ?? 'Скрыть детали';
        
        // Генерация HTML для истории заказов
        $ordersHtml = '';
        if (empty($orders)) {
            $ordersHtml = '
            <div class="text-center py-5">
                <i class="bi bi-cart fs-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted">' . htmlspecialchars($noOrdersText) . '</h5>
                <p class="text-muted">Сделайте свой первый заказ в нашем магазине!</p>
            </div>';
        } else {
            foreach ($orders as $order) {
                $id = $order['id'] ?? '';
                $shortId = substr($id, -8);
                $createdAt = $order['created_at'] ?? '';
                $dateFormatted = !empty($createdAt) ? date('d.m.Y H:i', strtotime($createdAt)) : '';
                $status = $order['status'] ?? 'new';
                $total = number_format($order['total'] ?? 0, 0, '.', ' ');
                $itemsCount = count($order['items'] ?? []);
                
                $statusTextMap = [
                    'new' => $texts['orders']['statusNew'] ?? 'Новый',
                    'processing' => $texts['orders']['statusProcessing'] ?? 'В обработке',
                    'completed' => $texts['orders']['statusCompleted'] ?? 'Выполнен',
                    'cancelled' => $texts['orders']['statusCancelled'] ?? 'Отменён'
                ];
                $statusBadgeClass = match($status) {
                    'new' => 'bg-primary',
                    'processing' => 'bg-warning text-dark',
                    'completed' => 'bg-success',
                    'cancelled' => 'bg-danger',
                    default => 'bg-secondary'
                };
                $statusDisplay = $statusTextMap[$status] ?? $status;
                
                $orderDetailsId = 'order-details-' . md5($id);
                
                $ordersHtml .= '
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <strong class="text-primary">#' . $shortId . '</strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">' . htmlspecialchars($dateText) . '</small>
                                        <div>' . htmlspecialchars($dateFormatted) . '</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block">' . htmlspecialchars($statusText) . '</small>
                                <span class="badge ' . $statusBadgeClass . '">' . htmlspecialchars($statusDisplay) . '</span>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block">' . htmlspecialchars($itemsText) . '</small>
                                <strong>' . $itemsCount . '</strong>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block">' . htmlspecialchars($totalText) . '</small>
                                <strong class="text-success">' . $total . ' ₽</strong>
                            </div>
                            <div class="col-md-3 text-end">
                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#' . $orderDetailsId . '" aria-expanded="false">
                                    <i class="bi bi-chevron-down me-1"></i>' . htmlspecialchars($viewDetailsText) . '
                                </button>
                            </div>
                        </div>
                        
                        <!-- Детали заказа -->
                        <div class="collapse mt-3" id="' . $orderDetailsId . '">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Информация о доставке</h6>
                                    <p class="mb-1"><strong>ФИО:</strong> ' . htmlspecialchars($order['fio'] ?? '') . '</p>
                                    <p class="mb-1"><strong>Телефон:</strong> ' . htmlspecialchars($order['phone'] ?? '') . '</p>
                                    <p class="mb-1"><strong>Адрес:</strong> ' . htmlspecialchars($order['address'] ?? '') . '</p>
                                    <p class="mb-1"><strong>Способ оплаты:</strong> ' . (($order['payment'] ?? '') === 'cash' ? 'Наличными' : 'Картой') . '</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Состав заказа</h6>';
                
                if (!empty($order['items'])) {
                    $ordersHtml .= '<div class="table-responsive"><table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th class="text-end">Цена</th>
                                <th class="text-center">Кол-во</th>
                                <th class="text-end">Сумма</th>
                            </tr>
                        </thead>
                        <tbody>';
                    
                    foreach ($order['items'] as $item) {
                        $itemTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                        $ordersHtml .= '
                            <tr>
                                <td>' . htmlspecialchars($item['name'] ?? '') . '</td>
                                <td class="text-end">' . number_format($item['price'] ?? 0, 0, '.', ' ') . ' ₽</td>
                                <td class="text-center">' . ($item['quantity'] ?? 1) . '</td>
                                <td class="text-end">' . number_format($itemTotal, 0, '.', ' ') . ' ₽</td>
                            </tr>';
                    }
                    
                    $ordersHtml .= '</tbody></table></div>';
                }
                
                $ordersHtml .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }
        
        $content = <<<HTML
<main>
    <div class="container py-4">
        <h1 class="mb-4">{$pageTitle}</h1>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <!-- Секция аватара -->
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3">{$avatarTitle}</h5>
                        
                        <div class="avatar-container mb-3">
                            {$avatarHtml}
                        </div>
                        
                        <form id="avatar-form" enctype="multipart/form-data">
                            <input type="file" id="avatar-input" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" class="d-none">
                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('avatar-input').click()">
                                <i class="bi bi-camera me-2"></i>{$avatarChange}
                            </button>
                        </form>
                        
                        <p class="text-muted small mt-2 mb-0">
                            {$avatarFormats}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <!-- Форма профиля -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">{$formTitle}</h5>
                        
                        <form id="profile-form">
                            <!-- Имя -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{$nameLabel}</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{$name}" placeholder="{$namePlaceholder}" required>
                            </div>
                            
                            <!-- Email (только чтение) -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{$emailLabel}</label>
                                <input type="email" class="form-control" id="email" value="{$email}" readonly>
                                <div class="form-text">{$emailNote}</div>
                            </div>
                            
                            <!-- Телефон (только чтение) -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">{$phoneLabel}</label>
                                <input type="tel" class="form-control" id="phone" value="{$phone}" readonly>
                                <div class="form-text">{$phoneNote}</div>
                            </div>
                            
                            <!-- Адрес доставки -->
                            <div class="mb-3">
                                <label for="address" class="form-label">{$addressLabel}</label>
                                <textarea class="form-control" id="address" name="address" rows="3" 
                                          placeholder="{$addressPlaceholder}">{$address}</textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" id="save-btn">
                                <span class="btn-text">{$saveBtnText}</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </form>
                        
                        <!-- Toast для уведомлений -->
                        <div class="toast-container position-fixed bottom-0 end-0 p-3">
                            <div id="profile-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header">
                                    <strong class="me-auto">Профиль</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                                </div>
                                <div class="toast-body"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- История заказов -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">{$ordersTitle}</h5>
                        {$ordersHtml}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
HTML;
        
        // Добавить CSS для профиля
        $css = '<link rel="stylesheet" href="/assets/css/profile.css">';
        
        // Добавить JS для профиля
        $js = '<script src="/assets/js/profile.js"></script>';
        
        // Используем родительский метод для рендеринга
        $html = parent::getTemplate($content, $texts);
        
        // Добавляем CSS и JS перед </body>
        $html = str_replace('</body>', $css . $js . '</body>', $html);
        
        echo $html;
    }
}
