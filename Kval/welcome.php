<?php
    session_start();
    include_once("../Kval/config/config.php");
    include_once("../Kval/config/functions.php");
    include_once("../Kval/config/db_connect.php");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Абитуриенты</title>
  <!-- Подключение стилей Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f2f2f2;
    }

    .container {
      max-width: 800px;
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

    p {
      text-align: center;
      margin-top: 20px;
    }

    .buttons-container {
      display: flex;
      justify-content: center;
      margin-top: 50px;
    }

    .button {
      font-size: 24px;
      padding: 20px 40px;
      margin: 10px;
      background-color: #337ab7;
      color: #ffffff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .button:hover {
      background-color: #23527c;
    }

		body {
			background-image: url(./resourses/fon.jpg);
			background-size: cover;
			background-repeat: no-repeat;
		}
  </style>
</head>
<body>
  <div class="container">
  <?php
if (isset($_SESSION['user_id'])) {
  $name = $_SESSION['user_id'];
  $query = "SELECT First_name FROM user WHERE user_id = $name";
  $result = mysqli_query($connection, $query);
  $name = mysqli_fetch_assoc($result);
  echo "<h1>Добро пожаловать, " . $name['First_name'] . "!</h1>";
  echo "<p>Вы вошли в свой аккаунт <a href='./logoutKval.php' class='btn btn-primary'>Выйти</a></p>";
  echo "<p>Перейти к задачам <a href='tasks.php' class='btn btn-primary'>Задачи</a></p>";
} else {
  echo "<h1>Добро пожаловать, Гость!</h1>";
  echo "<p>Вы не авторизованы <a href='auth.php' class='btn btn-primary'>Войти</a></p>";
}
?>
</body>
</html>