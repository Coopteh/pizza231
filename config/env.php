<?php
class EnvLoader
{
    private static array $vars = [];
    
    public static function load(string $basePath): void
    {
        $envFile = rtrim($basePath, '/\\') . '/.env';
        if (!file_exists($envFile)) {
            return;
        }
        
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim(trim($value), '"\'');
            self::$vars[$name] = $value;
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
        }
    }
    
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}