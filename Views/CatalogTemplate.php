<?php
namespace Views;

class CatalogTemplate extends BaseTemplate
{
    public static function render(array $products): string
    {
        $template = parent::getTemplate();
        $title = 'Каталог услуг — Страховая компания «Чёрный Вантуз»';
        $itemsHtml = '';
        
        foreach ($products as $product) {
            $priceDisplay = $product['price'] > 0 
                ? number_format($product['price'], 0, '.', ' ') . ' ₽/' . htmlspecialchars($product['period']) 
                : 'По запросу';
            
            // 🔐 Проверка авторизации для кнопки "В корзину"
            $isAuth = isset($_SESSION['user_id']);
            
            if ($isAuth && $product['price'] > 0) {
                $addToCartBtn = '<form method="POST" action="/cart/add" class="d-inline"><input type="hidden" name="id" value="'.$product['id'].'"><button type="submit" class="btn btn-primary rounded-pill px-4">В корзину</button></form>';
            } elseif ($product['price'] > 0) {
                // 🔐 Не авторизован — показываем подсказку
                $addToCartBtn = '<button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#authModal">В корзину</button>';
            } else {
                $addToCartBtn = '';
            }
            
            $itemsHtml .= '
            <div class="card border-0 shadow-sm mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="'.htmlspecialchars($product['image']).'" 
                             class="img-fluid h-100 w-100" 
                             style="object-fit:cover;min-height:200px" 
                             alt="'.htmlspecialchars($product['name']).'" 
                             onerror="this.src=\'https://placehold.co/400x300/0d6efd/ffffff?text='.urlencode($product['name']).'\'">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body p-4">
                            <h3 class="h4 fw-bold mb-2">
                                <a href="/product/'.$product['id'].'" class="text-decoration-none text-dark">
                                    '.htmlspecialchars($product['name']).'
                                </a>
                            </h3>
                            <p class="text-muted mb-3">'.htmlspecialchars($product['description']).'</p>
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-3">'.$priceDisplay.'</span>
                            <div class="d-flex gap-2">
                                <a href="/product/'.$product['id'].'" class="btn btn-outline-primary rounded-pill px-4">Подробнее</a>
                                '.$addToCartBtn.'
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        // 🔐 Модальное окно авторизации
        $authModal = '
        <div class="modal fade" id="authModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">🔐 Требуется авторизация</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-4">Чтобы добавить товар в корзину, пожалуйста, войдите в аккаунт или зарегистрируйтесь.</p>
                        <div class="d-grid gap-2">
                            <a href="/login" class="btn btn-primary rounded-pill">Войти</a>
                            <a href="/register" class="btn btn-outline-primary rounded-pill">Зарегистрироваться</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        
        $content = '<section class="container py-5"><h2 class="text-center mb-4">Каталог услуг</h2>'.$itemsHtml.$authModal.'</section>';
        return sprintf($template, $title, $content);
    }
}