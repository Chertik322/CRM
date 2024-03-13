<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

include_once("./config/config.php");
include_once("./config/functions.php");
include_once("./config/db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Получаем текущее значение Access_status для пользователя
    $query = "SELECT Access_status FROM user WHERE user_id = $userId";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $currentStatus = $row['Access_status'];

    // Инвертируем текущее значение Access_status
    $newStatus = $currentStatus == 0 ? 1 : 0;

    // Обновляем значение Access_status для пользователя
    $query = "UPDATE user SET Access_status = $newStatus WHERE user_id = $userId";

    // Выполнение запроса
    $result = mysqli_query($connection, $query);

    // Проверка результата выполнения запроса
    if ($result) {
      echo 'success'; // Отправляем успешный ответ клиенту
    } else {
      echo 'error'; // Отправляем ответ с ошибкой клиенту
    }
  }
}

?>
