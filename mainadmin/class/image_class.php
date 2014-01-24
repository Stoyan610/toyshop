<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Images extends Admin {
  protected $tbl;
  protected $tbl_big;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = 'A_IMAGE';
    $this->tbl_big = 'L_IMAGE';
  }
  
  //Получение фото по ID 
  public function GetImage($id) {
    $img_name = $this->db->ReceiveFieldOnCondition($this->tbl, 'FileName', 'ID', '=', $id);
    return $img_name[0];
  }
  
  //Получение изображений мультфильмов из данной таблицы БД 
  private function GetCatalogTable() {
    $arr_table = $this->db->ReceiveAllOnCondition($this->tbl, 'Width', '=', 0);
    if (!$arr_table)     echo "<table name='picture' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>FileName</td><td>Width</td><td>Height</td><td>Alt</td></tr><tr align='center'><td colspan='5'></td><td><a href='image.php?act=add'>Добавить изображение</a></td></tr></table><br />";
      else {
      $lines = count($arr_table);
      echo "<table name='picture' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'>";
      foreach ($arr_table[0] as $key => $val)     echo "<td>".$key."</td>";
      echo "<td></td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        foreach ($arr_table[$i] as $key => $val) {
          if ($key == 'FileName')       echo "<td><img src='".SITEURL.VIEW.PAGE.PICT."mult114x86/".$val.".jpg' alt='".$val."' height='86' width='114' /><br />".$val."</td>";
          else echo "<td>".$val."</td>";
        }
        echo "<td><a href='image.php?act=edit_cat&id=".$arr_table[$i]['ID']."'>Редактировать данные</a></td><td><a href='image.php?act=del_cat&id=".$arr_table[$i]['ID']."'>Удалить изображение</a></td></tr>";
      }
      echo "<tr align='center' style='background-color: #4BF831;'>";
      echo "<td colspan='6'></td><td><a href='image.php?act=add_cat'>Добавить изображение</a></td></tr></table><br />";
    } 
  }

  
  
  //Получение массива для посторения таблицы
  private function GetTableArray() {
    $arr_toy = $this->db->GetUniqField($this->tbl_big, 'Product_ID');
    $arr_table = array();
    foreach ($arr_toy as $toy) {
      $rows = $this->db->СountDataOnCondition($this->tbl_big, 'Product_ID', '=', $toy);
      $arr_big = $this->db->ReceiveAllOnCondition($this->tbl_big, 'Product_ID', '=', $toy);
      $arr_table[$toy]['Name'] = $this->db->ReceiveFieldOnCondition('A_PRODUCT', 'Name', 'ID', '=', $toy);
      for ($i = 0; $i < $rows; $i++) {
        $arr_table[$toy][$i]['ID'] = $arr_big[$i]['ID'];
        $arr_table[$toy][$i]['Image_ID'] = $arr_big[$i]['Image_ID'];
        $arr_table[$toy][$i]['FileName'] = $this->GetImage($arr_big[$i]['Image_ID']);
        $arr_table[$toy][$i]['Alt'] = $this->db->ReceiveFieldOnCondition($this->tbl, 'Alt', 'ID', '=', $arr_big[$i]['Image_ID']);
        $arr_table[$toy][$i]['Priority'] = $arr_big[$i]['Priority'];
      }
    }
    return $arr_table;
  }
  
  
  
  //Получение фото игрушек из данной таблицы БД 
  private function GetProductTable() {
    $arr_big = $this->db->ReceiveAll($this->tbl_big);
    if (!$arr_big)     echo "<table name='picture' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>Name</td><td>ID</td><td>Image_ID</td><td>FileName</td><td>Alt</td><td>Priority</td></tr><tr align='center'><td colspan='7'></td><td><a href='image.php?act=add'>Добавить фото</a></td></tr></table><br />";
    else {
      $arr_table = $this->GetTableArray();
      
      
      /*
      foreach ($arr_big as $key => $value) {
        $arr_img[$key] = $this->db->ReceiveAllOnId($this->tbl, $arr_big[$key]['Image_ID']);
      }
      $lines = count($arr_table);
      echo "<table name='picture' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'>";
      foreach ($arr_table[0] as $key => $val)     echo "<td>".$key."</td>";
      echo "<td></td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        foreach ($arr_table[$i] as $key => $val) {
          if ($key == 'FileName') {
            if ($this->GetSize($arr_table[$i]['ID']) == 0)     echo "<td><img src='".SITEURL.VIEW.PAGE.PICT."mult114x86/".$val.".jpg' alt='".$val."' height='86' width='114' /><br />".$val."</td>";
            else     echo "<td><img src='".SITEURL.VIEW.PAGE.PICT."toy135x135/".$val.".jpg' alt='".$val."' height='135' width='135' /><br />".$val."</td>";
          }
          else echo "<td>".$val."</td>";
        }
        echo "<td><a href='image.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать данные</a></td><td><a href='image.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить изображение</a></td></tr>";
      }
      echo "<tr align='center' style='background-color: #4BF831;'>";
      echo "<td colspan='6'></td><td><a href='image.php?act=add'>Добавить изображение</a></td></tr></table><br />";
     
    */
      
      
    } 
  }




  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    echo '<br /><h2>Таблица 1 - Все изображения мультфильмов</h2>';
    $this->GetCatalogTable();
    echo '<br /><h2>Таблица 1 - Все фото игрушек</h2>';
    $this->GetProductTable();
  }

  
  
  
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    
  }
  
  /*
    echo '<br /><h2>Это изображение будет удалено</h2>';
    $arr_table = $this->db->ReceiveAllOnId($this->tbl, $id);
    if (!$arr_table) {
      exit('Нечего удалять');
    }
    echo "<table name='delpic' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'>";
    foreach ($arr_table as $key => $val)    echo "<td>".$key."</td>";
    echo "<td></td></tr><tr align='center'>";
    foreach ($arr_table as $key => $val) {
      if ($key == 'FileName') {
        if ($this->GetSize($arr_table['ID']) == 0)     echo "<td><img src='".SITEURL.VIEW.PAGE.PICT."mult114x86/".$val.".jpg' alt='".$val."' height='86' width='114' /><br />".$val."</td>";
        else     echo "<td><img src='".SITEURL.VIEW.PAGE.PICT."toy135x135/".$val.".jpg' alt='".$val."' height='135' width='135' /><br />".$val."</td>";
          }
      else echo "<td>".$val."</td>";
    }
    echo "<td><form name='delete' action='delimage.php' method='post'><input type='radio' name='del' value='".$arr_table['ID']."' />Удалить<br /><input type='radio' name='del' value='0' />Отменить<br /><input type='submit' name='delete' value='Подтверждаю действие' /></form></td></tr></table><br />";
  }
  */
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    
  }
  /*
    echo '<br /><h2>Новая запись - изображение</h2>';
    echo "<form name='addpicture' action='addimage.php' method='post'>";
    echo "<table name='addpicture' cellspacing='0' cellpadding='5' border='1'>";
    //Выбрать файл
    echo "<tr><td style='font-size: 120%; font-weight: bold;'>Загружаемый файл<br />(возможные форматы: jpg, jpeg, png)</td><td><input type='file' name='ImageFile' /></td></tr>"; 
    echo "<tr><td style='font-size: 120%; font-weight: bold;'>Тип изображения</td><td><input type='radio' name='Type' value='mult' />Постер мультфильма<br /><input type='radio' name='Type' value='toy' />Фото игрушки</td></tr>";
    echo "<tr><td style='font-size: 120%; font-weight: bold;'>Имя сохраняемого изображения<br />(латиницей)</td><td><input type='text' name='FileName' value='' /></td></tr>";
    echo "<tr><td style='font-size: 120%; font-weight: bold;'>Описание</td><td><input type='text' name='Alt' value='' /></td></tr>";
    echo "<tr><td><input type='radio' name='do' value='add' />Добавить<br /><input type='radio' name='do' value='esc' />Отменить</td><td><input type='submit' name='add' value='Подтверждаю действие' /></td></tr></table></form><br />";
  }
  
  */
   
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    
  }
  
  /*
  echo '<br /><h2>'.$id.' - Изменение данных записи - изображения</h2>';
    //Подготовка массива для заполнения таблицы изменений. Для мультфильмов может меняться только Alt, для игрушек - Alt и Priority
    $arr_img = $this->db->ReceiveAllOnId($this->tbl, $id);
    //В случае мультфильмов
    if ($arr_img['Width'] == 0) {
      $arr_table = array('ID' => 'ID', 'FileName' => 'Изображение', 'Width' => 'Ширина', 'Height' => 'Высота', 'Alt', 'Описание');
      foreach ($arr_img as $key => $val) {
        $arr_total[$key] = array($arr_table[$key], $val);
      }
      //Вывод таблицы-формы
      echo "<form name='editimg' action='editimage.php' method='post'><table name='editimg' cellspacing='0' cellpadding='5' border='1'>";
      foreach ($arr_total as $key => $val) {
        echo "<tr><td style='font-size: 120%; font-weight: bold;'>".$val[0]."</td><td>";
        if ($key == 'FileName')       echo "<td><img src='".SITEURL.VIEW.PAGE.PICT."mult114x86/".$val[1].".jpg' alt='".$val[1]."' height='86' width='114' /><br />".$val[1]."</td></tr>";
        elseif ($key == 'ID')      echo "<input type='text' name='".$key."' value='".$val[1]."' readonly /></td></tr>";
        elseif ($key == 'Alt')      echo "<input type='text' name='".$key."' value='".$val[1]."' /></td></tr>";
        else      echo "<td>".$val[1]."</td></tr>";
      }
      echo "<tr><td><input type='radio' name='do' value='change' />Изменить<br /><input type='radio' name='do' value='esc' />Отменить</td><td><input type='submit' name='edit' value='Подтверждаю действие' /></td></tr></table></form><br />";
    }
    //В случае игрушек
    else {
      $arr_table = array('ID' => 'ID', 'FileName' => 'Изображение', 'Width' => 'Ширина', 'Height' => 'Высота', 'Alt', 'Описание', 'L_ID' => 'ID большого фото', 'Product_ID' => 'Название игрушки', 'Priority' => 'Приоритет');
      $arr_l_image = $this->db->ReceiveAllOnCondition('L_IMAGE', 'Image_ID', '=', $arr_img['ID']);
      if (!$arr_l_image) exit ('Нет соответствия больших фото товару - проверить таблицу L_IMAGE');
      $rows = count($arr_l_image);
      foreach ($arr_table as $key => $val) {
        if ($key == '') {
          
        }
        
        $arr_total[$key] = array($val, $arr_img[$key]);
      }
      
      
    }
  }
  
*/  
  

}

?>