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

//Проверка POST-параметра 
if (isset($_POST['edit'])) {
  $id = htmlspecialchars($_POST['ID']);
  //Формирование массива полей и значений для изменения записи
  $fields_values = array();
  $fields_values['Name'] = htmlspecialchars($_POST['Name']);
  $fields_values['Description'] = htmlspecialchars($_POST['Description']);
  $fields_values['Keywords'] = htmlspecialchars($_POST['Keywords']);
  $fields_values['Priority'] = htmlspecialchars($_POST['Priority']);
  $fields_values['PublishFrom'] = htmlspecialchars($_POST['PublishFrom']);
  $db->ChangeDataOnId(MULTS, $fields_values, $id);
}

//Возвращение на страницу catalog.php
header("Location: ".ADMINURL."catalog.php");
?>