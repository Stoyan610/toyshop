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
if (isset($_POST['changing'])) {
  //Определение ID строки для удаления
  $img_id = htmlspecialchars($_POST['img']);
  $id = htmlspecialchars($_POST['mult_id']);
  $db->ChangeFieldOnId(MULTS, 'Image_ID', $img_id, $id);
}

//Возвращение на страницу catalog.php
header("Location: ".ADMINURL."catalog.php");

?>