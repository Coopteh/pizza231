<?php
namespace App\Views;

require_once __DIR__ . '/BaseTemplate.php';

class CatalogTemplate extends BaseTemplate
{
    /**
     * Метод должен совпадать с родителем (принимает строку)
     */
    public static function getTemplate(string $content): string 
    {
        return parent::getTemplate($content);
    }

    /**
     * Основной метод для запуска каталога
     * @param array $products Массив товаров
     * @param string $search Поисковый запрос
     */
    public static function render(array $products = [], string $search = ''): string
    {
        // Генерируем HTML контента
        $productsGrid = self::renderProductsGrid($products);
        $searchInfo = self::renderSearchInfo(count($products), $search);

        $content = '
        <div class="container py-5">
            
            <!-- Заголовок + Поиск -->
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h1 class="display-5 fw-bold mb-4 text">Каталог товаров</h1>
                    
                    <!-- Форма поиска -->
                    <form method="GET" action="/catalog" class="col-md-6 col-lg-4 mx-auto">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control border-start-0 ps-0" 
                                placeholder="Поиск товаров..." 
                                value="' . htmlspecialchars($search) . '"
                                aria-label="Поиск">
                            <button class="btn btn-primary px-4" type="submit">Найти</button>
                        </div>
                    </form>
                    
                    <!-- Результаты поиска -->
                    ' . $searchInfo . '
                </div>
            </div>
            
            <!-- Сетка товаров -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                ' . $productsGrid . '
            </div>
            
        </div>
        ';
        
        // Вызываем совместимый метод getTemplate
        return self::getTemplate($content);
    }
    
    /**
     * Рендерит сетку карточек товаров
     */
    private static function renderProductsGrid(array $products): string
    {
        if (empty($products)) {
            return self::renderEmptyState();
        }

        $html = '';
        
        foreach ($products as $product) {
            $name = htmlspecialchars($product['name'] ?? 'Без названия');
            $description = htmlspecialchars($product['description'] ?? '');
            $price = number_format($product['price'] ?? 0, 0, '.', ' ');
            $image = htmlspecialchars($product['image'] ?? '/assets/img/no-image.jpg');
            $id = (int)($product['id'] ?? 0);
            
            // Короткое описание (макс. 100 символов)
            $shortDesc = mb_strlen($description) > 100 
                ? mb_substr($description, 0, 100) . '...' 
                : $description;
            
            $html .= '
            <div class="col">
                <div class="card h-100 bg-glass border-0 shadow-sm">
                    <div class="position-relative">
                        <img src="' . $image . '" 
                             class="card-img-top p-3" 
                             alt="' . $name . '"
                             style="height: 220px; object-fit: contain;"
                             onerror="this.src=\'/assets/img/no-image.jpg\';">
                        <span class="badge bg-success position-absolute top-0 end-0 m-3">В наличии</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text">' . $name . '</h5>
                        <p class="card-text text-50 small flex-grow-1">' . $shortDesc . '</p>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top r border-opacity-10">
                            <span class="h5 mb-0 text">' . $price . ' ₽</span>
                            <a href="/product/' . $id . '" class="btn btn-outline-light btn-sm px-3">Подробнее</a>
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
    private static function renderEmptyState(): string
    {
        return '
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-50 mb-3"></i>
                <h4 class="text">Ничего не найдено 😔</h4>
                <p class="text-50">Попробуйте изменить поисковый запрос</p>
                <a href="/catalog" class="btn btn-outline-light mt-3">Сбросить фильтр</a>
            </div>
        </div>';
    }
    
    /**
     * Инфо о результатах поиска
     */
    private static function renderSearchInfo(int $count, string $search): string
    {
        if (empty($search)) {
            return '<p class="text-50 mt-3">Всего товаров: <strong class="text">' . $count . '</strong></p>';
        }
        
        $query = htmlspecialchars($search);
        return '<p class="text-50 mt-3">Найдено по запросу "' . $query . '": <strong class="text">' . $count . '</strong></p>';
    }
}