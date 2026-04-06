<?php
namespace App\Views;

class ProductTemplate extends BaseTemplate {
    
    /**
     * Преобразует путь/имя изображения в безопасное имя файла
     */
    protected static function getImageFilename(string $image): string {
        $filename = basename($image);
        $filename = preg_replace('/[^a-zA-Z0-9._-]/u', '_', $filename);
        return $filename;
    }
    
    /**
     * Извлекает информацию о длительности из описания тура
     */
    protected static function extractDurationFromDescription(string $description): string {
        $patterns = [
            '/(\d+\s*(?:день|дня|дней|час|часа|часов|неделя|недели|недель))/ui',
            '/(\d+\s*(?:сутки|суток|минута|минуты|минут))/ui'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $description, $matches)) {
                return trim($matches[1]);
            }
        }
        return 'длительность по запросу';
    }
    
    /**
     * ✅ Шаблон для списка всех товаров (каталог)
     * @param array $products Массив товаров из модели
     */
    public static function getAllTemplate(array $products): string {
        $template = parent::getTemplate();
        
        $cards = '';
        
        // Если данные пришли в формате [0 => [...], 1 => [...]] — итерируем
        // Если это ассоциативный массив одного товара — обрабатываем как единственный элемент
        $items = array_values($products);
        
        foreach ($items as $product) {
            // Пропускаем, если элемент не массив или нет обязательных полей
            if (!is_array($product) || !isset($product['name'], $product['id'])) {
                continue;
            }
            
            $duration = self::extractDurationFromDescription($product['description'] ?? '');
            $imageFileName = self::getImageFilename($product['image'] ?? 'no-image.jpg');
            $price = $product['price'] ?? '0';
            $description = htmlspecialchars($product['description'] ?? '');
            $shortDesc = mb_strlen($description) > 120 
                ? mb_substr($description, 0, 120) . '...' 
                : $description;
            
            $cards .= <<<CARD
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="/assets/img/{$imageFileName}" 
                         class="card-img-top" 
                         alt="{$product['name']}"
                         style="height: 200px; object-fit: cover; border-radius: 0.375rem 0.375rem 0 0;"
                         onerror="this.src='/assets/img/no-image.jpg'">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{$product['name']}</h5>
                        <p class="card-text text-muted small mb-2">
                            <i class="bi bi-clock me-1"></i> {$duration}
                        </p>
                        <p class="card-text flex-grow-1 small">{$shortDesc}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h6 class="text-success mb-0 fw-bold">
                                <i class="bi bi-currency-ruble"></i> {$price}
                            </h6>
                            <small class="text-body-secondary">
                                <i class="bi bi-star-fill text-warning"></i> 4.9
                            </small>
                        </div>
                        <div class="mt-3">
                            <a href="/products/{$product['id']}" class="btn btn-primary btn-sm w-100">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>
CARD;
        }
        
        // Если товаров нет — показываем сообщение
        if (empty($cards)) {
            $cards = '<div class="col-12"><p class="text-center text-muted">Товары не найдены</p></div>';
        }
        
        $content = <<<CONTENT
        <h1 class="mb-4 text-center">Каталог туров</h1>
        <div class="row g-4">
            {$cards}
        </div>
CONTENT;
        
        $title = 'Каталог туров';
        return sprintf($template, $title, $content);
    }
    
    /**
     * Шаблон для карточки одного товара
     */
    public static function getCardTemplate(array $data): string {
        $template = parent::getTemplate();
        
        $duration = self::extractDurationFromDescription($data['description']);
        $rating = '4.9';
        $reviews = '15';
        $imageFileName = self::getImageFilename($data['image']);

        $card = <<<CARD
        <div class="container my-5">
            <img src="/assets/img/{$imageFileName}" class="float-start me-4" alt="{$data['name']}" style="width: 250px; height: auto; border-radius: 0.375rem;" onerror="this.src='/assets/img/no-image.jpg'">
            <div class="card-body ps-4">
                <h2 class="card-title">{$data['name']}</h2>
                <p class="card-text text-muted mb-4">
                    <i class="bi bi-calendar-week me-2"></i> Даты по запросу | <i class="bi bi-clock me-2"></i> {$duration}
                </p>
                <p class="card-text flex-grow-1">{$data['description']}</p>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h3 class="text-success"><i class="bi bi-currency-ruble"></i> {$data['price']}</h3>
                    <small class="text-body-secondary">
                        <i class="bi bi-star-fill text-warning"></i> {$rating} ({$reviews} отзывов)
                    </small>
                </div>
                <div class="mt-auto mt-3">
                    <form class="d-inline" action="/basket" method="POST">
                        <input type="hidden" name="id" value="{$data['id']}" />
                        <button type="submit" class="btn btn-success btn-lg me-3">Забронировать тур</button>
                    </form>
                    <a href="javascript:history.back()" role="button" class="btn btn-outline-primary btn-lg">Вернуться к выбору</a>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
CARD;

        $title = 'Тур: ' . $data['name'];
        $resultTemplate = sprintf($template, $title, $card);
        return $resultTemplate;
    }
}