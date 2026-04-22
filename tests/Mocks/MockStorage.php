<?php

namespace Tests\Mocks;

// Интерфейс IStorage (предполагаемый)
interface IStorage
{
    public function load(string $filename): array;
    public function save(string $filename, array $data): bool;
    public function exists(string $filename): bool;
    public function delete(string $filename): bool;
}

// Реализация заглушки для тестов
class MockStorage implements IStorage
{
    private array $data = [];
    private array $savedFiles = [];
    private bool $shouldFail = false;
    private ?string $failOnFile = null;

    public function __construct(array $initialData = [], bool $shouldFail = false, ?string $failOnFile = null)
    {
        $this->data = $initialData;
        $this->shouldFail = $shouldFail;
        $this->failOnFile = $failOnFile;
    }

    public function load(string $filename): array
    {
        if ($this->shouldFail && $filename === $this->failOnFile) {
            throw new \RuntimeException("Failed to load: {$filename}");
        }
        return $this->data[$filename] ?? [];
    }

    public function save(string $filename, array $data): bool
    {
        if ($this->shouldFail && $filename === $this->failOnFile) {
            return false;
        }
        $this->savedFiles[$filename] = $data;
        $this->data[$filename] = $data;
        return true;
    }

    public function exists(string $filename): bool
    {
        return isset($this->data[$filename]);
    }

    public function delete(string $filename): bool
    {
        if ($this->shouldFail && $filename === $this->failOnFile) {
            return false;
        }
        unset($this->data[$filename]);
        return true;
    }

    // Методы для проверок в тестах
    public function getSavedData(string $filename): ?array
    {
        return $this->savedFiles[$filename] ?? null;
    }

    public function getSavedFiles(): array
    {
        return array_keys($this->savedFiles);
    }

    public function reset(): void
    {
        $this->data = [];
        $this->savedFiles = [];
    }
}
