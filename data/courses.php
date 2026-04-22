<?php

// data/courses.php
// Централизованное хранилище данных о курсах (многоязычное)
return [
    1 => [
        'id' => 1,
        'title' => ['ru' => 'Python-разработчик', 'en' => 'Python Developer'],
        'icon' => 'PY',
        'description' => ['ru' => 'От основ программирования до создания полноценных веб-приложений.', 'en' => 'From programming basics to creating full-fledged web applications.'],
        'features' => [
            ['ru' => 'Синтаксис Python с нуля', 'en' => 'Python syntax from scratch'],
            ['ru' => 'Работа с базами данных', 'en' => 'Working with databases'],
            ['ru' => 'Создание REST API', 'en' => 'Creating REST API'],
            ['ru' => 'Дипломный проект в портфолио', 'en' => 'Diploma project in portfolio']
        ],
        'price_from' => '54 000 ₽',
        'price_numeric' => 54000,
        'duration' => ['ru' => '12 недель • 3-5 часов в неделю', 'en' => '12 weeks • 3-5 hours per week'],
        'duration_weeks' => 12,
        'level' => ['ru' => 'Начальный уровень', 'en' => 'Beginner Level'],
        'format' => [['ru' => 'Онлайн', 'en' => 'Online'], ['ru' => 'Офис', 'en' => 'Office']],
        'certificate' => true,
        'job_assistance' => true
    ],
    2 => [
        'id' => 2,
        'title' => ['ru' => 'Frontend: React', 'en' => 'Frontend: React'],
        'icon' => 'RX',
        'description' => ['ru' => 'Создавай современные интерактивные интерфейсы для веб-приложений.', 'en' => 'Create modern interactive interfaces for web applications.'],
        'features' => [
            ['ru' => 'HTML5, CSS3, JavaScript ES6+', 'en' => 'HTML5, CSS3, JavaScript ES6+'],
            ['ru' => 'React + Redux', 'en' => 'React + Redux'],
            ['ru' => 'Работа с API', 'en' => 'Working with API'],
            ['ru' => '3 проекта в портфолио', 'en' => '3 projects in portfolio']
        ],
        'price_from' => '50 000 ₽',
        'price_numeric' => 50000,
        'duration' => ['ru' => '10 недель • 4-6 часов в неделю', 'en' => '10 weeks • 4-6 hours per week'],
        'duration_weeks' => 10,
        'level' => ['ru' => 'Средний уровень', 'en' => 'Intermediate Level'],
        'format' => [['ru' => 'Онлайн', 'en' => 'Online'], ['ru' => 'Офис', 'en' => 'Office']],
        'certificate' => true,
        'job_assistance' => true
    ],
    3 => [
        'id' => 3,
        'title' => ['ru' => 'SQL и базы данных', 'en' => 'SQL and Databases'],
        'icon' => 'DB',
        'description' => ['ru' => 'Проектирование, оптимизация и работа с большими данными.', 'en' => 'Design, optimization and working with big data.'],
        'features' => [
            ['ru' => 'PostgreSQL, MySQL', 'en' => 'PostgreSQL, MySQL'],
            ['ru' => 'Сложные запросы', 'en' => 'Complex queries'],
            ['ru' => 'Оптимизация производительности', 'en' => 'Performance optimization'],
            ['ru' => 'Работа с Big Data', 'en' => 'Working with Big Data']
        ],
        'price_from' => '32 000 ₽',
        'price_numeric' => 32000,
        'duration' => ['ru' => '8 недель • 3-4 часа в неделю', 'en' => '8 weeks • 3-4 hours per week'],
        'duration_weeks' => 8,
        'level' => ['ru' => 'Начальный уровень', 'en' => 'Beginner Level'],
        'format' => [['ru' => 'Онлайн', 'en' => 'Online'], ['ru' => 'Офис', 'en' => 'Office']],
        'certificate' => true,
        'job_assistance' => false
    ],
    4 => [
        'id' => 4,
        'title' => ['ru' => 'Machine Learning', 'en' => 'Machine Learning'],
        'icon' => 'ML',
        'description' => ['ru' => 'Нейросети, компьютерное зрение, обработка естественного языка.', 'en' => 'Neural networks, computer vision, natural language processing.'],
        'features' => [
            ['ru' => 'Python для ML', 'en' => 'Python for ML'],
            ['ru' => 'TensorFlow, PyTorch', 'en' => 'TensorFlow, PyTorch'],
            ['ru' => 'Computer Vision, NLP', 'en' => 'Computer Vision, NLP'],
            ['ru' => 'Деплой моделей', 'en' => 'Model deployment']
        ],
        'price_from' => '112 000 ₽',
        'price_numeric' => 112000,
        'duration' => ['ru' => '16 недель • 5-7 часов в неделю', 'en' => '16 weeks • 5-7 hours per week'],
        'duration_weeks' => 16,
        'level' => ['ru' => 'Продвинутый уровень', 'en' => 'Advanced Level'],
        'format' => [['ru' => 'Онлайн', 'en' => 'Online']],
        'certificate' => true,
        'job_assistance' => true
    ],
    5 => [
        'id' => 5,
        'title' => ['ru' => 'Web3 & Blockchain', 'en' => 'Web3 & Blockchain'],
        'icon' => 'W3',
        'description' => ['ru' => 'Смарт-контракты, dApps, разработка на Solidity.', 'en' => 'Smart contracts, dApps, Solidity development.'],
        'features' => [
            ['ru' => 'Основы блокчейна', 'en' => 'Blockchain basics'],
            ['ru' => 'Solidity программирование', 'en' => 'Solidity programming'],
            ['ru' => 'Создание dApps', 'en' => 'Creating dApps'],
            ['ru' => 'Аудит смарт-контрактов', 'en' => 'Smart contract audit']
        ],
        'price_from' => '91 000 ₽',
        'price_numeric' => 91000,
        'duration' => ['ru' => '14 недель • 4-6 часов в неделю', 'en' => '14 weeks • 4-6 hours per week'],
        'duration_weeks' => 14,
        'level' => ['ru' => 'Средний уровень', 'en' => 'Intermediate Level'],
        'format' => [['ru' => 'Онлайн', 'en' => 'Online']],
        'certificate' => true,
        'job_assistance' => true
    ],
    6 => [
        'id' => 6,
        'title' => ['ru' => 'Mobile Dev (Flutter)', 'en' => 'Mobile Dev (Flutter)'],
        'icon' => 'MB',
        'description' => ['ru' => 'Кроссплатформенные приложения для iOS и Android.', 'en' => 'Cross-platform applications for iOS and Android.'],
        'features' => [
            ['ru' => 'Dart с нуля', 'en' => 'Dart from scratch'],
            ['ru' => 'Flutter фреймворк', 'en' => 'Flutter framework'],
            ['ru' => 'Публикация в Store', 'en' => 'Publishing to Store'],
            ['ru' => '2 приложения в портфолио', 'en' => '2 applications in portfolio']
        ],
        'price_from' => '66 000 ₽',
        'price_numeric' => 66000,
        'duration' => ['ru' => '12 недель • 4-5 часов в неделю', 'en' => '12 weeks • 4-5 hours per week'],
        'duration_weeks' => 12,
        'level' => ['ru' => 'Начальный уровень', 'en' => 'Beginner Level'],
        'format' => [['ru' => 'Онлайн', 'en' => 'Online'], ['ru' => 'Офис', 'en' => 'Office']],
        'certificate' => true,
        'job_assistance' => true
    ]
];
