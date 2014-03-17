<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Home extends TemplateHandler {
  protected $subst;
  protected $mults;
  protected $greeting;
  public $homebody;

  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->subst['%greeting%'] = $this->GetGreeting();
    $this->mults = $this->GetMult();
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Home Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Home Page Key Words...';
  }
  
  //Получение строки-приветствия html из файла tpl и замена в ней параметров
  protected function GetGreeting() {
    //Получение текста приветствия из базы данных
    $extract = $this->db->ReceiveFieldOnCondition(INFO, 'Title', 'Category', 'LIKE', '%ривет%');
    $this->greeting['%title_of_article%'] = $extract[0];
    $extract = $this->db->ReceiveFieldOnCondition(INFO, 'Brief', 'Category', 'LIKE', '%ривет%');
    $this->greeting['%hello%'] = $extract[0];
    $extract = $this->db->ReceiveFieldOnCondition(INFO, 'Text', 'Category', 'LIKE', '%ривет%');
    $this->greeting['%article_body%'] = $extract[0];
    return $this->ReplaceTemplate($this->greeting, 'greeting');
  }
  
  //Получение массива из 6 названий случайных мультфильмов для домашней страницы
  protected function GetMult() {
    $mults = array();
    //Получение общего числа всех мультфильмов в базе данных
    $mult_num = $this->db->СountDataOnCondition(MULTS, 'PublishFrom', '<', date("Y-m-d"));
    if ($mult_num == 0)    for ($i = 0; $i < 6; $i++)    $mults = 'emptymult';
    elseif ($mult_num <= 6) {
      $arr = $this->db->ReceiveFewFieldsOnCondition(MULTS, array('ID', 'Image_ID'), 'PublishFrom', '<', date("Y-m-d"));
      $n = count($arr);
      for ($i = 0; $i < 6; $i++) {
        $x = $i % $n;
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr[$x]['Image_ID']);
        $mults[$i]['img'] = $img[0];
        $mults[$i]['id'] = $arr[$x]['ID'];
      }
    }
    else {
      $arr = $this->db->ReceiveFewFieldsOnFullCondition(MULTS, array('ID', 'Image_ID'), 'PublishFrom', '<', date("Y-m-d"), 'RAND()', TRUE, 6);
      for ($i = 0; $i < 6; $i++) {       
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr[$i]['Image_ID']);
        $mults[$i]['img'] = $img[0];
        $mults[$i]['id'] = $arr[$i]['ID'];
      }
    }
    return $mults;
  }
  
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    //Создание массива мультфильмов для замены
    for ($i = 0; $i < 6; $i++) {
      $key = '%multname-'.$i.'%';
      $this->subst[$key] = $this->mults[$i]['img'];
      $key = '%multid-'.$i.'%';
      $this->subst[$key] = $this->mults[$i]['id'];
    }
    $this->homebody = $this->ReplaceTemplate($this->subst, 'homebody');
    $home_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/home.css' />".$this->general_2.$this->basket.$this->homebody.$this->general_3;
    return $home_html;
  }

}

?>