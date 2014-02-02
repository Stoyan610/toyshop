<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Product extends Admin {
  protected $tbl;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = TOYS;
  }
  
  
  
  
  
//НАДО БЫ ПРОВЕРИТЬ
  //Получение фото по ID игрушки 
  private function GetImage($id) {
    $arr_img = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', "`Product_ID`='".$id."' AND `Priority`='0'");
    if (count($arr_img) > 1) return 'Проверь приоритеты изображений этой игрушки';
    $Image_ID = $arr_img[0]['Image_ID'];
    $kind = $this->db->ReceiveFieldOnCondition(IMG, 'Kind', 'ID', '=', $Image_ID);
    if (($kind[0] == 'Игрушка') && ($Image_ID != 0)) {
      $img_name = $this->db->ReceiveFieldOnCondition(IMG, 'SmallFile', 'ID', '=', $Image_ID);
      $img = $img_name[0];
    }
    else    $img = 'emptytoy';
    return $img;
}

  //Подпрограмма выведения ячеек с ноготками игрушек
  private function GetImgCells ($arr) {
    foreach ($arr as $key => $val) {
      if ($key == 'Name') {
        echo "<td>".$val."</td>";
        $pic = $this->GetImage($arr['ID']);
        echo "<td><img src='".SITEURL.PICT."toy70x70/".$pic.".jpg' alt='".$pic."' width='70' height='70' /></td>";
      }
      elseif ($key == 'Catalog_ID') {
        $mult = $this->db->ReceiveFieldOnCondition(MULTS, 'Name', 'ID', '=', $val);
        echo "<td>".$mult[0]."</td>";
      }
      else echo "<td>".$val."</td>";
    }
  }

  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    echo '<h2>Таблица - Все игрушки по популярности</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    $arr_table = $this->db->ReceiveAll($this->tbl, 'Popularity', FALSE);
    if (!$arr_table)     echo "<p>Таблица пуста</p><a href='product.php?act=add' ".$stl.">Добавить игрушку</a><br /><br />";
    else {
      $lines = count($arr_table);
      echo "<a href='product.php?act=add' ".$stl.">Добавить игрушку</a><br /><br /><table name='toy' cellspacing='0' cellpadding='3' border='1'><tr align='center' ".$stl0."><td>ID</td><td>Название</td><td>Изображение</td><td>Мультфильм</td><td>Описание</td><td>Ключевые слова</td><td>Приоритет</td><td>Дата публикации</td><td>Цена</td><td>В наличии</td><td>Страна</td><td>Материал</td><td>Размеры</td><td>Вес</td><td>Сроки</td><td>Популярность</td><td></td><td></td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        $this->GetImgCells($arr_table[$i]);
        echo "<td><a href='product.php?act=changeimg&id=".$arr_table[$i]['ID']."'>Заменить изображения</a></td><td><a href='product.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать игрушку</a></td><td><a href='product.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить игрушку</a></td></tr>";
      }
      echo "</table><br />";
    }
  }

/* 
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
*/
/*
  //Проверка мультфильма на независимость от ссылок
  private function WhetherProduct($id) {
    return $this->db->ReceiveIDFieldsOnCondition(TOYS, 'Name', '`Catalog_ID` = '.$id);
  }
*/
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Эта запись будет удалена</h2>';
    
/*
    $arr_table = $this->db->ReceiveAllOnId($this->tbl, $id);
    if (!$this->WhetherProduct($id)) {
      if (!$arr_table)     exit('Нечего удалять');
      echo "<table name='delmult' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Название</td><td>Описание</td><td>Ключевые слова</td><td>Изображение</td><td>Приоритет</td><td>Дата публикации</td><td></td><td></td></tr><tr>";
      $this->GetImgCells($arr_table);
      echo "<td><form name='delete' action='delmult.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form></td><td><form name='cancel' action='delmult.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
    }
    else     echo "<p>К сожалению, удалить мультфильм  `".$arr_table['Name']."`  невозможно, так как с ним связаны существующие игрушки</p><a href='product.php?act=part&field=Catalog_ID&value=".$id."'>Показать список соответствующих игрушек?</a> или <a href='catalog.php'>Вернуться к списку мультфильмов?</a>";
*/
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение данных записи - игрушки</h2>';
    
/*
    $arr_table = $this->db->ReceiveAllOnId($this->tbl, $id);
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='editmult' cellspacing='0' cellpadding='5' border='1'><form name='editmult' action='editmult.php' method='post'>";
    echo "<tr><td ".$stl.">ID</td><td><input type='text' name='ID' value='".$arr_table['ID']."' readonly /></td></tr><tr><td ".$stl.">Название</td><td><input type='text' name='Name' value='".$arr_table['Name']."' /></td></tr><tr><td ".$stl.">Описание</td><td><input type='text' name='Description' value='".$arr_table['Description']."' size='100' /></td></tr><tr><td ".$stl.">Ключевые слова</td><td><input type='text' name='Keywords' value='".$arr_table['Keywords']."' size='50' /></td></tr><tr><td ".$stl.">Приоритет</td><td><input type='text' name='Priority' value='".$arr_table['Priority']."' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='".$arr_table['PublishFrom']."' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='editmult.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
*/
  }
   

  //Замена изображения в записи
  public function ChangeImage($Product_ID) {
    echo '<h2>Редактированите приоритетов или удаление картинок данной игрушки</h2>';
    //Получение массива изображений данной игрушки
    $arr_img = $this->db->ReceiveAllOnCondition(LIMG, 'Product_ID', '=', $Product_ID);
    if (!$arr_img)     echo "<p>Нет фото у данной игрушки</p><a href='product.php'>Вернуться к списку игрушек</a><br />или<br /><a href='product.php?act=addimg&id=".$Product_ID."'>Добавить изображение</a>";
    else {
      echo "<form name='change' action='imgchtoy.php' method='post'>";
      $stl = "style='display: inline-block; width: 170px; vertical-align: top; text-align: center;'";
      foreach ($arr_img as $num => $toyimage) {
        echo "<div id='".$toyimage['ID']."' ".$stl.">";
        foreach ($toyimage as $key => $val) {
          if ($key == 'ID')     echo "<input type='hidden' name='ID-".$num."' value='".$val."' />";
          if ($key == 'Image_ID') {
            if ($val == 0)    $pic = 'emptytoy';
            else    $pic = $this->db->ReceiveFieldOnCondition(IMG, 'SmallFile', 'ID', '=', $val);
            echo "<img src='".SITEURL.PICT."toy135x135/".$pic.".jpg' alt='".$pic."' width='135' height='135' onclick='delimage(".$toyimage['ID'].")' /><br />";
          }
          if ($key == 'Priority')     echo "<input type='text' name='Priority-".$num."' value='".$val."' /><br />";
        }
        echo "<input type='checkbox' name='del-".$num."' />Удалить";  
        echo "</div><br /><br />";
      }
      echo "<input type='submit' name='change' value='Подтверждаю изменения' /></form><br /><form name='cancel' action='imgchtoy.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form>";
    }  
  }
    
 
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Новая запись - игрушка</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='addtoy' cellspacing='0' cellpadding='5' border='1'><form name='addtoy' action='addtoy.php' method='post'>";
    echo "<tr><td ".$stl.">Название</td><td><input type='text' name='Name' value='' required /></td></tr>";
    //Получение массива названий мультфильмов
    $arr_mult = $this->db->ReceiveIDFieldsOnCondition(MULTS, 'Name');
    $arr_mult['0'] = '';
    $n = count($arr_mult);
    
    echo "<tr><td ".$stl.">Мультфильм</td><td><select name='Catalog_ID'>";
    foreach ($arr_mult as $key => $val) {
      echo "<option value='".$key."'";
      if ($key == '0') echo " selected";
      echo ">".$val."</option>";
    }
    echo "</select></td></tr>";
    
    echo "<tr><td ".$stl.">Описание</td><td><input type='text' name='Description' value='' size='100' /></td></tr><tr><td ".$stl.">Ключевые слова</td><td><input type='text' name='Keywords' value='' size='50' /></td></tr><tr><td ".$stl.">Приоритет</td><td><input type='text' name='Priority' value='' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='' /></td></tr><tr><td ".$stl.">Цена</td><td><input type='text' id='prc' name='Price' value=''  onblur='checknum(".'"prc"'.")' /></td></tr><tr><td ".$stl.">В наличии</td><td><input type='text' name='Quantity' value='' /></td></tr><tr><td ".$stl.">Страна</td><td><input type='text' name='Manufacture' value='' /></td></tr><tr><td ".$stl.">Матариал</td><td><input type='text' name='Material' value='' /></td></tr><tr><td ".$stl.">Размеры</td><td><input type='text' name='Dimension' value='' /></td></tr><tr><td ".$stl.">Вес</td><td><input type='text' id='wgt' name='Weight' value='' onblur='checknum(".'"wgt"'.")' /></td></tr><tr><td ".$stl.">Сроки</td><td><input type='text' name='Deadline' value='' /></td></tr><tr><td ".$stl.">Популярность</td><td><input type='text' name='Popularity' value='' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='submit' name='add' value='Подтверждаю добавление' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='addtoy.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }

    
  //Добавление изображения в запись
  public function AddImage($Product_ID) {
    echo '<h2>Добавление картинок к данной игрушке '.$Product_ID.'</h2>';

/*
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
    
    echo "<tr><td ".$stl.">Изображение</td><td id='pictures' ondblclick='galery()'>Для выбора изображения дважды кликни здесь<div id='hide0' hidden>".SITEURL.PICT."</div><div id='hide1' hidden>".$str_id."</div><div id='hide2' hidden>".$str_pic."</div><input type='hidden' name='Image_ID' value='' /></td></tr>";

  
    //Получение массива изображений данной игрушки
    $arr_img = $this->db->ReceiveAllOnCondition(LIMG, 'Product_ID', '=', $Product_ID);
    echo "<form name='change' action='imgchtoy.php' method='post'>";
    $stl = "style='display: inline-block;'";
    foreach ($arr_img as $num => $toyimage) {
      echo "<div id='".$toyimage['ID']."' ".$stl.">";
      foreach ($toyimage as $key => $val) {
        if ($key == 'ID')     echo "<input type='hidden' name='ID-".$num."' value='".$val."' />";
        if ($key == 'Image_ID') {
          $pic = $this->db->ReceiveFieldOnCondition(IMG, 'SmallFile', 'ID', '=', $val);
          echo "<img src='".PATH."www/".PICT."toy135x135/".$pic.".jpg' alt='".$pic."' width='135' height='135' onclick='delimage(".$toyimage['ID'].")' /><br />";
        }
        if ($key == 'Priority')     echo "<input type='text' name='Priority-".$num."' value='".$val."' /><br />";
        echo "<input type='checkbox' name='del-".$num."' />Удалить";  
      }
      echo "</div><br />";
    }
    echo "<input type='submit' name='change' value='Подтверждаю изменения' /></form><br /><form name='cancel' action='imgchtoy.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form>";

    
    $arr_img = $this->db->ReceiveAllOnCondition(IMG, 'Kind', ' LIKE ', 'Мульт%');
    
    foreach ($arr_img as $row) {
      $img_id = $row['ID'];
      $pic = $row['SmallFile'];
      echo "<div><img src='".SITEURL.PICT."mult114x86/".$pic.".jpg' alt='".$pic."' width='114' height='86' /><br /><input type='radio' name='img' value='".$img_id."' />".$row['Alt']."</div>";
    }
    echo "<br /><input type='hidden' name='mult_id' value='".$id."' /><input type='submit' name='changing' value='Подтверждаю выбор изображения' /></form><br /><form name='cancel' action='imgchmult.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form>";
*/
  }
    
    
  
}

?>