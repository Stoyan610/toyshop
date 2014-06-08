<?php
session_start();
//Запрет прямого обращения
define('ACCESS', TRUE);//Подключение конфигурационного файла
require_once 'config_address.php';
require_once PATH.'www/config.php';
//Проверка - если зашёл без логина, то на выход
if (empty($_SESSION['login'])) {
  header("Location: ".SITEURL);
  exit;
}
//Подключение модели - обработчика базы данных, и создание его объекта (с подключением к БД)
require_once PATH.'www/'.MODEL;
$db = new DbRover(DB_USER, DB_PASS);

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
  $field_list = array('Kind', 'FileName');
  $imginfo = $db->ReceiveFieldsOnId(IMG, $field_list, $iddel);
  $kind = $imginfo['Kind'];
  if ($kind == 'Игрушка') {
    $filename = $imginfo['FileName'];
    ImageDeleter($filename, 'toy400x400/');
    ImageDeleter($filename, 'toy135x135/');
    ImageDeleter($filename, 'toy70x70/');
  }
  else {
    $filename = $imginfo['FileName'];
    ImageDeleter($filename, 'mult228x171/');
    ImageDeleter($filename, 'mult114x86/');
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

?>