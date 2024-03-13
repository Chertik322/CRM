<?php
session_start();
include_once ("../Kval/config/config.php");
include_once ("../Kval/config/functions.php");
include_once ("../Kval/config/db_connect.php");

if (isset($_SESSION['user_id'])) {
    $name = $_SESSION['user_id'];
    $access_status = get_user_access_status($connection, $name);
    if ($access_status != 1){
        header("Location: ./welcome.php");
        exit();
    }
}
else{
    header("Location: ./welcome.php");
    exit();
}

  $users = get_users($connection);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Абитуриенты РЭУ</title>
	<!-- Подключаем стили Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
	<!-- Навигационное меню -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="./welcome.php">На главную</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <ul class="navbar-nav">
  <li class="nav-item">
        <a class="nav-link ml-auto" href="./tasks.php">Все задачи</a>
      </li>
      <li class="nav-item">
        <a class="nav-link ml-auto" href="./personal_tasks.php">Личные задачи</a>
      </li>
</ul>
<div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <div class="dropdown">
      <a id="userIcon" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  <img src="../Kval/resourses/user.png" alt="Пользователь" style="width: 30px; height: 30px;">
</a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userIcon">
          <a class="dropdown-item" href="ссылка_1">Личный кабинет</a>
          <?php if ($access_status == 1): ?>
            <a class="dropdown-item" href="./redact_task.php">Управление проектами</a>
          <a class="dropdown-item" href="redact_users.php">Управление пользователями</a>
          <?php endif; ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="../logout.php">Выйти</a>
        </div>
      </div>
    </li>
  </ul>
</div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function() {
    // При клике на иконку пользователя
    $('#userIcon').click(function(e) {
      e.preventDefault();
      $('#logoutOption').toggle();
    });
  });
</script>
<div class="container">
  <h3>Список имеющихся пользователей</h3>
  <!-- Вывод существующих пользователей -->
  <div class="list-group">
    <?php foreach ($users as $user): ?>
      <a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#user-<?php echo $user['user_id']; ?>">
        <?php echo $user['First_name']; ?> <?php echo $user['Last_name']; ?> (<?php echo $user['Name']; ?>)
      </a>
      <div id="user-<?php echo $user['user_id']; ?>" class="collapse mt-3">
        <p>ФИО:<?php echo $user['Last_name']; ?> <?php echo $user['First_name']; ?> <?php echo $user['Middle_name']; ?></p>
        <p>Отделение: <?php echo $user['Name']; ?></p>
        <p>Дата регистрации: <?php echo $user['Date_of_fusing']; ?></p>
        <p>Должность: <?php echo $user['nme']; ?></p>
        <p>Права доступа: <?php if($user['Access_status']==1)
        {
            echo "Редактор";
        }
        else{
            echo "Читатель";
        }; ?></p>
        <p>Права доступа: <?php if($user['delete_status']==1)
        {
            echo "Удален";
        }
        else{
            echo "Не удален";
        }; ?></p>
        <button class="btn btn-primary assign-btn" onclick="assignToEditor(<?php echo $user['user_id']; ?>)">Назначить редактором</button>
        <button class="btn btn-primary assign-btn" onclick="DeleteUser(<?php echo $user['user_id']; ?>)">Удалить пользователя</button>
        <!-- Добавьте другую информацию о пользователе, если необходимо -->
      </div>
    <?php endforeach; ?>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function assignToEditor(userId) {
    $.ajax({
    url: 'assign_editor.php', // Замените на путь к вашему серверному скрипту
    method: 'POST',
    data: { userId: userId },
    success: function(response) {
      // Обработка успешного ответа от сервера
      if (response === 'success') {
        alert('Статус доступа пользователя изменен');
      } else {
        alert('Ошибка при изменении статуса доступа пользователя');
      }
      // Дополнительные действия после изменения статуса доступа
    },
    error: function(xhr, status, error) {
      // Обработка ошибки AJAX-запроса
      console.log(xhr.responseText);
    }
  });
}
  function DeleteUser(userId) {
    // Отправляем AJAX-запрос на сервер
    $.ajax({
      url: 'Delete_user.php', // Замените на путь к вашему серверному скрипту
      method: 'POST',
      data: { userId: userId },
      success: function(response) {
        // Обработка успешного ответа от сервера
        alert('Пользователь успешно удален');
        // Дополнительные действия после удаления пользователя
      },
      error: function(xhr, status, error) {
        // Обработка ошибки AJAX-запроса
        console.log(xhr.responseText);
      }
    });
  }
</script>