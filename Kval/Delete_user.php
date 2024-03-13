<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once ("./config/config.php");
include_once ("./config/functions.php");
include_once ("./config/db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['userId'])) {
        $userId = $_POST['userId'];
        $query = "UPDATE user SET delete_status = 1 WHERE user_id = $userId";

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
