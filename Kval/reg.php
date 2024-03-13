<?php
session_start();
include_once ("../Kval/config/config.php");
include_once ("../Kval/config/functions.php");
include_once ("../Kval/config/db_connect.php");
// Создание нового аккаунта и занесения информации о нем в базу данных
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Проверяем, что пользователь заполнил все поля
    if (!empty($_POST['First_name']) && !empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['Last_name']) && !empty($_POST['Middle_name'])) {
        // Получаем данные из формы регистрации
        $First_name = $_POST['First_name'];
        $Last_name = $_POST['Last_name'];
        $Middle_name = $_POST['Middle_name'];
        $login = $_POST['login'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        // Проверяем, что пользователь с таким email еще не зарегистрирован
        $stmt = mysqli_prepare($connection, "SELECT user_id FROM user WHERE login = ?");
        if (!$stmt) {
            die('Ошибка при подготовке запроса: ' . mysqli_error($connection));
        }        
        mysqli_stmt_bind_param($stmt, 's', $login);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error_msg = 'Пользователь с таким логином уже зарегистрирован.';
        } else {
            // Регистрируем пользователя
            $stmt = mysqli_prepare($connection, "INSERT INTO user(First_name, Last_name, Middle_name, login, password) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'sssss', $First_name, $Last_name, $Middle_name, $login, $password);
            if (mysqli_stmt_execute($stmt)) {
                // Авторизуем пользователя и перенаправляем на главную страницу
                $_SESSION['user_id'] = mysqli_insert_id($connection);
                header('Location: welcome.php');
                exit();
            } else {
                $error_msg = 'Не удалось зарегистрировать пользователя. Попробуйте еще раз позже.';
            }
        }
    } else {
        $error_msg = 'Пожалуйста, заполните все поля формы.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Регистрация</title>
    <!-- Подключение стилей Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Ваши дополнительные стили, если есть -->
    <style>
        		body {
			background-image: url(./resourses/fon.jpg);
			background-size: cover;
			background-repeat: no-repeat;
		}
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center">Регистрация</h2>
            <?php if (isset($error_msg)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_msg; ?>
                </div>
            <?php } ?>
            <form method="POST">
                <div class="form-group">
                    <label for="First_name">Имя</label>
                    <input type="First_name" class="form-control" id="First_name" name="First_name" required>
                </div>
                <div class="form-group">
                    <label for="Last_name">Фамилия</label>
                    <input type="Last_name" class="form-control" id="Last_name" name="Last_name" required>
                </div>
                <div class="form-group">
                    <label for="Middle_name">Отчество</label>
                    <input type="Middle_name" class="form-control" id="Middle_name" name="Middle_name" required>
                </div>
                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="login" class="form-control" id="login" name="login" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Подтвердите пароль</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                <p class="text-center">Уже зарегистрированы? <a href="auth.php" class="button btn btn-primary">Войти</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>