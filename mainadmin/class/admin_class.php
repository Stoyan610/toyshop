<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

abstract class Admin {
  public $db;
  
  public function __construct($user, $pass) {
    $this->db = new DbRover($user, $pass);
  }
  
  //Получение полной информации из данной таблицы БД 
  abstract public function GetTable();
  //Добавление новой записи в таблицу БД
  abstract public function InsertItem();
  //Изменение записи в таблице БД
  abstract public function EditItem($id);
  //Удаление записи в таблице БД
  abstract public function DeleteItem($id);
  
  //Обработчик изображений - большой файл
  public function ImageHandlerBig($image_file, $new_img_name) {
    $arr_img = getimagesize($image_file);
    $orig_width = $arr_img[0];
    $orig_height = $arr_img[1];
    $rate = (($orig_height / 400) > ($orig_width / 400)) ? $orig_height / 400 : $orig_width / 400;
    $width = $orig_width / $rate;
    $height = $orig_height / $rate;
    $pointer = imagecreatefromjpeg($image_file);
    $new_pointer = imagecreatetruecolor($width, $height);
    imagecopyresampled($new_pointer, $pointer, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
    imagejpeg($new_pointer, PATH.'www/'.VIEW.PAGE.PICT.'toy400x400/'.$new_img_name.'.jpg');
  }

  //Обработчик изображений
  public function ImageHandler($image_file, $width, $height, $new_img_name, $dir_storage='') {
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
    imagejpeg($new_pointer, PATH.'www/'.VIEW.PAGE.PICT.$dir_storage.$new_img_name.'.jpg');
  }


  public function __destruct() {
  }
}

?>