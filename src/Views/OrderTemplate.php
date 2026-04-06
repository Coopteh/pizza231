<?php
namespace App\Views;

class OrderTemplate
{
    public function getOrderTemplate(array $arr): string
    {
        $content = '';
        
        // Заголовки страницы
        $content .= '<h1 class="mb-5">Создание заказа</h1>';
        $content .= '<h3>Корзина</h3>';
        
        $all_sum = 0;
        $products = $arr;
        
        if (count($products) > 0) {
            foreach ($products as $product) {
                $name = $product['name'];
                $price = $product['price'];
                $quantity = $product['quantity'];
                $sum = $product['sum'];
                $all_sum += $sum;

                $content .= <<<LINE
                <div class="row mb-2 border-bottom pb-2">
                    <div class="col-6">
                        {$name}
                    </div>
                    <div class="col-4">
                        {$quantity} ед. × {$price} руб.
                    </div>
                    <div class="col-2 text-end">
                        {$sum} ₽
                    </div>
                </div>
                LINE;
            }
            
            $content .= <<<LINE
            <div class="row mt-4">
                <div class="col-12 text-end">
                    <strong>Итого: {$all_sum} ₽</strong>
                </div>
            </div>
            LINE;
            
            $content .= <<<LINE
            <div class="row mt-3">
                <div class="col-6"></div>
                <div class="col-6 text-end">
                    <form action="/basket_clear" method="POST">
                        <button type="submit" class="btn btn-secondary">
                            Очистить корзину
                        </button>
                    </form>
                </div>
            </div>
            LINE;
            
        } else {
            $content .= <<<LINE
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-light">- нет добавленных товаров -</div>
                </div>
            </div>
            LINE;
        }
        
        // ✅ ПОДГОТОВКА значений ПЕРЕД heredoc
        $fioValue = htmlspecialchars($_POST['fio'] ?? '');
        $phoneValue = htmlspecialchars($_POST['phone'] ?? '');
        $emailValue = htmlspecialchars($_POST['email'] ?? '');
        $addressValue = htmlspecialchars($_POST['address'] ?? '');
        
        // === ФОРМА ДОСТАВКИ ===
        $content .= <<<LINE
        <h3 class="mt-5 mb-3">Данные для доставки</h3>
        <form action="/order" method="POST">
            <div class="mb-3">
                <label for="fio" class="form-label">Ваше ФИО <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="fio" name="fio" required value="{$fioValue}">
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">Телефон <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone" name="phone" required value="{$phoneValue}">
            </div>
            
            <!-- Поле Email -->
            <div class="mb-3">
                <label for="email" class="form-label">
                    Email <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="client@example.com" required value="{$emailValue}">
                <div class="form-text">
                    <i class="bi bi-envelope me-1"></i>
                    На этот адрес придёт подтверждение заказа
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Адрес доставки</label>
                <textarea class="form-control" id="address" name="address" rows="3" 
                          placeholder="г. Кемерово, ул. Примерная, д. 1">{$addressValue}</textarea>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-cart-check me-2"></i>Создать заказ
            </button>
        </form>
        LINE;
        
        $baseTemplate = BaseTemplate::getTemplate();
        return sprintf($baseTemplate, 'Заказ - Тур Агенство', $content);
    }
}