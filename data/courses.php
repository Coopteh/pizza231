<?php
// data/courses.php
// Централизованное хранилище данных об услугах
return [
    1 => [
        'id' => 1,
        'title' => 'Абонемент "Безлимит"',
        'icon' => '💪',
        'description' => 'Полный доступ ко всем зонам клуба без ограничений по времени.',
        'features' => [
            'Тренажерный зал',
            'Кардио зона',
            'Групповые программы',
            'Сауна и хаммам'
        ],
        'price_from' => '3 500 ₽',
        'price_numeric' => 3500,
        'duration' => '1 месяц',
        'duration_weeks' => 4,
        'level' => 'Популярный выбор',
        'format' => ['Офлайн'],
        'certificate' => false,
        'job_assistance' => false
    ],
    2 => [
        'id' => 2,
        'title' => 'Персональные тренировки',
        'icon' => '🏋️',
        'description' => 'Индивидуальная работа с тренером для достижения максимальных результатов.',
        'features' => [
            'Составление программы',
            'Контроль техники',
            'План питания',
            'Поддержка 24/7'
        ],
        'price_from' => '1 200 ₽',
        'price_numeric' => 1200,
        'duration' => '1 занятие (60 мин)',
        'duration_weeks' => 0,
        'level' => 'Премиум',
        'format' => ['Офлайн'],
        'certificate' => false,
        'job_assistance' => false
    ],
    3 => [
        'id' => 3,
        'title' => 'Групповые программы',
        'icon' => '👥',
        'description' => 'Йога, пилатес, зумба, кроссфит и другие направления.',
        'features' => [
            'Расписание на неделю',
            'Опытные инструкторы',
            'Разные уровни нагрузки',
            'Дружеская атмосфера'
        ],
        'price_from' => '2 500 ₽',
        'price_numeric' => 2500,
        'duration' => '8 занятий',
        'duration_weeks' => 4,
        'level' => 'Для всех',
        'format' => ['Офлайн'],
        'certificate' => false,
        'job_assistance' => false
    ],
    4 => [
        'id' => 4,
        'title' => 'Годовой контракт',
        'icon' => '📅',
        'description' => 'Максимальная выгода для тех, кто настроен серьезно.',
        'features' => [
            'Заморозка до 30 дней',
            'Гостевые визиты (5 шт)',
            'Персональная тренировка в подарок',
            'Фитнес-тестирование'
        ],
        'price_from' => '25 000 ₽',
        'price_numeric' => 25000,
        'duration' => '12 месяцев',
        'duration_weeks' => 52,
        'level' => 'Выгодно',
        'format' => ['Офлайн'],
        'certificate' => false,
        'job_assistance' => false
    ],
    5 => [
        'id' => 5,
        'title' => 'Детский фитнес',
        'icon' => '👶',
        'description' => 'Безопасные тренировки для детей от 6 до 14 лет.',
        'features' => [
            'Игровой формат',
            'Укрепление здоровья',
            'Малые группы',
            'Отчет для родителей'
        ],
        'price_from' => '2 000 ₽',
        'price_numeric' => 2000,
        'duration' => '1 месяц (8 занятий)',
        'duration_weeks' => 4,
        'level' => 'Дети',
        'format' => ['Офлайн'],
        'certificate' => false,
        'job_assistance' => false
    ],
    6 => [
        'id' => 6,
        'title' => 'Онлайн ведение',
        'icon' => '📱',
        'description' => 'Тренировки и питание под контролем тренера удаленно.',
        'features' => [
            'Приложение для тренировок',
            'Видеосвязь раз в неделю',
            'Коррекция плана',
            'Чат поддержки'
        ],
        'price_from' => '5 000 ₽',
        'price_numeric' => 5000,
        'duration' => '1 месяц',
        'duration_weeks' => 4,
        'level' => 'Онлайн',
        'format' => ['Онлайн'],
        'certificate' => false,
        'job_assistance' => false
    ],
    // === НОВЫЕ ТОВАРЫ ===
    7 => [
        'id' => 7,
        'title' => 'Ozempic',
        'icon' => '💉',
        'description' => 'Препарат для контроля аппетита и ускорения жиросжигания.',
        'features' => [
            'Снижение аппетита',
            'Контроль уровня сахара',
            'Ускорение метаболизма',
            'Консультация врача'
        ],
        'price_from' => '8 500 ₽',
        'price_numeric' => 8500,
        'duration' => '1 курс (4 недели)',
        'duration_weeks' => 4,
        'level' => 'Популярное',
        'format' => ['Офлайн'],
        'certificate' => true,
        'job_assistance' => false
    ],
    8 => [
        'id' => 8,
        'title' => 'Trenbolone',
        'icon' => '🔥',
        'description' => 'Мощный анаболик для набора мышечной массы и силы.',
        'features' => [
            'Быстрый рост мышц',
            'Увеличение силы',
            'Сжигание жира',
            'Повышение выносливости'
        ],
        'price_from' => '12 000 ₽',
        'price_numeric' => 12000,
        'duration' => '1 курс (8 недель)',
        'duration_weeks' => 8,
        'level' => 'Профи',
        'format' => ['Офлайн'],
        'certificate' => true,
        'job_assistance' => false
    ]
];