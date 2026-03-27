<?php
namespace Models;

class Product
{
    /**
     * Загрузка списка товаров
     */
    public function loadData(): array
    {
        return [
            ['id'=>1,'name'=>'Автострахование','description'=>'ОСАГО и КАСКО с онлайн-оформлением.','price'=>3500,'period'=>'год','image'=>'/assets/images/auto.jpg','coverage'=>'до 10 млн ₽','features'=>['Оформление за 15 мин','Выплаты за 3 дня','Помощь 24/7']],
            ['id'=>2,'name'=>'Имущество','description'=>'Защита недвижимости от пожара и затопления.','price'=>1200,'period'=>'год','image'=>'/assets/images/property.jpg','coverage'=>'до 10 млн ₽','features'=>['От пожара и затопления','Защита от кражи','Онлайн-оценка']],
            ['id'=>3,'name'=>'Здоровье (ДМС)','description'=>'Полисы для взрослых и детей.','price'=>8900,'period'=>'год','image'=>'/assets/images/health.jpg','coverage'=>'до 500 000 ₽','features'=>['Приём без очереди','Диагностика включена','Телемедицина']],
            ['id'=>4,'name'=>'Для бизнеса','description'=>'Страхование ответственности и грузов.','price'=>0,'period'=>'по запросу','image'=>'/assets/images/business.jpg','coverage'=>'до 50 млн ₽','features'=>['Ответственность','Грузы','ДМС для коллективов']],
            ['id'=>5,'name'=>'Путешествия','description'=>'Туристический полис для виз и поездок.','price'=>450,'period'=>'неделя','image'=>'/assets/images/travel.jpg','coverage'=>'до $50 000','features'=>['Медицина за рубежом','Эвакуация','Поддержка 24/7']],
            ['id'=>6,'name'=>'Страхование жизни','description'=>'Накопительное страхование жизни.','price'=>2000,'period'=>'мес','image'=>'/assets/images/life.jpg','coverage'=>'до 20 млн ₽','features'=>['Накопительная часть','Защита при НС','Налоговый вычет']]
        ];
    }
    
    /**
     * Получение товара по ID
     */
    public function getById(int $id): ?array
    {
        foreach ($this->loadData() as $product) {
            if ($product['id'] === $id) return $product;
        }
        return null;
    }
    
    /**
     * 🔹 Получение данных корзины из сессии
     * Этот метод отсутствовал — теперь добавлен!
     */
    public function getBasketData(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $basket = $_SESSION['basket'] ?? [];
        
        // Преобразуем формат корзины в удобный для отображения
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
    
    /**
     * 🔹 Сохранение заказа в JSON-файл
     * Путь: C:\xampp\htdocs\storage\order.json
     */
    public function saveData($arr)
    {
        // Путь к файлу заказа (без Config.php)
        $nameFile = 'C:/xampp/htdocs/storage/order.json';
        
        // Создаём папку, если не существует
        $dir = dirname($nameFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Читаем существующие записи
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
        
        // Добавляем новый заказ
        $allRecords[] = $arr;
        
        // Сохраняем в файл
        $json = json_encode($allRecords, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents($nameFile, $json);
    }
}