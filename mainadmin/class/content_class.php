<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Content extends Admin {
  protected $tbl;
  protected $allfields;
  protected $mostfields;
  
  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = INFO;
    $this->mostfields = array('ID', 'Category', 'Title', 'Brief', 'Revision', 'PublishFrom');
    $this->allfields = array('ID', 'Category', 'Title', 'Brief', 'Text', 'Revision', 'PublishFrom');
  }

  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    echo '<h2>Таблица - Вся информация по содержанию сайта по категориям</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->mostfields, 'Category', TRUE);
    if (!$arr_table)     echo "<p>Таблица пуста</p><a href='content.php?act=add' ".$stl.">Добавить информационную запись</a><br /><br />";
    else {
      $lines = count($arr_table);
      echo "<a href='content.php?act=add' ".$stl.">Добавить информационную запись</a><br /><br /><table name='info' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='6' /><col span='1' width='240px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Категория</td><td>Заголовок</td><td>Краткое содержание</td><td>Ревизия</td><td>Дата пуб-ции</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        foreach ($arr_table[$i] as $val)     echo "<td>".$val."</td>";
        echo "<td><ol><li><a href='content.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать данные</a></li><li><a href='content.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить данные</a></li></ol></td></tr>";
      }
      echo "</table><br />";
    }
  }

  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Эта запись будет удалена</h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->mostfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    echo "<table name='deleting' cellspacing='0' cellpadding='3' border='1'><tr align='center' ".$stl0."><td>ID</td><td>Категория</td><td>Заголовок</td><td>Краткое содержание</td><td>Ревизия</td><td>Дата пуб-ции</td></tr><tr align='center'>";
    foreach ($arr_table as $val)     echo "<td>".$val."</td>";
    echo "</tr></table><br /><form name='deleting' action='deleting.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='hidden' name='choice' value='content' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form><br /><form name='cancel' action='deleting.php' method='post'><input type='hidden' name='choice' value='content' /><input type='submit' name='cancel' value='Отмена' /></form>";
  }
 
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение данных записи - информации</h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='editing' cellspacing='0' cellpadding='5' border='1'><form name='editing' action='editing.php' method='post'>";
    echo "<tr><td ".$stl.">ID</td><td><input type='text' name='ID' value='".$arr_table['ID']."' readonly /></td></tr><tr><td ".$stl.">Категория</td><td><input type='text' name='Category' value='".$arr_table['Category']."' size='40' /></td></tr><tr><td ".$stl.">Заголовок</td><td><input type='text' name='Title' value='".$arr_table['Title']."' size='60' /></td></tr><tr><td ".$stl.">Краткое содержание</td><td><textarea name='Brief' rows='4' cols='50'>".$arr_table['Brief']."</textarea></td></tr><tr><td ".$stl.">Полное содержание</td><td><textarea id='req_text' name='Text' rows='15' cols='80'>".$arr_table['Text']."</textarea></td></tr><tr><td ".$stl.">Ревизия</td><td><input type='text' name='Revision' value='".$arr_table['Revision']."' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='".$arr_table['PublishFrom']."' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='hidden' name='choice' value='content' /><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='editing.php' method='post'><input type='hidden' name='choice' value='content' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Добавление новой записи - информации</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='adding' cellspacing='0' cellpadding='5' border='1'><form name='adding' action='adding.php' method='post' onSubmit='return mustbe()'>";
    echo "<tr><td ".$stl.">Категория</td><td><input id='req1' type='text' name='Category' value='' size='40' /> (* - обязательно)</td></tr><tr><td ".$stl.">Заголовок</td><td><input type='text' name='Title' value='' size='60' /></td></tr><tr><td ".$stl.">Краткое содержание</td><td><textarea name='Brief' rows='4' cols='50'></textarea></td></tr><tr><td ".$stl.">Полное содержание</td><td> (* - обязательно)<br /><textarea id='req_text' name='Text' rows='15' cols='80'></textarea></td></tr><tr><td ".$stl.">Ревизия</td><td><input type='text' name='Revision' value='' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='hidden' name='choice' value='content' /><input type='submit' name='add' value='Подтверждаю добавление' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='adding.php' method='post'><input type='hidden' name='choice' value='content' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
 
}

?>