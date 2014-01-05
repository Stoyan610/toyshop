<?php

echo '№ 6 - Класс создателя домашней страницы подключен<br />';

//Подключеие абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Home extends TemplateHandler {
  protected $subst;
  public $homebody;

  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->subst['%greeting%'] = $this->GetGreeting();
    $this->subst['%multname-1%'] = $this->GetMult(1);
    $this->subst['%multname-2%'] = $this->GetMult(2);
    $this->subst['%multname-3%'] = $this->GetMult(3);
    $this->subst['%multname-4%'] = $this->GetMult(4);
    $this->subst['%multname-5%'] = $this->GetMult(5);
    $this->subst['%multname-6%'] = $this->GetMult(6);
    
  }
  
  protected function GetGreeting() {
    return '';
  }
  protected function GetMult($n) {
    return ''.$n;
  }
  
  
  //Пробная публична функция
  public function GetSubst() {
    $arr = array();
    $arr[0] = $this->subst['%n_item%'];
    $arr[1] = $this->subst['%suffix%'];
    return $arr;
  }
  
  
  public function __destruct() {
  }
}

?>