<?php
namespace App\Controllers;

class BasketController {
    public function add():void {
            session_start();
            
            if (isset($_POST['id'])) {
                $product_id = $_POST['id']-1;
                $product_name = $_POST['name'];
            
                if (!isset($_SESSION['basket'])) {
                    $_SESSION['basket'] = [];
                }
            
                if (isset($_SESSION['basket'][$product_id])) {
                    $_SESSION['basket'][$product_id]['quantity']++;
                } else {
                    $_SESSION['basket'][$product_id] = [
                        'quantity' => 1
                    ];
                }
            //var_dump($_SESSION);
            //exit();
            $_SESSION['flash'] = "Товар '$product_name' успешно добавлен в корзину!";
            }
        }
        /* 
        Очистка корзины
        */
        public function clear():void {
            session_start();
            $_SESSION['basket'] = [];
        }
}