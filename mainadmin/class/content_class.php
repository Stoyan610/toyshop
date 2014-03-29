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
    $this->allfields = array('ID', 'Category', 'Title', 'Brief', 'Text', 'Revision', 'PublishFrom');
  }

  private function FillFields($b) {
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    if (!$b) {
      $str = 'Таблица пуста';
      $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields, 'Category', TRUE);
    }  
    else {
      $str = 'Странно, но таблица пуста';
      $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, 'Category', '=', $b);
    }
    if (!$arr_table)     echo "<p>".$str."</p><a href='content.php?act=add' ".$stl.">Добавить информационную запись</a><br /><br />";
    else {
      $lines = count($arr_table);
      
      $arr_cat = $this->db->GetUniqField($this->tbl, 'Category');
      $num = count($arr_cat);
      echo "<a href='content.php?act=add' ".$stl.">Добавить информационную запись</a><br /><br /><form name='cat' action='content.php' method='get'><input type='hidden' name='act' value='part' /><select name='Cat'>";
      for ($j = 0; $j < $num; $j++) {
        echo "<option ";
        if ($j == 0) echo "selected ";
        echo "value='".$arr_cat[$j]['Category']."'>".$arr_cat[$j]['Category']."</option>";
      }
      echo "</select>&nbsp;&nbsp;<input type='submit' name='send' value='Найти записи по категории' /></form><br />";
      echo "<table name='info' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='6' /><col span='1' width='240px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Категория</td><td>Заголовок</td><td>Краткое содержание</td><td>Ревизия</td><td>Дата пуб-ции</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        foreach ($arr_table[$i] as $key => $val) {
          if ($key == 'ID') echo "<td rowspan='2'>".$val."</td>";
          elseif ($key == 'Revision') {
            if ($val)   echo "<td>Действует</td>";
            else        echo "<td>В запасе</td>";
          }
          elseif ($key != 'Text') echo "<td>".$val."</td>";
        }
        echo "<td rowspan='2'><ol><li><a href='content.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать данные</a></li><li><a href='content.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить данные</a></li></ol></td></tr><tr><td colspan='5' style='background-color: #CFFFCF;'>".htmlspecialchars_decode($arr_table[$i]['Text'])."</td></tr>";
      }
      echo "</table><br />";
    }  
  }

  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    echo '<h2>Таблица - Вся информация по содержанию сайта по категориям</h2>';
    $this->FillFields(FALSE);
  }

  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnField($x) {
    echo '<h2>Таблица - Содержание по выбранной категории</h2>';
    $this->FillFields($x);
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Эта запись будет удалена</h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    echo "<table name='deleting' cellspacing='0' cellpadding='3' border='1'><tr align='center' ".$stl0."><td>ID</td><td>Категория</td><td>Заголовок</td><td>Краткое содержание</td><td>Ревизия</td><td>Дата пуб-ции</td></tr><tr align='center'>";
    foreach ($arr_table as $key => $val) {
      if ($key == 'ID') echo "<td rowspan='2'>".$val."</td>";
      elseif ($key != 'Text') echo "<td>".$val."</td>";
    }
    echo "</tr><tr><td colspan='5' style='background-color: #CFFFCF;'>".$arr_table['Text']."</td></tr></table><br /><form name='deleting' action='deleting.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='hidden' name='choice' value='content' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form><br /><form name='cancel' action='deleting.php' method='post'><input type='hidden' name='choice' value='content' /><input type='submit' name='cancel' value='Отмена' /></form>";
  }
 
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Добавление новой записи - информации</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<form name='adding' action='adding.php' method='post'><table name='adding' cellspacing='0' cellpadding='5' border='1'  width='100%'>";
    $arr_cat = $this->db->GetUniqField($this->tbl, 'Category');
    $num = count($arr_cat);
    echo "<tr><td ".$stl.">Выбрать категорию</td><td><select name='Category'>";
    for ($j = 0; $j < $num; $j++) {
      echo "<option ";
      if ($j == 0) echo "selected ";
      echo "value='".$arr_cat[$j]['Category']."'>".$arr_cat[$j]['Category']."</option>";
    }
    echo "</select></td><td ".$stl.">или ввести новую категорию</td><td><input type='text' name='new_Cat' value='' /></td></tr></td></tr>";
    echo "<tr><td ".$stl.">Заголовок</td><td><input type='text' name='Title' value='' size='40' /></td><td ".$stl.">Краткое содержание</td><td><input type='text' name='Brief' value='' size='80' /></td></tr><tr><td ".$stl.">Полное содержание</td><td colspan='3'><div id='editor'></div></td></tr><tr><td ".$stl.">Ревизия</td><td><select name='Revision'><option selected value='1'>Действует</option><option value='0'>В запасе</option></select></td><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='' /></td></tr><tr><td colspan='4' align='right'><input type='hidden' name='choice' value='content' /><input type='submit' name='add' value='Подтверждаю добавление' /></td></tr></table></form>";
    echo "<form name='cancel' action='adding.php' method='post'><table name='cancel' cellspacing='0' cellpadding='5' border='1' width='100%'><tr><td align='right'><input type='hidden' name='choice' value='content' /><input type='submit' name='cancel' value='Отмена' /></td></tr></table></form><br />";
  }
 
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение данных записи - информации</h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<form name='editing' action='editing.php' method='post'><table name='editing' cellspacing='0' cellpadding='5' border='1'  width='100%'>";
    echo "<tr><td ".$stl.">ID</td><td colspan='3'><input type='text' name='ID' value='".$arr_table['ID']."' readonly /></td></tr>";
    $arr_cat = $this->db->GetUniqField($this->tbl, 'Category');
    $num = count($arr_cat);
    echo "<tr><td ".$stl.">Категория</td><td><select name='Category'>";
    for ($j = 0; $j < $num; $j++) {
      echo "<option ";
      if ($arr_cat[$j]['Category'] == $arr_table['Category']) echo "selected ";
      echo "value='".$arr_cat[$j]['Category']."'>".$arr_cat[$j]['Category']."</option>";
    }
    echo "</select></td><td ".$stl.">или ввести новую категорию</td><td><input type='text' name='new_Cat' value='' /></td></tr></td></tr>";
    echo "<tr><td ".$stl.">Заголовок</td><td><input type='text' name='Title' value='".$arr_table['Title']."' size='40' /></td><td ".$stl.">Краткое содержание</td><td><input type='text' name='Brief' value='".$arr_table['Brief']."' size='80' /></td></tr><tr><td ".$stl.">Полное содержание</td><td colspan='3'><div id='editor'>".htmlspecialchars_decode($arr_table['Text'])."</div></td></tr><tr><td ".$stl.">Ревизия</td><td><select name='Revision'>";
    if ($arr_table['Revision']) echo "<option selected value='1'>Действует</option><option value='0'>В запасе</option>";
    else echo "<option value='1'>Действует</option><option selected value='0'>В запасе</option>";
    echo "</select></td><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='".$arr_table['PublishFrom']."' /></td></tr><tr><td colspan='4' align='right'><input type='hidden' name='choice' value='content' /><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></table></form>";
    echo "<form name='cancel' action='adding.php' method='post'><table name='cancel' cellspacing='0' cellpadding='5' border='1' width='100%'><tr><td align='right'><input type='hidden' name='choice' value='content' /><input type='submit' name='cancel' value='Отмена' /></td></tr></table></form><br />";
  }
 
}

?>