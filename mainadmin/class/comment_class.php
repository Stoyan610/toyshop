<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Feedback extends Admin {
  protected $tbl;
  protected $allfields;
  
  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = REP;
    $this->allfields = array('ID', 'Date', 'Name', 'Content', 'PublishFrom');
  }
  
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields, 'Date', FALSE);
    if (!$arr_table)     $admin_page .= $this->ReplaceTemplate(NULL, 'feedback_empty');
    else {
      $lines = count($arr_table);
      $substcomment['%table_lines%'] = '';
      for ($i = 0; $i < $lines; $i++) {
        $subst['%commentid%'] = $arr_table[$i]['ID'];
        $subst['%commentdate%'] = $arr_table[$i]['Date'];
        $subst['%commentname%'] = $arr_table[$i]['Name'];
        $subst['%publishfrom%'] = $arr_table[$i]['PublishFrom'];
        $subst['%commenttext%'] = htmlspecialchars_decode($arr_table[$i]['Content']);
        $substcomment['%table_lines%'] .= $this->ReplaceTemplate($subst, 'feedback_table_line');
      }
      $admin_page .= $this->ReplaceTemplate($substcomment, 'feedback_table');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $subst['%commentid%'] = $id;
    $subst['%commentdate%'] = $arr_table['Date'];
    $subst['%commentname%'] = $arr_table['Name'];
    $subst['%publishfrom%'] = $arr_table['PublishFrom'];
    $subst['%commenttext%'] = htmlspecialchars_decode($arr_table['Content']);
    $admin_page .= $this->ReplaceTemplate($subst, 'feedback_delete');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    $admin_page = $this->general;
    $admin_page .= $this->ReplaceTemplate(NULL, 'feedback_insert');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $subst['%commentid%'] = $id;
    $subst['%commentname%'] = $arr_table['Name'];
    $subst['%commenttext%'] = htmlspecialchars_decode($arr_table['Content']);
    $subst['%commentdate%'] = $arr_table['Date'];
    $subst['%publishfrom%'] = $arr_table['PublishFrom'];
    $admin_page .= $this->ReplaceTemplate($subst, 'feedback_edit');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
 
}

?>