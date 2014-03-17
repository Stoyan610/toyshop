<?php
session_start();

//Запрет прямого обращения
defined('ACCESS') or die('Access denied');

//Подключение абстрактного класса обработчика шаблонов
require_once 'template_handler.php';

class Order extends TemplateHandler {
  protected $subst;
  protected $subst_toy;
  protected $order;
  
  public function __construct($n_item) {
    parent::__construct($n_item);
    $this->subst['%ordertoy%'] = $this->GetToys2order();
  }
  
  //Получение содержания для meta тега описания страницы
  public function DescrPage() {
    return 'Order Page Description...';
  }
  //Получение содержания для meta тега ключевых слов страницы
  public function KeywordsPage() {
    return 'Order Page Key Words...';
  }

  //Получение шаблона вставки игрушек для данного заказа
  protected function GetToys2order() {
    $toys2order = '';
    $amount = 0;
    //Получние списка id всех игрушек
    $arrid = $this->db->ReceiveFields(TOYS, array('ID'), $sort='ID');
    foreach ($arrid as $val) {
      $toyid = $val['ID'];
       if (isset($_SESSION[$toyid.'toyitems'])) {
        $this->subst_toy['%toyid%'] = htmlspecialchars($_SESSION[$toyid.'toyid']);
        $this->subst_toy['%toyname%'] = htmlspecialchars($_SESSION[$toyid.'toyname']);
        $cond = "`Product_ID` = ".$toyid." AND  `Priority` = 0";
        $arr1 = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
        $img = $this->db->ReceiveFieldOnCondition(IMG, 'FileName', 'ID', '=', $arr1[0]);
        $this->subst_toy['%toyfilename%'] = $img[0];
        $this->subst_toy['%quantity%'] = htmlspecialchars($_SESSION[$toyid.'toyitems']);
        $this->subst_toy['%price%'] = htmlspecialchars($_SESSION[$toyid.'toyprice']);
        
        if (!isset($_SESSION['orderid']))    $toys2order .= $this->ReplaceTemplate($this->subst_toy, 'ordertoy');
        else    $toys2order .= $this->ReplaceTemplate($this->subst_toy, 'ordertoy2');
        
        $amount += $this->subst_toy['%quantity%'] * $this->subst_toy['%price%'];
      }
    }
    
    //$discount = 
    //$delivery_cost = 
    
    $this->subst['%amount%'] = $amount;
    return $toys2order;
  }
  
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
    if ($_SESSION['items'] == 0)     $this->order = "<h2>Ваша корзина пуста</h2><div style='text-align: center;'><img style='width: 500px;' src='views/pages/pictures/emptybasket.png' title='Корзина пуста' alt='emptybasket' /></div>";
    elseif (!isset($_SESSION['orderid']))    $this->order = $this->ReplaceTemplate($this->subst, 'orderbody');
    else {
      $this->subst['%orderid%'] = $_SESSION['orderid'];
      $this->order = $this->ReplaceTemplate($this->subst, 'ordermade');
    }
    
    $order_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/order.css' />".$this->general_2.$this->basket.$this->order.$this->general_3;
    return $order_html;
  }
  
}

?>