<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Catalog extends Admin {
  protected $tbl;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = 'A_CATALOG';
  }
  
  //Получение фото по ID 
  public function GetImage($id) {
    $img_name = $this->db->ReceiveFieldOnCondition('A_IMAGE', 'FileName', 'ID', '=', $id);
    return $img_name[0];
  }
    
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    $arr_table = $this->db->ReceiveAll($this->tbl, 'Priority', FALSE);
    
    if (!$arr_table) {
      $arr_table = array('ID', 'Name', 'Description', 'Keywords', 'Image', 'Priority', 'PublishFrom');
      echo "<table name='mult' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'>";
      foreach ($arr_table as $val)    echo "<td>".$val."</td>";
      echo "<td></td></tr><tr align='center'>";
      foreach ($arr_table as $val)    echo "<td></td>";
      echo "<td><a href='catalog.php?act=add'>Добавить мультфильм</a></td></tr></table><br />";
    }
    else {
      $lines = count($arr_table);
      echo "<table name='mult' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'>";
      foreach ($arr_table[0] as $key => $val) {
        if ($key == 'Image_ID')    echo "<td>Image</td>";
        echo "<td>".$key."</td>";
      }
      echo "<td></td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        foreach ($arr_table[$i] as $key => $val) {
          if ($key == 'Image_ID') {
            $pic = $this->GetImage($arr_table[$i]['Image_ID']);
            echo "<td><img src='".SITEURL.VIEW.PAGE.PICT."mult114x86/".$pic.".jpg' alt='".$pic."' height='86' width='114' /></td>";
          }
          echo "<td>".$val."</td>";
        }
        echo "<td><a href='catalog.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать мультфильм</a></td><td><a href='catalog.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить мультфильм</a></td></tr>";
      }
      echo "<tr align='center' style='background-color: #4BF831;'>";
      foreach ($arr_table[0] as $val) echo "<td></td>";
      echo "<td></td><td></td><td><a href='catalog.php?act=add'>Добавить мультфильм</a></td></tr></table><br />";
    }
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<br /><h2>Новая запись - мультфильм</h2>';
    $arr_table = array('Name' => 'Название', 'Description' => 'Описание', 'Keywords' => 'Ключевые слова', 'Image' => 'Файл изображения из БД', 'Priority' => 'Приоритет', 'PublishFrom' => 'Дата публикации, как (ГГГГ-ММ-ДД)');
    echo "<form name='addmult' action='addmult.php' method='post'>";
    echo "<table name='insertmult' cellspacing='0' cellpadding='5' border='1'>";
    foreach ($arr_table as $key => $val) {
      echo "<tr><td style='font-size: 120%; font-weight: bold;'>".$val."</td><td>";
      if ($key == 'Image') {
        //Выбрать картинку из базы данных
        $arr_img = $this->db->ReceiveField('A_IMAGE', 'FileName');
        foreach ($arr_img as $key2 => $val2) {
          echo "<input type='radio' name='new_img' value='".$key2."' />&nbsp;&rarr;&nbsp;<img src='".SITEURL.VIEW.PAGE.PICT."mult114x86/".$val2.".jpg' alt='".$val2."' height='86' width='114' /> - ".$val2."<br />";
        }
        echo "</td></tr>";
      }
      else {
        echo "<input type='text' name='".$key."' value=''";
        if ($key == 'Description')          echo "size='50'";
        echo " /></td></tr>";
      }
    }
    echo "<tr><td></td><td><input type='submit' name='add' value='Добавить в БД' /></td></tr></table></form><br />";
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<br /><h2>Эта запись будет удалена</h2>';
    $arr_table = $this->db->ReceiveAllOnId($this->tbl, $id);
    if (!$arr_table) {
      exit('Нечего удалять');
    }
    echo "<table name='delmult' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'>";
    foreach ($arr_table as $key => $val) {
      if ($key == 'Image_ID')    echo "<td>Image</td>";
      echo "<td>".$key."</td>";
    }
    echo "<td></td></tr><tr align='center'>";
    foreach ($arr_table as $key => $val) {
      if ($key == 'Image_ID') {
        $pic = $this->GetImage($arr_table['Image_ID']);
        echo "<td><img src='".SITEURL.VIEW.PAGE.PICT."mult114x86/".$pic.".jpg' alt='".$pic."' height='86' width='114' /></td>";
      }
      echo "<td>".$val."</td>";
    }
  echo "<td><form name='delete' action='delmult.php' method='post'><input type='radio' name='del' value='".$arr_table['ID']."' />Удалить<input type='radio' name='del' value='0' />Отменить<br /><input type='submit' name='delete' value='Подтверждаю действие' /></form></td></tr></table><br />";
  }
  
  
  
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo 'Изменяем информацию';
    /*
    $arr_table = $this->db->ReceiveAll($this->tbl, 'Priority', FALSE);
    
    if (!$arr_table) {
      $arr_table = array('ID', 'Name', 'Description', 'Keywords', 'Image', 'Priority', 'PublishFrom');
      echo "<table name='mult' cellspacing='0' cellpadding='3' border='1'><tr style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'>";
      foreach ($arr_table as $val)    echo "<td>$val</td>";
      echo "<td></td><td></td><td></td></tr>";
      echo "<form name='add' action='#' method='post'><tr style='background-color: #4BF831; font-style: italic;'>";
      foreach ($arr_table as $val)    echo "<td><input type='text' name='".$val."' value='' /></td>";
      echo "<td><input type='submit' name='add' value='Добавить' /></td><td></td><td></td></tr></form></table><br />";
    }
    else {
      $lines = count($arr_table);
      echo "<table name='mult' cellspacing='0' cellpadding='3' border='1'><tr style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'>";
      foreach ($arr_table[0] as $key => $val) {
        if ($key == 'Image_ID')    echo "<td>Image</td>";
        echo "<td>$key</td>";
      }
      echo "<td></td><td></td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<form name='line".$i."' action='#' method='post'><tr>";
        foreach ($arr_table[$i] as $key => $val) {
          if ($key == 'Image_ID') {
            $pic = $this->GetImage($arr_table[$i]['ID']);
            echo "<td>$pic</td>";
          }
          echo "<td><input type='text' name='".$key."' value='".$val."' /></td>";
        }
        echo "<td><input type='submit' name='edit' value='Запомнить изменения' /></td><td><input type='submit' name='image_edit' value='Изменить картинку' /></td><td><input type='submit' name='delete' value='Удалить' /></td></tr></form>";
      }
      echo "<form name='add' action='#' method='post'><tr style='background-color: #4BF831; font-style: italic;'>";
      foreach ($arr_table[0] as $key => $val) {
        if ($key == 'Image_ID')    echo "<td><input type='file' name='new_image' /></td>";
        echo "<td><input type='text' name='".$key."' value='' /></td>";
      }
      echo "<td><input type='submit' name='add' value='Добавить' /></td><td></td><td></td></tr></form></table><br />";
    }
  */
  }
   
    
  
}

?>