<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);
//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';

//Проверка - если не устанавливается подключение, то на выход
$admin_connect = @new mysqli(HOST, DB_USER, DB_PASS, DB);
if (mysqli_connect_errno()) {
  header("Location: ".SITEURL);
  exit;
}

if (isset($_POST['send'])) {
  $_SESSION['login'] = htmlspecialchars($_POST['log']);
  $_SESSION['password'] = htmlspecialchars($_POST['pass']);
  unset($_POST['send']);
  unset($_POST['log']);
  unset($_POST['pass']);
}

//Проверка - если зашёл с неправильными логином и паролем, то на выход
if (empty($_SESSION['login'])) {
  header("Location: ".SITEURL);
  exit;
}
$zapros = "SELECT COUNT(`ID`) FROM `".ADM."` WHERE `NAME` = '".addslashes($_SESSION['login'])."' AND `PASS` = '".hash('sha512', $_SESSION['password'])."'";
$result = $admin_connect->query($zapros);
if ($result == 0) {
  header("Location: ".SITEURL);
  unset($_SESSION['login']);
  unset($_SESSION['password']);
  exit;
}

//Отключить соединение - будет подключаться через объект модели
if ($admin_connect) $admin_connect->close();
//Проверка имени таблицы, Обработка GET-данных, и присваивание странице название рабочей таблицы
$table = empty($_GET['table']) ? '' : htmlspecialchars($_GET['table']);
unset($_GET['table']);

//Переход на страницу, соответствующую таблице
switch ($table) {
  case 'a_catalog': {
    header('Location: catalog.php');
    exit;
  }
  case 'a_product': {
    header('Location: product.php');
    exit;
  }
  case 'a_image': {
    header('Location: image.php');
    exit;
  }
  case 'a_client': {
    header('Location: client.php');
    exit;
  }
  case 'a_order': {
    header('Location: order.php');
    exit;
  }
  case 'a_content': {
    header('Location: content.php');
    exit;
  }
  case 'j_feedback': {
    header('Location: feedback.php');
    exit;
  }
  case '': {
    header('Location: order.php');
    break;
  } 
  default: {
    exit ('Нет такой таблицы');
    break;
  }
}

?>