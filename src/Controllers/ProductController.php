<?php

namespace App\Controllers;

use App\Models\Product;
use App\Views\ProductTemplate;
use App\Config\Config;
use App\Services\FileStorage;
use App\Services\DatabaseStorage;

class ProductController
{
    /**
     * Отображает список всех товаров или карточку одного товара.
     * 
     * @param int|null $id ID товара для отображения карточки (если передан)
     * @return string HTML-код для отображения
     */
    public function get($id = null): string 
    {
        // Создаем модель с внедренной зависимостью от сервиса хранения данных.
        // Для этого контроллера ресурс - это файл с товарами.
        $model = $this->createProductModel(Config::FILE_PRODUCTS);
        
        // Загружаем все данные через сервис, который находится внутри модели.
        $data = $model->loadData();

        // Если произошла ошибка чтения файла или файл пуст, $data будет null.
        // Чтобы избежать ошибок в шаблонах, приводим null к пустому массиву.
        if ($data === null) {
            $data = [];
        }

        if ($id) {
            // Проверяем, существует ли товар с таким ID в массиве данных.
            // Вычитаем 1, так как в массиве индексы начинаются с 0, а ID с 1.
            if (isset($data[$id - 1])) {
                $cardData = $data[$id - 1];
                return ProductTemplate::getCardTemplate($cardData);
            } else {
                // Обработка случая, когда товар не найден.
                return "Товар с ID {$id} не найден.";
            }
        } else {
            // Если ID не передан, отображаем список всех товаров.
            return ProductTemplate::getAllTemplate($data);            
        }
    }

    /**
     * Вспомогательный метод для создания экземпляра Product с внедренной зависимостью.
     * Избавляет от дублирования кода и связывает создание модели с конфигурацией приложения.
     * 
     * @param string $resourceName Имя файла или ресурса для работы с данными.
     * @return Product Готовый к работе объект модели.
     */
    private function createProductModel(string $resourceName): Product
    {
        // В зависимости от настроек в Config.php, создаем нужный сервис хранения.
        if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
            $serviceStorage = new FileStorage();
        } else {
            $serviceStorage = new DatabaseStorage();
        }
        
        // Создаем и возвращаем модель, передавая ей сервис и имя ресурса.
        return new Product($serviceStorage, $resourceName);
    }
}