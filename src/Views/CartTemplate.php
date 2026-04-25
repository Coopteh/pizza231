<?php
namespace App\Views;

use App\Models\Cart;

class CartTemplate extends BaseTemplate
{
    /**
     * Путь к файлу шаблона
     */
    private const TEMPLATE_PATH = __DIR__ . '/templates/cart.html.php';

    /**
     * Путь к файлу с текстами
     */
    private const TEXTS_PATH = __DIR__ . '/../../storage/templates/cart.json';

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

    public static function render(): string
    {
        // Загружаем тексты
        $texts = self::loadTexts();

        $cartItems = Cart::getItems();
        $total = Cart::getTotal();
        $count = Cart::getCount();

        $content = self::renderCartContent($cartItems, $total, $count, $texts);

        return parent::getTemplate($content, $texts);
    }

    private static function renderCartContent(array $items, float $total, int $count, array $texts = []): string
    {
        // Подготовка переменных для шаблона
        $isEmpty = empty($items);
        
        if ($isEmpty) {
            $cartRows = '';
            $totalFormatted = '0';
            $cartJson = '[]';
        } else {
            $cartRows = '';
            foreach ($items as $item) {
                $name = htmlspecialchars($item['name']);
                $price = number_format($item['price'], 0, '.', ' ');
                $image = htmlspecialchars($item['image']);
                $id = (int)$item['id'];
                $quantity = (int)$item['quantity'];
                $subtotal = $item['price'] * $quantity;
                
                $cartRows .= '
                <tr class="align-middle" data-id="' . $id . '">
                    <td class="py-3">
                        <div class="d-flex align-items-center">
                            <img src="' . $image . '" 
                                 alt="' . $name . '" 
                                 class="rounded me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 onerror="this.src=\'/assets/img/no-image.jpg\'">
                            <span class="fw-medium">' . $name . '</span>
                        </div>
                    </td>
                    <td class="py-3">' . $price . ' ₽</td>
                    <td class="py-3">
                        <div class="input-group input-group-sm" style="max-width: 120px;">
                            <button class="btn btn-outline-secondary btn-quantity" data-action="decrease" data-id="' . $id . '">−</button>
                            <input type="number" class="form-control text-center quantity-input" 
                                   value="' . $quantity . '" data-id="' . $id . '" min="1">
                            <button class="btn btn-outline-secondary btn-quantity" data-action="increase" data-id="' . $id . '">+</button>
                        </div>
                    </td>
                    <td class="py-3 fw-bold">' . number_format($subtotal, 0, '.', ' ') . ' ₽</td>
                    <td class="py-3 text-end">
                        <button class="btn btn-sm btn-outline-danger btn-remove" data-id="' . $id . '" title="Удалить">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>';
            }
            $totalFormatted = number_format($total, 0, '.', ' ');
            $cartJson = json_encode($items, JSON_UNESCAPED_UNICODE);
        }

        // Подключаем шаблон (тексты доступны через замыкание)
        ob_start();
        include self::TEMPLATE_PATH;
        return ob_get_clean();
    }
}