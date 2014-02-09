<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';
//Проверка - если зашёл без логина, то на выход
$goback = $_SERVER['HTTP_REFERER'];
if (empty($_SESSION['login'])) {
  header("Location: ".$goback);
  exit;
}
//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once PATH.'www/'.MODEL;
$db = new DbRover($_SESSION['login'], $_SESSION['password']);

//Выбор редактируемого объекта
$item = htmlspecialchars($_POST['choice']);
unset($_POST['choice']);

//Проверка POST-параметра 'edit'
if (isset($_POST['edit'])) {
  unset($_POST['edit']);
  $fields_values = array();
  $id = htmlspecialchars($_POST['ID']);
  unset($_POST['ID']);
  switch ($item) {
    case 'product': {
      //Формирование массива полей и значений для изменения записи
      $arr = array('Name', 'Catalog_ID', 'Description', 'Keywords', 'Priority', 'PublishFrom', 'Price', 'Quantity', 'Deadline', 'Manufacture', 'Material', 'Dimension', 'Weight', 'Popularity');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->ChangeDataOnId(TOYS, $fields_values, $id);
      break;
    }
    case 'catalog': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Name', 'Description', 'Keywords', 'Priority', 'PublishFrom');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->ChangeDataOnId(MULTS, $fields_values, $id);
      break;
    }
    case 'image': {
      //Формирование массива полей и значений для добавления в БД
      $fields_values['Alt'] = htmlspecialchars($_POST['Alt']);
      unset($_POST['Alt']);
      $db->ChangeDataOnId(IMG, $fields_values, $id);
      break;
    }
    case 'client': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Name', 'Phone', 'Mail');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $fields_values['Changed'] = date('Y-m-d');
      $db->ChangeDataOnId(CLNTS, $fields_values, $id);
      break;
    }
    
    
    default:
      break;
  }
}

//Возвращение на страницу запроса
header("Location: ".ADMINURL.$item.".php");

?>