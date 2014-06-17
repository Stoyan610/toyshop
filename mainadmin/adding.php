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
      if (!checkphone($cln_fields_values['Phone'])) exit('Телефон не корректен');
      if (!checkmail($cln_fields_values['Mail'])) exit('Почта не корректна');
      //Проверка на наличие данного клиента в базе или добавление в базу
      $cond = "`Name` = '".$cln_fields_values['Name']."' AND `Phone` = '".$cln_fields_values['Phone']."' AND `Mail` = '".$cln_fields_values['Mail']."'";
      $arrid = $db->ReceiveFieldOnManyConditions(CLNTS, 'ID', $cond);
      if ($arrid !== FALSE)     $Client_ID = $arrid[0];
      else {
        $cln_fields_values['Created'] = date('Y-m-d');
        $cln_fields_values['Changed'] = $cln_fields_values['Created'];
        $db->DataIn(CLNTS, $cln_fields_values);
        $Client_ID = $db->IdOfLast(CLNTS);
      }
      //Формирование массива полей и значений для добавления заказа
      $order_fields = array('DeliveryAddress', 'DeliveryTime', 'Info');
      foreach ($order_fields as $val) {
        $order_fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $order_fields_values['Client_ID'] = $Client_ID;
      $order_fields_values['Created'] = date('Y-m-d');
      $order_fields_values['Changed'] = $order_fields_values['Created'];
      $xstat = $db->ReceiveFieldsOnId(STAT, array('Status'), '1');
      $order_fields_values['Status'] = $xstat['Status'];
      
      $binder = 0;
      do {
        $ordernumber = date('nd').$binder.$Client_ID;
        $binder++;
      }
      while ($db->СountDataOnCondition(ORDS, 'Number', '=', $ordernumber) != 0);
      $order_fields_values['Number'] = $ordernumber;
      
      
      $db->DataIn(ORDS, $order_fields_values);
      $Order_ID = $db->IdOfLast(ORDS);
      //Определение и добавление новых записей в корзину
      $count = htmlspecialchars($_POST['count']);
      unset($_POST['count']);
      $bask_fields = array('Order_ID', 'Product_ID', 'Name', 'Price', 'Quantity');
      $bask_values = array();
      $bask_values[0] = $Order_ID;
      for ($i = 0; $i < $count; $i++) {
        if(!empty($_POST['del'.$i])) {
          unset($_POST['del'.$i]);
          unset($_POST['Quantity'.$i]);
          unset($_POST['Product_ID'.$i]);
          unset($_POST['Name'.$i]);
          unset($_POST['Price'.$i]);
        }
        else {
          $bask_values[1] = htmlspecialchars($_POST['Product_ID'.$i]);
          unset($_POST['Product_ID'.$i]);
          $bask_values[2] = htmlspecialchars($_POST['Name'.$i]);
          unset($_POST['Name'.$i]);
          $bask_values[3] = htmlspecialchars($_POST['Price'.$i]);
          unset($_POST['Price'.$i]);
          $bask_values[4] = htmlspecialchars($_POST['Quantity'.$i]);
          unset($_POST['Quantity'.$i]);
          $fields_values = array_combine($bask_fields, $bask_values);
          $db->DataIn(BASKET, $fields_values);
        }
      }
      break;
    }
    
    case 'content': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Category_ID', 'Title', 'Brief', 'Text', 'Revision', 'PublishFrom');
      foreach ($arr as $val) {
        if ($val == 'Text') {
          $fields_values[$val] = htmlspecialchars(addslashes($_POST['editor']));
          unset($_POST['editor']);
        }
        elseif ($val == 'Category_ID') {
          $cat_ids = $db->ReceiveFieldOnCondition(CAT, 'ID', 'Category', '=', htmlspecialchars($_POST[$val]));
          $fields_values[$val] = $cat_ids[0];
        }
        else $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $cat = htmlspecialchars($_POST['new_Cat']);
      unset($_POST['new_Cat']);
      if ($cat != '') {
        $cat_fields['Category'] = $cat;
        $db->DataIn(CAT, $cat_fields);
        $cat_id = $db->IdOfLast(CAT);
        $fields_values['Category_ID'] = $cat_id;
      }
      else {
        if ($fields_values['Revision'])     $db->ChangeFieldOnCondition(INFO, 'Revision', 0, 'Category_ID', '=', $fields_values['Category_ID']);
      }
      $db->DataIn(INFO, $fields_values);
      break;
    }
    
    case 'feedback': {
      //Формирование массива полей и значений для добавления в БД
      $fields_values['Content'] = htmlspecialchars(addslashes($_POST['editor']));
      unset($_POST['editor']);
      $arr = array('Date', 'Name', 'PublishFrom');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->DataIn(REP, $fields_values);
      break;
    }
    
    
    
    default:
      break;
  }


}

//Возвращение на страницу запроса
header("Location: ".ADMINURL.$item.".php");

?>