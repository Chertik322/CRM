<?php
session_start();
include_once ("../Kval/config/config.php");
include_once ("../Kval/config/functions.php");
include_once ("../Kval/config/db_connect.php");

if (!isset($_SESSION['user_id'])) {
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
  $user_id = $_SESSION['user_id'];
  $access_status = get_user_access_status($connection, $user_id);

$sql = "SELECT class_id FROM Users_clas WHERE user_id = '$user_id'";
$result = mysqli_query($connection, $sql);

if (!$result) {
    die('Ошибка выполнения запроса: ' . mysqli_error($connection));
}

$row = mysqli_fetch_assoc($result);
$class_id = $row['class_id'];
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
	<a class="nav-link" href="#" data-toggle="modal" data-target="#addTaskModal">Добавить задачу</a>
</li>
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
<style>
    .btn-delete {
        position: relative;
        top: 5px;
        right: 5px;
        width: 20px;
        height: 20px;
        padding: 0;
        line-height: 0;
        font-size: 14px;
    }

    .task-name {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .task-description {
        margin-top: 10px;
    }

    .btn-done {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 28px;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .btn-done {
  position: relative;
  width: 20px;
  height: 20px;
  padding: 0;
  border: none;
  border-radius: 50%;
  background-color: transparent;
  cursor: pointer;
}

.btn-done::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background-color: #999;
  transition: background-color 0.3s ease;
}

.btn-done::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 5px;
  height: 10px;
  border: solid #fff;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.btn-done.done::before {
  background-color: #28a745;
}

.btn-done.done::after {
  opacity: 1;
}


    .btn-details {
        content: 'ℹ';
        background-color: transparent;
        border: none;
        padding: 0;
        font-size: 14px;
    }

    .btn-details::after {
        content: 'ℹ';
    }

    .task-name {
        font-weight: bold;
    }

    .task-description {
        margin-top: 10px;
    }

    .task-done {
        text-decoration: line-through;
    }
    .task-wrapper {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }

    .task-name {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .task-description {
        margin-top: 10px;
    }

    .task-buttons {
        margin-top: 10px;
    }
    .task-done {
  background-color: #e6f0ff; /* Измените цвет на нужный вам */
}

</style>
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
<div class="border-top border-0 mb-2"></div>
<div class="table-responsive">
    <table class="table">
      <div form-group>
        <thead>
            <tr>
            <?php if ($class_id == 1): ?>
              <?php
$tasks = get_tasks_by_class_1($connection);
foreach ($tasks as $task) {
    $taskClass = $task['status'] == 1 ? 'task-done' : ''; // Проверяем статус и определяем класс
    echo '<div class="task-wrapper">';
    echo '<div class="task ' . $taskClass . '">'; // Добавляем класс к задаче
    echo '<button class="btn btn-sm btn-primary btn-details" type="button" data-toggle="collapse" data-target="#collapseTask' . $task['task_id'] . '"></button>';
    echo '<span class="task-name">' . $task['Name'] . '</span><br>';
    echo '<div class="collapse" id="collapseTask' . $task['task_id'] . '">';
    echo '<div class="card card-body task-description">' . $task['Description'] . '</div>';
// Получение списка приоритетов
$priorities = get_priority($connection);

// Идентификатор приоритета для задачи
$priorityId = $task['priority_id'];

// Поиск приоритета по идентификатору
$priorityName = '';
foreach ($priorities as $priority) {
    if ($priority['priority_id'] == $priorityId) {
        $priorityName = $priority['name'];
        break;
    }
}

// Вывод названия приоритета
echo '<div class="card card-body">' . $priorityName . '</div>';

    echo '</div>';
    echo '</div>';
    echo '<div class="task-buttons">';
    if ($access_status == 1){
    echo '<button class="btn btn-sm btn-danger btn-delete" data-taskid="' . $task['task_id'] . '" style="float: right; margin-top: -40px;">×</button>';
    $statusClass = $task['status'] === 1 ? 'task-done' : '';
    echo '<button class="btn btn-sm btn-success btn-done ' . $statusClass . '" data-taskid="' . $task['task_id'] . '" data-status="' . $task['status'] . '"></button>';
    }
    echo '<span class="due_date">Дата сдачи:' . $task['Date_of_submission'] . '</span><br>';
    echo '</div>';
    echo '</div>';
}
                    ?>
                    </div>

<?php elseif ($class_id == 2): ?>
  <?php
$tasks = get_tasks_by_class_2($connection);
foreach ($tasks as $task) {
    $taskClass = $task['status'] == 1 ? 'task-done' : ''; // Проверяем статус и определяем класс
    echo '<div class="task-wrapper">';
    echo '<div class="task ' . $taskClass . '">'; // Добавляем класс к задаче
    echo '<button class="btn btn-sm btn-primary btn-details" type="button" data-toggle="collapse" data-target="#collapseTask' . $task['task_id'] . '"></button>';
    echo '<span class="task-name">' . $task['Name'] . '</span><br>';
    echo '<div class="collapse" id="collapseTask' . $task['task_id'] . '">';
    echo '<div class="card card-body task-description">' . $task['Description'] . '</div>';
    // Получение списка приоритетов
$priorities = get_priority($connection);

// Идентификатор приоритета для задачи
$priorityId = $task['priority_id'];

// Поиск приоритета по идентификатору
$priorityName = '';
foreach ($priorities as $priority) {
    if ($priority['priority_id'] == $priorityId) {
        $priorityName = $priority['name'];
        break;
    }
}

// Вывод названия приоритета
echo '<div class="card card-body">' . $priorityName . '</div>';

    echo '</div>';
    echo '</div>';
    echo '<div class="task-buttons">';
    if ($access_status == 1){
    echo '<button class="btn btn-sm btn-danger btn-delete" data-taskid="' . $task['task_id'] . '" style="float: right; margin-top: -40px;">×</button>';
    $statusClass = $task['status'] === 1 ? 'task-done' : '';
    echo '<button class="btn btn-sm btn-success btn-done ' . $statusClass . '" data-taskid="' . $task['task_id'] . '" data-status="' . $task['status'] . '"></button>';
    }
    echo '<span class="due_date">Дата сдачи:' . $task['Date_of_submission'] . '</span><br>';
    echo '</div>';
    echo '</div>';
}
                    ?>
                    </div>

    <?php elseif ($class_id== 3): ?>
      <?php
$tasks = get_tasks_by_class_3($connection);
foreach ($tasks as $task) {
    $taskClass = $task['status'] == 1 ? 'task-done' : ''; // Проверяем статус и определяем класс
    echo '<div class="task-wrapper">';
    echo '<div class="task ' . $taskClass . '">'; // Добавляем класс к задаче
    echo '<button class="btn btn-sm btn-primary btn-details" type="button" data-toggle="collapse" data-target="#collapseTask' . $task['task_id'] . '"></button>';
    echo '<span class="task-name">' . $task['Name'] . '</span><br>';
    echo '<div class="collapse" id="collapseTask' . $task['task_id'] . '">';
    echo '<div class="card card-body task-description">' . $task['Description'] . '</div>';
// Получение списка приоритетов
$priorities = get_priority($connection);

// Идентификатор приоритета для задачи
$priorityId = $task['priority_id'];

// Поиск приоритета по идентификатору
$priorityName = '';
foreach ($priorities as $priority) {
    if ($priority['priority_id'] == $priorityId) {
        $priorityName = $priority['name'];
        break;
    }
}

// Вывод названия приоритета
echo '<div class="card card-body">' . $priorityName . '</div>';

    echo '</div>';
    echo '</div>';
    echo '<div class="task-buttons">';
    if ($access_status == 1){
    echo '<button class="btn btn-sm btn-danger btn-delete" data-taskid="' . $task['task_id'] . '" style="float: right; margin-top: -40px;">×</button>';
    $statusClass = $task['status'] === 1 ? 'task-done' : '';
    echo '<button class="btn btn-sm btn-success btn-done ' . $statusClass . '" data-taskid="' . $task['task_id'] . '" data-status="' . $task['status'] . '"></button>';
    }
    echo '<span class="due_date">Дата сдачи:' . $task['Date_of_submission'] . '</span><br>';
    echo '</div>';
    echo '</div>';
}
                    ?>
        <?php elseif ($class_id == 4): ?>
          </div>

          <?php
$tasks = get_tasks_by_class_4($connection);
foreach ($tasks as $task) {
    $taskClass = $task['status'] == 1 ? 'task-done' : ''; // Проверяем статус и определяем класс
    echo '<div class="task-wrapper">';
    echo '<div class="task ' . $taskClass . '">'; // Добавляем класс к задаче
    echo '<button class="btn btn-sm btn-primary btn-details" type="button" data-toggle="collapse" data-target="#collapseTask' . $task['task_id'] . '"></button>';
    echo '<span class="task-name">' . $task['Name'] . '</span><br>';
    echo '<div class="collapse" id="collapseTask' . $task['task_id'] . '">';
    echo '<div class="card card-body task-description">' . $task['Description'] . '</div>';
// Получение списка приоритетов
$priorities = get_priority($connection);

// Идентификатор приоритета для задачи
$priorityId = $task['priority_id'];

// Поиск приоритета по идентификатору
$priorityName = '';
foreach ($priorities as $priority) {
    if ($priority['priority_id'] == $priorityId) {
        $priorityName = $priority['name'];
        break;
    }
}

// Вывод названия приоритета
echo '<div class="card card-body">' . $priorityName . '</div>';

    echo '</div>';
    echo '</div>';
    echo '<div class="task-buttons">';
    if ($access_status == 1){
    echo '<button class="btn btn-sm btn-danger btn-delete" data-taskid="' . $task['task_id'] . '" style="float: right; margin-top: -40px;">×</button>';
    $statusClass = $task['status'] === 1 ? 'task-done' : '';
    echo '<button class="btn btn-sm btn-success btn-done ' . $statusClass . '" data-taskid="' . $task['task_id'] . '" data-status="' . $task['status'] . '"></button>';
    }
    echo '<span class="due_date">Дата сдачи:' . $task['Date_of_submission'] . '</span><br>';
    echo '</div>';
    echo '</div>';
}
                    ?>
                    </div>

<?php endif; ?>
<script>
$(document).ready(function() {
	// При клике на кнопку "Добавить"
	$('#addTaskButton').click(function(e) {
		e.preventDefault();

		// Получаем данные формы
		var taskName = $('#taskName').val();
		var taskDescription = $('#taskDescription').val();
		var taskDueDate = $('#taskDueDate').val();
		var priorityId = $('#priority').val();
		var classId = $('#classId').val();

		// Отправляем данные на сервер через AJAX
		$.ajax({
			url: 'add_task.php',
			type: 'POST',
			data: {
				taskName: taskName,
				taskDescription: taskDescription,
				taskDueDate: taskDueDate,
				priorityId: priorityId,
				classId: classId
			},
			success: function(response) {
					// Обработка успешного ответа от сервера
					alert('Задача успешно добавлена!');
					// Закрываем модальное окно
					$('#addTaskModal').modal('hide');
					// Очищаем значения полей формы
					$('#addTaskForm')[0].reset();
				},
				error: function(xhr, status, error) {
					// Обработка ошибок
					alert('Произошла ошибка при добавлении задачи.');
					console.log(xhr.responseText);
				}
			});
		});
	});
    </script>
<script>
$(document).ready(function() {
  $('.btn-done').click(function() {
    var button = $(this);
    var taskid = button.data('taskid');
    var currentStatus = button.data('status');
    var newStatus = currentStatus === 1 ? 0 : 1;
    $.ajax({
      url: 'update_task_status.php',
      method: 'POST',
      data: { taskid: taskid, status: newStatus },
      success: function(response) {
        // Здесь можно обновить интерфейс, если необходимо
        console.log('Статус задачи обновлен!');
        // Меняем класс кнопки и обновляем статус в атрибуте data-status
        button.toggleClass('task-done');
        button.data('status', newStatus);
        // Автоматическое обновление страницы
        location.reload();
      },
      error: function(xhr, status, error) {
        console.log('Произошла ошибка при обновлении статуса задачи: ' + error);
      }
    });
  });
});

</script>
<script>
$(document).ready(function() {
  $('.btn-delete').click(function() {
    var button = $(this);
    var taskid = button.data('taskid');
    $.ajax({
      url: 'delete_task.php',
      method: 'POST',
      data: { taskid: taskid },
      success: function(response) {
        // Здесь можно обновить интерфейс, если необходимо
        console.log('Задача успешно удалена!');
        // Удаление задачи из интерфейса
        button.closest('.task-wrapper').remove();
      },
      error: function(xhr, status, error) {
        console.log('Произошла ошибка при удалении задачи: ' + error);
      }
    });
  });
});
    </script>