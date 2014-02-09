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

//Выбор добавляемого объекта
$item = htmlspecialchars($_POST['choice']);
unset($_POST['choice']);

//Проверка POST-параметра 'add'
if (isset($_POST['add'])) {
  unset($_POST['add']);
  $fields_values = array();
  $fields_values['ID'] = NULL;
  switch ($item) {
    case 'product': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Name', 'Catalog_ID', 'Description', 'Keywords', 'Priority', 'PublishFrom', 'Price', 'Quantity', 'Deadline', 'Manufacture', 'Material', 'Dimension', 'Weight', 'Popularity');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->DataIn(TOYS, $fields_values);
      break;
    }
    case 'catalog': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Name', 'Description', 'Keywords', 'Image_ID', 'Priority', 'PublishFrom');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->DataIn(MULTS, $fields_values);
      break;
    }
    

    default:
      break;
  }


}

//Возвращение на страницу запроса
header("Location: ".ADMINURL.$item.".php");

?>