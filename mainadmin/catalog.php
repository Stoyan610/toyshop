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
require_once PATH.'mainadmin/class/catalog_class.php';

//Уничтожить объект, если он был создан на предыдущей страницы
if (isset($creator)) {
  unset($creator);
}

$creator = new Catalog(DB_USER, DB_PASS);

if (!isset($_GET['act'])) {
  $creator->GetTable();
}
else {
  $act = htmlspecialchars($_GET['act']);
  unset($_GET['act']);
  switch ($act) {
    case 'changeimg': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->ChangeImage($id);
    break;
    }
    case 'edit': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->EditItem($id);
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
      $Image_ID = htmlspecialchars($_GET['Image_ID']);
      unset($_GET['Image_ID']);
      $creator->GetTableOnImage($Image_ID);
    break;
    }
    
    
    default: {
      exit ('Это сделать невозможно');
    }
  }
}
 
?>