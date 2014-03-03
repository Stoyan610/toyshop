<?php
session_start();

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Проверка, сколько товаров в корзине
if (!isset($_SESSION['items'])) $_SESSION['items'] = 0;
$n_item = $_SESSION['items'];

//Проверка кода страницы. Обработка GET-данных, и преобразование html-кодов в html-сущности, и присваивание странице название файла страницы
if (!isset($_GET['page'])) $page = 'home.php';
else {
  $page = htmlspecialchars($_GET['page']).'php';
  unset($_GET['page']);
}

//Подключение класса обработчика шаблонов
require_once CLASSES.$page;
//Уничтожить объект, если он был создан для предыдущей страницы
if (isset($pager)) unset($pager);

//Создание объекта, соответствующего затребованной странице
switch ($page) {
  case 'home.php': {
    $pager = new Home($n_item);
    
        //Просто проверка
        echo $pager->CreatePage();
        echo '<br />';
        
        
    
    break;
  }
  case 'cartoon.php': {
    $pager = new Cartoon($n_item);
    break;
  }
  
  //Далее по списку классов страниц
  
  default:
    break;
}




?>