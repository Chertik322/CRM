<?php
session_start();
include_once ("../Kval/config/config.php");
include_once ("../Kval/config/functions.php");
include_once ("../Kval/config/db_connect.php");

// Если пользователь уже авторизован, перенаправляем его на главную страницу
if (isset($_SESSION['user_id'])) {
  header('Location: welcome.php');
  exit();
}

// Если данные были отправлены через POST-запрос, пытаемся авторизовать пользователя
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $login = $_POST['login'];
  $password = $_POST['password'];
  
  if (login($connection, $login, $password)) {
    // Если авторизация прошла успешно, перенаправляем пользователя на главную страницу
    header('Location: welcome.php');
    exit();
  } else {
    $error_msg = 'Неверный логин или пароль';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Авторизация</title>
    <!-- Подключение стилей Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333333;
            text-align: center;
            margin-top: 0;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="login"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #337ab7;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #23527c;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #337ab7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Авторизация</h1>
        <?php
        // Если есть ошибки, выводим их
        if(isset($error_message)){
            echo "<p style='color:red;'>{$error_message}</p>";
        }
        ?>
        <form method="post">
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="login" class="form-control" id="login" name="login">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div>
                <button type="submit" class="btn btn-primary" name="vhod">Войти</button>
            </div>
        </form>
        <p>Еще не зарегистрированы? <a href="reg.php" class = 'btn btn-primary'>Зарегистрироваться</a></p>
    </div>
</body>
</html>
