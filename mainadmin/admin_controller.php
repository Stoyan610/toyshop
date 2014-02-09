<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);
//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';
//Проверка - если зашёл не через форму, то на выход
if ((empty($_SESSION['login']))&&(empty($_POST['send']))) {
  $goback = $_SERVER['HTTP_REFERER'];
  header("Location: ".$goback);
  exit;
}

if (isset($_POST['send'])) {
  $_SESSION['login'] = htmlspecialchars($_POST['log']);
  $_SESSION['password'] = htmlspecialchars($_POST['pass']);
  unset($_POST['send']);
  unset($_POST['log']);
  unset($_POST['pass']);
}
//Проверка - если не устанавливается подключение, то на выход
$admin_connect = @new mysqli(HOST, $_SESSION['login'], $_SESSION['password'], DB);
if (mysqli_connect_errno()) {
  unset($_SESSION['login']);
  unset($_SESSION['password']);
  $goback = $_SERVER['HTTP_REFERER'];
  header("Location: ".$goback);
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
    break;
  } 
  default: {
    exit ('Нет такой таблицы');
    break;
  }
}

//Подключение html файла для подключения таблиц
require_once 'half_index.php';

?>
  
</body>
</html>