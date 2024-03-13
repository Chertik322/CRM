<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once("./config/config.php");
include_once("./config/functions.php");
include_once("./config/db_connect.php");

// Получаем идентификатор задачи из POST-запроса
$taskid = $_POST['taskid'];

// Удаляем задачу из базы данных
$query = "DELETE FROM tasks WHERE task_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $taskid);

$result = mysqli_stmt_execute($stmt);

// Проверяем успешность выполнения запроса
if ($result) {
    $response = ['success' => true, 'message' => 'Задача успешно удалена'];
} else {
    $response = ['success' => false, 'message' => 'Ошибка при удалении задачи: ' . mysqli_error($connection)];
}

header('Content-Type: application/json');
echo json_encode($response);

?>
