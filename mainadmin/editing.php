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
  if (($mail != "") && (preg_match($smp_eml, $mail) == 0))     $r = FALSE;
  return $r;
}

//Выбор редактируемого объекта
$item = htmlspecialchars($_POST['choice']);
unset($_POST['choice']);

//Проверка POST-параметра 'edit'
if (isset($_POST['edit'])) {
  unset($_POST['edit']);
  $fields_values = array();
  $id = htmlspecialchars($_POST['ID']);
  unset($_POST['ID']);
  switch ($item) {
    case 'product': {
      //Формирование массива полей и значений для изменения записи
      $arr = array('Name', 'Catalog_ID', 'Description', 'Keywords', 'Priority', 'PublishFrom', 'Price', 'Quantity', 'Deadline', 'Manufacture', 'Material', 'Dimension', 'Weight', 'Popularity');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->ChangeDataOnId(TOYS, $fields_values, $id);
      break;
    }
    case 'catalog': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Name', 'Description', 'Keywords', 'Priority', 'PublishFrom');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->ChangeDataOnId(MULTS, $fields_values, $id);
      break;
    }
    case 'image': {
      //Формирование массива полей и значений для добавления в БД
      $fields_values['Alt'] = htmlspecialchars($_POST['Alt']);
      unset($_POST['Alt']);
      $db->ChangeDataOnId(IMG, $fields_values, $id);
      break;
    }
    case 'client': {
      //Формирование массива полей и значений для добавления в БД
      $arr = array('Name', 'Phone', 'Mail');
      foreach ($arr as $val) {
        $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
        }
      //Проверка телефона и почты
      if (!checkphone($fields_values['Phone'])) exit('Телефон не корректен');
      if (!checkmail($fields_values['Mail'])) exit('Почта не корректна');
      $fields_values['Changed'] = date('Y-m-d');
      $db->ChangeDataOnId(CLNTS, $fields_values, $id);
      break;
    }
    
    case 'order': {
      //Формирование массива для изменения записи заказа
      $order_fields = array('DeliveryAddress', 'DeliveryTime', 'Info');
      $order_values[] = htmlspecialchars($_POST['DAddress']);
      unset($_POST['DAddress']);
      $order_values[] = htmlspecialchars($_POST['DTime']);
      unset($_POST['DTime']);
      $order_values[] = htmlspecialchars($_POST['Info']);
      unset($_POST['Info']);
      $order_fields_values = array_combine($order_fields, $order_values);
      $order_fields_values['Changed'] = date('Y-m-d');
      
      //Определение ID записи для удаления и удаление её из корзины или изменение записи
      $count = htmlspecialchars($_POST['count']);
      unset($_POST['count']);
      $num = htmlspecialchars($_POST['inicount']);
      unset($_POST['inicount']);
      for ($i = 0; $i < $num; $i++) {
        if(!empty($_POST['del'.$i])) {
          unset($_POST['del'.$i]);
          $baskQnt = 0;
          unset($_POST['Quantity'.$i]);
          $baskID = htmlspecialchars($_POST['baskID'.$i]);
          unset($_POST['baskID'.$i]);
          $db->DataOffOnId(BASKET, $baskID);
        }
        else {
          $baskID = htmlspecialchars($_POST['baskID'.$i]);
          unset($_POST['baskID'.$i]);
          $baskQnt = htmlspecialchars($_POST['Quantity'.$i]);
          unset($_POST['Quantity'.$i]);
          $db->ChangeDataOnId(BASKET, array('Quantity' => $baskQnt), $baskID);
        }
      }
      //Определение и добавление новых записей в корзину
      $bask_fields = array('Order_ID', 'Product_ID', 'Name', 'Price', 'Quantity');
      $bask_values = array();
      $bask_values[0] = htmlspecialchars($_POST['ordID']);
      unset($_POST['ordID']);
      for ($i = $num; $i < $count; $i++) {
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
      $db->ChangeDataOnId(ORDS, $order_fields_values, $bask_values[0]);
      break;
    }
    
    case 'content': {
      //Формирование массива полей и значений для изменения записи в БД
      $arr = array('Category', 'Title', 'Brief', 'Text', 'Revision', 'PublishFrom');
      foreach ($arr as $val) {
        if ($val == 'Text') {
          $fields_values[$val] = htmlspecialchars($_POST['editor']);
          unset($_POST['editor']);
        }
        else $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $cat = htmlspecialchars($_POST['new_Cat']);
      unset($_POST['new_Cat']);
      if ($cat != '') $fields_values['Category'] = $cat;
      else {
        if ($fields_values['Revision'])     $db->ChangeFieldOnCondition(INFO, 'Revision', 0, 'Category', '=', $fields_values['Category']);
      }
      $db->ChangeDataOnId(INFO, $fields_values, $id);
      break;
    }
    
    case 'feedback': {
      //Формирование массива полей и значений для изменения записи в БД
      $arr = array('Date', 'Name', 'Content', 'PublishFrom');
      foreach ($arr as $val) {
        if ($val == 'Content') {
          $fields_values[$val] = htmlspecialchars($_POST['editor']);
          unset($_POST['editor']);
        }
        else $fields_values[$val] = htmlspecialchars($_POST[$val]);
        unset($_POST[$val]);
      }
      $db->ChangeDataOnId(REP, $fields_values, $id);
      break;
    }
    
    
    default:
      break;
  }
}

//Возвращение на страницу запроса
header("Location: ".ADMINURL.$item.".php");

?>