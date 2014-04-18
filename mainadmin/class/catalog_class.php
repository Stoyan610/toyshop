<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Catalog extends Admin {
  protected $tbl;
  protected $allfields;
  protected $smallfields;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = MULTS;
    $this->allfields = array('ID', 'Name', 'Description', 'Keywords', 'Image_ID', 'Priority', 'PublishFrom');
    $this->smallfields = array('ID', 'FileName');
  }
  
  //Получение фото по ID 
  private function GetImage($id) {
    $kind = $this->db->ReceiveFieldOnCondition(IMG, 'Kind', 'ID', '=', $id);
    if (($kind[0] == 'Мультфильм') && ($id != 0)) {
      $img_name = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $id);
      $img = $img_name[0];
    }
    else    $img = 'emptymult';
    return $img;
  }
  
  protected function GetTableLine($arr) {
    $subst = array();
    $subst['%catalogid%'] = $arr['ID'];
    $subst['%catalogname%'] = $arr['Name'];
    $subst['%description%'] = $arr['Description'];
    $subst['%toycount%'] = $this->db->СountDataOnCondition(TOYS, 'Catalog_ID', '=', $subst['%catalogid%']);
    $subst['%keywords%'] = $arr['Keywords'];
    $subst['%pictpath%'] = SITEURL.PICT;
    $subst['%pictname%'] = $this->GetImage($arr['Image_ID']);
    $subst['%priority%'] = $arr['Priority'];
    $subst['%publishfrom%'] = $arr['PublishFrom'];
    return $subst;
  }
  
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields,'Priority', FALSE);
    if (!$arr_table)     $admin_page .= $this->ReplaceTemplate(NULL, 'catalog_empty');
    else {
      $lines = count($arr_table);
      $substtable['%table_name%'] = 'Все мультфильмы по приоритету';
      $substtable['%table_lines%'] = '';
      for ($i = 0; $i < $lines; $i++) {
        $substline = $this->GetTableLine($arr_table[$i]);
        $substtable['%table_lines%'] .= $this->ReplaceTemplate($substline, 'catalog_table_line');
      }
      $admin_page .= $this->ReplaceTemplate($substtable, 'catalog_table');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnImage($Image_ID) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, 'Image_ID', '=', $Image_ID);
    if (!$arr_table) {
      $substimage['%delid%'] = $Image_ID;
      $admin_page .= $this->ReplaceTemplate($substimage, 'catalog_empty_1');
    }
    else {
      $lines = count($arr_table);
      $substtable['%table_name%'] = 'Все мультфильмы с выбранным изображением';
      $substtable['%table_lines%'] = '';
      for ($i = 0; $i < $lines; $i++) {
        $substline = $this->GetTableLine($arr_table[$i]);
        $substtable['%table_lines%'] .= $this->ReplaceTemplate($substline, 'catalog_table_line_1');
      }
      $admin_page .= $this->ReplaceTemplate($substtable, 'catalog_table');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    //Проверка мультфильма на независимость от ссылок
    $check = $this->db->ReceiveIDFieldsOnCondition(TOYS, 'Name', '`Catalog_ID` = '.$id);
    if (!$check) {
      if (!$arr_table)     exit('Нечего удалять');
      $subst = $this->GetTableLine($arr_table);
      $admin_page .= $this->ReplaceTemplate($subst, 'catalog_delete');
    }
    else {
      $subst['%catalogname%'] = $arr_table['Name'];
      $subst['%catalogid%'] = $arr_table['ID'];
      $admin_page .= $this->ReplaceTemplate($subst, 'catalog_del_impos');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $subst['%title%'] = 'Изменение данных записи - мультфильма';
    $subst['%catalogid%'] = $arr_table['ID'];
    $subst['%catalogname%'] = $arr_table['Name'];
    $subst['%description%'] = $arr_table['Description'];
    $subst['%keywords%'] = $arr_table['Keywords'];
    $subst['%priority%'] = $arr_table['Priority'];
    $subst['%publishfrom%'] = $arr_table['PublishFrom'];
    $admin_page .= $this->ReplaceTemplate($subst, 'catalog_edit');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Замена изображения в записи
  public function ChangeImage($id) {
    $admin_page = $this->general;
    //Получение массива изображений мультфильмов
    $arr_img = $this->db->ReceiveFewFieldsOnCondition(IMG, $this->smallfields, 'Kind', ' LIKE ', 'Мульт%');
    if (!$arr_img)     $admin_page .= $this->ReplaceTemplate(NULL, 'catalog_image_empty');
    else {
      $substimage['%imageline%'] = '';
      foreach ($arr_img as $row) {
        $substline['%pictid%'] = $row['ID'];
        $substline['%pictname%'] = $row['FileName'];
        $substline['%pictpath%'] = SITEURL.PICT;
        $substline['%pictalt%'] = $row['Alt'];
        $substimage['%imageline%'] .= $this->ReplaceTemplate($substline, 'catalog_image_line');
      }
      $substimage['%imageid%'] = $id;
      $admin_page .= $this->ReplaceTemplate($substimage, 'catalog_image');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    $admin_page = $this->general;
    $subst['%title%'] = 'Новая запись - мультфильм';
    //Получение массива изображений мультфильмов
    $arr_img = $this->db->ReceiveFewFieldsOnCondition(IMG, $this->smallfields, 'Kind', ' LIKE ', 'Мульт%');
    $arr_id = array();
    $arr_pic = array();
    $arr_id[0] = 0;
    $arr_pic[0] = 'emptymult';
    foreach ($arr_img as $value) {
      $arr_id[] = $value['ID'];
      $arr_pic[] = $value['FileName'];
    }
    $subst['%pictpath%'] = SITEURL.PICT;
    $subst['%str_id%'] = implode('~', $arr_id);
    $subst['%str_pic%'] = implode('~', $arr_pic);
    $admin_page .= $this->ReplaceTemplate($subst, 'catalog_insert');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
}

?>