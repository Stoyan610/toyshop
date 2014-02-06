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
if (isset($_POST['add'])) {
  //Формирование массива полей и значений для добавления в БД
  $fields_values = array();
  $fields_values['ID'] = NULL;
  $fields_values['Name'] = htmlspecialchars($_POST['Name']);
  $fields_values['Description'] = htmlspecialchars($_POST['Description']);
  $fields_values['Keywords'] = htmlspecialchars($_POST['Keywords']);
  $fields_values['Image_ID'] = htmlspecialchars($_POST['ImageID']);
  $fields_values['Priority'] = htmlspecialchars($_POST['Priority']);
  $fields_values['PublishFrom'] = htmlspecialchars($_POST['PublishFrom']);
  unset($_POST['add']);
  unset($_POST['Name']);
  unset($_POST['Description']);
  unset($_POST['Keywords']);
  unset($_POST['ImageID']);
  unset($_POST['Priority']);
  unset($_POST['PublishFrom']);
  $db->DataIn(MULTS, $fields_values);
}

//Возвращение на страницу catalog.php
header("Location: ".ADMINURL."catalog.php");

?>