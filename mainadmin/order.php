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

$creator = new Order($_SESSION['login'], $_SESSION['password']);

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
    break;
    }

    default: {
      exit ('Это сделать невозможно');
    }
    
  }
}
 
?>eak;
    }

    default: {
      exit ('Это сделать невозможно');
    }
    
  }
}
 
?>
  
</body>
</html>->GetTableOnField('Number', $num);
      }
    break;
    }

    default: {
      exit ('Это сделать невозможно');
    }
    
  }
}
 
?>
  
</body>
</html>