<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);
//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';
//Проверка - если зашёл без логина, то на выход
if (empty($_SESSION['login'])) {
  header("Location: ".SITEURL);
  exit;
}
//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once PATH.'www/'.MODEL;
//Создание объекта, соответствующего таблице, и подключение класса обработчика соответствующей таблицы
require_once PATH.'mainadmin/class/order_class.php';

//Уничтожить объект, если он был создан на предыдущей страницы
if (isset($creator)) {
  unset($creator);
}

$creator = new Order(DB_USER, DB_PASS);

if (!isset($_GET['act'])) {
  $creator->GetTable();
}
else {
  $act = htmlspecialchars($_GET['act']);
  unset($_GET['act']);
  switch ($act) {
    case 'edit': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->EditItem($id);
    break;
    }
    case 'basket': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->GetBasket($id);
    break;
    }
    case 'del': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->DeleteItem($id);
    break;
    }
    case 'add': {
      $creator->InsertItem();
    break;
    }
    case 'part': {
      if (isset($_GET['Client_ID'])) {
        $Client_ID = htmlspecialchars($_GET['Client_ID']);
        unset($_GET['Client_ID']);
        $creator->GetTableOnField('Client_ID', $Client_ID);
      }
      elseif (isset($_GET['Number'])) {
        $num = htmlspecialchars($_GET['Number']);
        unset($_GET['Number']);
        $num = preg_replace('~\D+~', '', $num);
        $creator->GetTableOnField('Number', $num);
      }
      if (isset($_GET['Status'])) {
        $Status = htmlspecialchars($_GET['Status']);
        unset($_GET['Status']);
        $creator->GetTableOnField('Status', $Status);
      }
    break;
    }
    case 'find_client': {
      if (isset($_GET['Name'])) {
        $name = htmlspecialchars($_GET['Name']);
        unset($_GET['Name']);
        $name = "%".$name."%";
        $creator->GetTableOnCond('Name', $name);
      }
      if (isset($_GET['Phone'])) {
        $phone = htmlspecialchars($_GET['Phone']);
        unset($_GET['Phone']);
        $phone = preg_replace('~\D+~', '', $phone);
        $phone = chunk_split($phone, 1, "%");
        $phone = "%".$phone;
        $creator->GetTableOnCond('Phone', $phone);
      }
    break;
    }

    default: {
      exit ('Это сделать невозможно');
    }
    
  }
}
 
?>