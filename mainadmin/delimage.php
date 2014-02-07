<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';
//Проверка - если зашёл без логина, то на выход
$goback = $_SERVER['HTTP_REFERER'];
if (empty($_SESSION['login'])) {
  header("Location: ".$goback);
  exit;
}
//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once PATH.'www/'.MODEL;
$db = new DbRover($_SESSION['login'], $_SESSION['password']);

//Проверка POST-параметра 
if (isset($_POST['delete'])) {
  //Определение ID строки для удаления
  $iddel = htmlspecialchars($_POST['del']);
  unset($_POST['delete']);
  unset($_POST['del']);
  
  //Удаление файла изображения
  function ImageDeleter ($image_file, $dir_storage='') {
    $fullfile = PATH.'www/'.PICT.$dir_storage.$image_file.'.jpg';
    if (file_exists($fullfile))     unlink($fullfile);
  }
  
  //Удаление соответствующих файлов изображений
  $field_list = array('Kind', 'BigFile', 'SmallFile', 'ThumbnailFile');
  $imginfo = $db->ReceiveFieldsOnId(IMG, $field_list, $iddel);
  $kind = $imginfo['Kind'];
  if ($kind == 'Игрушка') {
    $big = $imginfo['BigFile'];
    ImageDeleter($big, 'toy400x400/');
    $small = $imginfo['SmallFile'];
    ImageDeleter($small, 'toy135x135/');
    $nail = $imginfo['ThumbnailFile'];
    ImageDeleter($small, 'toy70x70/');
  }
  else {
    $big = $imginfo['BigFile'];
    ImageDeleter($big, 'mult228x171/');
    $small = $imginfo['SmallFile'];
    ImageDeleter($small, 'mult114x86/');
  }
  
  $db->DataOffOnId(IMG, $iddel);
  echo "<h2>Файл удалён! Запись удалена!</h2>";
  
  //Возвращение на страницу image.php

echo <<<REDIRECT
<script type='text/javascript'>
  var delay = 3000;
  setTimeout("document.location.href='image.php'", delay);
</script>
REDIRECT;

}

//Возвращение на страницу image.php

echo <<<REDIRECT
<script type='text/javascript'>
	setTimeout("document.location.href='image.php'", 0);
</script>
REDIRECT;

?>