<?php

echo '№ 7 - Класс обработчика шаблонов подключен<br />';

abstract class TemplateHandler {
  public $db;
  protected $subst_gen_0;
  protected $subst_gen_1;
  protected $subst_gen_2;
  protected $subst_basket;
  protected $subst_mgr;
  protected $subst_gen_3;
  public $general_1;
  public $general_2;
  public $general_3;
  public $basket;
  

  public function __construct($n_item) {
    $this->db = new DbRover();
    $this->subst_gen_1['%title_of_site%'] = TITLE;
    $this->subst_gen_0['%site_url%'] = VIEW.PAGE;
    $this->subst_mgr['%toyname%'] = $this->Toy4mgr(0);
    $this->subst_gen_2['%merry_go_round%'] = $this->GetMerryGoRound();
    $this->subst_basket['%n_item%'] = $n_item;
    $this->subst_basket['%suffix%'] = $this->GetSuffix($n_item);
    $this->subst_gen_3['%site_map%'] = $this->GetSiteMap();
    $this->subst_gen_3['%SEO%'] = $this->GetSEO();
    $this->general_1 = $this->GetReplacedTemplate($this->subst_gen_1, 'general');
    $this->general_2 = $this->GetReplacedTemplate($this->subst_gen_2, 'general2');
    $this->general_3 = $this->GetReplacedTemplate($this->subst_gen_3, 'general3');
    $this->basket = $this->GetReplacedTemplate($this->subst_basket, 'basket');
        
    
  }
  
  //Эта функция уже готова...
  //Получение правильного суффикса для "игрушек" в корзине
  protected function GetSuffix($n_item) {
    $ind = $n_item % 10;
    if ($n_item == 0) $n_item="нет";
    if (($n_item >= 10) && ($n_item <= 20)) $suff="ек";
    else {
      switch($ind) {
        case 1: {
          $suff="ка";
          break;
        }
        case 2: {
          $suff="ки";
          break;
        }
        case 3: {
          $suff="ки";
          break;
        }
        case 4: {
          $suff="ки";
          break;
        }
        default: {
          $suff="ек";
        }
      }
    }
    return $suff;
  }
  //Эта функция уже готова...
  //Получение массива из 24 имён игрушек для "карусели"
  protected function GetToys4mgr() {
    $n_mgr = 24;
    $toys = array();
    //Получение общего числа всех игрушек в базе данных
    $toy_num = $this->db->СountData(TOYS);
    if ($toy_num <= $n_mgr) {
      $rest = $n_mgr - $toy_num;
      $toys = $this->db->ReceiveField(TOYS, 'Name');
      for ($i = 0; $i < $rest; $i++) {
        $toys[] = EMPTY_TOY;
      }
    }
    else {
      $starttoy = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'Popularity', '>', 0);
      $toy_pop = count($starttoy);
      $rest = $n_mgr - $toy_pop;
      $resttoy = $this->db->ReceiveRandomOnCondition(TOYS, 'Name', 'Popularity', '=', 0, $rest);
      $toys = array_merge($starttoy, $resttoy);
    }
    return $toys;
  }
  //Эта функция уже готова...
  //Получение элемента массива из имён игрушек для "карусели"
  protected function Toy4mgr($i) {
    $arr = $this->GetToys4mgr();
    return $arr[$i];
  }
  
  
  //Получение строки html для составления карусели
  protected function GetMerryGoRound() {
    
    
    //$this->GetReplacedTemplate($this->subst_mgr, 'merrygoround');
    
    return 'Something';
  }
  
  
  
  protected function GetSiteMap() {
    return 'Some Site Map';
  }
  protected function GetSEO() {
    return 'Some SEO';
  }
  
  //Эта функция уже готова...
  //Замена параметров в строке
  protected function GetReplacedString($arr, $templ_str) {
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
  
  //Эта функция уже готова...
  //Получение строки из файла и замена в ней параметров
  protected function GetReplacedTemplate($arr, $file_name) {
    $content = file_get_contents(TEMPLATE.$file_name.".tpl");
    $next_content = $this->GetReplacedString($this->subst_gen_0, $content);
    return $this->GetReplacedString($arr, $next_content);
  }
  

  
  
  
  public function __destruct() {
  }
}

?>