<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

abstract class Admin {
  public $db;
  protected $subst_gen_0;
  protected $subst_gen_1;
  protected $subst_list;
  public $general;
  
  public function __construct($user, $pass) {
    $this->db = new DbRover($user, $pass);
    $this->subst_gen_0['%admin_url%'] = ADMINURL;
    $this->subst_gen_1['%title_of_admin%'] = 'Administrator page';
    $this->subst_list = array(array('%table%' => 'a_catalog', '%list_name%' => 'Категории'), array('%table%' => 'a_product', '%list_name%' => 'Товары'), array('%table%' => 'a_image', '%list_name%' => 'Изображения'), array('%table%' => 'a_client', '%list_name%' => 'Клиенты'), array('%table%' => 'a_order', '%list_name%' => 'Заказы'), array('%table%' => 'a_content', '%list_name%' => 'Контент'), array('%table%' => 'j_feedback', '%list_name%' => 'Отзывы'));
    $this->subst_gen_1['%list_item%'] = $this->GetList();
    $this->general = $this->ReplaceTemplate($this->subst_gen_1, 'general');
  }
  
  //Получение полной информации из данной таблицы БД 
  abstract public function GetTable();
  //Добавление новой записи в таблицу БД
  abstract public function InsertItem();
  //Изменение записи в таблице БД
  abstract public function EditItem($id);
  //Удаление записи в таблице БД
  abstract public function DeleteItem($id);

  //Замена параметров в строке
  protected function ReplaceString($arr, $templ_str) {
    if ($arr === NULL)      return $templ_str;
    $predecessor = array();
		$successor = array();
		$i = 0;
		foreach ($arr as $key => $value) {
			$predecessor[$i] = $key;
			$successor[$i] = $value;
			$i++;
		}
		return str_replace($predecessor, $successor, $templ_str);
  }
  //Получение строки html из файла tpl и замена в ней параметров
  protected function ReplaceTemplate($arr, $file_name) {
    $content = file_get_contents(ADMINURL."templates/".$file_name.".tpl");
    $next_content = $this->ReplaceString($this->subst_gen_0, $content);
    return $this->ReplaceString($arr, $next_content);
  }
  //Заполнение списка вызова таблиц
  protected function GetList() {
    $sub = '';
    foreach ($this->subst_list as $val)     $sub .= $this->ReplaceTemplate($val, 'list_item');
    return $sub;  
  }


  public function __destruct() {
  }
}

?>