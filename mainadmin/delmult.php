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
if (isset($_POST['delete'])) {
  //Определение ID строки для удаления
  $iddel = htmlspecialchars($_POST['del']);
  unset($_POST['delete']);
  unset($_POST['del']);
  $db->DataOffOnId(MULTS, $iddel);
  $id_img = $db->ReceiveFieldOnCondition(MULTS, 'Image_ID', 'ID', '=', $iddel);
  if ($id_img > 0) header("Location: ".ADMINURL."image.php?act=del&id=".$id_img);
}

//Возвращение на страницу catalog.php
header("Location: ".ADMINURL."catalog.php");

?>