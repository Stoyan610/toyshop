<?php
session_start();

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Search extends TemplateHandler {
  protected $tbl;
  protected $search_phrase;
  protected $subst;
  protected $searchresult;
  
  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->tbl = array(array('title'=>TOYS, 'kind'=>'Игрушка', 'fields'=>array('Name', 'Description', 'Keywords', 'Manufacture', 'Material')), array('title'=>MULTS, 'kind'=>'Мультфильм', 'fields'=>array('Name', 'Description', 'Keywords')), array('title'=>REP, 'kind'=>'Отзыв', 'fields'=>array('Name', 'Content')), array('title'=>INFO, 'kind'=>'' ,'fields'=>array('Category', 'Title', 'Brief', 'Text')));
    $this->search_phrase = $_SESSION['search_phrase'];
    $this->subst['%searchphrase%'] = $this->search_phrase;
    $this->subst['%searchresultrows%'] = $this->GetResult($this->search_phrase);
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Search Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Search Page Key Words...';
  }
  
  //Получение шаблона результата поиска по запросу
  protected function GetResult($phrase) {
    if (($phrase == '') || ($phrase == 'Напиши, что найти...'))     return FALSE;
    //Разбиение фразы на отдельные слова
    $reg_sep = '~[\s,;]+~';
    $arr_phr = array();
    $arr_res = array();
    $arr_phr = preg_split($reg_sep, $phrase);
    $count = count($arr_phr);
    $tbl_num = count($this->tbl);
    $dt = date('Y-m-d');
    $result = '';
    $num = 0;
    for ($j = 0; $j < $tbl_num; $j++) {
      $field_num = count($this->tbl[$j]['fields']);
      $condition = "`PublishFrom`<'".$dt."' AND ";
      for ($i = 0; $i < $count; $i++) {
        if ($i != 0)    $condition .= " AND ";
        for ($k = 0; $k < $field_num; $k++) {
          if ($k == 0)    $condition .= "(";
          else    $condition .= " OR ";
          $condition .= "`".$this->tbl[$j]['fields'][$k]."` LIKE '%".addslashes($arr_phr[$i])."%' ";
        }
        $condition .= ")";
      }
      $arr_res[$j] = $this->db->ReceiveFieldOnManyConditions($this->tbl[$j]['title'], 'ID', $condition);
      if ($arr_res[$j][0] == '')        continue;
      foreach ($arr_res[$j] as $id) {
        $substres['%number%'] = ++$num;
        $x = $this->db->ReceiveFieldOnCondition($this->tbl[$j]['title'], $this->tbl[$j]['fields'][0], 'ID', '=', $id);
        $substres['%refname%'] = $x[0];
        $expr = '';
        for ($k = 0; $k < $field_num; $k++) {
          $xpr = $this->db->ReceiveFieldOnCondition($this->tbl[$j]['title'], $this->tbl[$j]['fields'][$k], 'ID', '=', $id);
          $flag = FALSE;
          for ($i = 0; $i < $count; $i++) {
            if (preg_match('~(^|\b).*'.$arr_phr[$i].'.*(\b|$)~', $xpr[0]))      $flag = TRUE;
          }
          if ($flag) {
            if ($expr != '')      $expr .= '; ';
            $expr .= $xpr[0];
          }
        }
        $expr = htmlspecialchars_decode($expr);
        $expr = preg_replace('~\<[^\<\>]+\>~', "", $expr);
        $y = 100000;
        for ($i = 0; $i < $count; $i++) {
          preg_match('~'.$arr_phr[$i].'~', $expr, $arr_match, PREG_OFFSET_CAPTURE);
          $koef = strlen($expr)/mb_strlen($expr, 'utf-8');
          $arr_match[0][1] = (int)($arr_match[0][1]/$koef);
          $y = min(array($y,  $arr_match[0][1]));
        }
        if (mb_strlen($expr, 'utf-8') > 200) {
          if ($y < 150)       $substres['%expression%'] = mb_substr($expr, 0, 200, 'utf-8').'...';
          else        $substres['%expression%'] = '...'.mb_substr($expr, $y - 150, 200, 'utf-8').'...';
        }
        else      $substres['%expression%'] = $expr;
        for ($i = 0; $i < $count; $i++) {
          $substres['%expression%'] = str_replace($arr_phr[$i], "<span class='yellow'>".$arr_phr[$i]."</span>", $substres['%expression%']);
        }
        if ($this->tbl[$j]['kind'] == '')       $substres['%kind%'] = $this->db->ReceiveFieldOnCondition($this->tbl[$j]['title'], 'Category', 'ID', '=', $id);
        else    $substres['%kind%'] = $this->tbl[$j]['kind'];
        $arr_kind = array('грушк'=>"toyitem&toy=".$id, 'ульт'=>"catalogue-next&mult=".$id, 'тзы'=>"comments", 'остав'=>"delivery", 'онтак'=>"contacts");
        $substres['%pagename%'] = "home";
        foreach ($arr_kind as $key => $val) {
          $regexp = "~^.*".$key.".*$~";
          if (preg_match($regexp, $substres['%kind%']))     $substres['%pagename%'] = $val;
        }
        $result .= $this->ReplaceTemplate($substres, 'search_line');
      }
    }
    if ($result == '')    $result = '%%%';
    return $result;
  }

  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    if (!$this->subst['%searchresultrows%'])     $this->searchresult = '<p id="empty">Пустой запрос</p>';
    elseif ($this->subst['%searchresultrows%'] == '%%%')     $this->searchresult = '<p id="empty">По запросу <b>"'.$this->search_phrase.'"</b> ничего не найдено</p>';
    else      $this->searchresult = $this->ReplaceTemplate($this->subst, 'search');
    $search_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/search.css' />".$this->general_2.$this->basket.$this->searchresult.$this->general_3;
    return $search_html;
  }

}

?>