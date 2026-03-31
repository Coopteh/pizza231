<?php
namespace App\Controllers;
use App\Views\OrderTemplate;
use App\Views\BaseTemplate;
use App\Models\Product;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController extends BaseTemplate {
    public function get(): string 
    {
        $product = new Product();
        // получаем массив с характеристиками товаров из корзины
        $data = $product->getBasketData();
        return OrderTemplate::getOrderTemplate($data);
    }

    public function create() {
        $model = new Product();
        // список заказанных продуктов - берем список товаров из корзины
        $products = $model->getBasketData();
        // подготовка массив c данными заказа
        $arr = $model->prepareData( $_POST, $products );
        // сохранение заказа
        $model->saveData($arr);

        // отправка емайл
        if ($this->sendMail($arr['email'], $arr)) {
            // очистка корзины
            $_SESSION['basket'] = [];
            // вывод сообщения
            $_SESSION['flash'] = "Спасибо! Ваш заказ успешно создан и передан службе доставки";
            header("Location: /");
        } else {
            header("Location: /order");
        }
	    return '';
    }

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
                $mail->SetFrom("coopteh231@mail.ru","PIZZA-221");
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