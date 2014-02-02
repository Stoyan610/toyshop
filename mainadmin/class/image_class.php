<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Images extends Admin {
  protected $tbl;
  
  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = IMG;
  }
  
  //Вывод преложения для получения требуемой информации из данной таблицы БД 
  public function GetTable() {
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<a href='image.php?act=add' ".$stl.">Добавить изображение</a><br /><br /><form name='getkind' action='image.php' method='get'><table name='getkind' cellspacing='0' cellpadding='5' border='1'>";
    echo "<tr><td ".$stl.">Выбор типа изображений<br />для вывода в таблице</td><td><input type='radio' name='act' value='get_cat' />Мультфильмы<br /><input type='radio' name='act' value='get_prod' />Игрушки<br /><input type='radio' name='act' value='get_all' checked/>Все изображения</td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='submit' name='gettable' value='Выбор сделан' /></td></tr></table></form><br />";
  }
  
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnKind($kind) {
    echo '<h2>Таблица - Все изображения по выбору "'.$kind.'"</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<a href='image.php?act=add' ".$stl.">Добавить изображение</a><br /><br />";
    $arr_table = $this->db->ReceiveAllOnCondition($this->tbl, 'Kind', ' LIKE ', $kind.'%');
    if (!$arr_table)     echo "<p>Таблица пуста - изображений нет</p>";
    else {
      $lines = count($arr_table);
      echo "<table name='picture' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Тип</td><td>Изображение</td><td>Большой файл</td><td>Ширина</td><td>Высота</td><td>Описание</td><td>Маленький файл</td><td>Файл-ноготок</td><td></td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        foreach ($arr_table[$i] as $key => $val) {
          echo "<td>".$val."</td>";
          if ($key == 'Kind') {
            if ($val == 'Игрушка') {
              $pic = $arr_table[$i]['ThumbnailFile'];
              echo "<td><img src='".SITEURL.PICT."toy70x70/".$pic.".jpg' alt='".$pic."' width='70' height='70' /></td>";
            }
             else {
               $pic = $arr_table[$i]['SmallFile'];
               echo "<td><img src='".SITEURL.PICT."mult114x86/".$pic.".jpg' alt='".$pic."' width='114' height='86' /></td>";
             }
          }
        }
        echo "<td><a href='image.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать изображение</a></td><td><a href='image.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить изображение</a></td></tr>";
      }
      echo "</table><br />";
    }
  }
  
  
  //Надо бы протестировать
  //Проверка изображения на независимость от активных ссылок
  private function WhetherActive($id, $kind) {
    $today = time();
    if ($kind == 'Игрушка') {
      $prod_id = $this->db->ReceiveFieldOnCondition(LIMG, 'Product_ID', 'Image_ID', '=', $id);
      if (!$prod_id)    return TRUE;
      else {
        $num = count($prod_id);
        for ($i = 0; $i < $num; $i++) {
          $date = $this->db->ReceiveFieldOnCondition(TOYS, 'PublishFrom', 'ID', '=', $prod_id[$i]);
          $a = date_parse($date);
          $date = mktime(0, 0, 1, $a['month'], $a['day'], $a['year']);
          if ($date > $today)    return FALSE;
          else     return TRUE;
        }
      }
    }
    else {
      $date = $this->db->ReceiveFieldOnCondition(MULTS, 'PublishFrom', 'Image_ID', '=', $id);
      if (!$prod_id)    return TRUE;
      else {
        $a = date_parse($date);
        $date = mktime(0, 0, 1, $a['month'], $a['day'], $a['year']);
        if ($date > $today)    return FALSE;
        else     return TRUE;
      }
    }
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Это изображение будет удалено</h2>';
    $arr_table = $this->db->ReceiveAllOnId($this->tbl, $id);
    if (!$arr_table)     exit('Нечего удалять - такого изображения нет');
    $pic = $arr_table['SmallFile'];
    $kind = $arr_table['Kind'];
    if ($this->WhetherActive($id, $kind)) {
      echo "<table name='delpic' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Тип</td><td>Изображение</td><td>Большой файл</td><td>Ширина</td><td>Высота</td><td>Описание</td><td>Маленький файл</td><td>Файл-ноготок</td><td></td><td></td></tr><tr align='center'>";
      foreach ($arr_table as $key => $val) {
        echo "<td>".$val."</td>";
        if (($key == 'Kind') && ($kind == 'Игрушка'))      echo "<td><img src='".SITEURL.PICT."toy135x135/".$pic.".jpg' alt='".$pic."' width='135' height='135' /></td>";
        elseif (($key == 'Kind') && ($kind != 'Игрушка'))     echo "<td><img src='".SITEURL.PICT."mult114x86/".$pic.".jpg' alt='".$pic."' width='114' height='86' /></td>";
      }
        echo "<td><form name='delete' action='delimage.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form></td><td><form name='cancel' action='delimage.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
      }
      else {
        echo "<p>К сожалению, удалить изображение  `".$arr_table['Name']."`  невозможно, так как с ним связаны активные мультфильмы или игрушки</p>";
        if ($kind == 'Игрушка')     echo "<a href='product.php?act=part&field=Image_ID&value=".$id."'>Показать список соответствующих игрушек?</a> ";
        else    echo "<a href='catalog.php?act=part&Image_ID=".$id."'>Показать список соответствующих мультфильмов?</a> ";
        echo "или <a href='image.php'>Вернуться к списку изображений?</a>";
      }
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Новая запись - изображение</h2>';
    echo "<table name='addpicture' cellspacing='0' cellpadding='5' border='1'>";
    echo "<form name='addimage' action='addimage.php' method='post' enctype='multipart/form-data'>";
    $stl = "style='font-size: 120%; font-weight: bold;'";
    //Выбрать файл
    echo "<tr><td ".$stl.">Загружаемый файл<br />(только формата jpg)</td><td><input id='file' type='file' name='ImageFile' onchange='checkjpg()' /></td></tr>";
    echo "<tr><td ".$stl.">Тип изображения</td><td><input type='radio' name='Type' value='Мультфильм' checked />Постер мультфильма<br /><input type='radio' name='Type' value='Игрушка' />Фото игрушки</td></tr><tr><td ".$stl.">Описание</td><td><input id='alt' type='text' name='Alt' value='' required /></td></tr><tr><td ".$stl.">Имя файла большого изображения<br />(латиницей)</td><td><input id='big' type='text' name='BigFile' value='' required /></td></tr><tr><td ".$stl.">Имя файла маленького изображения<br />(латиницей)</td><td><input id='small' type='text' name='SmallFile' value='' required /></td></tr><tr><td ".$stl.">Имя файла изображения-ноготка<br />(латиницей)</td><td><input id='nail' type='text' name='ThumbnailFile' value='' required /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input onmouseover='valid()' type='submit' name='add' value='Подтверждаю добавление' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='addimage.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение записи - изображения</h2>';
    $arr_table = $this->db->ReceiveAllOnId($this->tbl, $id);
    if (!$arr_table)     exit('Нечего редактировать - такого изображения нет');
    $pic = $arr_table['SmallFile'];
    $kind = $arr_table['Kind'];
    echo "<table name='editpic' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Тип изображения</td><td>Изображение</td><td>Описание</td><td></td><td></td></tr><tr><form name='edit' action='editimage.php' method='post'>";
    foreach ($arr_table as $key => $val) {
      if ($key == 'ID') {
        echo "<td>".$val."<input type='hidden' name='ID' value='".$val."' /></td>";
      }
      if ($key == 'Kind') {
        echo "<td>".$val."</td>";
        if ($kind == 'Игрушка')      echo "<td><img src='".SITEURL.PICT."toy135x135/".$pic.".jpg' alt='".$pic."' width='135' height='135' /></td>";
        else     echo "<td><img src='".SITEURL.PICT."mult114x86/".$pic.".jpg' alt='".$pic."' width='114' height='86' /></td>";
      }
      if ($key == 'Alt') {
        echo "<td><input type='text' name='Alt' value='".$val."' size='100' /></td>";
      }
    }
    echo "<td><input type='submit' name='edit' value='Подтверждаю изменения' /></td></form><td><form name='cancel' action='editimage.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
}

?>