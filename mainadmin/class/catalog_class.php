<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Catalog extends Admin {
  protected $tbl;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = MULTS;
  }
  
  //Получение фото по ID 
  private function GetImage($id) {
    $kind = $this->db->ReceiveFieldOnCondition(IMG, 'Kind', 'ID', '=', $id);
    if (($kind[0] == 'Мультфильм') && ($id != 0)) {
      $img_name = $this->db->ReceiveFieldOnCondition(IMG, 'SmallFile', 'ID', '=', $id);
      $img = $img_name[0];
    }
    else    $img = 'emptymult';
    return $img;
  }
  
  //Подпрограмма выведения ячеек с маленьким изображением мультфильмов
  private function GetImgCells ($arr) {
    foreach ($arr as $key => $val) {
      if ($key == 'Image_ID') {
        $pic = $this->GetImage($arr['Image_ID']);
        echo "<td><img src='".SITEURL.PICT."mult114x86/".$pic.".jpg' alt='".$pic."' width='114' height='86' /></td>";
      }
      else echo "<td>".$val."</td>";
    }
  }
  
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    echo '<h2>Таблица - Все мультфильмы по приоритету</h2>';
    $arr_table = $this->db->ReceiveAll($this->tbl, 'Priority', FALSE);
    if (!$arr_table)     echo "<p>Таблица пуста</p><a href='catalog.php?act=add' style='font-size: 120%; font-weight: bold;'>Добавить мультфильм</a><br /><br />";
    else {
      $lines = count($arr_table);
      echo "<a href='catalog.php?act=add' style='font-size: 120%; font-weight: bold;'>Добавить мультфильм</a><br /><br /><table name='mult' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Название</td><td>Описание</td><td>Ключевые слова</td><td>Изображение</td><td>Приоритет</td><td>Дата публикации</td><td></td><td></td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        $this->GetImgCells($arr_table[$i]);
        echo "<td><a href='catalog.php?act=changeimg&id=".$arr_table[$i]['ID']."'>Заменить изображение</a></td><td><a href='catalog.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать мультфильм</a></td><td><a href='catalog.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить мультфильм</a></td></tr>";
      }
      echo "</table><br />";
    }
  }

  
//НАДО ПРОВЕРИТЬ !!!
 
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnImage($Image_ID) {
    echo '<h2>Таблица - Все мультфильмы с выбранным изображением</h2>';
    $arr_table = $this->db->ReceiveAllOnCondition($this->tbl, 'Image_ID', '=', $Image_ID);
    if (!$arr_table)     echo "<p>Таблица пуста - изображение можно удалять</p><a href='image.php?act=del&id=".$Image_ID."'>Удалить выбранное изображение</a>";
    else {
      $lines = count($arr_table);
      echo "<table name='multonpic' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Название</td><td>Описание</td><td>Ключевые слова</td><td>Изображение</td><td>Приоритет</td><td>Дата публикации</td><td></td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        $this->GetImgCells($arr_table[$i]);
        echo "<td><a href='catalog.php?act=changeimg&id=".$arr_table[$i]['ID']."'>Заменить изображение</a></td><td><a href='catalog.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить мультфильм</a></td></tr>";
      }
      echo "</table><br />";
    }
  }
  
  
  
  
  //Проверка мультфильма на независимость от ссылок
  private function WhetherProduct($id) {
    return $this->db->ReceiveIDFieldsOnCondition(TOYS, 'Name', '`Catalog_ID` = '.$id);
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Эта запись будет удалена</h2>';
    $arr_table = $this->db->ReceiveAllOnId($this->tbl, $id);
    if (!$this->WhetherProduct($id)) {
      if (!$arr_table)     exit('Нечего удалять');
      echo "<table name='delmult' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Название</td><td>Описание</td><td>Ключевые слова</td><td>Изображение</td><td>Приоритет</td><td>Дата публикации</td><td></td><td></td></tr><tr>";
      $this->GetImgCells($arr_table);
      echo "<td><form name='delete' action='delmult.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form></td><td><form name='cancel' action='delmult.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
    }
    else     echo "<p>К сожалению, удалить мультфильм  `".$arr_table['Name']."`  невозможно, так как с ним связаны существующие игрушки</p><a href='product.php?act=part&field=Catalog_ID&value=".$id."'>Показать список соответствующих игрушек?</a> или <a href='catalog.php'>Вернуться к списку мультфильмов?</a>";
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение данных записи - мультфильма</h2>';
    $arr_table = $this->db->ReceiveAllOnId($this->tbl, $id);
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='editmult' cellspacing='0' cellpadding='5' border='1'><form name='editmult' action='editmult.php' method='post'>";
    echo "<tr><td ".$stl.">ID</td><td><input type='text' name='ID' value='".$arr_table['ID']."' readonly /></td></tr><tr><td ".$stl.">Название</td><td><input type='text' name='Name' value='".$arr_table['Name']."' /></td></tr><tr><td ".$stl.">Описание</td><td><input type='text' name='Description' value='".$arr_table['Description']."' size='100' /></td></tr><tr><td ".$stl.">Ключевые слова</td><td><input type='text' name='Keywords' value='".$arr_table['Keywords']."' size='50' /></td></tr><tr><td ".$stl.">Приоритет</td><td><input type='text' name='Priority' value='".$arr_table['Priority']."' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='".$arr_table['PublishFrom']."' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='editmult.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
   
  //Замена изображения в записи
  public function ChangeImage($id) {
    //Получение массива изображений мультфильмов
    $arr_img = $this->db->ReceiveAllOnCondition(IMG, 'Kind', ' LIKE ', 'Мульт%');
    if (!$arr_img)     echo "<p>Не из чего выбирать</p><a href='catalog.php'>Вернуться к списку мультфильмов</a>";
    $stl = "style='display: inline-block; width: 150px; height: 160px; vertical-align: top; text-align: center;'";
    echo "<form name='change' action='imgchmult.php' method='post'>";
    foreach ($arr_img as $row) {
      $img_id = $row['ID'];
      $pic = $row['SmallFile'];
      echo "<div ".$stl."><img src='".SITEURL.PICT."mult114x86/".$pic.".jpg' alt='".$pic."' width='114' height='86' /><br /><input type='radio' name='img' value='".$img_id."' />".$row['Alt']."</div>";
    }
    echo "<br /><input type='hidden' name='mult_id' value='".$id."' /><input type='submit' name='changing' value='Подтверждаю выбор изображения' /></form><br /><form name='cancel' action='imgchmult.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form>";
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Новая запись - мультфильм</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='addmult' cellspacing='0' cellpadding='5' border='1'><form name='editmult' action='addmult.php' method='post'>";
    echo "<tr></tr><tr><td ".$stl.">Название</td><td><input type='text' name='Name' value='' /></td></tr><tr><td ".$stl.">Описание</td><td><input type='text' name='Description' value='' size='100' /></td></tr><tr><td ".$stl.">Ключевые слова</td><td><input type='text' name='Keywords' value='' size='50' /></td></tr><tr><td ".$stl.">Приоритет</td><td><input type='text' name='Priority' value='' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='' /></td></tr>";
    //Получение массива изображений мультфильмов
    $arr_img = $this->db->ReceiveAllOnCondition(IMG, 'Kind', ' LIKE ', 'Мульт%');
    $arr_id = Array();
    $arr_pic = Array();
    $arr_id[0] = 0;
    $arr_pic[0] = 'emptymult';
    foreach ($arr_img as $value) {
      $arr_id[] = $value['ID'];
      $arr_pic[] = $value['SmallFile'];
    }
    $str_id = implode('~', $arr_id);
    $str_pic = implode('~', $arr_pic);
    
    echo "<tr><td ".$stl.">Изображение<input id='imageid' type='hidden' name='ImageID' value='0' /></td><td id='pictures' ondblclick='galery()'>Для выбора изображения дважды кликни здесь<div id='hide0' hidden>".SITEURL.PICT."</div><div id='hide1' hidden>".$str_id."</div><div id='hide2' hidden>".$str_pic."</div><input type='hidden' name='Image_ID' value='' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='submit' name='add' value='Подтверждаю добавление' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='addmult.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
}

?>