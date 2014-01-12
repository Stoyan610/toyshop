<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);
//Подключение конфигурационного файла
require_once '../www/config.php';
//Проверка - если зашёл не через форму, то на выход
if ((empty($_SESSION['login']))&&(empty($_POST['send']))) {
  $goback = $_SERVER['HTTP_REFERER'];
  header("Location: ".$goback);
  exit;
}

if (isset($_POST['send'])) {
  $_SESSION['login'] = htmlspecialchars($_POST['log']);
  $_SESSION['password'] = htmlspecialchars($_POST['pass']);
  unset($_POST['send']);
  unset($_POST['log']);
  unset($_POST['pass']);
}
//Проверка - если не устанавливается подключение, то на выход
$admin_connect = @new mysqli(HOST, $_SESSION['login'], $_SESSION['password'], DB);
if (mysqli_connect_errno()) {
  unset($_SESSION['login']);
  unset($_SESSION['password']);
  $goback = $_SERVER['HTTP_REFERER'];
  header("Location: ".$goback);
  exit;
}
//Отключить соединение - будет подключаться через объект модели
if ($admin_connect) $admin_connect->close();
//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once PATH.'www/'.MODEL;
//Подключение html файла
require_once 'half_index.php';

//Проверка имени таблицы, Обработка GET-данных, и присваивание странице название рабочей таблицы
$table = empty($_GET['table']) ? '' : htmlspecialchars($_GET['table']);
unset($_GET['table']);

echo 'Вроде всё работает. Имя таблицы - '.$table;
echo '<br />';

//Уничтожить объект, если он был создан для предыдущей страницы
if (isset($creator)) {
  unset($creator);
  echo 'Объект был - уничтожен';
}
else  echo 'Объекта не было';
echo '<br />'; 

//Создание объекта, соответствующего таблице, и подключение класса обработчика соответствующей таблицы
switch ($table) {
  case 'A_CATALOG': {
    require_once PATH.'mainadmin/class/catalog.php';
    $creator = new Catalog($_SESSION['login'], $_SESSION['password']);
    break;
  }
  
  default: {
    exit ('Нет такой таблтцы');
    break;
  }
}
  
  $creator->GetTable();
  
  $creator->ImageHandler(PATH.'mainadmin/Ape_meditation.jpg', 100, 100, 'new_Ape');
  
  
/*

  case 'A_PRODUCT': {
    require_once 'class/product.php';
    $creator = new Product();
    break;
  }
  //Далее по списку nf,kbw

 


*/

?>
  
</body>
</html>