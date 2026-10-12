<?php
namespace App\Services;

class ValidateOrderData
{
    private array $errors = [];
    private array $sanitized = [];
    
    /**
     * Валидация данных формы заказа
     * @param array $data Данные формы
     * @return array ['valid' => bool, 'errors' => array, 'data' => array]
     */
    public function validate(array $data): array
    {
        $this->errors = [];
        $this->sanitized = [];
        
        // === ФИО ===
        $fio = $this->sanitize($data['fio'] ?? $data['name'] ?? '');
        if (empty($fio)) {
            $this->errors[] = 'ФИО обязательно для заполнения';
        } elseif (mb_strlen($fio) < 3) {
            $this->errors[] = 'ФИО должно содержать не менее 3 символов';
        }
        $this->sanitized['fio'] = $fio;
        
        // === Адрес ===
        $address = $this->sanitize($data['address'] ?? '');
        if (empty($address)) {
            $this->errors[] = 'Адрес доставки обязателен';
        } {
            $len = mb_strlen($address);
            if ($len < 10) {
                $this->errors[] = 'Адрес слишком короткий (минимум 10 символов)';
            }
            if ($len > 200) {
                $this->errors[] = 'Адрес слишком длинный (максимум 200 символов)';
            }
        }
        $this->sanitized['address'] = $address;
        
        // === Телефон ===
        $phone = trim($data['phone'] ?? '');
        if (empty($phone)) {
            $this->errors[] = 'Телефон обязателен для заполнения';
        } else {
            // Оставляем только + и цифры
            $phone = preg_replace('/[^\d+]/', '', $phone);
            if (mb_strlen($phone) < 10) {
                $this->errors[] = 'Некорректный номер телефона';
            }
        }
        $this->sanitized['phone'] = $phone;
        
        // === Email ===
        $email = $this->sanitize($data['email'] ?? '');
        if (empty($email)) {
            $this->errors[] = 'Email обязателен для подтверждения заказа';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Некорректный формат email';
        }
        $this->sanitized['email'] = strtolower($email);
        
        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'data' => $this->sanitized
        ];
    }
    
    /**
     * Санитизация: защита от XSS
     */
    private function sanitize(string $value): string
    {
        return htmlspecialchars(
            strip_tags(
                trim($value)
            ),
            ENT_QUOTES,
            'UTF-8'
        );
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}