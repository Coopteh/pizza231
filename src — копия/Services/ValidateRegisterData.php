<?php
namespace App\Services;

/**
 * Сервис валидации данных регистрации
 * @package App\Services
 */
class ValidateRegisterData
{
    /** @var array<string> Список ошибок валидации */
    private array $errors = [];
    
    /** @var array<string, mixed> Санитизированные данные */
    private array $sanitized = [];
    
    /** 
     * @var callable|null Callback для проверки уникальности email
     * @phpstan-var callable(string): bool|null
     */
    private $emailExistsCallback = null;
    
    /**
     * Конструктор
     * @param object|null $storage Объект хранилища с методом emailExists(string): bool
     */
    public function __construct($storage = null)
    {
        // Если передано хранилище с методом emailExists — настроим callback
        if ($storage && method_exists($storage, 'emailExists')) {
            $this->emailExistsCallback = [$storage, 'emailExists'];
        }
    }
    
    /**
     * Установить callback для проверки уникальности email
     * @param callable $callback
     * @return self
     */
    public function setEmailExistsCallback($callback): self
    {
        $this->emailExistsCallback = $callback;
        return $this;
    }
    
    /**
     * Основная метод валидации
     * @param array<string, mixed> $data Входные данные формы
     * @return array{valid: bool, errors: array<string>, data: array<string, mixed>}
     */
    public function validate(array $data): array
    {
        $this->errors = [];
        $this->sanitized = [];
        
        // === Санитизация и валидация имени ===
        $username = $this->sanitize($data['username'] ?? '');
        if (empty($username)) {
            $this->errors[] = 'Имя пользователя обязательно для заполнения';
        } elseif (mb_strlen($username) < 2) {
            $this->errors[] = 'Имя должно содержать не менее 2 символов';
        }
        $this->sanitized['username'] = $username;
        
        // === Санитизация и валидация email ===
        $email = $this->sanitize($data['email'] ?? '');
        if (empty($email)) {
            $this->errors[] = 'Email обязателен для заполнения';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Некорректный формат email';
        } elseif ($this->emailExistsCallback && is_callable($this->emailExistsCallback)) {
            // Проверка уникальности через callback
            if (call_user_func($this->emailExistsCallback, $email)) {
                $this->errors[] = 'Пользователь с таким email уже зарегистрирован';
            }
        }
        $this->sanitized['email'] = strtolower($email);
        
        // === Валидация пароля ===
        $password = $data['password'] ?? '';
        $passwordConfirm = $data['confirm_password'] ?? $data['password_confirm'] ?? '';
        
        if (empty($password)) {
            $this->errors[] = 'Пароль обязателен для заполнения';
        } elseif (mb_strlen($password) < 6) {
            $this->errors[] = 'Пароль должен содержать не менее 6 символов';
        }
        
        // Поддержка обоих названий полей: confirm_password и password_confirm
        if ($password !== $passwordConfirm) {
            $this->errors[] = 'Пароли не совпадают';
        }
        
        // Пароль не санитизируем — хешируем позже
        $this->sanitized['password'] = $password;
        
        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'data' => $this->sanitized
        ];
    }
    
    /**
     * Санитизация строки: защита от XSS и инъекций
     * @param string $value
     * @return string
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
    
    /**
     * Получить список ошибок после валидации
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
    /**
     * Получить санитизированные данные
     * @return array<string, mixed>
     */
    public function getSanitizedData(): array
    {
        return $this->sanitized;
    }
}