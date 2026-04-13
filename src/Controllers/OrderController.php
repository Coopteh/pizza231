<?php
namespace App\Controllers;
use App\Views\OrderTemplate;
use App\Views\BaseTemplate;
use App\Models\Product;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Services\DatabaseStorage;
use App\Services\FileStorage;
use App\Config\Config;
use App\Services\OrderStorage;
use App\Services\ProductStorage;
use PDO;

class OrderController extends BaseTemplate {
    
    public function get(): string 
    {
        if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
            $serviceStorage = new FileStorage();
            $tableName = Config::FILE_DATA;  // путь к файлу
        } else {
            $serviceStorage = new DatabaseStorage();
            $tableName = Config::TABLE_PRODUCTS;  // имя таблицы
        }
        
        // Конструктор Product принимает 2 параметра: сервис + имя ресурса
        $product = new Product($serviceStorage, $tableName);
        
        $data = $product->getBasketData();
        return OrderTemplate::getOrderTemplate($data);
        // // Загрузка товаров из БД или файла
        // if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
        //     $loadService = new \App\Services\FileStorage();
        // } else {
        //     $loadService = new OrderStorage($this->getPDO()); // ваша функция получения PDO
        // }
        
        // // Конструктор теперь принимает 2 параметра!
        // $product = new Product($loadService, Config::TABLE_PRODUCTS);
        
        // $data = $product->getBasketData();
        // return OrderTemplate::getOrderTemplate($data);
    }

    public function create() {
        if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
            $serviceStorage = new FileStorage();
            $tableName = Config::FILE_DATA;
        } else {
            $serviceStorage = new DatabaseStorage();
            $tableName = Config::TABLE_PRODUCTS;
        }
        
        $model = new Product($serviceStorage, $tableName);

        $products = $model->getBasketData();
        $arr = $model->prepareData($_POST, $products);
        
        // ✅ Сохранение заказа
        $model->saveData($arr);  // Если saveData использует имя ресурса из конструктора
    
        if ($this->sendMail($arr['email'], $arr)) {
            $_SESSION['basket'] = [];
            $_SESSION['flash'] = "Спасибо! Ваш заказ успешно создан и передан службе доставки";
            header("Location: /");
            exit;
        } else {
            $_SESSION['flash'] = "Ошибка отправки письма";
            header("Location: /order");
            exit;
        }
    }
        // //  Сервис для загрузки (товары)
        // if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
        //     $loadService = new \App\Services\FileStorage();
        // } else {
        //     $loadService = new ProductStorage($this->getPDO());
        // }
        
        // $model = new Product($loadService, Config::TABLE_PRODUCTS);

        // $products = $model->getBasketData();
        // $arr = $model->prepareData($_POST, $products);
        
        // // Сохранение заказа через отдельный сервис
        // if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
        //     $saveService = new \App\Services\FileStorage();
        // } else {
        //     $saveService = new OrderStorage($this->getPDO());
        // }
        // $saveService->saveData(Config::TABLE_ORDERS, $arr);    
    
    // Вспомогательный метод для получения PDO
    // private function getPDO(): \PDO {
    //     static $pdo = null;
    //     if ($pdo === null) {
    //         $this->connection = new PDO(
    //         Config::MYSQL_DNS,
    //         Config::MYSQL_USER,
    //         Config::MYSQL_PASSWORD
    //     );
        
    //     }
    //     return $pdo;
    
    // public function get(): string 
    // {
    //     if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
    //         $serviceStorage = new FileStorage();
    //     } else {
    //         $serviceStorage = new DatabaseStorage();
    //     }
    //     $product = new Product($serviceStorage, Config::FILE_DATA, Config::FILE_ORDERS);

    //     // получаем массив с характеристиками товаров из корзины
    //     $data = $product->getBasketData();
    //     return OrderTemplate::getOrderTemplate($data);
    //     // $product = new Product();
    //     // // получаем массив с характеристиками товаров из корзины
    //     // $data = $product->getBasketData();
    //     // return OrderTemplate::getOrderTemplate($data);
    // }

    // public function create() {
        
    //     if (Config::STORAGE_TYPE == Config::TYPE_FILE) {
    //         $serviceStorage = new FileStorage();
    //     } else {
    //         $serviceStorage = new DatabaseStorage();
    //     }
    //     $model = new Product($serviceStorage, Config::FILE_DATA, Config::FILE_ORDERS);

    //     // список заказанных продуктов - берем список товаров из корзины
    //     $products = $model->getBasketData();
    //     // подготовка массив c данными заказа
    //     $arr = $model->prepareData( $_POST, $products );
    //     // сохранение заказа
    //     $model->saveData($arr);
    
    //     // $model = new Product();
    //     // // список заказанных продуктов - берем список товаров из корзины
    //     // $products = $model->getBasketData();
    //     // // подготовка массив c данными заказа
    //     // $arr = $model->prepareData( $_POST, $products );
    //     // // сохранение заказа
    //     // $model->saveData($arr);

        // отправка емайл
        // if ($this->sendMail($arr['email'], $arr)) {
        //     // очистка корзины
        //     $_SESSION['basket'] = [];
        //     // вывод сообщения
        //     $_SESSION['flash'] = "Спасибо! Ваш заказ успешно создан и передан службе доставки";
        //     header("Location: /");
        // } else {
        //     header("Location: /order");
        // }
	    // return '';

    public function sendMail($email, $data) {
        $mail = new PHPMailer();
        if (isset($email) && !empty($email)) {
            $details= "";
            foreach($data['products'] as $prod) {
                $details .= "{$prod['name']} - {$prod['quantity']} шт. x {$prod['price']} руб.<br>";
            }
            $orderMessage = <<<MSG
            Ваш заказ:<br>
            ФИО: {$data['fio']}<br>
            Адрес: {$data['address']}<br>
            Телефон: {$data['phone']}<br>
            Емайл: {$data['email']}<br>
            Общая сумма заказа: {$data['all_sum']}<br>
            <hr>
            Параметры заказа:
            {$details}
            <hr>
            MSG;
            // var_dump($orderMessage); exit();
            try {
                $mail->SMTPDebug = 2;
                $mail->CharSet = 'UTF-8';
                $mail->SetFrom("coopteh231@mail.ru","MAGAZIN-231");
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'ssl://smtp.mail.ru';                   //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'coopteh231@mail.ru';                     //SMTP username
                $mail->Password   = 'oBdxSwM2AWnco7ALXUk5';
                $mail->Port       = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Subject = 'Заявка с сайта: PIZZA-231';
                $mail->Body = "Информационное сообщение c сайта PIZZA-231 <br><br>
                ------------------------------------------<br><br>
                Спасибо!<br><br>
                Ваш заказ успешно создан и передан службе доставки.<br><br>"
                . $orderMessage ."<br>
                Сообщение сгенерировано автоматически.";
                if ($mail->send()) {
                    return true;
                } else {
                    throw new Exception('Ошибка с отправкой письма ' . $mail->ErrorInfo);
                }
            } catch (Exception $error) {
                $message = $error->getMessage();
                $_SESSION['flash'] = "Ошибка: $message";
            }
        }
        return false;
    }
}