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

// Получаем текущий статус задачи из базы данных
$query = "SELECT status FROM tasks WHERE task_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $taskid);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $currentStatus);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Вычисляем новый статус задачи
$newStatus = $currentStatus == 1 ? 0 : 1;

// Обновляем статус задачи в базе данных
$query = "UPDATE tasks SET status = ? WHERE task_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'ii', $newStatus, $taskid);
$result = mysqli_stmt_execute($stmt);

// Проверяем успешность выполнения запроса
if ($result) {
    $response = ['success' => true, 'message' => 'Статус задачи обновлен'];
} else {
    $response = ['success' => false, 'message' => 'Ошибка при обновлении статуса задачи'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
