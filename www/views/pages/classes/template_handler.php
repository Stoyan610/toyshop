<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

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
    $this->db = new DbRover(DB_USER, DB_PASS);
    $this->subst_gen_0['%site_url%'] = VIEW.PAGE;
    $this->subst_gen_1['%title_of_site%'] = TITLE;
    $this->subst_gen_1['%present_page_description%'] = $this->DescrPage();
    $this->subst_gen_1['%resent_page_key_words%'] = $this->KeywordsPage();
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
    
//Эти функции уже готовы...
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
  //Получение строки html из файла tpl и замена в ней параметров
  protected function GetReplacedTemplate($arr, $file_name) {
    $content = file_get_contents(TEMPLATE.$file_name.".tpl");
    $next_content = $this->GetReplacedString($this->subst_gen_0, $content);
    return $this->GetReplacedString($arr, $next_content);
  }
  //Получение массива из 24 имён игрушек для "карусели"
  protected function GetToys4mgr() {
    $toys = array();
    //Получение общего числа всех игрушек в базе данных
    $toy_num = $this->db->СountData(TOYS);
    if ($toy_num == 0)    for ($i = 0; $i < N_MGR; $i++)    $toys = EMPTY_TOY;
    if ($toy_num <= N_MGR) {
      $starttoy = $this->db->ReceiveField(TOYS, 'Name');
      $rep = ((N_MGR % $toy_num) == 0) ? (N_MGR - (N_MGR % $toy_num)) / $toy_num : (N_MGR - (N_MGR % $toy_num)) / $toy_num + 1;
      for ($i = 0; $i < $rep; $i++)      $toys = array_merge($toys, $starttoy);
      while (count($toys) > N_MGR) {
        array_pop($toys);
      }
    }
    else {
      $starttoy = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'Popularity', '>', 0);
      $toy_pop = count($starttoy);
      if ($toy_pop < N_MGR) {
        $resttoy = $this->db->ReceiveRandomOnCondition(TOYS, 'Name', 'Popularity', '=', 0, (N_MGR - $toy_pop));
        $toys = array_merge($starttoy, $resttoy);
      }
      else      $toys = $starttoy;
    }
    return $toys;
  }
  //Получение строки html для составления "карусели"
  protected function GetMerryGoRound() {
    $toy4mgr = $this->GetToys4mgr();
    $mgr = $this->GetReplacedTemplate(NULL, 'merrygoround');
    $str = '';
    for ($i = 0; $i < N_MGR; $i++) {
      $this->subst_mgr['%toyname%'] = $toy4mgr[$i];
      $str .= $this->GetReplacedString($this->subst_mgr, $mgr);
    }
    return $str;
  }
  //Получение правильного суффикса для слова "игрушки" в корзине
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
  
  
  
  
  
  protected function GetSiteMap() {
    return 'Some Site Map';
  }
  protected function GetSEO() {
    return 'Some SEO';
  }
  //Получение содержания для meta тега описания страницы
  abstract public function DescrPage();
  //Получение содержания для meta тега ключевых слов страницы
  abstract public function KeywordsPage();
  //Получение строки html для составления страницы
  abstract public function CreatePage();






  public function __destruct() {
  }
}

?>