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
    echo '<h2>Таблица - Все отзывы о сайте по дате</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    $str = 'Таблица пуста';
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields, 'Date', FALSE);
    if (!$arr_table)     echo "<p>".$str."</p><a href='feedback.php?act=add' ".$stl.">Вставить отзыв</a><br /><br />";
    else {
      $lines = count($arr_table);
      echo "<a href='feedback.php?act=add' ".$stl.">Вставить отзыв</a><br /><br />";
      echo "<table name='info' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='4' /><col span='1' width='240px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Дата</td><td>Автор</td><td>Дата пуб-ции</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        foreach ($arr_table[$i] as $key => $val) {
          if ($key == 'ID') echo "<td rowspan='2'>".$val."</td>";
          elseif ($key != 'Content') echo "<td>".$val."</td>";
        }  
        $stl2 = "style='height: 150px; overflow: auto;'";
        echo "<td rowspan='2'><ol><li><a href='feedback.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать отзыв</a></li><li><a href='feedback.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить отзыв</a></li></ol></td></tr><tr><td colspan='3' style='background-color: #CFFFCF;'><div ".$stl2.">".htmlspecialchars_decode($arr_table[$i]['Content'])."</div></td></tr>";
      }
      echo "</table><br />";
    }  
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Эта запись будет удалена</h2>';
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    echo "<table name='deleting' cellspacing='0' cellpadding='3' border='1'><tr align='center' ".$stl0."><td>ID</td><td>Дата</td><td>Автор</td><td>Дата пуб-ции</td></tr><tr align='center'>";
    foreach ($arr_table as $key => $val) {
      if ($key == 'ID') echo "<td rowspan='2'>".$val."</td>";
      elseif ($key != 'Content') echo "<td>".$val."</td>";
    }
    echo "</tr><tr><td colspan='3' style='background-color: #CFFFCF;'>".htmlspecialchars_decode($arr_table['Content'])."</td></tr></table><br /><form name='deleting' action='deleting.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='hidden' name='choice' value='feedback' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form><br /><form name='cancel' action='deleting.php' method='post'><input type='hidden' name='choice' value='feedback' /><input type='submit' name='cancel' value='Отмена' /></form>";
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Вставка нового отзыва</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<form name='adding' action='adding.php' method='post'><table name='adding' cellspacing='0' cellpadding='5' border='1' width='100%'><tr><td ".$stl.">Автор</td><td colspan='3'><input type='text' name='Name' value='' size='50' /></td></tr><tr><td colspan='4' ".$stl.">Содержание</td></tr><tr><td colspan='4'><div id='editor'></div></td></tr><tr><td ".$stl.">Дата</td><td><input type='text' id='pick' name='Date' value='' /></td><td ".$stl.">Дата публикации</td><td><input type='text' id='pick_next' name='PublishFrom' value='' /></td></tr><tr><td colspan='4' align='right'><input type='hidden' name='choice' value='feedback' /><input type='submit' name='add' value='Подтверждаю добавление' /></td></tr></table></form>";
    echo "<form name='cancel' action='adding.php' method='post'><table name='cancel' cellspacing='0' cellpadding='5' border='1' width='100%'><tr><td align='right'><input type='hidden' name='choice' value='feedback' /><input type='submit' name='cancel' value='Отмена' /></td></tr></table></form><br />";
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение данных записи - информации</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    echo "<form name='editing' action='editing.php' method='post'><table name='editing' cellspacing='0' cellpadding='5' border='1'  width='100%'><tr><td ".$stl.">ID</td><td><input type='text' name='ID' value='".$arr_table['ID']."' readonly /></td><td ".$stl.">Автор</td><td><input type='text' name='Name' value='".$arr_table['Name']."' size='50' readonly /></td></tr><tr><td colspan='4' ".$stl.">Содержание</td></tr><tr><td colspan='4'><div id='editor'>".htmlspecialchars_decode($arr_table['Content'])."</div></td></tr><tr><td ".$stl.">Дата</td><td><input type='text' name='Date' value='".$arr_table['Date']."' readonly /></td><td ".$stl.">Дата публикации</td><td><input type='text' id='pick_next' name='PublishFrom' value='".$arr_table['PublishFrom']."' /></td></tr><tr><td colspan='4' align='right'><input type='hidden' name='choice' value='feedback' /><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></table></form>";
    echo "<form name='cancel' action='editing.php' method='post'><table name='cancel' cellspacing='0' cellpadding='5' border='1' width='100%'><tr><td align='right'><input type='hidden' name='choice' value='feedback' /><input type='submit' name='cancel' value='Отмена' /></td></tr></table></form><br />";
  }
 
}

?>