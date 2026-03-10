<?php

define('BASE_PATH', __DIR__);  
define('BASE_URL', '/Pizza221/');

require_once BASE_PATH . '/vendor/autoload.php';


use App\BaseTemplate;


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$uri = str_replace(BASE_URL, '', $uri);
$uri = '/' . trim($uri, '/');


if ($uri === '/') {
    $uri = '/index.php';
}


switch ($uri) {
    case '/index.php':
    case '/':
        $pageTitle = "Главная - Fashion Store";
        $content = getHomePage();
        break;
    
    case '/catalog':
        $pageTitle = "Каталог - Fashion Store";
        $content = getCatalogPage();
        break;
    
    case '/cart':
        $pageTitle = "Корзина - Fashion Store";
        $content = getCartPage();
        break;
    
    default:
        $pageTitle = "Страница не найдена";
        http_response_code(404);
        $content = "
            <div class='text-center py-5'>
                <h1>404</h1>
                <p class='lead'>Страница не найдена</p>
                <a href='" . BASE_URL . "' class='btn'>Вернуться на главную</a>
            </div>
        ";
        break;
}


$template = BaseTemplate::getTemplate();
$resultTemplate = sprintf($template, $pageTitle, $content);
echo $resultTemplate;


function getHomePage(): string
{
    return "
        <!-- Hero Section -->
        <section class='hero'>
            <h1>Новая коллекция 2025</h1>
            <p>Стильная одежда для повседневной жизни и особых случаев</p>
            <a class='btn me-2' href='/Pizza221/catalog'>Смотреть каталог</a>
            <a class='btn btn-outline' href='/Pizza221/catalog?sale=1'>Скидки</a>
        </section>

        <!-- Категории -->
        <section class='categories'>
            <h2 class='section-title'>Категории</h2>
            <div class='row g-3'>
                <div class='col-6 col-md-3'>
                    <a href='/Pizza221/catalog?cat=men' class='category-card'>
                        <div style='background:#e0e0e0;height:150px;border-radius:4px;margin-bottom:0.8rem;display:flex;align-items:center;justify-content:center;font-size:2rem;'>👔</div>
                        <h5>Мужчинам</h5>
                    </a>
                </div>
                <div class='col-6 col-md-3'>
                    <a href='/Pizza221/catalog?cat=women' class='category-card'>
                        <div style='background:#e0e0e0;height:150px;border-radius:4px;margin-bottom:0.8rem;display:flex;align-items:center;justify-content:center;font-size:2rem;'>👗</div>
                        <h5>Женщинам</h5>
                    </a>
                </div>
                <div class='col-6 col-md-3'>
                    <a href='/Pizza221/catalog?cat=kids' class='category-card'>
                        <div style='background:#e0e0e0;height:150px;border-radius:4px;margin-bottom:0.8rem;display:flex;align-items:center;justify-content:center;font-size:2rem;'>🧒</div>
                        <h5>Детям</h5>
                    </a>
                </div>
                <div class='col-6 col-md-3'>
                    <a href='/Pizza221/catalog?cat=accessories' class='category-card'>
                        <div style='background:#e0e0e0;height:150px;border-radius:4px;margin-bottom:0.8rem;display:flex;align-items:center;justify-content:center;font-size:2rem;'>👜</div>
                        <h5>Аксессуары</h5>
                    </a>
                </div>
            </div>
        </section>

        <!-- Популярные товары -->
        <section class='products'>
            <h2 class='section-title'>Популярные товары</h2>
            <div class='row g-3'>
                <div class='col-md-3 col-6'>
                    <div class='product-card'>
                        <div class='product-image'>👕</div>
                        <div class='card-body'>
                            <h5 class='card-title'>Футболка Basic</h5>
                            <p class='card-text'>Хлопок, унисекс</p>
                            <div class='product-price'>1 290 ₽</div>
                            <button class='btn'>В корзину</button>
                        </div>
                    </div>
                </div>
                <div class='col-md-3 col-6'>
                    <div class='product-card'>
                        <div class='product-image'>👖</div>
                        <div class='card-body'>
                            <h5 class='card-title'>Джинсы Slim</h5>
                            <p class='card-text'>Классический крой</p>
                            <div class='product-price'>3 490 ₽</div>
                            <button class='btn'>В корзину</button>
                        </div>
                    </div>
                </div>
                <div class='col-md-3 col-6'>
                    <div class='product-card'>
                        <div class='product-image'>🧥</div>
                        <div class='card-body'>
                            <h5 class='card-title'>Куртка демисезонная</h5>
                            <p class='card-text'>Водоотталкивающая</p>
                            <div class='product-price'>5 990 ₽</div>
                            <button class='btn'>В корзину</button>
                        </div>
                    </div>
                </div>
                <div class='col-md-3 col-6'>
                    <div class='product-card'>
                        <div class='product-image'>👟</div>
                        <div class='card-body'>
                            <h5 class='card-title'>Кроссовки Urban</h5>
                            <p class='card-text'>Удобная подошва</p>
                            <div class='product-price'>4 290 ₽</div>
                            <button class='btn'>В корзину</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    ";
}

function getCatalogPage(): string
{
    return "
        <h1 class='mb-4'>Каталог товаров</h1>
        <p>Здесь будет полный каталог товаров...</p>
    ";
}

function getCartPage(): string
{
    return "
        <h1 class='mb-4'>Корзина</h1>
        <p>Ваша корзина пуста</p>
        <a href='/Pizza221/' class='btn'>Вернуться к покупкам</a>
    ";
}