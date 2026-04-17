# KODA.md — Инструкция для AI-ассистента

## Обзор проекта

**Название:** Страховая компания «Чёрный Вантуз»  
**Тип:** Веб-приложение на PHP (MVC-архитектура)  
**Целевая группа:** Учебный проект для группы ИС-231  
**Описание:** Интернет-магазин страховых услуг с функциями авторизации, корзины, оформления заказов и админ-панели.

---

## Технологии и стек

| Компонент | Технология |
|-----------|------------|
| **Бэкенд** | PHP >= 7.4 |
| **Фронтенд** | Bootstrap 5.3, HTML5, CSS3, JavaScript |
| **База данных** | MySQL (через PDO) |
| **Почтовый сервер** | PHPMailer (SMTP Yandex) |
| **Зависимости** | Composer (phpmailer/phpmailer, phpunit/phpunit) |
| **Веб-сервер** | Apache (XAMPP) |

---

## Структура проекта

```
C:/xampp/htdocs/
├── index.php              # Точка входа, роутинг
├── .htaccess              # Конфигурация Apache (URL rewriting)
├── .env                   # Переменные окружения (SMTP, БД, ADMIN_CODE)
├── composer.json          # Зависимости и скрипты
├── migrate.php            # Скрипт миграции БД
├── config/
│   ├── env.php            # Загрузчик .env
│   └── database.php       # Подключение к MySQL (PDO)
├── Controllers/           # Контроллеры (12 шт.)
│   ├── HomeController.php
│   ├── AuthController.php
│   ├── BasketController.php
│   ├── OrderController.php
│   ├── AdminController.php
│   ├── ProductController.php
│   ├── ProfileController.php
│   ├── VerificationController.php
│   └── ...
├── Models/                # Модели данных
│   ├── Product.php        # Работа с товарами/услугами
│   ├── User.php           # Работа с пользователями
│   ├── Order.php          # Работа с заказами
│   └── Log.php            # Логирование событий
├── Views/                 # Шаблоны представлений
│   ├── BaseTemplate.php   # Базовый HTML-шаблон
│   ├── AdminTemplate.php  # Шаблоны админ-панели
│   ├── AuthTemplate.php   # Формы входа/регистрации
│   ├── BasketTemplate.php # Корзина
│   ├── OrderTemplate.php  # Оформление заказа
│   └── ...
├── Storage/               # Файловые данные (для миграции)
│   ├── products.json      # 📦 Каталог услуг (старый формат)
│   ├── users.json         # 👥 Пользователи (старый формат)
│   ├── order.json         # 📋 Заказы (старый формат)
│   ├── logs.json          # 📜 Журнал событий (старый формат)
│   └── codes/             # Коды верификации
├── assets/                # Статические ресурсы
│   ├── css/
│   ├── js/
│   └── images/
└── tests/                 # PHPUnit тесты
    └── ProductTest.php
```

---

## База данных

### Подключение

База данных MySQL подключается через PDO (Singleton pattern).

**Файл:** `config/database.php`

**Настройки в .env:**
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=black_vantuz
DB_USER=root
DB_PASS=
```

### Таблицы

| Таблица | Описание |
|---------|----------|
| `users` | Пользователи (id, name, email, password, role, verified...) |
| `products` | Товары/услуги (id, name, description, price, features JSON) |
| `orders` | Заказы (order_id, user_id, products JSON, total, status) |
| `logs` | Журнал событий (id, timestamp, user, action, details JSON) |
| `verification_codes` | Коды верификации email |

### Миграция

Запустите в браузере: `http://localhost/migrate.php`

Скрипт создаст БД, таблицы и перенесёт данные из JSON-файлов.

---

## Сборка и запуск

### Предварительные требования
- XAMPP (Apache + MySQL + PHP >= 7.4)
- Composer
- Git

### Установка

1. **Установить зависимости:**
   ```bash
   composer install
   ```

2. **Настроить .env:**
   ```env
   # База данных
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=black_vantuz
   DB_USER=root
   DB_PASS=

   # SMTP
   SMTP_HOST=smtp.yandex.ru
   SMTP_PORT=465
   SMTP_USER=ваш_email@yandex.ru
   SMTP_PASS=ваш_пароль_приложения

   # Админ
   ADMIN_CODE=123123
   ```

3. **Разместить файлы:**
   - Поместить проект в `C:/xampp/htdocs/`
   - Убедиться, что Apache и MySQL запущены

4. **Выполнить миграцию БД:**
   - Откройте в браузере: `http://localhost/migrate.php`
   - Данные из JSON будут перенесены в MySQL

5. **Запустить сервер:**
   ```bash
   # Через XAMPP Control Panel запустить Apache и MySQL
   # Или через терминал:
   cd C:/xampp/htdocs
   php -S localhost:8000 -t .
   ```

6. **Открыть в браузере:**
   ```
   http://localhost
   ```

### Команды разработки

| Команда | Описание |
|---------|----------|
| `composer install` | Установка зависимостей |
| `composer test` | Запуск PHPUnit тестов |
| `php -S localhost:8000 -t .` | Запуск встроенного сервера PHP |

---

## Архитектура

### MVC-паттерн

```
┌─────────────────────────────────────────────────────────────┐
│                      index.php (Router)                      │
│                    .htaccess (Rewrite)                       │
└────────────────────────────┬────────────────────────────────┘
                             │
        ┌────────────────────┼────────────────────┐
        ▼                    ▼                    ▼
┌──────────────┐    ┌────────────────┐    ┌──────────────┐
│ Controllers  │───▶│     Models     │───▶│    Views     │
│  (Логика)    │    │   (Данные)     │    │  (Шаблоны)   │
└──────────────┘    └────────────────┘    └──────────────┘
        │                    │
        └────────────────────┘
              MySQL (PDO)
```

### Ключевые классы

| Класс | Назначение |
|-------|------------|
| `EnvLoader` | Загрузка переменных из `.env` |
| `BaseTemplate` | Базовый HTML-шаблон с навигацией |
| `Product` | Управление товарами, корзиной, заказами |
| `User` | Авторизация, регистрация, управление пользователями |
| `Log` | Система логирования событий |
| `AdminController` | Управление товарами, пользователями, логами |

---

## Маршрутизация

### Основные маршруты

| URL | Метод | Описание |
|-----|-------|----------|
| `/` | GET | Главная страница |
| `/services` | GET | Страница услуг |
| `/products` | GET | Каталог продуктов |
| `/product/{id}` | GET | Карточка товара |
| `/about` | GET | О компании |
| `/cart` | GET | Корзина |
| `/cart/add` | POST | Добавить в корзину |
| `/cart/remove` | GET | Удалить из корзины |
| `/order` | GET/POST | Оформление заказа |
| `/login` | GET/POST | Вход |
| `/register` | GET/POST | Регистрация |
| `/logout` | GET | Выход |
| `/profile` | GET | Профиль пользователя |
| `/verify` | GET | Подтверждение email |
| `/admin` | GET | Админ-панель |
| `/admin/logs` | GET | Журнал событий |

---

## Правила разработки

### Стиль кода

1. **PSR-4 автозагрузка:** Пространства имён соответствуют структуре директорий
   - `Controllers\` → `Controllers/`
   - `Models\` → `Models/`
   - `Views\` → `Views/`

2. **Именование:**
   - Классы: PascalCase (`HomeController`, `ProductModel`)
   - Методы: camelCase (`getById()`, `saveData()`)
   - Файлы: совпадают с именами классов

3. **Комментарии:** Использование эмодзи для визуального разделения (🔹, ✅, ⚠️)

### Безопасность

1. **XSS-защита:** Все пользовательские данные проходят через `htmlspecialchars()` и `strip_tags()`
2. **Пароли:** Хеширование через `password_hash()` с `PASSWORD_DEFAULT`
3. **Сессии:** Старт сессии в начале каждого запроса, проверка `session_status()`
4. **Валидация:** Проверка всех входных данных (email, количество товаров и т.д.)

### Тестирование

- Фреймворк: **PHPUnit 9.6**
- Расположение: `tests/`
- Запуск: `composer test`

Пример теста:
```php
public function testPrepareDataCalculatesTotalCorrectly()
{
    $model = new Product();
    $result = $model->prepareData($formData, $basketData);
    $this->assertEquals(1300, $result['all_sum']);
}
```

### Логирование

- Все важные действия логируются в таблицу `logs` MySQL
- Максимум 500 записей (автоматическая ротация)
- Логи включают: timestamp, user, action, IP, details

---

## Структура базы данных

### products

| Поле | Тип | Описание |
|------|-----|----------|
| id | INT | PRIMARY KEY |
| name | VARCHAR(255) | Название |
| description | TEXT | Описание |
| price | DECIMAL(10,2) | Цена |
| period | VARCHAR(50) | Период (год/мес и т.д.) |
| image | VARCHAR(500) | Путь к изображению |
| coverage | VARCHAR(100) | Покрытие |
| features | JSON | Преимущества (массив) |

### users

| Поле | Тип | Описание |
|------|-----|----------|
| id | INT | PRIMARY KEY |
| name | VARCHAR(255) | Имя пользователя |
| email | VARCHAR(255) | UNIQUE email |
| password | VARCHAR(255) | Хешированный пароль |
| phone | VARCHAR(50) | Телефон |
| card_last4 | VARCHAR(10) | Последние 4 цифры карты |
| role | ENUM | 'user' или 'admin' |
| verified | BOOLEAN | Подтверждён ли email |
| created_at | TIMESTAMP | Дата регистрации |

### orders

| Поле | Тип | Описание |
|------|-----|----------|
| id | INT | PRIMARY KEY |
| order_id | VARCHAR(50) | UNIQUE номер заказа |
| user_id | INT | FOREIGN KEY → users |
| fio | VARCHAR(255) | ФИО клиента |
| phone | VARCHAR(50) | Телефон |
| delivery_type | ENUM | 'email' или 'courier' |
| products | JSON | Товары заказа |
| total | DECIMAL(10,2) | Итоговая сумма |
| status | ENUM | new/processing/completed/cancelled |

### logs

| Поле | Тип | Описание |
|------|-----|----------|
| id | VARCHAR(50) | PRIMARY KEY |
| timestamp | DATETIME | Время события |
| user | VARCHAR(255) | Пользователь |
| action | VARCHAR(255) | Действие |
| ip | VARCHAR(50) | IP-адрес |
| details | JSON | Детали события |

---

## Известные особенности

1. **Два типа хранения корзины:** Session (по умолчанию) и Cookie
2. **Верификация email:** Требуется для регистрации (код отправляется на email)
3. **Админ-доступ:** Активируется кодом из `.env` (`ADMIN_CODE=123123`)
4. **Миграция данных:** Перенос из JSON в MySQL выполняется через `migrate.php`

---

## Полезные ссылки

- **Диаграмма классов:** `Storage/class diagram.drawio`
- **Документация PHP:** https://www.php.net/manual/ru/
- **Bootstrap 5:** https://getbootstrap.com/docs/5.3/
- **PHPUnit:** https://phpunit.de/documentation.html

---

## Контекст для AI

При работе с этим проектом:
1. **Всегда** проверяй наличие `.env` перед использованием SMTP-настроек и настроек БД
2. **Используй** `Database::getConnection()` для получения подключения к MySQL
3. **Следуй** существующему стилю (PSR-4, эмодзи-комментарии, prepared statements)
4. **Предлагай** тесты при изменении бизнес-логики
5. **Уважай** систему логирования — новые важные действия должны фиксироваться в таблицу `logs`
6. **Помни** о безопасности — все пользовательские данные должны проходить валидацию и экранирование
