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
require_once PATH.'mainadmin/class/image_class.php';

//Уничтожить объект, если он был создан на предыдущей страницы
if (isset($creator)) {
  unset($creator);
}

$creator = new Images($_SESSION['login'], $_SESSION['password']);

if (!isset($_GET['act'])) {
  $creator->GetTable();
}
else {
  $act = htmlspecialchars($_GET['act']);
  unset($_GET['act']);
  switch ($act) {
    case 'get_cat': {
      $kind = 'Мульт';
      $creator->GetTableOnKind($kind);
    break;
    }
    case 'get_prod': {
      $kind = 'Игр';
      $creator->GetTableOnKind($kind);
    break;
    }
    case 'get_all': {
      $kind = NULL;
      $creator->GetTableOnKind($kind);
    break;
    }
    case 'add': {
      $creator->InsertItem();
    break;
    }
    case 'del': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->DeleteItem($id);
    break;
    }
    case 'edit': {
      $id = htmlspecialchars($_GET['id']);
      unset($_GET['id']);
      $creator->EditItem($id);
    break;
    }
    
    
    default: {
      exit ('Это сделать невозможно');
    }
  }
}


?>
  
</body>
</html>  default: {
      exit ('Это сделать невозможно');
    }
  }
}


?>
  
</body>
</html>