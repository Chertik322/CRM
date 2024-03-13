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
if (isset($_POST['Name']) && isset($_POST['Description'])) {
    // Получение данных из формы
    $Name = mysqli_real_escape_string($connection, $_POST['Name']);
    $Description = mysqli_real_escape_string($connection, $_POST['Description']);
    add_project($connection, $Name);
    if (mysqli_error($connection)) {
        die(mysqli_error($connection));
    }
  }
  $projects = get_projects($connection);

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
          <a class="dropdown-item" href="./redact_users.php">Управление пользователями</a>
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
        <h3>Список проектов</h3>
        <!-- Вывод существующих проектов -->
        <div class="list-group">
    <?php foreach ($projects as $project): ?>
        <a href="#" class="list-group-item list-group-item-action" data-toggle="collapse" data-target="#project-<?php echo $project['project_id']; ?>">
            <?php echo $project['project_name']; ?>
        </a>
        <div id="project-<?php echo $project['project_id']; ?>" class="collapse mt-3">
        <p>Название проекта: <?php echo $project['project_name']; ?></p>
            <p>Описание: <?php echo $project['description']; ?></p>
            <p>Дата начала: <?php echo $project['start_date']; ?></p>
            <p>Дата окончания: <?php echo $project['end_date']; ?></p>
            <!-- Добавьте другую информацию о проекте, если необходимо -->
            <button class="btn btn-primary assign-btn" onclick="assignToEditor(<?php echo $project['project_id']; ?>)"></button>
            <button class="btn btn-primary assign-btn" onclick="DeleteProject(<?php echo $project['project_id']; ?>)">Завершить проект</button>
        </div>
    <?php endforeach; ?>
</div>
        <!-- Кнопка "Добавить проект" -->
        <button type="button" class="btn btn-primary mt-3" data-toggle="collapse" data-target="#addProjectForm">
            <i class="fas fa-plus"></i> Добавить новый проект
        </button>
        <!-- Форма добавления нового проекта -->
        <div id="addProjectForm" class="collapse mt-3">
        <form method="post">
                <div class="form-group">
                    <label for="Name">Название проекта</label>
                    <input type="text" class="form-control" id="Name" name="Name" placeholder="Введите название">
                </div>
                <div class="form-group">
                    <label for="Description">Описание проекта</label>
                    <textarea class="form-control" id="Description" name="Description"
                        placeholder="Введите описание"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Добавить проект</button>
                </div>
            </form>
        </div>
    </div>
