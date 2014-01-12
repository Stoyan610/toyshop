<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Catalog extends Admin {
  protected $tbl;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = 'A_CATALOG';
  }
  
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    echo 'Получи таблицу';
    echo '<br />';
  }
  //Добавление новой записи в таблицу БД
  public function InsertRow() {
    echo 'Вставляем новую запись';
  }
  //Изменение записи в таблице БД
  public function EditRow() {
    echo 'Изменяем информацию';
  }
  
  
  
  /*public function __destruct() {
  }
  */
}

?>