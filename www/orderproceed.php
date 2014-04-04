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

$arr_cln = array();
$arr_ord = array();
$arr_bask = array();
//Формирование массива полей и значений для добавления клиента в БД
$arr_cln['Name'] = htmlspecialchars($_POST['name']);
unset($_POST['name']);
$arr_cln['Phone'] = htmlspecialchars($_POST['phone']);
unset($_POST['phone']);
$arr_cln['Mail'] = htmlspecialchars($_POST['email']);
unset($_POST['email']);
//Проверка телефона и почты
if (!checkphone($arr_cln['Phone'])) exit('Телефон не корректен');
if (!checkmail($arr_cln['Mail'])) exit('Почта не корректна');
$arr_ord['DeliveryAddress'] = htmlspecialchars($_POST['addr']);
unset($_POST['addr']);
$_SESSION['DeliveryCost'] = htmlspecialchars($_POST['city']);
unset($_POST['city']);
$arr_ord['DeliveryTime'] = htmlspecialchars($_POST['time']);
unset($_POST['time']);
$arr_ord['Info'] = htmlspecialchars($_POST['extra']);
unset($_POST['extra']);

//Проверка - был ли уже оформлен заказ
if (!isset($_SESSION['thisorderid'])) {
  $flag = TRUE;
  $flag2 = TRUE;
}
else {
  $flag = FALSE;
  $flag2 = FALSE;
  if (($_SESSION['Name'] != $arr_cln['Name']) || ($_SESSION['Phone'] != $arr_cln['Phone']) || ($_SESSION['Mail'] != $arr_cln['Mail']))    $flag = TRUE;
  if (($_SESSION['DeliveryAddress'] != $arr_ord['DeliveryAddress']) || ($_SESSION['DeliveryTime'] != $arr_ord['DeliveryTime']) || ($_SESSION['Info'] != $arr_ord['Info']))    $flag2 = TRUE;
}

if ($flag) {
  $_SESSION['Name'] = $arr_cln['Name'];
  $_SESSION['Phone'] = $arr_cln['Phone'];
  $_SESSION['Mail'] = $arr_cln['Mail'];
  $cond = "`Name` = '".$arr_cln['Name']."' AND `Phone` = '".$arr_cln['Phone']."' AND `Mail` = '".$arr_cln['Mail']."'";
  $arrid = $db->ReceiveFieldOnManyConditions(CLNTS, 'ID', $cond);
  if ($arrid !== FALSE)     $Client_ID = $arrid[0];
  else {
    $arr_cln['Created'] = date('Y-m-d');
    $arr_cln['Changed'] = $arr_cln['Created'];
    $db->DataIn(CLNTS, $arr_cln);
    $Client_ID = $db->IdOfLast(CLNTS);
    $_SESSION['Client_ID'] = $Client_ID;
  }
}
else {
  $Client_ID = htmlspecialchars($_SESSION['Client_ID']);
}

//Формирование массива полей и значений для добавления заказа в БД
$arr_ord['Client_ID'] = $Client_ID;

if ($flag) {
  $arr_ord['Created'] = date('Y-m-d');
  $arr_ord['Changed'] = $arr_ord['Created'];
  
  $binder = 0;
  do {
    $ordernumber = date('nd').$binder.$Client_ID;
    $binder++;
  }
  while ($db->СountDataOnCondition(ORDS, 'Number', '=', $ordernumber) != 0);
  $arr_ord['Number'] = $ordernumber;
  
  $_SESSION['Number'] = $arr_ord['Number'];
  $_SESSION['DeliveryAddress'] = $arr_ord['DeliveryAddress'];
  $_SESSION['DeliveryTime'] = $arr_ord['DeliveryTime'];
  $_SESSION['Info'] = $arr_ord['Info'];
  $db->DataIn(ORDS, $arr_ord);
  $Order_ID = $db->IdOfLast(ORDS);
  //Аннулирование старого заказа
  if (isset($_SESSION['thisorderid'])) {
    $db->DataOffOnId (ORDS, htmlspecialchars($_SESSION['thisorderid']));
    $db->DataOffOnCondition(BASKET, 'Order_ID', '=', htmlspecialchars($_SESSION['thisorderid']));
  }
}
elseif ($flag2) {
  $_SESSION['DeliveryAddress'] = $arr_ord['DeliveryAddress'];
  $_SESSION['DeliveryTime'] = $arr_ord['DeliveryTime'];
  $_SESSION['Info'] = $arr_ord['Info'];
  $Order_ID = htmlspecialchars($_SESSION['thisorderid']);
  $arr_ord['Changed'] = date('Y-m-d');
  $db->ChangeDataOnId(ORDS, $arr_ord, $Order_ID);
}
else {
  $Order_ID = htmlspecialchars($_SESSION['thisorderid']);
}

//Очистка корзины, относящейся к заказу, т.к. будет всё добавляться по новой
$db->DataOffOnCondition(BASKET, 'Order_ID', '=', $Order_ID);

//Формирование массива полей и значений для добавления корзины в БД и в текст емейла администратору
$message = 'Имя клиента: '.$_SESSION['Name'].'\nТелефон: '.$_SESSION['Phone'].'\nЭлектронный адрес: '.$_SESSION['Mail'].'\nЗаказано:\n';
$arr_bask['Order_ID'] = $Order_ID;
$items = 0;
$count = 0;
$amount = 0;
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
  
  $amount += $arr_bask['Price']*$arr_bask['Quantity'];
  
  $message .= $count.'-й товар: '.$arr_bask['Name'].'\nв количестве '.$arr_bask['Quantity'].'штук\n';
  }
$_SESSION['items'] = $items;
$_COOKIE['items'] = htmlspecialchars($_SESSION['items']);
$_SESSION['orderid'] = $Order_ID;
unset($_SESSION['thisorderid']);

$amount += $_SESSION['DeliveryCost'];
$message .= 'Всего '.$items.' игрушек\nНа сумму с доставкой '.$amount.' руб\nАдрес доставки:\n';


// ? ? ? ? ? ? ? ? ? ? ? ? ? ? ?
//Отправка емейла администратору с информацией о данном заказе
function add_eol($text) {
  $mes = '';
  do {
    $str_rep = mb_substr($text, 0, 70, 'utf-8');
    $mes .= $str_rep.'\n';
    $text = str_replace($str_rep, '', $text);
    $lng = mb_strlen($text, 'utf-8');
  }
  while ($lng != 0);
  return $mes;
}

$to_whom = ADMIN_EMAIL;
$from = SEND_EMAIL;
$add_headers = "From: ".$from."\r\nReply_To: nowhere@nomail.non\r\nConent-type: text/plain; charset=utf-8\r\n";
$subject = "Новый заказ № ".$_SESSION['Number'];

$message .= add_eol($_SESSION['DeliveryAddress']);
$message .= 'Время доставки: '.$_SESSION['DeliveryTime'].'\n';
$message .= 'Прочая информация:\n'.add_eol($_SESSION['Info']);

if (!mail($to_whom, $subject, $message, $add_headers))      exit ("не O.K.");
// ? ? ? ? ? ? ? ? ? ? ? ? ? ? ?


//Возвращение на страницу заказа
header("Location: index.php?page=order");

?>