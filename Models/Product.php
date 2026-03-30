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
        return [
            ['id'=>1,'name'=>'Автострахование','description'=>'ОСАГО и КАСКО с онлайн-оформлением.','price'=>3500,'period'=>'год','image'=>'/assets/images/auto.jpg','coverage'=>'до 10 млн ₽','features'=>['Оформление за 15 мин','Выплаты за 3 дня','Помощь 24/7']],
            ['id'=>2,'name'=>'Имущество','description'=>'Защита недвижимости от пожара и затопления.','price'=>1200,'period'=>'год','image'=>'/assets/images/property.jpg','coverage'=>'до 10 млн ₽','features'=>['От пожара и затопления','Защита от кражи','Онлайн-оценка']],
            ['id'=>3,'name'=>'Здоровье (ДМС)','description'=>'Полисы для взрослых и детей.','price'=>8900,'period'=>'год','image'=>'/assets/images/health.jpg','coverage'=>'до 500 000 ₽','features'=>['Приём без очереди','Диагностика включена','Телемедицина']],
            ['id'=>4,'name'=>'Для бизнеса','description'=>'Страхование ответственности и грузов.','price'=>0,'period'=>'по запросу','image'=>'/assets/images/business.jpg','coverage'=>'до 50 млн ₽','features'=>['Ответственность','Грузы','ДМС для коллективов']],
            ['id'=>5,'name'=>'Путешествия','description'=>'Туристический полис для виз и поездок.','price'=>450,'period'=>'неделя','image'=>'/assets/images/travel.jpg','coverage'=>'до $50 000','features'=>['Медицина за рубежом','Эвакуация','Поддержка 24/7']],
            ['id'=>6,'name'=>'Страхование жизни','description'=>'Накопительное страхование жизни.','price'=>2000,'period'=>'мес','image'=>'/assets/images/life.jpg','coverage'=>'до 20 млн ₽','features'=>['Накопительная часть','Защита при НС','Налоговый вычет']]
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
        
        foreach ($basketData as $item) {
            if (isset($item['product'], $item['quantity'])) {
                $product = $item['product'];
                $quantity = max(1, (int)$item['quantity']);
                $price = (float)$product['price'];
                $subtotal = $price * $quantity;
                
                $allSum += $subtotal;
                
                $preparedProducts[] = [
                    'id' => (int)$product['id'],
                    'name' => htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'),
                    'price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }
        }
        
        return [
            'order_id' => 'ORD_' . uniqid(),
            'user_id' => $_SESSION['user_id'] ?? 0,
            'user_email' => $_SESSION['user_email'] ?? '',
            'fio' => $sanitized['fio'] ?? '',
            'phone' => $sanitized['phone'] ?? '',
            'delivery_type' => $sanitized['delivery_type'] ?? 'email',
            'email' => $sanitized['email'] ?? '',
            'address' => $sanitized['address'] ?? '',
            'products' => $preparedProducts,
            'all_sum' => $allSum,
            'total' => $allSum,
            'created_at' => date('d.m.Y H:i:s'),
            'status' => 'new'
        ];
    }
    
    private function saveProducts(array $products): void
    {
        $file = 'C:/xampp/htdocs/Storage/products.json';
        $dir = dirname($file);
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        file_put_contents($file, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}