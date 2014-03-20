<?php
session_start();

//Подключение модели - обработчика базы данных
define('ACCESS', TRUE);
require_once 'config.php';
require_once MODEL;

$db = new DbRover(DB_USER, DB_PASS);

if (isset($_POST['cancel_x'])) {
  unset($_POST['cancel_x']);
  unset($_POST['cancel_y']);
  if (isset($_SESSION['orderid'])) {
    $Order_ID = htmlspecialchars($_SESSION['orderid']);
    unset($_SESSION['orderid']);
  }
  elseif (isset($_SESSION['thisorderid'])) {
    $Order_ID = htmlspecialchars($_SESSION['thisorderid']);
    unset($_SESSION['thisorderid']);
  }
  else    exit();
  $db->DataOffOnId (ORDS, $Order_ID);
  $db->DataOffOnCondition(BASKET, 'Order_ID', '=', $Order_ID);
  
  $_SESSION['items'] = 0;
  foreach ($_SESSION as $key => $val) {
    if (preg_match('~^\d+~', $key)) unset($_SESSION[$key]);
  }
  
}


// ? ? ? ? ? ? ? ? ? ? ? ? ? ? ?
//Отправка емейла администратору с информацией об аннулированном заказе
// ? ? ? ? ? ? ? ? ? ? ? ? ? ? ?



//Возвращение на страницу заказа
header("Location: index.php?page=order");

?>