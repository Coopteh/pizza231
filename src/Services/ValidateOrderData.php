<?php
namespace Services;

class ValidateOrderData
{
    private array $errors = [];
    private array $sanitized = [];

    public function validate(array $data): array
    {
        $fio = trim($data['fio'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $type = $data['delivery_type'] ?? 'email';
        $email = trim($data['email'] ?? '');
        $address = trim($data['address'] ?? '');

        $this->sanitized['fio'] = htmlspecialchars(strip_tags($fio), ENT_QUOTES, 'UTF-8');
        $this->sanitized['address'] = htmlspecialchars(strip_tags($address), ENT_QUOTES, 'UTF-8');
        $this->sanitized['phone'] = $this->sanitizePhone($phone);

        // Валидация ФИО
        if (empty($this->sanitized['fio']) || mb_strlen($this->sanitized['fio']) < 3) {
            $this->errors[] = 'ФИО должно содержать минимум 3 символа';
        }

        // Валидация телефона
        if (empty($this->sanitized['phone'])) {
            $this->errors[] = 'Введите корректный номер телефона';
        }

        // Валидация в зависимости от типа доставки
        if ($type === 'email') {
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Укажите корректный email для доставки';
            }
            $this->sanitized['email'] = filter_var($email, FILTER_SANITIZE_EMAIL);
            $this->sanitized['address'] = null;
        } else {
            if (empty($this->sanitized['address']) || 
                mb_strlen($this->sanitized['address']) < 10 || 
                mb_strlen($this->sanitized['address']) > 200) {
                $this->errors[] = 'Адрес должен содержать от 10 до 200 символов';
            }
            $this->sanitized['email'] = null;
        }

        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'sanitized' => $this->sanitized
        ];
    }

    private function sanitizePhone(string $phone): string
    {
        $cleanPhone = preg_replace('/[^\d+]/', '', trim($phone));
        if (strlen($cleanPhone) > 12) {
            $cleanPhone = substr($cleanPhone, 0, 12);
        }
        return $cleanPhone;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}