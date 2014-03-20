<?php
session_start();

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class ToyItem extends TemplateHandler {
  protected $toyid;
  protected $multid;
  protected $subst;
  protected $subst_active;
  protected $subst_hidden;
  protected $subst_active_toy;
  protected $subst_hidden_toy;
  protected $subst_sight;
  protected $product;
  
  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->toyid = $_SESSION['toy'];
    $this->multid = $this->GetMultID($this->toyid);
    $this->subst['%toyid%'] = $this->toyid;
    $this->subst['%activefilm%'] = $this->GetActiveMult($this->multid);
    $this->subst['%hiddenfilms%'] = $this->GetHiddenMult($this->multid);
    $this->subst['%activetoynail%'] = $this->GetActiveToy($this->toyid);
    $this->subst['%toynails%'] = $this->GetHiddenToy($this->multid, $this->toyid);
    $this->subst['%toyname%'] = $this->subst_active_toy['%toyname%'];
    $this->subst['%sights%'] = $this->GetSights($this->toyid);
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Product Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Product Page Key Words...';
  }

  //Получение id мультфильма
  protected function GetMultID($toyid) {
    $arr = $this->db->ReceiveFieldOnCondition(TOYS, 'Catalog_ID', 'ID', '=', $toyid);
    return $arr[0];
  }

  //Получение шаблона вставки данного мультфильма
  protected function GetActiveMult($multid) {
    $this->subst_active['%multid%'] = $multid;
    $name = $this->db->ReceiveFieldOnCondition(MULTS, 'Name', 'ID', '=', $multid);
    $this->subst_active['%multname%'] = $name[0];
    $j = 11;
    $r_exp = '[\-\s]';
    $old_wrds = mb_split($r_exp, $name[0]);
    foreach ($old_wrds as $value) {
      $lng = mb_strlen($value, utf8);
      if ($lng > $j)  $new_wrds[] = mb_substr($value, 0, $j-1).".";
      else $new_wrds[] = $value;
    }
    $newname = str_replace($old_wrds, $new_wrds, $name[0]);
    $this->subst_active['%shortmultname%'] = $newname;
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
        $j = 11;
        $r_exp = '[\-\s]';
        $old_wrds = mb_split($r_exp, $arr[$i]['Name']);
        foreach ($old_wrds as $value) {
          $lng = mb_strlen($value, utf8);
          if ($lng > $j)  $new_wrds[] = mb_substr($value, 0, $j-1).".";
          else $new_wrds[] = $value;
        }
        $newname[$i] = str_replace($old_wrds, $new_wrds, $arr[$i]['Name']);
        unset($old_wrds);
        unset($new_wrds);
        $this->subst_hidden['%shortmultname%'] = $newname[$i];
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr[$i]['Image_ID']);
        $this->subst_hidden['%multfilename%'] = $img[0];
        $hiddens .= $this->ReplaceTemplate($this->subst_hidden, 'hiddenfilms');
      }
    }
    return $hiddens;
  }
  
  //Получение шаблона вставки ноготка активной игрушки данного мультфильма
  protected function GetActiveToy($toyid) {
    $this->subst_active_toy['%toyid%'] = $toyid;
    $name = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'ID', '=', $toyid);
    $this->subst_active_toy['%toyname%'] = $name[0];
    $cond = "`Product_ID` = ".$toyid." AND  `Priority` = 0";
    $arr = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
    $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr[0]);
    $this->subst_active_toy['%toyfilename%'] = $img[0];
    $active_toy = $this->ReplaceTemplate($this->subst_active_toy, 'activetoynail');
    return $active_toy;
  }
  
  //Получение шаблона вставки ноготков прочих игрушек, соответствующих мультфильму c id
  protected function GetHiddenToy($multid, $toyid) {
    $hiddentoys = '';
    //Получение общего числа всех игрушек данного мультфильма
    $cond = "`Catalog_ID` = ".$multid." AND `PublishFrom` < '".date("Y-m-d")."'";
    $arr = $this->db->ReceiveFieldOnManyConditions(TOYS, 'ID', $cond);
    $toy_num = $this->db->СountDataOnManyConditions(TOYS, $cond);
    for ($i = 0; $i < $toy_num; $i++) {
      if ($arr[$i] != $toyid) {
        $this->subst_hidden_toy['%toyid%'] = $arr[$i];
        $cond1 = "`Product_ID` = ".$arr[$i]." AND  `Priority` = 0";
        $arr1 = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond1);
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr1[0]);
        $this->subst_hidden_toy['%toyfilename%'] = $img[0];
        $toyname = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'ID', '=', $arr[$i]);
        $this->subst_hidden_toy['%toyname%'] = $toyname[0];
        $hiddentoys .= $this->ReplaceTemplate($this->subst_hidden_toy, 'toynails');
      }
    }
    return $hiddentoys;
  }
  
  //Получение шаблона вставки вставки главного вида данной игрушки
  protected function GetProduct($toyid) {
    $toyname = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'ID', '=', $toyid);
    $this->subst['%toyname%'] = $toyname[0];
    $cond = "`Product_ID` = ".$toyid." AND  `Priority` = 0";
    $arr = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
    $img = $this->db->ReceiveFewFieldsOnCondition(IMG, array('FileName', 'Width', 'Height'), 'ID', '=', $arr[0]);
    $this->subst['%toyfilename%'] = $img[0]['FileName'];
    $this->subst['%width%'] = $img[0]['Width'];
    $this->subst['%height%'] = $img[0]['Height'];
    $arr1 = $this->db->ReceiveFewFieldsOnCondition(TOYS, array('Price', 'Quantity', 'Deadline', 'Weight', 'Dimension', 'Material', 'Manufacture'), 'ID', '=', $toyid);
    $this->subst['%price%'] = $arr1[0]['Price'];
    $this->subst['%onstock%'] = $arr1[0]['Quantity'];
    
    $_SESSION[$toyid.'onstock'] = $arr1[0]['Quantity'];
    
    $this->subst['%howlong%'] = $arr1[0]['Deadline'];
    $this->subst['%weight%'] = $arr1[0]['Weight'];
    $this->subst['%dimension%'] = $arr1[0]['Dimension'];
    $this->subst['%composition%'] = $arr1[0]['Material'];
    $this->subst['%country%'] = $arr1[0]['Manufacture'];
  }
  
  //Получение шаблона вставки ноготков других видов данной игрушки
  protected function GetSights($toyid) {
    $toynails = '';
    $toy_num = $this->db->СountDataOnCondition(LIMG, 'Product_ID', '=', $toyid);
    if ($toy_num == 0)    return "";
    else {
      $arr = $this->db->ReceiveFewFieldsOnFullCondition(LIMG, array('Image_ID', 'Priority'), 'Product_ID', '=', $toyid, 'Priority', FALSE);
      for ($i = 0; $i < $toy_num; $i++) {
        if ($arr[$i]['Priority'] != 0) {
          $this->subst_sight['%sightnum%'] = $i+1;
          $toyname = $this->db->ReceiveFieldOnCondition(TOYS, 'Name', 'ID', '=', $toyid);
          $this->subst_sight['%toyname%'] = $toyname[0];
          $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr[$i]['Image_ID']);
          $this->subst_sight['%toyfilenailnum%'] = $img[0];
          $toynails .= $this->ReplaceTemplate($this->subst_sight, 'sights');
        }
      }
    }
    return $toynails;
  }
  
  
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    $this->GetProduct($this->toyid);
    $this->product = $this->ReplaceTemplate($this->subst, 'toyitem');
    
    if ($_SESSION['items'] == 0) {
      $ins = "<input class='dim' type='image' src='".PAGE."pictures/order_button.png' name='to_order' disabled />";
    }
    else {
      $ins =  "<input type='image' src='".PAGE."pictures/order_button.png' name='to_order' />";
    }
    $order_button =  "<td colspan='2' style='text-align: right;'><form name='order' action='index.php' method='get'>
			<input type='hidden' name='page' value='order' />
			".$ins."
			</form></td>
			</tr>
		</table>
		</div>";
    
    $toy_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/single.css' />"."<link rel='stylesheet' type='text/css' href='".PAGE."styles/catalogues.css' />".$this->general_2.$this->basket.$this->product.$order_button.$this->general_3;
    return $toy_html;
  }
  
}

?>