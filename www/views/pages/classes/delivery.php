<?php
session_start();

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Delivery extends TemplateHandler {
  protected $subst;
  protected $dlvr;
  
  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->subst['%delivery_details%'] = $this->GetDetails();
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Delivery Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Delivery Page Key Words...';
  }
  
  //Получение данных о доставке в html из файла tpl и замена параметров
  protected function GetDetails() {
    //Получение номера категории "Доставка" из базы данных
    $categ = $this->db->ReceiveFieldOnCondition(CAT, 'ID', 'Category', '=', 'Доставка');
    //Получение текста из базы данных
    $cond = "`Category_ID` = '".$categ[0]."' AND `Revision` = '1' AND `PublishFrom` <= '".date('Y-m-d')."'";
    $extract = $this->db->ReceiveFieldOnManyConditions(INFO, 'Text', $cond);
    return htmlspecialchars_decode($extract[0]);
  }
  
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    $this->dlvr = $this->ReplaceTemplate($this->subst, 'delivery');
    
    $dlvr_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/contacts.css' />".$this->general_2.$this->basket.$this->dlvr.$this->general_3;
    return $dlvr_html;
  }
  
}

?>