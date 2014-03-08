<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

abstract class TemplateHandler {
  public $db; 
  protected $subst_gen_0;
  protected $subst_gen_1;
  protected $subst_gen_2;
  protected $subst_gen_3;
  protected $subst_basket;
  public $general_1;
  public $general_2;
  public $general_3;
  public $basket;
  protected $subst_mgr;
  
  public function __construct($n_item) {
    $this->db = new DbRover(DB_USER, DB_PASS);
    $this->subst_gen_0['%site_url%'] = PAGE;
    $this->subst_gen_1['%title_of_site%'] = TITLE;
    $this->subst_gen_1['%present_page_description%'] = $this->DescrPage();
    $this->subst_gen_1['%present_page_key_words%'] = $this->KeywordsPage();
    $this->subst_gen_2['%merry_go_round%'] = $this->GetMerryGoRound();
    $this->subst_gen_3['%site_map%'] = $this->GetSiteMap();
    $this->subst_gen_3['%SEO%'] = $this->GetSEO();
    $this->general_1 = $this->ReplaceTemplate($this->subst_gen_1, 'general');
    $this->general_2 = $this->ReplaceTemplate($this->subst_gen_2, 'general2');
    $this->general_3 = $this->ReplaceTemplate($this->subst_gen_3, 'general3');
    $this->subst_basket['%n_item%'] = $n_item;
    $this->subst_basket['%suffix%'] = $this->GetSuffix($n_item);
    $this->basket = $this->ReplaceTemplate($this->subst_basket, 'basket');
  }
   
  //Получение содержания для meta тега описания страницы
  abstract public function DescrPage();
  //Получение содержания для meta тега ключевых слов страницы
  abstract public function KeywordsPage();
  //Получение строки html для составления страницы
  abstract public function CreatePage();

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
    $content = file_get_contents(TEMPLATE.$file_name.".tpl");
    $next_content = $this->ReplaceString($this->subst_gen_0, $content);
    return $this->ReplaceString($arr, $next_content);
  }
  
  //Получение строки html для составления "карусели"
  protected function GetMerryGoRound() {
    $toy4mgr = $this->GetToys4mgr();
    $str = '';
    for ($i = 0; $i < N_MGR; $i++) {
      
      
      $this->subst_mgr['%toyid%'] = $toy4mgr[$i]['id'];
      $this->subst_mgr['%toyfilename%'] = $toy4mgr[$i]['img'];
      $this->subst_mgr['%toyname%'] = $toy4mgr[$i]['name'];
      
      
      $str .= $this->ReplaceTemplate($this->subst_mgr, 'merrygoround');
    }
    return $str;
  }
 
  //Получение массива из 24 имён игрушек для "карусели"
  protected function GetToys4mgr() {
    $toys = array();
    //Получение общего числа всех игрушек в базе данных
    $toy_num = $this->db->СountDataOnCondition(TOYS, 'PublishFrom', '<', date("Y-m-d"));
    if ($toy_num == 0)    for ($i = 0; $i < N_MGR; $i++)    $toys[$i] = 'emptytoy';
    
    elseif ($toy_num <= N_MGR) {
      $arr = $this->db->ReceiveFewFieldsOnCondition(TOYS, array('ID', 'Name'), 'PublishFrom', '<', date("Y-m-d"));
      $n = count($arr);
      for ($i = 0; $i < N_MGR; $i++) {
        $x = $i % $n;
        $cond = "`Product_ID` = ".$arr[$x]['ID']." AND  `Priority` = 0";
        $arr1 = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr1[0]);
        $toys[$i]['img'] = $img[0];
        $toys[$i]['id'] = $arr[$x]['ID'];
        $toys[$i]['name'] = $arr[$x]['Name'];
      }
    }
    else {
      $arr = $this->db->ReceiveFieldOnFullCondition(TOYS, 'ID', 'PublishFrom', '<', date("Y-m-d"), 'Popularity', FALSE, N_MGR);
      for ($i = 0; $i < N_MGR; $i++) {
        $cond = "`Product_ID` = ".$arr[$i]." AND  `Priority` = 0";
        $arr1 = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr1[0]);
        $toys[$i]['img'] = $img[0];
        $toys[$i]['id'] = $arr[$i];
        $name = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'ID', '=', $arr[$i]);
        $toys[$i]['name'] = $name[0];
      }
    }
    return $toys;
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
  

  public function __destruct() {
  }
}

?>