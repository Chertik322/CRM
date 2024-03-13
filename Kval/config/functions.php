<?php
include_once ("config.php");
include_once ("db_connect.php");

function ajax_echo(
    $title = '',
    $text = '',
    $error = false,
    $type = 'ERROR',
    $other = null
) {
    return json_encode(array(
        "error" => $error,
        "type" => $type,
        "title" => $title,
        "desc" => $text,
        "other" => $other,
        "datetime" => array(
            'd' => date('d'),
            'm' => date('m'),
            'Y' => date('Y'),
            'H' => date('H'),
            'i' => date('i'),
            's' => date('s'),
            'full' => date('d-m-Y H:i:s'),
        )
    ));
}
// Вход
function login($connection, $login, $password) {
  // Получаем пользователя по email
  $user = get_user_by_login($connection, $login);
  
  // Если пользователя нет в базе данных, возвращаем false
  if (!$user) {
    return false;
  }
  
  // Если пароль не совпадает, возвращаем false
  if (!password_verify($password, $user['password'])) {
    return false;
  }
  
  // Если все проверки прошли успешно, сохраняем user_id в сессии
  $_SESSION['user_id'] = $user['user_id'];
  $_SESSION['First_name'] = $user['First_name'];
  
  // Возвращаем true, чтобы показать, что авторизация прошла успешно
  return true;
}
// Добавление пользователя
function add_user($connection, $First_name, $Last_name, $Middle_name, $login, $password) {
  $stmt = mysqli_prepare($connection, "INSERT INTO user(First_name, Last_name, Middle_name, login, password) VALUES (?, ?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, 'sssss', $First_name, $Last_name, $Middle_name, $login, $password);
  mysqli_stmt_execute($stmt);
  return mysqli_insert_id($connection);
}
// Поиск пользователя по его почте
function get_user_by_login($connection, $login) {
  $stmt = mysqli_prepare($connection, "SELECT * FROM user WHERE login = ?");
  mysqli_stmt_bind_param($stmt, 's', $login);
  mysqli_stmt_execute($stmt);
  return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
// Проверка на то, является ли пользователь читателем или редактором
function get_user_access_status($connection, $user_id) {
  $stmt = mysqli_prepare($connection, "SELECT Access_status FROM user WHERE user_id = ?");
  mysqli_stmt_bind_param($stmt, 'i', $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $access_status);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
  return $access_status;
}
function add_project($connection, $Name) {
  $query = "INSERT INTO projects (Name) 
            VALUES ('$Name')";
  mysqli_query($connection, $query);
  return mysqli_insert_id($connection);
}
function get_users($connection) {
  $sql = "SELECT DISTINCT u.delete_status, u.user_id, u.First_name, u.Last_name, u.Middle_name, u.post_id, u.Date_of_fusing, c.Name, p.nme
          FROM user u
          LEFT JOIN Users_posrt up ON u.user_id = up.user_id
          LEFT JOIN posts p ON up.post_id = p.post_id
          LEFT JOIN Users_clas uc ON u.user_id = uc.user_id
          LEFT JOIN class c ON uc.class_id = c.class_id";

  $result = mysqli_query($connection, $sql);

  if (!$result) {
    die('Ошибка выполнения запроса: ' . mysqli_error($connection));
  }
  $users = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $user = array(
      'user_id' => $row['user_id'],
      'First_name' => $row['First_name'],
      'Last_name' => $row['Last_name'],
      'Middle_name' => $row['Middle_name'],
      'post_id' => $row['post_id'],
      'Date_of_fusing' => $row['Date_of_fusing'],
      'Name' => $row['Name'], // Добавляем название класса в массив данных пользователя
      'nme' => $row['nme'],
      'Access_status' => get_user_access_status($connection, $row['user_id']), // Получаем статус доступа пользователя
      'delete_status'=>$row['delete_status']
    );

    $users[] = $user;
  }

  return $users;
}
function get_priority($connection) {
  // Запрос для извлечения приоритетов из таблицы "priority"
  $sql = "SELECT priority_id, Name FROM priority";

  // Выполнение запроса
  $result = mysqli_query($connection, $sql);

  // Проверка наличия результатов
  if ($result) {
      // Создание пустого массива для хранения приоритетов
      $priorities = array();

      // Извлечение данных каждого приоритета
      while ($row = mysqli_fetch_assoc($result)) {
          $priorityId = $row['priority_id'];
          $priorityName = $row['Name'];

          // Создание ассоциативного массива для хранения приоритета
          $priority = array(
              'priority_id' => $priorityId,
              'name' => $priorityName
          );

          // Добавление приоритета в массив приоритетов
          $priorities[] = $priority;
      }

      // Возвращение массива приоритетов
      return $priorities;
  } else {
      // Если результаты не найдены, возвращение пустого массива
      return array();
  }
}
function get_classes($connection) {
  // Запрос для извлечения классов из таблицы "class"
  $sql = "SELECT class_id,  Name FROM class";

  // Выполнение запроса
  $result = mysqli_query($connection, $sql);

  // Проверка наличия результатов
  if ($result) {
      // Создание пустого массива для хранения классов
      $classes = array();

      // Извлечение данных каждого класса
      while ($row = mysqli_fetch_assoc($result)) {
          $class_id = $row['class_id'];
          $classname = $row['Name'];

          // Создание ассоциативного массива для хранения класса
          $class = array(
              'class_id' => $class_id,
              'Name' => $classname
          );

          // Добавление класса в массив классов
          $classes[] = $class;
      }

      // Возвращение массива классов
      return $classes;
  } else {
      // Если результаты не найдены, возвращение пустого массива
      return array();
  }
}
function get_tasks_by_class_1($connection) {
  $query = "SELECT * FROM tasks WHERE class_id = 1";
  $stmt = $connection->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  $tasks = [];
  while ($row = $result->fetch_assoc()) {
      $tasks[] = $row;
  }

  return $tasks;
}
function get_tasks_by_class_2($connection) {
  $query = "SELECT * FROM tasks WHERE class_id = 2";
  $stmt = $connection->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  $tasks = [];
  while ($row = $result->fetch_assoc()) {
      $tasks[] = $row;
  }

  return $tasks;
}
function get_tasks_by_class_3($connection) {
  $query = "SELECT * FROM tasks WHERE class_id = 3";
  $stmt = $connection->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  $tasks = [];
  while ($row = $result->fetch_assoc()) {
      $tasks[] = $row;
  }

  return $tasks;
}
function get_tasks_by_class_4($connection) {
  $query = "SELECT * FROM tasks WHERE class_id = 4";
  $stmt = $connection->prepare($query);
  $stmt->execute();
  $result = $stmt->get_result();

  $tasks = [];
  while ($row = $result->fetch_assoc()) {
      $tasks[] = $row;
  }

  return $tasks;
}
function get_person($connection, $user_id) {
  $sql = "SELECT DISTINCT u.delete_status, u.user_id, u.First_name, u.Last_name, u.Middle_name, u.post_id, u.Date_of_fusing, c.Name, p.nme, uc.class_id
  FROM user u
  LEFT JOIN Users_posrt up ON u.user_id = up.user_id
  LEFT JOIN posts p ON up.post_id = p.post_id
  LEFT JOIN Users_clas uc ON u.user_id = uc.user_id
  LEFT JOIN class c ON uc.class_id = c.class_id
  WHERE u.user_id = '$user_id'"; // Добавляем условие WHERE для фильтрации по user_id

  $result = mysqli_query($connection, $sql);

  if (!$result) {
    die('Ошибка выполнения запроса: ' . mysqli_error($connection));
  }
  $users = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $user = array(
      'user_id' => $row['user_id'],
      'First_name' => $row['First_name'],
      'Last_name' => $row['Last_name'],
      'Middle_name' => $row['Middle_name'],
      'post_id' => $row['post_id'],
      'Date_of_fusing' => $row['Date_of_fusing'],
      'Name' => $row['Name'], // Добавляем название класса в массив данных пользователя
      'nme' => $row['nme'],
      'Access_status' => get_user_access_status($connection, $row['user_id']), // Получаем статус доступа пользователя
      'class_id' => $row['class_id'], // Добавляем идентификатор класса в массив данных пользователя
    );

    $users[] = $user;
  }

  return $users;
}
