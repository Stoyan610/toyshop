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


function checkphone($phone) {
  $r = TRUE;
  $smp_phn = "~^\+\d{1,3}\(\d{2,5}\)\d{1,3}(\-\d{2}){2}$~";
  if (preg_match($smp_phn, $phone) == 0)     $r = FALSE;
  return $r;
}
function checkmail($mail) {
  $r = TRUE;
  $smp_eml = "~^([a-z0-9][\w\.-]*[a-z0-9])@((?:[a-z0-9]+[\.-]?)*[a-z0-9]\.[a-z]{2,})$~";
  if (preg_match($smp_eml, $mail) == 0)     $r = FALSE;
  return $r;
}


//Выбор добавляемого объекта
$item = htmlspecialchars($_POST['choice']);
unset($_POST['choice']);
//Проверка POST-параметра 'add'
if (isset($_POST['add'])) {
  unset($_POST['add']);
  $fields_values = array();
  $fields_values['ID'] = NULL;
  switch ($item) {
    case 'product': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Name', 'Catalog_ID', 'Description', 'Keywords', 'Priority', 'PublishFrom', 'Price', 'Quantity', 'Deadline', 'Manufacture', 'Material', 'Dimension', 'Weight', 'Popularity');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->DataIn(TOYS, $fields_values);
      break;
    }
    case 'catalog': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Name', 'Description', 'Keywords', 'Image_ID', 'Priority', 'PublishFrom');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->DataIn(MULTS, $fields_values);
      break;
    }
    case 'order': {
      //Формирование массива полей и значений для добавления клиента в БД
      $arr_cln = array('Name', 'Phone', 'Mail');
      foreach ($arr_cln as $val) {
        $cln_fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      //Проверка телефона и почты
      if (checkphone(!$cln_fields_values['Phone'])) exit('Телефон не корректен');
      if (checkmail(!$cln_fields_values['Mail'])) exit('Почта не корректна');
      $cond = "`Name` = '".$cln_fields_values['Name']."' AND `Phone` = '".$cln_fields_values['Phone']."' AND `Mail` = '".$cln_fields_values['Mail']."'";
      $arrid = $db->ReceiveFieldOnManyConditions(CLNTS, 'ID', $cond);
      if ($arrid !== FALSE)     $Client_ID = $arrid[0];
      else {
        $cln_fields_values['Created'] = date('Y-m-d');
        $cln_fields_values['Changed'] = $cln_fields_values['Created'];
        $db->DataIn(CLNTS, $cln_fields_values);
        $Client_ID = $db->IdOfLast(CLNTS);
      }
      //Формирование массива полей и значений для добавления заказа в БД
      $arr_ord = array('DeliveryAddress', 'DeliveryTime', 'Info');
      foreach ($arr_ord as $val) {
        $ord_fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $ord_fields_values['Client_ID'] = $Client_ID;
      $ord_fields_values['Created'] = date('Y-m-d');
      $ord_fields_values['Changed'] = $ord_fields_values['Created'];
      $db->DataIn(ORDS, $ord_fields_values);
      $Order_ID = $db->IdOfLast(ORDS);
      //Формирование массива полей и значений для добавления корзины в БД
      $basket = htmlspecialchars($_POST['Products']);
      $arr_bask = array('Product_ID', 'Name', 'Price', 'Order_ID', 'Quantity');
      unset($_POST['Products']);
      $arr_prod = explode('^', $basket);
      foreach ($arr_prod as $val) {
        $arr_values = explode('~', $val);
        array_splice($arr_values, 3, 1);
        $bask_fields_values = array_combine($arr_bask, $arr_values);
        $bask_fields_values['Order_ID'] = $Order_ID;
        $db->DataIn(BASKET, $bask_fields_values);
      }
      break;
    }
    
    
    
    default:
      break;
  }


}

//Возвращение на страницу запроса
header("Location: ".ADMINURL.$item.".php");

?>