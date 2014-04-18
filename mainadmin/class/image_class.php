<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Images extends Admin {
  protected $tbl;
  protected $allfields;
  
  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = IMG;
    $this->allfields = array('ID', 'Kind', 'FileName', 'Width', 'Height', 'Alt'); 
  }
  
  //Вывод преложения для получения требуемой информации из данной таблицы БД 
  public function GetTable() {
    $admin_page = $this->general;
    $admin_page .= $this->ReplaceTemplate(NULL, 'image_tablechoice');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnKind($kind) {
    $admin_page = $this->general;
    $substimage['%imagekind%'] = $kind;
    $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, 'Kind', ' LIKE ', $kind.'%');
    if (!$arr_table)     $admin_page .= $this->ReplaceTemplate($substimage, 'image_empty');
    else {
      $lines = count($arr_table);
      $substimage['%imagelines%'] = '';
      for ($i = 0; $i < $lines; $i++) {
        $subst['%imageid%'] = $arr_table[$i]['ID'];
        $subst['%imagekind%'] = $arr_table[$i]['Kind'];
        $subst['%pictname%'] = $arr_table[$i]['FileName'];
        if ($arr_table[$i]['Kind'] == 'Игрушка') {
          $subst['%pictpath%'] = SITEURL.PICT.'toy70x70/';
          $subst['%minwidth%'] = 70;
          $subst['%minheight%'] = 70;
        }
        else {
          $subst['%pictpath%'] = SITEURL.PICT.'mult114x86/';
          $subst['%minwidth%'] = 114;
          $subst['%minheight%'] = 86;
        }
        $subst['%imagewidth%'] = $arr_table[$i]['Width'];
        $subst['%imageheight%'] = $arr_table[$i]['Height'];
        $subst['%imagealt%'] = $arr_table[$i]['Alt'];
        if ($this->WhetherActive($subst['%imageid%'], $subst['%imagekind%']))     $subst['%imageactive%'] = 'Не задействовано';
        else     $subst['%imageactive%'] = 'Используется';
        $substimage['%imagelines%'] .= $this->ReplaceTemplate($subst, 'image_table_line');
      }
      $admin_page .= $this->ReplaceTemplate($substimage, 'image_table');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
 
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
          $a = date_parse($date[0]);
          $date = mktime(0, 0, 1, $a['month'], $a['day'], $a['year']);
          if ($date <= $today)    return FALSE;
          else     return TRUE;
        }
      }
    }
    else {
      $date = $this->db->ReceiveFieldOnCondition(MULTS, 'PublishFrom', 'Image_ID', '=', $id);
      if (!$date)    return TRUE;
      else {
        $a = date_parse($date[0]);
        $date = mktime(0, 0, 1, $a['month'], $a['day'], $a['year']);
        if ($date <= $today)    return FALSE;
        else     return TRUE;
      }
    }
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять - такого изображения нет');
    $kind = $arr_table['Kind'];
    $subst['%imageid%'] = $id;
    $subst['%imagekind%'] = $kind;
    $subst['%pictname%'] = $arr_table['FileName'];
    if ($kind == 'Игрушка') {
      $subst['%pictpath%'] = SITEURL.PICT.'toy135x135/';
      $subst['%minwidth%'] = 135;
      $subst['%minheight%'] = 135;
    }
    else {
      $subst['%pictpath%'] = SITEURL.PICT.'mult114x86/';
      $subst['%minwidth%'] = 114;
      $subst['%minheight%'] = 86;
    }
    $subst['%imagewidth%'] = $arr_table['Width'];
    $subst['%imageheight%'] = $arr_table['Height'];
    $subst['%imagealt%'] = $arr_table['Alt'];
    if ($this->WhetherActive($id, $kind))     $admin_page .= $this->ReplaceTemplate ($subst, 'image_delete');
    else {
      if ($kind == 'Игрушка') {
        $subst['%resphref%'] = "product.php?act=part&field=Image_ID&value=".$id;
        $subst['%respitem%'] = 'игрушек';
      }
      else {
        $subst['%resphref%'] = "catalog.php?act=part&Image_ID=".$id;
        $subst['%respitem%'] = 'мультфильмов';
      }
      $admin_page .= $this->ReplaceTemplate ($subst, 'image_del_impos');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
   
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    $admin_page = $this->general;
    $admin_page .= $this->ReplaceTemplate(NULL, 'image_insert');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего редактировать - такого изображения нет');
    $kind = $arr_table['Kind'];
    $subst['%imageid%'] = $id;
    $subst['%imagekind%'] = $kind;
    $subst['%pictname%'] = $arr_table['FileName'];
    if ($kind == 'Игрушка') {
      $subst['%pictpath%'] = SITEURL.PICT.'toy135x135/';
      $subst['%minwidth%'] = 135;
      $subst['%minheight%'] = 135;
    }
    else {
      $subst['%pictpath%'] = SITEURL.PICT.'mult114x86/';
      $subst['%minwidth%'] = 114;
      $subst['%minheight%'] = 86;
    }
    $subst['%imagealt%'] = $arr_table['Alt'];
    $admin_page .= $this->ReplaceTemplate ($subst, 'image_edit');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
}

?>