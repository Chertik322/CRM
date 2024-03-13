<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once("./config/config.php");
include_once("./config/functions.php");
include_once("./config/db_connect.php");

// Получение данных из POST-запроса
$taskName = $_POST['taskName'];
$taskDescription = $_POST['taskDescription'];
$taskDueDate = $_POST['taskDueDate'];
$priorityId = $_POST['priorityId'];
$classId = $_POST['classId'];

// Проверка наличия обязательных полей
if (empty($taskName) || empty($taskDescription) || empty($taskDueDate) || empty($priorityId) || empty($classId)) {
    // Отправка ошибки в случае отсутствия обязательных полей
    http_response_code(400);
    echo "Пожалуйста, заполните все поля.";
    exit();
}

// Дополнительная обработка данных (если необходимо)

// Вставка данных в базу данных
$query = "INSERT INTO tasks (Name, Description, Date_of_submission, priority_id, class_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $connection->prepare($query);
$stmt->bind_param("sssss", $taskName, $taskDescription, $taskDueDate, $priorityId, $classId);
if ($stmt->execute()) {
    // Отправка успешного ответа
    http_response_code(200);
    echo "Задача успешно добавлена!";
} else {
    // Отправка ошибки в случае неудачного выполнения запроса
    http_response_code(500);
    echo "Произошла ошибка при добавлении задачи.";
    exit();
}
?>
