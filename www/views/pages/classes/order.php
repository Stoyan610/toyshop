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
    $this->subst['%Mos%'] = MOSC;
    $this->subst['%Area%'] = MAREA;
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
    
    $this->subst['%amount%'] = $amount;
    return $toys2order;
  }
  
  protected function GetClientData() {
    if (isset($_SESSION['thisorderid'])) {
      $this->subst['%Name%'] = htmlspecialchars($_SESSION['Name']);
      $this->subst['%Phone%'] = htmlspecialchars($_SESSION['Phone']);
      $this->subst['%Mail%'] = htmlspecialchars($_SESSION['Mail']);
      $this->subst['%DeliveryAddress%'] = htmlspecialchars($_SESSION['DeliveryAddress']);
      $this->subst['%DeliveryTime%'] = htmlspecialchars($_SESSION['DeliveryTime']);
      $this->subst['%Info%'] = htmlspecialchars($_SESSION['Info']);
    }
    else {
      $this->subst['%Name%'] = '';
      $this->subst['%Phone%'] = '';
      $this->subst['%Mail%'] = '';
      $this->subst['%DeliveryAddress%'] = '';
      $this->subst['%DeliveryTime%'] = '';
      $this->subst['%Info%'] = '';
    }
  }
    
  //--------!!!!!!!!!--------   Эта функция готова...  -------!!!!!!!!!--------
  //Получение строки html для составления домашней страницы
  public function CreatePage() {
        
    if ($_SESSION['DeliveryCost'] == MAREA) {
      $this->subst['%Moscheck%'] = '';
      $this->subst['%Areacheck%'] = 'checked';
    }
    else {
      $this->subst['%Moscheck%'] = 'checked';
      $this->subst['%Areacheck%'] = '';
    }
        
  
    if ($_SESSION['items'] == 0)     $this->order = "<h2>Ваша корзина пуста</h2><div style='text-align: center;'><img style='width: 500px;' src='views/pages/pictures/emptybasket.png' title='Корзина пуста' alt='emptybasket' /></div>";
    elseif (!isset($_SESSION['orderid'])) {
      $this->GetClientData();
      $this->order = $this->ReplaceTemplate($this->subst, 'orderbody');
    }
    else {
      // Расчёт стоимости доставки для включения в итоговую сумму
      if (isset($_SESSION['DeliveryCost'])) {
        $delivery_cost = $_SESSION['DeliveryCost'];
        $this->subst['%amount%'] += $delivery_cost;
      }


      $this->subst['%ordernumber%'] = $_SESSION['Number'];
      $this->order = $this->ReplaceTemplate($this->subst, 'ordermade');
    }
    
    $order_html = $this->general_1."<link rel='stylesheet' type='text/css' href='".PAGE."styles/order.css' />".$this->general_2.$this->basket.$this->order.$this->general_3;
    return $order_html;
  }
  
}

?>