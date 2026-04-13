<?php 
if ($_SERVER['REQUEST_METHOD']=='POST') {
    echo '<span ID="success">Вы ввели данные : '. $_POST['username'] .', '.
     $_POST['password']."</span>";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Войти</title>
</head>
<body>
    <h1>Авторизация</h1>
    <form method="post">
        <label for="username">Имя:</label><br>
        <input type="text" id="username" name="username"><br><br>
        
        <label for="password">Пароль:</label><br>
        <input type="text" id="password" name="password"><br><br>
        
        <button type="submit">Войти</button>
    </form>
</body>
</html>