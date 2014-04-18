<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Client extends Admin {
  protected $tbl;
  protected $allfields;
  
  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = CLNTS;
    $this->allfields = array('ID', 'Name', 'Phone', 'Mail', 'Created', 'Changed');
  }
  
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields,'Changed', FALSE);
    if (!$arr_table)     exit ("Таблица пуста.<br />Клиент добавляется только при оформлении заказа.");
    $lines = count($arr_table);
    $substclient['%table_lines%'] = '';
    for ($i = 0; $i < $lines; $i++) {
      $subst['%clientid%'] = $arr_table[$i]['ID'];
      $subst['%clientname%'] = $arr_table[$i]['Name'];
      $subst['%ordercount%'] = $this->db->СountDataOnCondition(ORDS, 'Client_ID', '=', $subst['%clientid%']);
      $subst['%clientphone%'] = $arr_table[$i]['Phone'];
      $subst['%clientmail%'] = $arr_table[$i]['Mail'];
      $subst['%clientcreated%'] = $arr_table[$i]['Created'];
      $subst['%clientchanged%'] = $arr_table[$i]['Changed'];
      $substclient['%table_lines%'] .= $this->ReplaceTemplate($subst, 'client_table_line');
    }
    $admin_page .= $this->ReplaceTemplate($substclient, 'client_table');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Проверка клиента на независимость от заказов
  private function WhetherClient($id) {
    return $this->db->ReceiveIDFieldsOnCondition(ORDS, 'Changed', '`Client_ID` = '.$id);
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $subst['%clientid%'] = $id;
    $subst['%clientname%'] = $arr_table['Name'];
    $subst['%clientphone%'] = $arr_table['Phone'];
    $subst['%clientmail%'] = $arr_table['Mail'];
    $subst['%clientcreated%'] = $arr_table['Created'];
    $subst['%clientchanged%'] = $arr_table['Changed'];
    if (!$this->WhetherClient($id))     $admin_page .= $this->ReplaceTemplate($subst, 'client_delete');
    else     $admin_page .= $this->ReplaceTemplate($subst, 'client_del_impos');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $subst['%clientid%'] = $id;
    $subst['%clientname%'] = $arr_table['Name'];
    $subst['%clientphone%'] = $arr_table['Phone'];
    $subst['%clientmail%'] = $arr_table['Mail'];
    $admin_page .= $this->ReplaceTemplate($subst, 'client_edit');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Нового клиента может добавить только сам клиент через оформление заказа</h2>';
  }
 
}

?>