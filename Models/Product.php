<?php
namespace Models;

class Product
{
    public function loadData(): array
    {
        $file = 'C:/xampp/htdocs/Storage/products.json';
        if (file_exists($file)) {
            $data = file_get_contents($file);
            $products = json_decode($data, true);
            if (is_array($products) && !empty($products)) {
                return $products;
            }
        }
        // 🔹 Дефолтные данные — клубные карты фитнес-клуба
        return [
            [
                'id' => 1,
                'name' => 'Безлимит',
                'description' => 'Полный доступ ко всем зонам клуба: тренажёрный зал, групповые программы, бассейн, сауна.',
                'price' => 3500,
                'period' => 'мес',
                'image' => '/assets/images/card-unlimited.jpg',
                'coverage' => 'Все зоны',
                'features' => [
                    'Тренажёрный зал 24/7',
                    'Групповые программы без ограничений',
                    'Бассейн и спа-зона',
                    'Гостевые визиты (2 в месяц)',
                    'Заморозка карты до 14 дней'
                ]
            ],
            [
                'id' => 2,
                'name' => 'Утро',
                'description' => 'Доступ в клуб с 07:00 до 12:00. Идеально для ранних тренировок.',
                'price' => 1900,
                'period' => 'мес',
                'image' => '/assets/images/card-morning.jpg',
                'coverage' => 'Зал + Групповые (утро)',
                'features' => [
                    'Доступ с 07:00 до 12:00',
                    'Тренажёрный зал',
                    'Утренние групповые занятия',
                    'Раздевалка и душ',
                    'Бесплатная парковка'
                ]
            ],
            [
                'id' => 3,
                'name' => 'Базовая',
                'description' => 'Доступ только в тренажёрный зал. Оптимально для самостоятельных тренировок.',
                'price' => 1200,
                'period' => 'мес',
                'image' => '/assets/images/card-basic.jpg',
                'coverage' => 'Тренажёрный зал',
                'features' => [
                    'Кардио- и силовая зоны',
                    'Свободные веса',
                    'Базовые тренажёры',
                    'Раздевалка и душ',
                    'Вводный инструктаж'
                ]
            ],
            [
                'id' => 4,
                'name' => 'Персональный тренер',
                'description' => 'Индивидуальные тренировки с сертифицированным специалистом.',
                'price' => 0,
                'period' => 'по запросу',
                'image' => '/assets/images/card-personal.jpg',
                'coverage' => 'Индивидуально',
                'features' => [
                    'Персональная программа',
                    'Контроль техники',
                    'Питание и восстановление',
                    'Замеры и прогресс',
                    'Поддержка 24/7 в чате'
                ]
            ],
            [
                'id' => 5,
                'name' => 'Студенческая',
                'description' => 'Специальный тариф для студентов дневной формы обучения.',
                'price' => 890,
                'period' => 'мес',
                'image' => '/assets/images/card-student.jpg',
                'coverage' => 'Зал + Групповые (по расписанию)',
                'features' => [
                    'Скидка при предъявлении студенческого',
                    'Доступ в часы пик со скидкой',
                    'Групповые занятия по расписанию',
                    'Бесплатный фитнес-тест',
                    'Участие в студенческих челленджах'
                ]
            ],
            [
                'id' => 6,
                'name' => 'Годовая',
                'description' => 'Выгодный тариф на 12 месяцев. Экономия до 20% по сравнению с помесячной оплатой.',
                'price' => 29900,
                'period' => 'год',
                'image' => '/assets/images/card-year.jpg',
                'coverage' => 'Все зоны + бонусы',
                'features' => [
                    'Экономия до 20%',
                    'Приоритетная запись на групповые',
                    'Бесплатная заморозка до 30 дней',
                    'Гостевые визиты (5 в год)',
                    'Скидка 15% на персональные тренировки',
                    'Подарок: фитнес-браслет или сумка'
                ]
            ]
        ];
    }
    
    public function getById(int $id): ?array
    {
        foreach ($this->loadData() as $product) {
            if ($product['id'] === $id) return $product;
        }
        return null;
    }
    
    public function getBasketData(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $basket = $_SESSION['basket'] ?? [];
        $result = [];
        foreach ($basket as $productId => $item) {
            $product = $this->getById((int)$productId);
            if ($product) {
                $result[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'] ?? 1,
                    'subtotal' => $product['price'] * ($item['quantity'] ?? 1)
                ];
            }
        }
        return $result;
    }
    
    public function saveData($arr)
    {
        $nameFile = 'C:/xampp/htdocs/storage/order.json';
        $dir = dirname($nameFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $allRecords = [];
        if (file_exists($nameFile)) {
            $data = file_get_contents($nameFile);
            if (!empty($data)) {
                $allRecords = json_decode($data, true);
                if (!is_array($allRecords)) {
                    $allRecords = [];
                }
            }
        }
        $allRecords[] = $arr;
        $json = json_encode($allRecords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents($nameFile, $json);
    }
    
    public function updateProduct(array $data): bool
    {
        $products = $this->loadData();
        foreach ($products as &$product) {
            if ($product['id'] === $data['id']) {
                $product = $data;
                $this->saveProducts($products);
                return true;
            }
        }
        return false;
    }
    
    public function addProduct(array $data): bool
    {
        $products = $this->loadData();
        $products[] = $data;
        $this->saveProducts($products);
        return true;
    }
    
    /**
     * 🔹 Подготовка данных заказа для сохранения
     * @param array $formData - данные из формы ($_POST)
     * @param array $basketData - товары из корзины
     * @return array - подготовленный массив для сохранения
     */
    public function prepareData(array $formData, array $basketData): array
{
    // Санитизация текстовых полей
    $sanitized = [];
    $textFields = ['fio', 'phone', 'email', 'address', 'delivery_type'];
    foreach ($textFields as $field) {
        if (isset($formData[$field])) {
            $value = trim($formData[$field]);
            $sanitized[$field] = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
        }
    }
    
    $allSum = 0;
    $preparedProducts = [];
    
    // 🔥 Цикл формирования списка товаров (ОБЯЗАТЕЛЬНО так)
    foreach ($basketData as $item) {
        if (isset($item['product'], $item['quantity'])) {
            $product = $item['product'];
            $quantity = max(1, (int)$item['quantity']);
            $price = (float)($product['price'] ?? 0);
            $subtotal = $price * $quantity;
            
            $allSum += $subtotal;
            
            $preparedProducts[] = [
                'id' => (int)($product['id'] ?? 0),
                'name' => htmlspecialchars($product['name'] ?? 'Услуга', ENT_QUOTES, 'UTF-8'),
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
    
    // 🔥 Возвращаем массив с ключом 'products'
    return [
        'order_id' => 'GYM_' . uniqid(),
        'user_id' => $_SESSION['user_id'] ?? 0,
        'user_email' => $_SESSION['user_email'] ?? '',
        'fio' => $sanitized['fio'] ?? '',
        'phone' => $sanitized['phone'] ?? '',
        'delivery_type' => $sanitized['delivery_type'] ?? 'email',
        'email' => $sanitized['email'] ?? '',
        'address' => $sanitized['address'] ?? '',
        'products' => $preparedProducts,  // 🔥 Ключ именно 'products'
        'all_sum' => $allSum,
        'total' => $allSum,
        'created_at' => date('d.m.Y H:i:s'),
        'status' => 'new'
    ];
}
}