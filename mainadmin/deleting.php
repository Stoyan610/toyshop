<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';
//Проверка - если зашёл без логина, то на выход
if (empty($_SESSION['login'])) {
  header("Location: ".SITEURL);
  exit;
}
//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once PATH.'www/'.MODEL;
$db = new DbRover($_SESSION['login'], $_SESSION['password']);

//Выбор удаляемого объекта
$item = htmlspecialchars($_POST['choice']);
unset($_POST['choice']);

//Проверка POST-параметра 'delete' 
if (isset($_POST['delete'])) {
  unset($_POST['delete']);
  $iddel = htmlspecialchars($_POST['del']);
  unset($_POST['del']);
  switch ($item) {
    case 'product': {
      $db->DataOffOnId(TOYS, $iddel);
      $db->DataOffOnCondition(LIMG, 'Product_ID', '=', $iddel);
      break;
    }
    case 'catalog': {
      $db->DataOffOnId(MULTS, $iddel);
      break;
    }
    case 'client': {
      $db->DataOffOnId(CLNTS, $iddel);
      break;
    }
    case 'order': {
      $db->DataOffOnId(ORDS, $iddel);
      $db->DataOffOnCondition(BASKET, 'Order_ID', '=', $iddel);
      break;
    }

      
    default:
      break;
  }
}  

//Возвращение на страницу запроса
header("Location: ".ADMINURL.$item.".php");

?>