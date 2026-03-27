<?php
namespace Controllers;
use Views\OrderTemplate;
class OrderController
{
private array $orders = [];
public function __construct()
{
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
// Загружаем заказы из файла
$ordersFile = __DIR__ . '/../data/orders.json';
if (file_exists($ordersFile)) {
$this->orders = json_decode(file_get_contents($ordersFile), true) ?? [];
}
}
public function checkout(): string
{
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
$cartItems = $_SESSION['cart'] ?? [];
if (empty($cartItems)) {
header('Location: /cart?error=empty_cart');
exit;
}
return OrderTemplate::getCheckoutTemplate($cartItems);
}
public function process(): void
{
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
$cartItems = $_SESSION['cart'] ?? [];
if (empty($cartItems)) {
header('Location: /cart?error=empty_cart');
exit;
}
// Получаем данные из формы
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$paymentMethod = $_POST['payment_method'] ?? 'card';
$comment = trim($_POST['comment'] ?? '');
// Валидация
$errors = [];
if (empty($name)) $errors[] = 'name';
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'email';
if (empty($phone)) $errors[] = 'phone';
if (!empty($errors)) {
$_SESSION['order_errors'] = $errors;
$_SESSION['order_data'] = $_POST;
header('Location: /cart/checkout');
exit;
}
// Создаем заказ
$orderId = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
$totalPrice = 0;
foreach ($cartItems as $item) {
$priceNum = (int)preg_replace('/[^0-9]/', '', $item['price']);
$totalPrice += $priceNum;
}
$order = [
'id' => $orderId,
'name' => $name,
'email' => $email,
'phone' => $phone,
'payment_method' => $paymentMethod,
'comment' => $comment,
'items' => $cartItems,
'total' => $totalPrice,
'status' => 'pending',
'created_at' => date('Y-m-d H:i:s'),
'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];
// Сохраняем заказ
$this->orders[] = $order;
$ordersFile = __DIR__ . '/../data/orders.json';
if (!is_dir(dirname($ordersFile))) {
mkdir(dirname($ordersFile), 0755, true);
}
file_put_contents($ordersFile, json_encode($this->orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
// Очищаем корзину
$_SESSION['cart'] = [];
// Очищаем временные данные
unset($_SESSION['order_errors']);
unset($_SESSION['order_data']);
// Перенаправляем на страницу успеха
header('Location: /cart/success?order=' . $orderId);
exit;
}
public function success(): string
{
$orderId = $_GET['order'] ?? '';
return OrderTemplate::getSuccessTemplate($orderId);
}
public function getOrders(): array
{
return $this->orders;
}
}