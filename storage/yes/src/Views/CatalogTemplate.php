<?php
namespace App\Views;

require_once __DIR__ . '/BaseTemplate.php';

class CatalogTemplate extends BaseTemplate
{
    /**
     * Путь к файлу шаблона
     */
    private const TEMPLATE_PATH = __DIR__ . '/templates/catalog.html.php';

    /**
     * Путь к файлу с текстами
     */
    private const TEXTS_PATH = __DIR__ . '/../../storage/templates/catalog.json';

    /**
     * Загружает тексты из JSON файла
     */
    private static function loadTexts(): array
    {
        $path = self::TEXTS_PATH;
        if (!file_exists($path)) {
            return [];
        }
        $json = file_get_contents($path);
        return json_decode($json, true) ?? [];
    }

    /**
     * Метод должен совпадать с родителем (принимает строку)
     */
    public static function getTemplate(string $content = '', array $texts = []): string
    {
        return parent::getTemplate($content, $texts);
    }

    /**
     * Основной метод для запуска каталога
     * @param array $products Массив товаров
     * @param string $search Поисковый запрос
     */
    public static function render(array $products = [], string $search = ''): string
    {
        // Загружаем тексты
        $texts = self::loadTexts();

        // Генерируем HTML контента
        $productsGrid = self::renderProductsGrid($products, $texts);
        $searchInfo = self::renderSearchInfo(count($products), $search, $texts);

        // Подключаем шаблон
        ob_start();
        include self::TEMPLATE_PATH;
        $content = ob_get_clean();
        
        return self::getTemplate($content, $texts);
    }
    
    /**
     * Рендерит сетку карточек товаров
     */
    private static function renderProductsGrid(array $products, array $texts = []): string
    {
        $productText = $texts['product'] ?? [];

        if (empty($products)) {
            return self::renderEmptyState($texts);
        }
        
        $addToCartText = $productText['addToCart'] ?? 'В корзину';
        $detailsText = $productText['details'] ?? 'Подробнее';
        $inStockText = $productText['inStock'] ?? 'В наличии';

        $html = '';
        
        foreach ($products as $product) {
            $name = htmlspecialchars($product['name'] ?? 'Без названия');
            $description = htmlspecialchars($product['description'] ?? '');
            $price = number_format($product['price'] ?? 0, 0, '.', ' ');
            $image = htmlspecialchars($product['image'] ?? '/assets/img/no-image.jpg');
            $id = (int)($product['id'] ?? 0);
            
            // JSON данные для JavaScript
            $productJson = htmlspecialchars(json_encode([
                'id' => $id,
                'name' => $product['name'] ?? '',
                'price' => (float)($product['price'] ?? 0),
                'image' => $product['image'] ?? ''
            ], JSON_UNESCAPED_UNICODE), ENT_QUOTES);
            
            // Короткое описание (макс. 100 символов)
            $shortDesc = mb_strlen($description) > 100 
                ? mb_substr($description, 0, 100) . '...' 
                : $description;
            
            $html .= '
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="' . $image . '" 
                             class="card-img-top p-3" 
                             alt="' . $name . '"
                             style="height: 220px; object-fit: contain;"
                             onerror="this.src=\'/assets/img/no-image.jpg\';">
                        <span class="badge bg-success position-absolute top-0 end-0 m-3">' . htmlspecialchars($inStockText) . '</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">' . $name . '</h5>
                        <p class="card-text text-muted small flex-grow-1">' . $shortDesc . '</p>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="h5 mb-0">' . $price . ' ₽</span>
                            <div class="btn-group">
                                <!-- Кнопка добавления в корзину -->
                                <button type="button" 
                                        class="btn btn-primary btn-sm px-3 btn-add-to-cart"
                                        data-product=\'' . $productJson . '\'
                                        data-id="' . $id . '">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus me-1" viewBox="0 0 16 16">
                                        <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z"/>
                                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                    </svg>
                                    <span class="btn-text">' . htmlspecialchars($addToCartText) . '</span>
                                </button>
                                <!-- Блок управления количеством (скрыт по умолчанию) -->
                                <div class="input-group input-group-sm quantity-controls d-none" data-product-id="' . $id . '">
                                    <button class="btn btn-outline-secondary btn-qty" data-action="decrease" type="button">−</button>
                                    <input type="number" class="form-control text-center qty-input" 
                                           value="1" min="1" data-product-id="' . $id . '">
                                    <button class="btn btn-outline-secondary btn-qty" data-action="increase" type="button">+</button>
                                </div>
                                <button type="button" class="btn btn-outline-dark btn-sm px-3 btn-product-details" data-id="' . $id . '">' . htmlspecialchars($detailsText) . '</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        return $html;
    }
    
    /**
     * Сообщение, когда товары не найдены
     */
    private static function renderEmptyState(array $texts = []): string
    {
        $emptyText = $texts['empty'] ?? [];

        return '
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted mb-3"></i>
                <h4>' . htmlspecialchars($emptyText['title'] ?? 'Ничего не найдено 😔') . '</h4>
                <p class="text-muted">' . htmlspecialchars($emptyText['message'] ?? 'Попробуйте изменить поисковый запрос') . '</p>
                <a href="/catalog" class="btn btn-outline-dark mt-3">' . htmlspecialchars($emptyText['resetLink'] ?? 'Сбросить фильтр') . '</a>
            </div>
        </div>';
    }
    
    /**
     * Инфо о результатах поиска
     */
    private static function renderSearchInfo(int $count, string $search, array $texts = []): string
    {
        $searchText = $texts['search'] ?? [];

        if (empty($search)) {
            return '<p class="text-muted mt-3">' . htmlspecialchars($searchText['total'] ?? 'Всего товаров:') . ' <strong>' . $count . '</strong></p>';
        }
        
        $query = htmlspecialchars($search);
        return '<p class="text-muted mt-3">' . htmlspecialchars($searchText['results'] ?? 'Найдено по запросу') . ' "' . $query . '": <strong>' . $count . '</strong></p>';
    }
}