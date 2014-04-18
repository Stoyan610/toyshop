<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Product extends Admin {
  protected $tbl;
  protected $allfields;
  protected $limgfields;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = TOYS;
    $this->allfields = array('ID', 'Name', 'Catalog_ID', 'Description', 'Keywords', 'Priority', 'PublishFrom', 'Price', 'Quantity', 'Deadline', 'Manufacture', 'Material', 'Dimension', 'Weight', 'Popularity'); 
    $this->limgfields = array('ID', 'Product_ID', 'Image_ID', 'Priority'); 
  }
  
  //Получение фото по ID игрушки 
  private function GetImage($id) {
    $arr_img = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', "`Product_ID`='".$id."' AND `Priority`='0'");
    if (count($arr_img) > 1) return 'Проверь приоритеты изображений этой игрушки';
    $Image_ID = $arr_img[0];
    $kind = $this->db->ReceiveFieldOnCondition(IMG, 'Kind', 'ID', '=', $Image_ID);
    if (($kind[0] == 'Игрушка') && ($Image_ID != 0)) {
      $img_name = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $Image_ID);
      $img = $img_name[0];
    }
    else    $img = 'emptytoy';
    return $img;
}

  //Получение количества фото по ID игрушки 
  private function CountImage($id) {
    return $this->db->СountDataOnCondition(LIMG, 'Product_ID', '=', $id);
  }
  
  
  
  
  //Подпрограмма выведения ячеек с ноготками игрушек
  private function GetImgCells ($arr) {
    $subst['%toyid%'] = $arr['ID'];
    $subst['%toyname%'] = $arr['Name'];
    $subst['%pictname%'] = $this->GetImage($arr['ID']);
    $subst['%num%'] = $this->CountImage($arr['ID']);
    $subst['%pictpath%'] = SITEURL.PICT;
    $mult = $this->db->ReceiveFieldOnCondition(MULTS, 'Name', 'ID', '=', $arr['Catalog_ID']);
    $subst['%multname%'] = $mult[0];
    $subst['%description%'] = $arr['Description'];
    $subst['%keywords%'] = $arr['Keywords'];
    $subst['%toypriority%'] = $arr['Priority'];
    $subst['%publishfrom%'] = $arr['PublishFrom'];
    $subst['%toyrice%'] = $arr['Price'];
    $subst['%quantity%'] = $arr['Quantity'];
    $subst['%deadline%'] = $arr['Deadline'];
    $subst['%manufacture%'] = $arr['Manufacture'];
    $subst['%material%'] = $arr['Material'];
    $subst['%dimension%'] = $arr['Dimension'];
    $subst['%weight%'] = $arr['Weight'];
    $subst['%toypopularity%'] = $arr['Popularity'];
    return $subst;
  }

  
  
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields, 'Popularity', FALSE);
    if (!$arr_table)     $admin_page .= $this->ReplaceTemplate(NULL, 'product_empty');
    else {
      $lines = count($arr_table);
      $substtable['%table_name%'] = 'Все игрушки по популярности';
      $substtable['%table_lines%'] = '';
      for ($i = 0; $i < $lines; $i++) {
        $substline = $this->GetImgCells($arr_table[$i]);
        $substtable['%table_lines%'] .= $this->ReplaceTemplate($substline, 'product_table_line');
      }
      $admin_page .= $this->ReplaceTemplate($substtable, 'product_table');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  

  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnField($field, $value) {
    $admin_page = $this->general;
    if ($field == 'Catalog_ID') {
      $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, $field, '=', $value);
      if (!$arr_table) {
        $substtable['%catalogid%'] = $value;
        $admin_page .= $this->ReplaceTemplate($substtable, 'product_empty_1');
      }
      else {
        $lines = count($arr_table);
        $substtable['%table_name%'] = 'Все игрушки с выбранным мультфильмом';
        for ($i = 0; $i < $lines; $i++) {
          $substline = $this->GetImgCells($arr_table[$i]);
          $substtable['%table_lines%'] .= $this->ReplaceTemplate($substline, 'product_table_line_1');
        }
        $admin_page .= $this->ReplaceTemplate($substtable, 'product_table');
      }
    }
    elseif ($field == 'Image_ID') {
      $product_id = $this->db->ReceiveFieldOnCondition(LIMG, 'Product_ID', $field, '=', $value);
      $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, 'ID', '=', $product_id[0]);
      if (!$arr_table) {
        $substtable['%imageid%'] = $value;
        $admin_page .= $this->ReplaceTemplate($substtable, 'product_empty_2');
      }
      else {
        $lines = count($arr_table);
        $substtable['%table_name%'] = 'Все игрушки с выбранным изображением';
        for ($i = 0; $i < $lines; $i++) {
          $substline = $this->GetImgCells($arr_table[$i]);
          $substtable['%table_lines%'] .= $this->ReplaceTemplate($substline, 'product_table_line_1');
        }
        $admin_page .= $this->ReplaceTemplate($substtable, 'product_table');
      }
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  
  
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $substline = $this->GetImgCells($arr_table);
    $admin_page .= $this->ReplaceTemplate($substline, 'product_delete');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  
  
  
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $substedit['%toyid%'] = $arr_table['ID'];
    $substedit['%toyname%'] = $arr_table['Name'];
    //Получение массива названий мультфильмов
    $arr_mult = $this->db->ReceiveIDFieldsOnCondition(MULTS, 'Name');
    $arr_mult['0'] = '';
    $substedit['%options%'] = '';
    foreach ($arr_mult as $key => $val) {
      $subst['%catalogid%'] = $key;
      $subst['%catalogname%'] = $val;
      $subst['%selected%'] = '';
      if ($key == $arr_table['Catalog_ID'])     $subst['%selected%'] = ' selected';
      $substedit['%options%'] .= $this->ReplaceTemplate($subst, 'product_edit_mult');
    }
    $substedit['%description%'] = $arr_table['Description'];
    $substedit['%keywords%'] = $arr_table['Keywords'];
    $substedit['%toypriority%'] = $arr_table['Priority'];
    $substedit['%publishfrom%'] = $arr_table['PublishFrom'];
    $substedit['%toyprice%'] = $arr_table['Price'];
    $substedit['%toyquantity%'] = $arr_table['Quantity'];
    $substedit['%deadline%'] = $arr_table['Deadline'];
    $substedit['%manufacture%'] = $arr_table['Manufacture'];
    $substedit['%toymaterial%'] = $arr_table['Material'];
    $substedit['%toydimension%'] = $arr_table['Dimension'];
    $substedit['%toyweight%'] = $arr_table['Weight'];
    $substedit['%toypopularity%'] = $arr_table['Popularity'];
    $admin_page .= $this->ReplaceTemplate($substedit, 'product_edit');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  
  
  
  
  //Замена изображения в записи
  public function ChangeImage($Product_ID) {
    $admin_page = $this->general;
    //Получение массива изображений данной игрушки
    $arr_img = $this->db->ReceiveFewFieldsOnCondition(LIMG, $this->limgfields, 'Product_ID', '=', $Product_ID);
    $substimg['%productid%'] = $Product_ID;
    if (!$arr_img)      $admin_page .= $this->ReplaceTemplate($substimg, 'product_img_empty');
    else {
      $substimg['%count%'] = 0;
      $substimg['%imglist%'] = '';
      foreach ($arr_img as $num => $toyimage) {
        $substimg['%count%']++;
        $subst['%num%'] = $num;
        $subst['%limgid%'] = $toyimage['ID'];
        $subst['%imageid%'] = $toyimage['Image_ID'];
        $subst['%imageid%'] = $toyimage['Image_ID'];
        $subst['%pictpath%'] = SITEURL.PICT;
        if ($toyimage['Image_ID'] == 0)    $subst['%pictname%'] = 'emptytoy';
        else {
          $pic = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $toyimage['Image_ID']);
          $subst['%pictname%'] = $pic[0];
        }
        $subst['%priority%'] = $toyimage['Priority'];
        $substimg['%imglist%'] .= $this->ReplaceTemplate($subst, 'product_imglist');
      }
      $admin_page .= $this->ReplaceTemplate($substimg, 'product_img_change');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  
  
  
  
  //Добавление изображения в запись
  public function AddImage($Product_ID) {
    $admin_page = $this->general;
    //Получение массива изображений игрушек
    $field_list = array('ID', 'FileName');
    $arr_img = $this->db->ReceiveFewFieldsOnCondition(IMG, $field_list, 'Kind', ' LIKE ', 'Игр%');
    if (!$arr_img)     $admin_page .= $this->ReplaceTemplate(NULL, 'product_img_empty_1');
    else {
      $substimg['%toyid%'] = $Product_ID;
      $substimg['%imglist%'] = '';
      foreach ($arr_img as $row) {
        $subst['%pictid%'] = $row['ID'];
        $subst['%pictname%'] = $row['FileName'];
        $subst['%pictpath%'] = SITEURL.PICT;
        $substimg['%imglist%'] .= $this->ReplaceTemplate($subst, 'product_imglist_1');
      }
      $admin_page .= $this->ReplaceTemplate($substimg, 'product_img_add');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  

  
  
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    $admin_page = $this->general;
    //Получение массива названий мультфильмов
    $arr_mult = $this->db->ReceiveIDFieldsOnCondition(MULTS, 'Name');
    $arr_mult['0'] = '';
    $substedit['%options%'] = '';
    foreach ($arr_mult as $key => $val) {
      $subst['%catalogid%'] = $key;
      $subst['%catalogname%'] = $val;
      if ($key == '0') $subst['%selected%'] = " selected";
      $substedit['%options%'] .= $this->ReplaceTemplate($subst, 'product_edit_mult');
    }
    $admin_page .= $this->ReplaceTemplate($substedit, 'product_edit');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
}

?>