<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);

mb_internal_encoding(utf8); 
mb_regex_encoding(utf8);

if (!isset($_SESSION['items'])) {
  $_SESSION['items'] = 0;
}
if (isset($_POST['add_x']) && ($_POST['page'] == 'toyitem')) {
  $_SESSION['items']++;
  $toyid = htmlspecialchars($_POST['toyid']);
  unset($_POST['toyid']);
  $_SESSION[$toyid.'toyid'] = $toyid;
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

if (isset($_GET['comment_x'])) {
  $com = htmlspecialchars($_GET['comment_x']);
  unset($_GET['comment_x']);
  $_SESSION['comment'] = $com;
}

if (isset($_GET['phrase'])) {
  $search_phrase = htmlspecialchars($_GET['phrase']);
  unset($_GET['phrase']);
  $_SESSION['search_phrase'] = $search_phrase;
}

//Подключение конфигурационного файла
require_once 'config_address.php';
require_once 'config.php';
//Подключение контроллера
require_once CONTROLLER;

?>