# Апрельский треш!  
Занятия по курсам "Тестирование" и "Управление проектами"  
позволили выявить ряд недостатков в существующем коде проекта, мириться с которыми просто нельзя:  
- после изменений модели Product юнит-тест ProductTest просто не работает;
- метрика цикломатической сложности показываем зашкаливающую сложность (12-40) для класса Router,
который срочно надо отрефакторить!  

## Задание-1 - mock-объект в тестах

Работа с git
```
запустите Git Bash 
перейдите в каталог c:/xampp/htdocs
> cd c:/xampp/htdocs
выполните в bash терминале - получение изменений
> git pull
создайте новую ветку
> git checkout -b april-trash
```
Мы изменили модуль Product, теперь в модель передается при создании  
серсив для работы с данными и прочие параметры   
`public function __construct(IStorage $service, string $nameLoad, string $nameSave)`  
`$model = new Product($serviceStorage, Config::FILE_PRODUCTS, Config::FILE_ORDERS);`  
Задача - изменить тесты ProdutTest с использованием mock-объекта заглушки  
```
        $storage = new MockStorage();
        $model = new Product($storage, "", "");
```  
чтобы тесты успешно работали!  

## Задание-2 - рефакторинг маршрутизатора

Как снизить сложность кода маршрутизатора
```
Метод route в классе Router - имеет высокую цикломатическую сложность.
Необходим рефакторингу (изменение кода с целью его оптимизации):
Основная проблема этого кода - большой switch
- повторяющийся код (создание контроллеров, обработка маршрута - $pieces[2], редиректы)
- жёсткая связность (прямая зависимость от конкретных классов контроллеров)

✅ Стратегии рефакторинга

1. Выделите карту маршрутов (Route Map) - используйте таблицу маршрутов (массив) вместо switch

private function getRoutes(): array {
        return [
            'about' => ['controller' => AboutController::class, 
                        'method' => 'get'],
            'products' => ['controller' => ProductController::class, 
                        'method' => 'get', 
                        'params' => ['id' => $this->id]],
            'basket' => ['controller' => BasketController::class, 
                        'method' => 'add', 
                        'redirect' => true],
            'order' => [
                'GET' => ['controller' => OrderController::class, 
                        'method' => 'get'],
                'POST' => ['controller' => OrderController::class, 
                        'method' => 'create'],
            ],
            'basket_clear' => ['controller' => BasketController::class,
                        'method' => 'clear',                         
                        'redirect' => true],
        ];
    }

2. Используйте таблицу маршрутов (массив) вместо switch в основном методе route()

private int $id = 0;
public function route(string $url): string {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1];
        $this->id = (isset($pieces[2])) ? intval($pieces[2]) : 0;
        $method = $_SERVER['REQUEST_METHOD'];

        $routes = $this->getRoutes();
        if (!isset($routes[$resource])) {
            return $this->handleDefault();
        }
    
        $route = $routes[$resource];
    
        // Обработка методов для ресурса (например, order)
        if (isset($route[$method])) {
            $route = $route[$method];
        }
    
        return $this->executeRoute($route, $pieces);
}

3. Вынесите логику выполнения в отдельный метод

    private function executeRoute(array $route, array $pieces): string {
        $controller = new $route['controller']();
        $params = ($this->id) ? ['id' => $this->id] : [];
        $result = $controller->{$route['method']}(...$params);
    
        if ($route['redirect'] ?? false) {
            $prevUrl = $server['HTTP_REFERER'] ?? '/';
            header("Location: {$prevUrl}");
            return '';
        }
        return $result ?? '';
    }

    private function handleDefault(): string {
        return (new HomeController())->get();
    }

Было - цикломатическая сложность = 8
Стало - цикломатическая сложность = 6
но при добавлении маршрутов цикломатическая сложность метода route увеличиваться не будет
```

Закоммитьте и запуште изменения
```
> git status
> git add .
> git status
> git commit -m "Флеш сообщения"
> git push
```
Передвиньте в Project issues с этой задачей на "In progress"  
Скопируйте ссылку на ветку с выполненным заданием и закройте issue со ссылкой на эту ветку.  

Сдайте работу - создав запрос на изменения Pull Request   
- зайдите на github и создайте Pull Request со своего аккаунта в исходный репозиторий (для аккаунта Coopteh)  
