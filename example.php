<?php
declare(strict_types=1);

/**
 * Вычисляет сумму элементов числового массива
 */
function calculateSum(array $numbers): int|float 
{
    $sum = 0; // Инициализация переменной ДО цикла

    foreach ($numbers as $value) {
        $sum += $value;
    }

    return $sum;
}

$numbers = [1, 2, 3, 4, 5];
echo 'Сумма чисел: ' . calculateSum($numbers) . PHP_EOL;