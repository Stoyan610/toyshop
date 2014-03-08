<?php
session_start();

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class CatalogueFilm extends TemplateHandler {
  protected $multid;
  protected $subst;
  protected $subst_active;
  protected $subst_hidden;
  protected $subst_toy;
  public $cataloguefilm;
  
  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->multid = $_SESSION['mult'];
    $this->subst['%activefilm%'] = $this->GetActiveMult($this->multid);
    $this->subst['%activefilmname%'] = $this->subst_active['%multname%'];
    $this->subst['%hiddenfilms%'] = $this->GetHiddenMult($this->multid);
    $this->subst['%toynails%'] = $this->GetToysofMult($this->multid, 'toynails');
    $this->subst['%toysoffilm%'] = $this->GetToysofMult($this->multid, 'toysoffilm');
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Catalogue-next Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Catalogue-next Page Key Words...';
  }
   
  //Получение шаблона вставки данного мультфильма
  protected function GetActiveMult($multid) {
    $this->subst_active['%multid%'] = $multid;
    $name = $this->db->ReceiveFieldOnCondition(MULTS, 'Name', 'ID', '=', $multid);
    $this->subst_active['%multname%'] = $name[0];
    $arr1 = $this->db->ReceiveFieldOnCondition(MULTS, 'Image_ID', 'ID', '=', $multid);
    $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr1[0]);
    $this->subst_active['%multfilename%'] = $img[0];
    $active = $this->ReplaceTemplate($this->subst_active, 'activefilm');
    return $active;
  }
  
  //Получение шаблона вставки прочих мультфильмов
  protected function GetHiddenMult($multid) {
    $hiddens = '';
    //Получение общего числа всех мультфильмов в базе данных
    $mult_num = $this->db->СountDataOnCondition(MULTS, 'PublishFrom', '<', date("Y-m-d"));
    $arr = $this->db->ReceiveFewFieldsOnCondition(MULTS, array('ID', 'Name', 'Image_ID'), 'PublishFrom', '<', date("Y-m-d"));
    for ($i = 0; $i < $mult_num; $i++) {
      if ($arr[$i]['ID'] != $multid) {
        $this->subst_hidden['%multid%'] = $arr[$i]['ID'];
        $this->subst_hidden['%multname%'] = $arr[$i]['Name'];
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr[$i]['Image_ID']);
        $this->subst_hidden['%multfilename%'] = $img[0];
        $hiddens .= $this->ReplaceTemplate($this->subst_hidden, 'hiddenfilms');
      }
    }
    return $hiddens;
  }

  //Получение шаблона вставки в ряд ноготков игрушек, соответствующих мультфильму c id
  protected function GetToysofMult($multid, $arg1) {
    $toys = '';
    $cond = "`Catalog_ID` = ".$multid." AND `PublishFrom` < '".date("Y-m-d")."'";
    $arr = $this->db->ReceiveFieldOnManyConditions(TOYS, 'ID', $cond);
    $toy_num = $this->db->СountDataOnManyConditions(TOYS, $cond);
    if ($toy_num == 0)    return "В КАТАЛОГЕ ИГРУШЕК НЕТ !!!";
    else {
      for ($i = 0; $i < $toy_num; $i++) {
        $cond1 = "`Product_ID` = ".$arr[$i]." AND  `Priority` = 0";
        $arr1 = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond1);
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr1[0]);
        $this->subst_toy['%toyfilename%'] = $img[0];
        $this->subst_toy['%toyid%'] = $arr[$i];
        $toyname = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'ID', '=', $arr[$i]);
        $this->subst_toy['%toyname%'] = $toyname[0];
        $toys .= $this->ReplaceTemplate($this->subst_toy, $arg1);
      }
    }
    return $toys;
  }
 
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    $this->cataloguefilm = $this->ReplaceTemplate($this->subst, 'cataloguefilm');
    
    $cartoon_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/catalogue-next.css' />"."<link rel='stylesheet' type='text/css' href='".PAGE."styles/catalogues.css' />".$this->general_2.$this->basket.$this->cataloguefilm.$this->general_3;
    return $cartoon_html;
  }
  
}

?>