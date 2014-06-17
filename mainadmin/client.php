<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);
//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';
//Проверка - если зашёл без логина, то на выход
if (empty($_SESSION['login'])) {
  header("Location: ".SITEURL);
  exit;
}
//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once PATH.'www/'.MODEL;
//Создание объекта, соответствующего таблице, и подключение класса обработчика соответствующей таблицы
require_once PATH.'mainadmin/class/client_class.php';

//Уничтожить объект, если он был создан на предыдущей страницы
if (isset($creator)) {
  unset($creator);
}

$creator = new Client(DB_USER, DB_PASS);
if (!isset($_GET['act'])) {
  $creator->GetTable();
}
else {
  $act = htmlspecialchars($_GET['act']);
  unset($_GET['act']);
  switch ($act) {
    case 'edit': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->EditItem($id);
    break;
    }
    case 'del': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->DeleteItem($id);
    break;
    }
    case 'find_client': {
      if (isset($_GET['Name'])) {
        $name = htmlspecialchars($_GET['Name']);
        unset($_GET['Name']);
        $name = "%".$name."%";
        $creator->GetTableOnCond('Name', $name);
      }
      if (isset($_GET['Phone'])) {
        $phone = htmlspecialchars($_GET['Phone']);
        unset($_GET['Phone']);
        $phone = preg_replace('~\D+~', '', $phone);
        $phone = chunk_split($phone, 1, "%");
        $phone = "%".$phone;
        $creator->GetTableOnCond('Phone', $phone);
      }
    break;
    }

    default: {
      exit ('Это сделать невозможно');
    }
  }
}

?>