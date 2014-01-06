<?php

echo '№ 6 - Класс создателя домашней страницы подключен<br />';

//Подключеие абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Home extends TemplateHandler {
  protected $subst;
  protected $mults;
  public $homebody;

  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->subst['%greeting%'] = $this->GetGreeting();
    $this->mults = $this->GetMult();
  }
  
  //Получение строки-приветствия html из файла tpl и замена в ней параметров
  protected function GetGreeting() {
    return 'Some Greeting!!!';
  }
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    
    return 'Home Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    
    return 'Home Page Key Words...';
  }  
  
  
  //Эта функция готова...
  //Получение массива из 6 названий случайных мультфильмов для домашней страницы
  protected function GetMult() {
    $mults = array();
    //Получение общего числа всех мультфильмов в базе данных
    $mult_num = $this->db->СountData(MULTS);
    if ($mult_num <= 6) {
      $startmult = $this->db->ReceiveField(MULTS, 'Mult_Name');
      $rep = ((6 % $mult_num) == 0) ? (6 - (6 % $mult_num)) / $mult_num : (6 - (6 % $mult_num)) / $mult_num + 1;
      for ($i = 0; $i < $rep; $i++) $mults = array_merge($mults, $startmult);
      while (count($mults) > 6) {
        array_pop($mults);
      }
    }
    else {
      $startmult = $this->db->ReceiveFieldOnCondition(MULTS, 'Mult_Name', 'Number_at_home', '>', 0, 6);
      if (count($startmult) < 6) {
        $restmult = $this->db->ReceiveRandomOnCondition(MULTS, 'Mult_Name', 'Number_at_home', '=', 0, (6 - count($startmult)));
        $mults = array_merge($startmult, $restmult);
      }
      else      $mults = $startmult;
    }
    return $mults;
  }
  //Эта функция готова...
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    //Создание массива для замены
    for ($i = 0; $i < 6; $i++) {
      $key = '%multname-'.$i.'%';
      $this->subst[$key] = $this->mults[$i];
    }
    $this->homebody = $this->GetReplacedTemplate($this->subst, 'homebody');
    $home_html = $this->general_1."<link rel='stylesheet' type='text/css' href='styles/home.css' />".$this->general_2.$this->basket.$this->homebody.$this->general_3;
    return $home_html;
  }
  
  
  
  
  //Пробная публичная функция
  public function GetSubst() {
    $arr = array();
    $arr[0] = $this->subst_basket['%n_item%'];
    $arr[1] = $this->subst_basket['%suffix%'];
    return $arr;
  }
  
  
  /*public function __destruct() {
  }
  */
}

?>