# 🔹 Инструкция по миграции на MySQL

## 📋 Обзор

Проект переведён с JSON-файлов на MySQL базу данных через PDO.

---

## 🚀 Быстрый старт

### 1. Запустите XAMPP
- Запустите Apache и **MySQL** через XAMPP Control Panel

### 2. Выполните миграцию
Откройте в браузере:
```
http://localhost/migrate.php
```

Скрипт автоматически:
- ✅ Создаст базу данных `black_vantuz`
- ✅ Создаст все необходимые таблицы
- ✅ Перенесёт данные из `Storage/*.json` в MySQL

### 3. Проверьте работу сайта
```
http://localhost/
```

---

## 📊 Структура базы данных

### Таблицы

| Таблица | Описание |
|---------|----------|
| `users` | Пользователи (авторизация, профили) |
| `products` | Товары/услуги страхования |
| `orders` | Заказы пользователей |
| `logs` | Журнал событий системы |
| `verification_codes` | Коды верификации email |

### Схема БД

```sql
CREATE DATABASE black_vantuz CHARACTER SET utf8mb4;

-- users: id, name, email, password, phone, role, verified...
-- products: id, name, description, price, period, image, features(JSON)...
-- orders: order_id, user_id, fio, phone, products(JSON), total...
-- logs: id, timestamp, user, action, ip, details(JSON)...
-- verification_codes: user_id, code, expires_at...
```

---

## ⚙️ Настройка подключения

Файл `.env`:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=black_vantuz
DB_USER=root
DB_PASS=
```

### Изменение настроек

| Параметр | Значение по умолчанию | Описание |
|----------|----------------------|----------|
| `DB_HOST` | `localhost` | Хост MySQL |
| `DB_PORT` | `3306` | Порт MySQL |
| `DB_NAME` | `black_vantuz` | Имя БД |
| `DB_USER` | `root` | Пользователь |
| `DB_PASS` | `` (пусто) | Пароль |

---

## 🔧 Устранение проблем

### Ошибка подключения к БД
```
❌ Ошибка подключения к БД: [SQLSTATE ...]
```

**Решение:**
1. Проверьте запущен ли MySQL в XAMPP
2. Проверьте настройки в `.env`
3. Убедитесь что порт 3306 свободен

### Данные не перенеслись
1. Убедитесь что файлы `Storage/*.json` существуют
2. Проверьте права доступа к файлам
3. Запустите `migrate.php` повторно (данные не дублируются)

### Сайт работает медленно
- Проверьте что используются prepared statements (уже реализовано)
- Добавьте индексы в таблицы при необходимости

---

## 📝 Дополнительные команды

### Очистка БД (сброс)
```sql
-- В phpMyAdmin или mysql CLI:
DROP DATABASE IF EXISTS black_vantuz;
```
После этого запустите `migrate.php` заново.

### Просмотр данных
- **phpMyAdmin**: `http://localhost/phpmyadmin/`
- Выберите базу `black_vantuz`
- Просматривайте таблицы `users`, `products`, `orders`, `logs`

---

## ✅ Проверка миграции

После миграции проверьте:
1. Регистрация новых пользователей работает
2. Вход в систему работает
3. Добавление товаров в корзину работает
4. Создание заказов работает
5. Админ-панель отображает данные из БД
6. Логи сохраняются в таблицу `logs`

---

## 🔐 Безопасность

- Пароли хешируются через `password_hash()` (bcrypt)
- Все запросы используют **prepared statements** (защита от SQL-инъекций)
- Пользовательские данные проходят **htmlspecialchars()** (защита от XSS)
- Сессии защищены стандартными механизмами PHP

---

## 📚 Для разработчиков

### Использование в коде

```php
use Models\User;

$userModel = new User();
$user = $userModel->findByEmail('test@example.com');
```

### Доступ к PDO напрямую

```php
use Database;

$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([1]);
```

---

## 🔄 Откат к JSON (если нужно)

1. Удалите подключение к БД из `index.php`
2. Верните старые модели из git
3. Удалите `config/database.php`

Рекомендуется оставить MySQL — это более надёжное и масштабируемое решение.
