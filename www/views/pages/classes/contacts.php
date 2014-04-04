<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Contact extends TemplateHandler {
  protected $subst;
  
  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->subst['%contactlink%'] = $this->GetContact();
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Contacts Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Contacts Page Key Words...';
  }
  
  //Получение контактов в html из файла tpl и замена параметров
  protected function GetContact() {
    //Получение текста из базы данных
    $cond = "`Category` LIKE '%онтак%' AND `Revision` = '1' AND `PublishFrom` <= '".date('Y-m-d')."'";
    $extract = $this->db->ReceiveFieldOnManyConditions(INFO, 'Text', $cond);
    return htmlspecialchars_decode($extract[0]);
  }
  
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    $cont_info = $this->ReplaceTemplate($this->subst, 'contacts');
    $contact_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/contacts.css' />".$this->general_2.$this->basket.$cont_info.$this->general_3;
    return $contact_html;
  }

}

?>