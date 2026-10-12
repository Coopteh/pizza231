<?php

namespace App\Controllers;

use App\Models\Product; // Модель использует ILoadStorage!
use App\Views\ProductTemplate; // Убедитесь, что этот класс существует или создайте заглушку.
use App\Config\Config;
use App\Services\FileStorage; // Реализует и ILoadStorage, и ISaveStorage.
use App\Services\ProductDBStorage; // Реализует только ILoadStorage.
// use App\Services\DatabaseStorage; // Этот класс больше не нужен.
// use App\Services\IStorage; // Старый интерфейс не нужен.

class ProductController
{
    /**
     * Отображает список всех товаров или карточку одного товара.
     *
     * @param int|null $id ID товара для отображения карточки (если передан)
     * @return string HTML-код для отображения или текст ошибки.
     */
    public function get($id = null): string
    {
         // Создаем модель с внедренной зависимостью от сервиса хранения данных.
         // Тип сервиса зависит от константы в Config.
         if (Config::STORAGE_TYPE == Config::TYPE_DB) {
             // Для БД используем ProductDBStorage (только чтение)
             // Имя ресурса - это имя таблицы из конфига.
             $serviceStorage = new ProductDBStorage();
             // Модель Product требует объект, реализующий ILoadStorage. ProductDBStorage его реализует.
             // Передаем имя таблицы как "имя ресурса".
             $model = new Product($serviceStorage, Config::TABLE_PRODUCTS);
         } else {
             // Для файлов используем FileStorage (и чтение, и запись)
             // Имя ресурса - это путь к файлу из конфига.
             $serviceStorage = new FileStorage();
             // FileStorage реализует ILoadStorage, поэтому его можно передать в конструктор Product.
             // Передаем путь к файлу как "имя ресурса".
             $model = new Product($serviceStorage, Config::FILE_PRODUCTS);
         }
         
         // Загружаем все данные через сервис, который находится внутри модели.
         // Если данных нет (null), приводим к пустому массиву для безопасности.
         $data = $model->loadData() ?: [];
         
         if ($id) {
             // Проверяем, существует ли товар с таким ID в массиве данных.
             // Вычитаем 1, так как в массиве индексы начинаются с 0, а ID с 1.
             if (isset($data[$id - 1])) {
                 return ProductTemplate::getCardTemplate($data[$id - 1]);
             } else {
                 return "Товар с ID {$id} не найден.";
             }
         } else {
             // Если ID не передан, отображаем список всех товаров.
             return ProductTemplate::getAllTemplate($data);
         }
     }
}