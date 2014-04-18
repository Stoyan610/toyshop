<?php
session_start();

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Проверка кода страницы. Обработка GET-данных, и преобразование html-кодов в html-сущности, и присваивание странице название файла страницы
$page = $_SESSION['page'];

//Подключение класса обработчика шаблонов
require_once CLASSES.$page;
//Уничтожить объект, если он был создан для предыдущей страницы
if (isset($pager)) unset($pager);

//Создание объекта, соответствующего затребованной странице
switch ($page) {
  case 'home.php': {
    $pager = new Home($n_item);
    break;
  }
  case 'catalogue.php': {
    $pager = new Catalogue($n_item);
    break;
  }
  case 'catalogue-next.php': {
    $pager = new CatalogueFilm($n_item);
    break;
  }
  
  case 'toyitem.php': {
    $pager = new ToyItem($n_item);
    break;
  }
  
  case 'order.php': {
    $pager = new Order($n_item);
    break;
  }
  
  case 'delivery.php': {
    $pager = new Delivery($n_item);
    break;
  }
  
  case 'contacts.php': {
    $pager = new Contact($n_item);
    break;
  }
  
  case 'comments.php': {
    $pager = new Comment($n_item);
    break;
  }
  
  case 'search.php': {
    $pager = new Search($n_item);
    break;
  }
  
  
  default:
    break;
}

echo $pager->CreatePage();
    



?>