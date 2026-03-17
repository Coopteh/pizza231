<?php
namespace Models;

class Product
{
    public function loadData(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Автострахование',
                'description' => 'ОСАГО и КАСКО с онлайн-оформлением. Выплаты в установленные сроки.',
                'price' => 3500,
                'period' => 'год',
                'image' => '/assets/images/auto.jpg',
                'coverage' => 'до 10 млн ₽',
                'features' => ['Оформление за 15 мин', 'Выплаты за 3 дня', 'Помощь 24/7']
            ],
            [
                'id' => 2,
                'name' => 'Имущество',
                'description' => 'Защита недвижимости от пожара, затопления, кражи и стихийных бедствий.',
                'price' => 1200,
                'period' => 'год',
                'image' => '/assets/images/property.jpg',
                'coverage' => 'до 10 млн ₽',
                'features' => ['От пожара и затопления', 'Защита от кражи', 'Онлайн-оценка']
            ],
            [
                'id' => 3,
                'name' => 'Здоровье (ДМС)',
                'description' => 'Полисы для взрослых и детей. Обслуживание в партнёрских клиниках.',
                'price' => 8900,
                'period' => 'год',
                'image' => '/assets/images/health.jpg',
                'coverage' => 'до 500 000 ₽',
                'features' => ['Приём без очереди', 'Диагностика включена', 'Телемедицина']
            ],
            [
                'id' => 4,
                'name' => 'Для бизнеса',
                'description' => 'Страхование ответственности, грузов и здоровья сотрудников.',
                'price' => 0,
                'period' => 'по запросу',
                'image' => '/assets/images/business.jpg',
                'coverage' => 'до 50 млн ₽',
                'features' => ['Ответственность', 'Грузы', 'ДМС для коллективов']
            ],
            [
                'id' => 5,
                'name' => 'Путешествия',
                'description' => 'Туристический полис для виз и поездок. Покрытие медицинских расходов за рубежом.',
                'price' => 450,
                'period' => 'неделя',
                'image' => '/assets/images/travel.jpg',
                'coverage' => 'до $50 000',
                'features' => ['Медицина за рубежом', 'Эвакуация', 'Поддержка 24/7']
            ],
            [
                'id' => 6,
                'name' => 'Страхование жизни',
                'description' => 'Накопительное страхование жизни. Защита семьи и инвестиции в будущее.',
                'price' => 2000,
                'period' => 'мес',
                'image' => '/assets/images/life.jpg',
                'coverage' => 'до 20 млн ₽',
                'features' => ['Накопительная часть', 'Защита при НС', 'Налоговый вычет']
            ]
        ];
    }
    
    public function getById(int $id): ?array
    {
        $data = $this->loadData();
        foreach ($data as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }
}