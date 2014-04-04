<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Comment extends TemplateHandler {
  protected $arr_fields;
  protected $subst;
  protected $substcomment;
  
  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->arr_fields = array('Name', 'Content', 'Date', 'PublishFrom');
    $this->subst['%commenttext%'] = $this->GetComment();
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Comments Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Comments Page Key Words...';
  }
  
  //Получение отзывов html из файла tpl и замена в нём параметров
  protected function GetComment() {
    //Получение массива ID отзывов из базы данных
    $com_dat = $this->db->ReceiveFields(REP, $this->arr_fields, 'Date', FALSE);
    $extract = '';
    foreach ($com_dat as $val) {
      if ($val['PublishFrom'] <= date('Y-m-d')) {
        $this->substcomment['%commentator%'] = $val['Name'];
        $this->substcomment['%fulltext%'] = htmlspecialchars_decode($val['Content']);
        $this->substcomment['%issuedate%'] = $val['Date'];
      }
      $extract .= $this->ReplaceTemplate($this->substcomment, 'commenttext');  
    }
    if ($extract == '')     $extract = file_get_contents(TEMPLATE."comment_net.tpl");       return $extract;
  }
  
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    if (isset($_SESSION['comment'])) {
      $comm_data = $this->ReplaceTemplate(array('%issuedate%' => date('Y-m-d')), 'leavecomment');
      unset($_SESSION['comment']);
    }
    else {
      $comm_data = $this->ReplaceTemplate($this->subst, 'comments');
    }
    $comment_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/comments.css' />".$this->general_2.$this->basket.$comm_data.$this->general_3;
    return $comment_html;
  }

}

?>