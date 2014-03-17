<?php
session_start();

//Подключение модели - обработчика базы данных
define('ACCESS', TRUE);
require_once 'config.php';
require_once MODEL;

$db = new DbRover(DB_USER, DB_PASS);

function checkphone($phone) {
  $r = TRUE;
  $smp_phn = "~^\+\d{1,3}\(\d{2,5}\)\d{1,3}(\-\d{2}){2}$~";
  if (preg_match($smp_phn, $phone) == 0)     $r = FALSE;
  return $r;
}
function checkmail($mail) {
  $r = TRUE;
  $smp_eml = "~^([a-z0-9][\w\.-]*[a-z0-9])@((?:[a-z0-9]+[\.-]?)*[a-z0-9]\.[a-z]{2,})$~";
  if (($mail != "") && (preg_match($smp_eml, $mail) == 0))     $r = FALSE;
  return $r;
}

//Формирование массива полей и значений для добавления клиента в БД
$arr_cln = array();
$arr_cln['Name'] = htmlspecialchars($_POST['name']);
unset($_POST['name']);
$arr_cln['Phone'] = htmlspecialchars($_POST['phone']);
unset($_POST['phone']);
$arr_cln['Mail'] = htmlspecialchars($_POST['email']);
unset($_POST['email']);
//Проверка телефона и почты
if (!checkphone($arr_cln['Phone'])) exit('Телефон не корректен');
if (!checkmail($arr_cln['Mail'])) exit('Почта не корректна');
$cond = "`Name` = '".$arr_cln['Name']."' AND `Phone` = '".$arr_cln['Phone']."' AND `Mail` = '".$arr_cln['Mail']."'";
$arrid = $db->ReceiveFieldOnManyConditions(CLNTS, 'ID', $cond);
if ($arrid !== FALSE)     $Client_ID = $arrid[0];
else {
  $arr_cln['Created'] = date('Y-m-d');
  $arr_cln['Changed'] = $arr_cln['Created'];
  $db->DataIn(CLNTS, $arr_cln);
  $Client_ID = $db->IdOfLast(CLNTS);
}

//Формирование массива полей и значений для добавления заказа в БД
$arr_ord = array();
$arr_ord['Client_ID'] = $Client_ID;
$arr_ord['Created'] = date('Y-m-d');
$arr_ord['Changed'] = $arr_ord['Created'];
$arr_ord['DeliveryAddress'] = htmlspecialchars($_POST['addr']);
unset($_POST['addr']);
$arr_ord['DeliveryTime'] = htmlspecialchars($_POST['time']);
unset($_POST['time']);
$arr_ord['Info'] = htmlspecialchars($_POST['extra']);
unset($_POST['extra']);
$db->DataIn(ORDS, $arr_ord);
$Order_ID = $db->IdOfLast(ORDS);

//Формирование массива полей и значений для добавления корзины в БД
$arr_bask = array();
$arr_bask['Order_ID'] = $Order_ID;
$items = 0;
foreach ($_POST as $key => $val) {
  $arr_bask['Product_ID'] = (integer)$key;
  $arr_bask['Quantity'] = htmlspecialchars($val);
  unset($_POST[$key]);
  $items += $arr_bask['Quantity'];
  $arr1 = $db->ReceiveFewFieldsOnCondition(TOYS, array('Name', 'Price'), 'ID', '=', $arr_bask['Product_ID']);
  $arr_bask['Name'] = $arr1[0]['Name'];
  $arr_bask['Price'] = $arr1[0]['Price'];
  $db->DataIn(BASKET, $arr_bask);
  
  $_SESSION[$arr_bask['Product_ID'].'toyid'] = $arr_bask['Product_ID'];
  $_SESSION[$arr_bask['Product_ID'].'toyname'] = $arr_bask['Name'];
  $_SESSION[$arr_bask['Product_ID'].'toyprice'] = $arr_bask['Price'];
  $_SESSION[$arr_bask['Product_ID'].'toyitems'] = $arr_bask['Quantity'];
}
$_SESSION['items'] = $items;
$_SESSION['orderid'] = $Order_ID;


// ? ? ? ? ? ? ? ? ? ? ? ? ? ? ?
//Отправка емейла администратору с информацией о данном заказе
// ? ? ? ? ? ? ? ? ? ? ? ? ? ? ?



//Возвращение на страницу заказа
header("Location: index.php?page=order");

?>