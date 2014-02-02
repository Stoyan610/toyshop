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
  $fields_values = array();
  $fields_values['Alt'] = htmlspecialchars($_POST['Alt']);
  $db->ChangeDataOnId(IMG, $fields_values, $id);
}

//Возвращение на страницу image.php
header("Location: ".ADMINURL."image.php");
?>