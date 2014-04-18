<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Order extends Admin {
  protected $tbl;
  protected $allfields;
  protected $basketfields;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = ORDS;
    $this->allfields = array('ID', 'Number', 'Client_ID', 'DeliveryAddress', 'DeliveryTime', 'Info', 'Created', 'Changed'); 
    $this->basketfields = array('Product_ID', 'Name', 'Price', 'Quantity');
  }

  protected function GetTableLine($arr) {
    $subst = array();
    $subst['%orderid%'] = $arr['ID'];
    $subst['%ordernumber%'] = $arr['Number'];
    //Получение данных о клиенте
    $field_list = array('Name', 'Phone', 'Mail');
    $client = $this->db->ReceiveFieldsOnId(CLNTS, $field_list, $arr['Client_ID']);
    $subst['%clientname%'] = $client['Name'];
    $subst['%clientphone%'] = $client['Phone'];
    $subst['%clientmail%'] = $client['Mail'];
    //Получение данных о товарах в корзине
    $goods = $this->db->ReceiveFewFieldsOnCondition(BASKET, $this->basketfields, 'Order_ID', '=', $arr['ID']);
    $count = count($goods);
    $subst['%ordersum%'] = 0;
    $subst['%ordergoods%'] = '';
    for ($j = 0; $j < $count; $j++) {
      $subst['%ordergoods%'] .= "<li>".$goods[$j]['Name']."</li>";
      $subst['%ordersum%'] += $goods[$j]['Price']*$goods[$j]['Quantity'];
    }
    $subst['%address%'] = $arr['DeliveryAddress'];
    $subst['%deliverytime%'] = $arr['DeliveryTime'];
    $subst['%deliveryinfo%'] = $arr['Info'];
    $subst['%created%'] = $arr['Created'];
    $subst['%changed%'] = $arr['Changed'];
    return $subst;
  }

  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields, 'Created', FALSE);
    if (!$arr_table)     $admin_page .= $this->ReplaceTemplate(NULL, 'order_empty');
    else {
      $lines = count($arr_table);
      $substtable['%table_name%'] = 'Все заказы по дате оформления';
      $substtable['%table_lines%'] = '';
      for ($i = 0; $i < $lines; $i++) {
        $substline = $this->GetTableLine($arr_table[$i]);
        $substtable['%table_lines%'] .= $this->ReplaceTemplate($substline, 'order_table_line');
      }
      $admin_page .= $this->ReplaceTemplate($substtable, 'order_table');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
 
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnField($field, $x) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, $field, '=', $x);
    if (!$arr_table) {
      $sub_x['%delid%'] = $x;
      $admin_page .= $this->ReplaceTemplate($sub_x, 'order_empty_1');
    }
    else {
      $lines = count($arr_table);
      $substtable['%table_name%'] = 'Выбранные заказы';
      $substtable['%table_lines%'] = '';
      for ($i = 0; $i < $lines; $i++) {
        $substline = $this->GetTableLine($arr_table[$i]);
        $substtable['%table_lines%'] .= $this->ReplaceTemplate($substline, 'order_table_line');
      }
      $admin_page .= $this->ReplaceTemplate($substtable, 'order_table');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Получение информации из корзины для данного заказа из БД 
  public function GetBasket($id) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFewFieldsOnCondition(BASKET, $this->basketfields, 'Order_ID', '=', $id);
    if (!$arr_table)     echo "<p>Корзина пуста</p><br /><br />";
    else {
      $lines = count($arr_table);
      $arrnum = $this->db->ReceiveFieldsOnId($this->tbl, array('Number'), $id);
      $substbask['%ordernumber%'] = $arrnum['Number'];      
      $substbask['%basket_lines%'] = '';      
      for ($i = 0; $i < $lines; $i++) {
        //Получение изображения игрушки
        $cond = "`Product_ID` = '".$arr_table[$i]['Product_ID']."' AND `Priority` = '0'";
        $arr_img = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
        if (!$arr_img)    $substline['%filename%'] = 'emptytoy';
        else {
          $Image_ID = $arr_img[0];
          $pictures = $this->db->ReceiveFieldsOnId(IMG, array('FileName'), $Image_ID);
          $substline['%filename%'] = $pictures['FileName'];
        }
        $substline['%pict_path%'] = SITEURL.PICT;
        $substline['%toyname%'] = $arr_table[$i]['Name'];
        $substline['%toyprice%'] = $arr_table[$i]['Price'];
        $substline['%toyquantity%'] = $arr_table[$i]['Quantity'];
        
        $substbask['%basket_lines%'] .= $this->ReplaceTemplate($substline, 'order_basket_line');
      }
      $admin_page .= $this->ReplaceTemplate($substbask, 'order_basket');
    }
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $substline = $this->GetTableLine($arr_table);
    $admin_page .= $this->ReplaceTemplate($substline, 'order_delete');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }

  //Получение массива данных и изображений игрушек и превращение его в строку
  private function ToyImg2string($bask_toys) {
    $n = count($bask_toys);
    $arr_toy = $this->db->ReceiveFields(TOYS, array('ID', 'Name', 'Price', 'Quantity', 'Deadline'));
    foreach ($arr_toy as $key => $val) {
      $flag = TRUE;
      for ($i = 0; $i < $n; $i++)     if ($bask_toys[$i] == $val['ID']) $flag = FALSE;
      if ($flag) {
        $cond = "`Product_ID` = '".$val['ID']."' AND `Priority` = '0'";
        $arr_img = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
        if (!$arr_img) $arr_toy[$key]['Pic'] = 'emptytoy';
        else {
          $Image_ID = $arr_img[0];
          $pic = $this->db->ReceiveFieldsOnId(IMG, array('FileName'), $Image_ID);
          $arr_toy[$key]['Pic'] = $pic['FileName'];
        }
        $str_rows[$key] = implode('~', $arr_toy[$key]);
      }
    }
    $str_toys = implode('^', $str_rows);
    return $str_toys;
  }
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    $admin_page = $this->general;
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $substedit['%title%'] = 'Изменение данных записи - заказа';
    $substedit['%orderid%'] = $arr_table['ID'];
    $substedit['%ordernumber%'] = $arr_table['Number'];
    //Получение массива данных клиента
    $arr_clnt = $this->db->ReceiveFieldsOnId(CLNTS, array('Name', 'Phone', 'Mail'), $arr_table['Client_ID']);
    $substedit['%clientname%'] = $arr_clnt['Name'];
    $substedit['%clientphone%'] = $arr_clnt['Phone'];
    $substedit['%clientmail%'] = $arr_clnt['Mail'];
    $substedit['%orderaddress%'] = $arr_table['DeliveryAddress'];
    $substedit['%ordertime%'] = $arr_table['DeliveryTime'];
    $substedit['%orderinfo%'] = $arr_table['Info'];
    //Получение данных о корзине
    $goods = $this->db->ReceiveFewFieldsOnCondition(BASKET, array('ID', 'Product_ID', 'Name', 'Price', 'Quantity'), 'Order_ID', '=', $id);
    $num = count($goods);
    //Получение общего количества игрушек
    $all = $this->db->СountData(TOYS);
    $substedit['%ordertoy%'] = '';
    for ($i = 0; $i < $all; $i++) {
      $substtoy['%ind%'] = $i;
      $substtoy['%toyname%'] = $goods[$i]['Name'];
      $substtoy['%toyid%'] = $goods[$i]['ID'];
      $substtoy['%toyprice%'] = $goods[$i]['Price'];
      $substtoy['%toyquantity%'] = $goods[$i]['Quantity'];
      if ($i < $num)      $substedit['%ordertoy%'] .= $this->ReplaceTemplate($substtoy, 'order_toy_1');
      else        $substedit['%ordertoy%'] .= $this->ReplaceTemplate(array('%ind%' => $i), 'order_toy_2');
    }
    for ($i = 0; $i < $num; $i++)     $bask_toys[$i] = $goods[$i]['Product_ID'];
    //Получение строки из массива данных и изображений игрушек
    $substedit['%strtoys%'] = $this->ToyImg2string($bask_toys);
    $substedit['%toynum%'] = $num;
    $substedit['%picpath%'] = SITEURL.PICT;
    $admin_page .= $this->ReplaceTemplate($substedit, 'order_edit');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    $admin_page = $this->general;
    $substadd['%title%'] = 'Новая запись - заказ';
    //Определение сроков доставки
    $substadd['%deliverytime%'] = MINTIME;
    //Получение общего количества игрушек
    $all = $this->db->СountData(TOYS);
    $substadd['%ordertoy%'] = '';
    for ($i = 0; $i < $all; $i++) {
      $substtoy['%ind%'] = $i;
      $substadd['%ordertoy%'] .= $this->ReplaceTemplate(array('%ind%' => $i), 'order_toy_2');
    }
    //Получение строки из массива данных и изображений игрушек
    $substadd['%strtoys%'] = $this->ToyImg2string(NULL);
    $substadd['%picpath%'] = SITEURL.PICT;
    $admin_page .= $this->ReplaceTemplate($substadd, 'order_insert');
    $admin_page .= '</body></html>';
    echo $admin_page;
  }
  
}

?>