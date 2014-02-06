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
  $fields_values['Catalog_ID'] = htmlspecialchars($_POST['Catalog_ID']);
  $fields_values['Description'] = htmlspecialchars($_POST['Description']);
  $fields_values['Keywords'] = htmlspecialchars($_POST['Keywords']);
  $fields_values['Priority'] = htmlspecialchars($_POST['Priority']);
  $fields_values['PublishFrom'] = htmlspecialchars($_POST['PublishFrom']);
  $fields_values['Price'] = htmlspecialchars($_POST['Price']);
  $fields_values['Quantity'] = htmlspecialchars($_POST['Quantity']);
  $fields_values['Manufacture'] = htmlspecialchars($_POST['Manufacture']);
  $fields_values['Material'] = htmlspecialchars($_POST['Material']);
  $fields_values['Dimension'] = htmlspecialchars($_POST['Dimension']);
  $fields_values['Weight'] = htmlspecialchars($_POST['Weight']);
  $fields_values['Deadline'] = htmlspecialchars($_POST['Deadline']);
  $fields_values['Popularity'] = htmlspecialchars($_POST['Popularity']);
  unset($_POST['add']);
  unset($_POST['Name']);
  unset($_POST['Catalog_ID']);
  unset($_POST['Description']);
  unset($_POST['Keywords']);
  unset($_POST['Priority']);
  unset($_POST['PublishFrom']);
  unset($_POST['Price']);
  unset($_POST['Quantity']);
  unset($_POST['Manufacture']);
  unset($_POST['Material']);
  unset($_POST['Dimension']);
  unset($_POST['Weight']);
  unset($_POST['Deadline']);
  unset($_POST['Popularity']);
  $db->DataIn(TOYS, $fields_values);
}

//Получение ID поседней добавленной игрушки
$id = $db->IdOfLast(TOYS);

//Возвращение на страницу product.php
header("Location: ".ADMINURL."product.php");

?>