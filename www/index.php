<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);

mb_internal_encoding(utf8); 
mb_regex_encoding(utf8);

//Проверка, сколько товаров в корзине
if ($_COOKIE['recalc'] == 1) {
  setcookie('recalc', 1, time() - 1);
  
  $_SESSION['items'] = htmlspecialchars($_COOKIE['items']);
  setcookie('items', 0, time() - 1);
  
  foreach ($_SESSION as $key => $val) {
    if (preg_match('~\dtoy~', $key)) unset($_SESSION[$key]);
  }

  $maxn = htmlspecialchars($_COOKIE['maxn']);
  for ($i = 0; $i < $maxn; $i++) {
    $toyid = htmlspecialchars($_COOKIE[$i.'toyid']);
    setcookie($i.'toyid', 0, time() - 1);
    $_SESSION[$toyid.'toyid'] = $toyid;
    $_SESSION[$toyid.'toyname'] = htmlspecialchars($_COOKIE[$toyid.'toyname']);
    setcookie($toyid.'toyname', 0, time() - 1);
    $_SESSION[$toyid.'toyprice'] = htmlspecialchars($_COOKIE[$toyid.'toyprice']);
    setcookie($toyid.'toyprice', 0, time() - 1);
    $_SESSION[$toyid.'toyitems'] = htmlspecialchars($_COOKIE[$toyid.'toyitems']);
    setcookie($toyid.'toyitems', 0, time() - 1);
  }
}

if (!isset($_SESSION['items'])) {
  $_SESSION['items'] = 0;
}
if (isset($_POST['add_x']) && ($_POST['page'] == 'toyitem')) {
  $_SESSION['items']++;
  $toyid = htmlspecialchars($_POST['toyid']);
  unset($_POST['toyid']);
  $_SESSION[$toyid.'toyid'] = $toyid;
  
//$_SESSION[$toyid.'add'] = TRUE;
  
  $_SESSION[$toyid.'toyname'] = htmlspecialchars($_POST['toyname']);
  unset($_POST['toyname']);
  $_SESSION[$toyid.'toyprice'] = htmlspecialchars($_POST['toyprice']);
  unset($_POST['toyprice']);
  if (!isset($_SESSION[$toyid.'toyitems'])) {
    $_SESSION[$toyid.'toyitems'] = 1;
  }
  else {
    $_SESSION[$toyid.'toyitems']++;
  }
  setcookie($toyid.'onstock', htmlspecialchars($_SESSION[$toyid.'onstock']));
}

$n_item = $_SESSION['items'];
if ($n_item == 0) $n_item = 'нет';

//Проверка кода страницы. Обработка GET-данных, и преобразование html-кодов в html-сущности, и присваивание странице название файла страницы
if (!isset($_GET['page']) && !isset($_POST['page'])) $page = 'home.php';
elseif (isset($_GET['page'])) {
  $page = htmlspecialchars($_GET['page']).'.php';
  unset($_GET['page']);
}
elseif (isset($_POST['page'])) {
  $page = htmlspecialchars($_POST['page']).'.php';
  unset($_POST['page']);
  }

$_SESSION['page'] = $page;

if (isset($_GET['mult'])) {
  $mult = htmlspecialchars($_GET['mult']);
  unset($_GET['mult']);
  $_SESSION['mult'] = $mult;
}

if (isset($_GET['toy'])) {
  $toy = htmlspecialchars($_GET['toy']);
  unset($_GET['toy']);
  $_SESSION['toy'] = $toy;
}

//Подключение конфигурационного файла
require_once 'config_address.php';
require_once 'config.php';
//Подключение контроллера
require_once CONTROLLER;

?>