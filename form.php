<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма заказа</title>
    <style> .error { color: red; } </style>
</head>
<body>
    <h1>Форма заказа</h1>
    
    <form id="orderForm" method="post" action="/submit-order">
        
        <!-- Поле "Имя" -->
        <label for="name">Имя:</label><br>
        <input type="text" id="name" name="name"><br><br>
        
        <!-- Поле "Телефон" -->
        <label for="phone">Телефон:</label><br>
        <input type="text" id="phone" name="phone" maxlength="12"><br><br>
        
        <!-- Поле "Адрес электронной почты" -->
        <label for="email">Адрес электронной почты:</label><br>
        <input type="text" id="email" name="email"><br><br>
        
        <!-- Поле "Способ оплаты" -->
        <p>Выберите способ оплаты:</p>
        <select id="paymentMethod" name="paymentMethod">
            <option value="">-- Выберите --</option>
            <option value="card">Кредитная карта</option>
            <option value="paypal">PayPal</option>
            <option value="cash">Наличные при получении</option>
        </select><br><br>
        
        <!-- Кнопка "Отправить заказ" -->
        <button type="submit">Отправить заказ</button>
        
    </form>
   
</body>
</html>
