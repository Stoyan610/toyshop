<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Content extends Admin {
  protected $tbl;
  protected $allfields;
  
  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = INFO;
    $this->allfields = array('ID', 'Category_ID', 'Title', 'Brief', 'Text', 'Revision', 'PublishFrom');
  }

  private function GetCategory($id) {
    $cat = $this->db->ReceiveFieldOnCondition(CAT, 'Category', 'ID', '=', $id);
    return $cat[0];
  }
  
  private function FillFields($b) {
    $admin_page = $this->general;
    if (!$b) {
      $substcontent['%title%'] = 'Вся информация по содержанию сайта по категориям';
      $substcontent['%empty%'] = 'Таблица пуста';
      $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields, 'Category_ID', TRUE);
    }
    else {
      $substcontent['%title%'] = 'Содержание по выбранной категории';
      $substcontent['%empty%'] = 'Странно, но таблица пуста';
      $b_id = $this->db->ReceiveFieldOnCondition(CAT, 'ID', 'Category', '=', $b);
      $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, 'Category_ID', '=', $b_id[0]);
    }
    
    if (!$arr_table)     $admin_page .= $this->ReplaceTemplate ($substcontent, 'content_empty');
    else {
      $lines = count($arr_table);
      $arr_cat = $this->db->ReceiveFieldOnCondition(CAT, 'Category', 'ID', '>', '0');
      $num = count($arr_cat);
      $substcontent['%options%'] = '';
      for ($j = 0; $j < $num; $j++) {
        if ($j == 0)    $subst['%selected%'] = 'selected';
        else    $subst['%selected%'] = '';
        $subst['%contentcat%'] = $arr_cat[$j];
        $substcontent['%options%'] .= $this->ReplaceTemplate($subst, 'content_table_opt');
      }
      $substcontent['%table_lines%'] = '';
      for ($i = 0; $i < $lines; $i++) {
        $substline['%contentid%'] = $arr_table[$i]['ID'];
        $substline['%contentcat%'] = $this->GetCategory($arr_table[$i]['Category_ID']);
        $substline['%contenttitle%'] = $arr_table[$i]['Title'];
        $substline['%contentbrief%'] = $arr_table[$i]['Brief'];
        if ($arr_table[$i]['Revision'])    $substline['%contentrevision%'] = 'Действует';
        else        $substline['%contentrevision%'] = 'В запасе';
        $substline['%publishfrom%'] = $arr_table[$i]['PublishFrom'];
        $substline['%contenttext%'] = htmlspecialchars_decode($arr_table[$i]['Text']);
        $substcontent['%table_lines%'] .= $this->ReplaceTemplate($substline, 'content_table_line');
      }
      $admin_page .= $this->ReplaceTemplate($substcontent, 'content_table');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    $this->FillFields(FALSE);
  }
  
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnField($x) {
    $this->FillFields($x);
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $subst['%contentid%'] = $arr_table['ID'];
    $subst['%contentcat%'] = $this->GetCategory($arr_table['Category_ID']);
    $subst['%contenttitle%'] = $arr_table['Title'];
    $subst['%contentbrief%'] = $arr_table['Brief'];
    $subst['%contentrevision%'] = $arr_table['Revision'];
    $subst['%publishfrom%'] = $arr_table['PublishFrom'];
    $subst['%contenttext%'] = htmlspecialchars_decode($arr_table['Text']);
    $admin_page .= $this->ReplaceTemplate($subst, 'content_delete');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    $admin_page = $this->general;
    $arr_cat = $this->db->ReceiveFieldOnCondition(CAT, 'Category', 'ID', '>', '0');
    $num = count($arr_cat);
    $substcontent['%options%'] = '';
    for ($j = 0; $j < $num; $j++) {
      if ($j == 0)      $subst['%selected%'] = 'selected';
      else      $subst['%selected%'] = '';
      $subst['%contentcat%'] = $arr_cat[$j];
      $substcontent['%options%'] .= $this->ReplaceTemplate($subst, 'content_table_opt');
    }
    $admin_page .= $this->ReplaceTemplate($substcontent, 'content_insert');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $substcontent['%contentid%'] = $id;
    $arr_cat = $this->db->ReceiveFieldOnCondition(CAT, 'ID', 'ID', '>', '0');
    $num = count($arr_cat);
    $substcontent['%options%'] = '';
    for ($j = 0; $j < $num; $j++) {
      if ($arr_cat[$j] == $arr_table['Category_ID'])     $subst['%selected%'] = 'selected';
      else      $subst['%selected%'] = '';
      $subst['%contentcat%'] = $this->GetCategory($arr_cat[$j]);
      $substcontent['%options%'] .= $this->ReplaceTemplate($subst, 'content_table_opt');
    }
    $substcontent['%contenttitle%'] = $arr_table['Title'];
    $substcontent['%contentbrief%'] = $arr_table['Brief'];
    $substcontent['%contenttext%'] = htmlspecialchars_decode($arr_table['Text']);
    if ($arr_table['Revision']) {
      $substcontent['%revisionselect1%'] = 'selected';
      $substcontent['%revisionselect2%'] = '';
    }
    else {
      $substcontent['%revisionselect2%'] = 'selected';
      $substcontent['%revisionselect1%'] = '';
    }
    $substcontent['%publishfrom%'] = $arr_table['PublishFrom'];
    $admin_page .= $this->ReplaceTemplate($substcontent, 'content_edit');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
 
}

?>