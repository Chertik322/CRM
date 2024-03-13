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
  $name = $_SESSION['user_id'];
  $access_status = get_user_access_status($connection, $name);
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
<!-- Список задач -->
<div class="border-top border-0 mb-2"></div>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th class="border-left" style="width: 25%;" colspan="1">
                    Приемная комиссия
                </th>
                <th class="border-left" style="width: 25%;" colspan="1">
                    Студенческий совет
                </th>
                <th class="border-left" style="width: 25%;" colspan="1">
                    Отдел разработки
                </th>
                <th class="border-left" style="width: 25%;" colspan="1">
                    Отдел учителей
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
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
                </td>
                <td>
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
                </td>
                <td>
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
                </td>
                <td>
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
    if ($access_status == 1){
    echo '<div class="task-buttons">';
    echo '<button class="btn btn-sm btn-danger btn-delete" data-taskid="' . $task['task_id'] . '" style="float: right; margin-top: -40px;">×</button>';
    $statusClass = $task['status'] === 1 ? 'task-done' : '';
    }
    echo '<button class="btn btn-sm btn-success btn-done ' . $statusClass . '" data-taskid="' . $task['task_id'] . '" data-status="' . $task['status'] . '"></button>';
    echo '<span class="due_date">Дата сдачи:' . $task['Date_of_submission'] . '</span><br>';
    echo '</div>';
    echo '</div>';
}
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addTaskModalLabel">Добавить задачу</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- Форма добавления задачи -->
				<form id="addTaskForm" method="POST">
					<div class="form-group">
						<label for="taskName">Название задачи</label>
						<input type="text" class="form-control" id="taskName" name="taskName" required>
					</div>
					<div class="form-group">
						<label for="taskDescription">Описание задачи</label>
						<textarea class="form-control" id="taskDescription" name="taskDescription" rows="3" required></textarea>
					</div>
					<div class="form-group">
						<label for="taskDueDate">Срок выполнения</label>
						<input type="date" class="form-control" id="taskDueDate" name="taskDueDate" required>
					</div>
					<div class="form-group">
	<label for="classId">ID класса</label>
	<select class="form-control" id="classId" name="classId">
		<?php
		$classes = get_classes($connection);
		foreach ($classes as $class) {
			echo '<option value="' . $class['class_id'] . '">' . $class['Name'] . '</option>';
		}
		?>
	</select>
</div>
                    <div class="form-group">
	<label for="priority">Приоритет</label>
	<select class="form-control" id="priority" name="priority">
		<?php
		$priorities = get_priority($connection);
		foreach ($priorities as $priority) {
			echo '<option value="' . $priority['priority_id'] . '">' . $priority['name'] . '</option>';
		}
		?>
	</select>
</div>
					<button type="button" class="btn btn-primary" id="addTaskButton">Добавить</button>
				</form>
			</div>
		</div>
	</div>
</div>
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