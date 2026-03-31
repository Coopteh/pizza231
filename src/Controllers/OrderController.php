<?php
namespace App\Controllers;
use App\Views\OrderTemplate;
use App\Models\Product;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController {
        public function get(): string {
        // MODEL CREATION
        $model = new Product();
        $data = $model->getBasketData();
        // var_dump($_SESSION);
        // exit();
        return OrderTemplate::getOrderTemplate($data);
    }
    public function create() {
        
        $model = new Product();
        $products  = $model -> getBasketData();
        $arr = $model -> prepareData($_POST, $products);
        $model ->saveData($arr);
        $_SESSION['basket'] = [];
        $_SESSION['flash'] = "Спасибо! Ваш заказ успешно создан и передан службе доставки";

	        header("Location: /");
	        return '';

        // LEGACY, REUSE LATER
        // // $arr = [];
        // // $arr['fio'] = urldecode( $_POST['fio'] );
        // //     $arr['address'] = urldecode( $_POST['address'] );
        // //     $arr['phone'] = $_POST['phone'];
        // //     $arr['created_at'] = date("d-m-Y H:i:s");	// добавим дату и время создания заказа

        // $model = new Product();
        // // список заказанных продуктов - берем список товаров из корзины
        //     $products = $model->getBasketData();
        //     $arr['products'] = $products;
        // // подсчитаем общую сумму заказа
        //     $all_sum = $model ->prepareData($products);
        //     // $all_sum = 0;
        //     // foreach ($products as $product) {
        //     // $all_sum += $product['price'] * $product['quantity'];
        //     // }
        //     // $arr['all_sum'] = $all_sum;
        //     $arr['all_sum'] = $all_sum;

        //     $model->saveData($arr);

        //     $_SESSION['basket'] = [];

        //     $_SESSION['flash'] = "Спасибо! Ваш заказ успешно создан и передан службе доставки";

	    //     header("Location: /");
	    //     return '';
    }
    public function sendMail() {
        $email="goyim@jew.il";
        $mail = new PHPMailer();
        
        if (isset($email) && !empty($email)) {
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
                $mail->Subject = 'Заявка с сайта: БЫТОТЕХ';
                $mail->Body = "Информационное сообщение c сайта PIZZA-231 <br><br>
                ------------------------------------------<br><br>
                Спасибо!<br><br>
                Ваш заказ успешно создан и передан службе доставки.<br><br>
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