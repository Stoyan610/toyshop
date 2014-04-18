<?php

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Catalogue extends TemplateHandler {
  protected $subst;
  protected $subst_row;
  protected $subst_toy;
  public $cataloguemain;

  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->subst['%rowsfilmandtoys%'] = $this->GetMultsandToys();
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Catalogue Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Catalogue Page Key Words...';
  }
  
  //Получение шаблона вставки в таблицу рядов с фильмами и соответствующими игрушками
  protected function GetMultsandToys() {
    $rows = '';
    //Получение общего числа всех мультфильмов в базе данных
    $mult_num = $this->db->СountDataOnCondition(MULTS, 'PublishFrom', '<', date("Y-m-d"));
    if ($mult_num == 0)    return "КАТАЛОГОВ И ИГРУШЕК НЕТ !!!";
    else {
      $arr = $this->db->ReceiveFewFieldsOnCondition(MULTS, array('ID', 'Name', 'Image_ID'), 'PublishFrom', '<', date("Y-m-d"));
      for ($i = 0; $i < $mult_num; $i++) {
        $this->subst_row['%multid%'] = $arr[$i]['ID'];
        $this->subst_row['%multname%'] = $arr[$i]['Name'];
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr[$i]['Image_ID']);
        $this->subst_row['%multfilename%'] = $img[0];
        $wtoys = $this->GetToysofMult($arr[$i]['ID']);
        if ($wtoys)     $this->subst_row['%toysofmult%'] = $wtoys;
        else          continue;
        $rows .= $this->ReplaceTemplate($this->subst_row, 'rowsfilmandtoys');
      }
    }
    return $rows;
  }
  
  //Получение шаблона вставки игрушек, соответствующих мультфильму c id
  protected function GetToysofMult($id) {
    $toys = '';
    //Получение общего числа всех игрушек, соответствующих мультфильму
    $cond = "`Catalog_ID` = ".$id." AND `PublishFrom` < '".date("Y-m-d")."'";
    $arr = $this->db->ReceiveFieldOnManyConditions(TOYS, 'ID', $cond);
    $toy_num = $this->db->СountDataOnManyConditions(TOYS, $cond);
    if ($toy_num == 0)    return FALSE;
    else {
      for ($i = 0; $i < $toy_num; $i++) {
        $cond1 = "`Product_ID` = ".$arr[$i]." AND  `Priority` = 0";
        $arr1 = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond1);
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr1[0]);
        $this->subst_toy['%toyfilename%'] = $img[0];
        $this->subst_toy['%toyid%'] = $arr[$i];
        $toyname = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'ID', '=', $arr[$i]);
        $this->subst_toy['%toyname%'] = $toyname[0];
        $toys .= $this->ReplaceTemplate($this->subst_toy, 'toysoffilm');
      }
    }
    return $toys;
  }
  
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    $this->cataloguemain = $this->ReplaceTemplate($this->subst, 'cataloguemain');
    $cartoon_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/catalogue.css' />"."<link rel='stylesheet' type='text/css' href='".PAGE."styles/catalogues.css' />".$this->general_2.$this->basket.$this->cataloguemain.$this->general_3;
    return $cartoon_html;
  }
  
}

?>