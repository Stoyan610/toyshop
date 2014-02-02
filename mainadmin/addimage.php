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

  //Проверка и получение пути к загружаемому файлу - O.K.
  function ImageSafe() {
    $blacklist = array("php", "phtml", "php3", "php4", "js", "html", "htm", "shtml", "shtm", "xhtml", "xml", "xsml", "xsl", "xsd", "kml", "wsd", "py", "pyw", "rb", "rbw");
    foreach ($blacklist as $ext)
  		if(preg_match("~\.".$ext."\$~i", $_FILES['ImageFile']['name'])) exit;	//Проверка расширения
    $type = $_FILES['ImageFile']['type'];
    $size = $_FILES['ImageFile']['size'];
    if (($type != "image/jpg") && ($type != "image/jpeg")) exit;    //Проверка MIME-type
    if ($size > 2097152) exit;                                      //Проверка размера (< 2 MB)
	  $image_file = PATH.'www/'.LOAD.$_FILES['ImageFile']['name'];
    move_uploaded_file($_FILES['ImageFile']['tmp_name'], $image_file);
  
    return $image_file;
  }

  //Обработчик изображений - большой файл - O.K.
  function ImageHandlerBig($image_file, $new_img_name) {
    $arr_img = getimagesize($image_file);
    $orig_width = $arr_img[0];
    $orig_height = $arr_img[1];
    $rate = (($orig_height / 400) > ($orig_width / 400)) ? $orig_height / 400 : $orig_width / 400;
    $width = $orig_width / $rate;
    $height = $orig_height / $rate;
    $pointer = imagecreatefromjpeg($image_file);
    $new_pointer = imagecreatetruecolor($width, $height);
    imagecopyresampled($new_pointer, $pointer, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
    imagejpeg($new_pointer, PATH.'www/'.PICT.'toy400x400/'.$new_img_name.'.jpg');
    $sizes = array();
    $sizes['width'] = $width;
    $sizes['height'] = $height;
    return $sizes;
  }

  //Обработчик изображений - O.K.
  function ImageHandler($image_file, $width, $height, $new_img_name, $dir_storage='') {
    $arr_img = getimagesize($image_file);
    $orig_width = $arr_img[0];
    $orig_height = $arr_img[1];
    if (($orig_height / $height) < ($orig_width / $width)) {
      $rate = $orig_height / $height;
      $x = ($orig_width - $width * $rate) / 2;
      $y = 0;
      $orig_width = $width * $rate;
    }
    else {
      $rate = $orig_width / $width;
      $y = ($orig_height - $height * $rate) / 2;
      $x = 0;
      $orig_height = $height * $rate;
    }
    $pointer = imagecreatefromjpeg($image_file);
    $new_pointer = imagecreatetruecolor($width, $height);
    imagecopyresampled($new_pointer, $pointer, 0, 0, $x, $y, $width, $height, $orig_width, $orig_height);
    imagejpeg($new_pointer, PATH.'www/'.PICT.$dir_storage.$new_img_name.'.jpg');
  }

//Проверка POST-параметра 
if (isset($_POST['add'])) {
  //Создание файлов картинок и формирование массива полей и значений для добавления в БД
  $fields_values = array();
  $fields_values['ID'] = NULL;
  $fields_values['Kind'] = htmlspecialchars($_POST['Type']);
  
  $image_file = ImageSafe();
  
  $fields_values['BigFile'] = htmlspecialchars($_POST['BigFile']);
  $fields_values['SmallFile'] = htmlspecialchars($_POST['SmallFile']);
  $fields_values['Alt'] = htmlspecialchars($_POST['Alt']);
  if ($fields_values['Kind'] == 'Игрушка') {
    $fields_values['ThumbnailFile'] = htmlspecialchars($_POST['ThumbnailFile']);
    $sizes = ImageHandlerBig($image_file, $fields_values['BigFile']);
    $fields_values['Width'] = $sizes['width'];
    $fields_values['Height'] = $sizes['height'];
    ImageHandler($image_file, 135, 135, $fields_values['SmallFile'], 'toy135x135/');
    ImageHandler($image_file, 70, 70, $fields_values['ThumbnailFile'], 'toy70x70/');
  }
  else {
    $fields_values['ThumbnailFile'] = '';
    ImageHandler($image_file, 228, 171, $fields_values['BigFile'], 'mult228x171/');
    $fields_values['Width'] = 228;
    $fields_values['Height'] = 171;
    ImageHandler($image_file, 114, 86, $fields_values['SmallFile'], 'mult114x86/');
  }
  
  $db->DataIn(IMG, $fields_values);
  
  //Возвращение на страницу image.php
  echo "<h2>Файл загружен! Запись добавлена!</h2>";

echo <<<REDIRECT
<script type='text/javascript'>
	var delay = 2000;
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