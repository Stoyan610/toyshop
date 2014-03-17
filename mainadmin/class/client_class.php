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
    echo '<h2>Таблица - Все клиенты по дате изменения</h2>';
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields,'Changed', FALSE);
    if (!$arr_table)     exit ("Таблица пуста.<br />Клиент добавляется только при оформлении заказа.");
    else {
      $lines = count($arr_table);
      echo "<table name='client' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='6' /><col span='1' width='240px' /></colgroup><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Имя</td><td>Телефон</td><td>Почта</td><td>Дата создания</td><td>Дата изменения</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        foreach ($arr_table[$i] as $val)     echo "<td>".$val."</td>";
        echo "<td><ol><li><a href='client.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать данные</a></li><li><a href='order.php?act=part&Client_ID=".$arr_table[$i]['ID']."'>Заказы клиента</a></li><li><a href='client.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить клиента</a></li></ol></td></tr>";
      }
      echo "</table><br />";
    }
  }

  //Проверка клиента на независимость от заказов
  private function WhetherClient($id) {
    return $this->db->ReceiveIDFieldsOnCondition(ORDS, 'Changed', '`Client_ID` = '.$id);
  }

  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Эта запись будет удалена</h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$this->WhetherClient($id)) {
      if (!$arr_table)     exit('Нечего удалять');
      echo "<table name='deleting' cellspacing='0' cellpadding='3' border='1'><tr align='center' style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'><td>ID</td><td>Имя</td><td>Телефон</td><td>Почта</td><td>Дата создания</td><td>Дата изменения</td></tr><tr align='center'>";
      foreach ($arr_table as $val)     echo "<td>".$val."</td>";
      echo "</tr></table><br /><form name='deleting' action='deleting.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='hidden' name='choice' value='client' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form><br /><form name='cancel' action='deleting.php' method='post'><input type='hidden' name='choice' value='client' /><input type='submit' name='cancel' value='Отмена' /></form>";
    }
    else     echo "<p>К сожалению, удалить клиента `".$arr_table['Name']."`  невозможно, так как с ним связаны существующие заказы</p><a href='order.php?act=part&Client_ID=".$id."'>Показать список соответствующих заказов?</a> или <a href='client.php'>Вернуться к списку клиентов?</a>";
  }
 
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение данных записи - клиента </h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='editing' cellspacing='0' cellpadding='5' border='1'><form name='editing' action='editing.php' method='post'>";
    echo "<tr><td ".$stl.">ID</td><td><input type='text' name='ID' value='".$arr_table['ID']."' readonly /></td></tr><tr><td ".$stl.">Имя</td><td><input type='text' name='Name' value='".$arr_table['Name']."' /></td></tr><tr><td ".$stl.">Телефон</td><td><input type='text' name='Phone' value='".$arr_table['Phone']."' /></td></tr><tr><td ".$stl.">Почта</td><td><input type='text' name='Mail' value='".$arr_table['Mail']."' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='hidden' name='choice' value='client' /><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='editing.php' method='post'><input type='hidden' name='choice' value='client' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Нового клиента может добавить только сам клиент через оформление заказа</h2>';
  }
 
}

?>