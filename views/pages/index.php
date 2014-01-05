<?php
session_start();
if (!isset($_SESSION['items'])) $_SESSION['items'] = 0;
$n_item = $_SESSION['items'];
echo $n_item;
echo '<br />';
echo '№ 4 - Создатели видов страниц подключены<br />';

//Проверка кода страницы, Обработка GET-данных, и преобразование html коды в html-сущности, и присваивание странице название файла страницы
if (!$_GET['page']) $page = 'home.php';
else {
  $page = htmlspecialchars($_GET['page']).'php';
  unset($_GET['page']);
}

echo '№ 5 - '.$page;
echo '<br />';

//Подключение класса обработчика шаблонов
require_once CLASSES.$page;
//Создание объекта, соответствующего затребованной странице
switch ($page) {
  case 'home.php': {
    $pager = new Home($n_item);
    echo '<br />';
    if (is_object($pager))      echo '№ 8 - !!!! Object has been created !!!!';
    else      echo '№ 8 - !!!! Object has not been created !!!!';
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


//Просто проверка
echo '№ 9 - '.$pager->GetSubst()[0];
echo '<br />';
echo '№ 10 - '.$pager->GetSubst()[1];
echo '<br />';


?>