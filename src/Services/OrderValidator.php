<?php
namespace App\Services;

/**
 * OrderValidator - строгая валидация и санитизация данных формы заказа.
 * Совместим с PHP 8.0+. Возвращает очищенные данные, готовые для сохранения в БД.
 */
class OrderValidator
{
    private array $errors = [];
    private array $cleanData = [];

    /**
     * Запускает полный цикл валидации.
     * @return bool true если все проверки пройдены
     */
    public function validate(array $formData, array $basketData): bool
    {
        $this->validateFio($formData);
        $this->validateEmail($formData);
        $this->validatePhone($formData);
        $this->validateAddress($formData);
        $this->validateBasket($basketData);
        $this->validateComment($formData);

        return empty($this->errors);
    }

    public function getErrors(): array { return $this->errors; }
    public function getCleanData(): array { return $this->cleanData; }

    // ─────────────────────────────────────────────────────────────────────────
    private function validateFio(array $data): void
    {
        $value = trim($data['fio'] ?? '');
        if ($value === '') {
            $this->errors['fio'] = 'Поле ФИО обязательно для заполнения.';
            return;
        }
        // Разрешаем буквы (в т.ч. кириллицу), пробелы, дефисы. Длина 2-100.
        if (!preg_match('/^[\p{L}\s\-]{2,100}$/u', $value)) {
            $this->errors['fio'] = 'ФИО должно содержать от 2 до 100 символов (только буквы, пробелы, дефис).';
            return;
        }
        $this->cleanData['fio'] = $value;
    }

    private function validateEmail(array $data): void
    {
        $value = trim($data['email'] ?? '');
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Укажите корректный адрес электронной почты.';
            return;
        }
        // Приводим к нижнему регистру для единообразия в БД
        $this->cleanData['email'] = strtolower($value);
    }

    private function validatePhone(array $data): void
    {
        $raw = $data['phone'] ?? '';
        // Оставляем только цифры и знак +
        $value = preg_replace('/[^\d+]/', '', $raw);
        // E.164 совместимый формат (международный)
        if (!preg_match('/^\+?[1-9]\d{1,14}$/', $value)) {
            $this->errors['phone'] = 'Некорректный формат телефона. Пример: +79001234567';
            return;
        }
        $this->cleanData['phone'] = $value;
    }

    private function validateAddress(array $data): void
    {
        $value = trim($data['address'] ?? '');
        if ($value === '') {
            $this->errors['address'] = 'Укажите адрес доставки.';
            return;
        }
        if (mb_strlen($value) < 10) {
            $this->errors['address'] = 'Адрес слишком короткий. Укажите город, улицу и дом.';
            return;
        }
        // Удаляем управляющие символы, сохраняем UTF-8
        $this->cleanData['address'] = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
    }

    private function validateBasket(array $basket): void
    {
        if (empty($basket) || !is_array($basket)) {
            $this->errors['basket'] = 'Корзина пуста.';
            return;
        }

        $validProducts = [];
        foreach ($basket as $item) {
            if (!is_array($item) || !isset($item['id'], $item['qty'], $item['price'])) {
                $this->errors['basket'] = 'Ошибка структуры данных корзины.';
                return;
            }

            $qty = (int)$item['qty'];
            $price = (float)$item['price'];

            if ($qty <= 0 || $price < 0) {
                $this->errors['basket'] = 'Количество и цена товара должны быть положительными.';
                return;
            }

            $validProducts[] = [
                'product_id' => (int)$item['id'],
                'qty'        => $qty,
                'price'      => $price
            ];
        }

        $this->cleanData['products'] = $validProducts;
    }

    private function validateComment(array $data): void
    {
        if (!empty($data['comment'])) {
            $value = trim($data['comment']);
            if (mb_strlen($value) > 500) {
                $this->errors['comment'] = 'Комментарий не должен превышать 500 символов.';
                return;
            }
            $this->cleanData['comment'] = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
        }
    }
}