<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

abstract class Admin {
  public $db;
  
  public function __construct($user, $pass) {
    $this->db = new DbRover($user, $pass);
  }
  
  //Получение полной информации из данной таблицы БД 
  abstract public function GetTable();
  //Добавление новой записи в таблицу БД
  abstract public function InsertItem();
  //Изменение записи в таблице БД
  abstract public function EditItem($id);
  //Удаление записи в таблице БД
  abstract public function DeleteItem($id);
  

  public function __destruct() {
  }
}

?>