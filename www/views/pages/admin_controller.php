<?php

//use mysqli;

session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);
//Подключение конфигурационного файла
require_once '../www/config.php';

if ((empty($_SESSION['login']))||(empty($_POST['send']))) {
  header("Refresh: 3; Location: index.php");
  exit("Нет логина администратора");
}

/*
if (isset($_POST['send'])) {
  $_SESSION['login'] = htmlspecialchars($_POST['log']);
  $_SESSION['password'] = htmlspecialchars($_POST['pass']);
  unset($_POST['send']);
  unset($_POST['log']);
  unset($_POST['pass']);
}
$admin_connect = @new mysqli(HOST, $_SESSION['login'], $_SESSION['password'], DB);
if (mysqli_connect_errno()) {
  unset($_SESSION['login']);
  unset($_SESSION['password']);
  $goback = $_SERVER['HTTP_REFERER'];
  header("Refresh: 3; Location: ".$goback);
  exit("Нет соединения");
}
if ($admin_connect) $admin_connect->close();



//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once MODEL;


*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>Administrator page</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<style>
  body {background-color: #B3FFA5;}
</style>
</head>
<body>

  <ol>
    <li><a href="admin_controller.php?table=A_CATALOG">Категории</a></li>
    <li><a href="admin_controller.php?table=A_PRODUCT">Товары</a></li>
    <!-- ........................... -->
  </ol>
  
  
  
  
<?php


echo 'Вроде всё работает';


/*

//Проверка имени таблицы, Обработка GET-данных, и присваивание странице название рабочей таблицы
$table = empty($_GET['table']) ? '' : htmlspecialchars($_GET['table']);
unset($_GET['table']);
//Уничтожить объект, если он был создан для предыдущей страницы
if (!empty($creator)) unset($creator);
//Создание объекта, соответствующего таблице, и подключение класса обработчика соответствующей таблицы
switch ($table) {
  case 'A_CATALOG': {
    require_once 'class/catalog.php';
    $creator = new Catalog();
    break;
  }
  case 'A_PRODUCT': {
    require_once 'class/product.php';
    $creator = new Product();
    break;
  }
  //Далее по списку nf,kbw

  default: {
      exit();
    break;
  }
}


*/

?>

  
  
  
</body>
</html>