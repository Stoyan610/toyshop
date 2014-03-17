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
  
  //Подпрограмма выведения ячеек в таблице
  private function FillCells ($arr) {
    foreach ($arr as $key => $val) {
      if ($key == 'Client_ID') {
        //Получение данных о клиенте
        $field_list = array('Name', 'Phone', 'Mail');
        $client = $this->db->ReceiveFieldsOnId(CLNTS, $field_list, $val);
        echo "<td>".$client['Name']."</td>";
        echo "<td>".$client['Phone']."<br />".$client['Mail']."</td>";
        //Получение данных о товарах в корзине
        $goods = $this->db->ReceiveFewFieldsOnCondition(BASKET, $this->basketfields, 'Order_ID', '=', $arr['ID']);
        $count = count($goods);
        $sum = 0;
        echo "<td><ol>";
        for ($i = 0; $i < $count; $i++) {
          echo "<li>".$goods[$i]['Name']."</li>";
          $sum += $goods[$i]['Price']*$goods[$i]['Quantity'];
        }
        echo "</ol></td>";
        echo "<td>".$sum."</td>";
      }
      else echo "<td>".$val."</td>";
    }
  }
  
  //Получение полной информации из данной таблицы БД 
  public function GetTable() {
    echo '<h2>Таблица - Все заказы по дате оформления</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    $arr_table = $this->db->ReceiveFields($this->tbl, $this->allfields, 'Created', FALSE);
    if (!$arr_table)     echo "<p>Таблица пуста</p><a href='order.php?act=add' ".$stl.">Добавить заказ</a><br /><br />";
    else {
      $lines = count($arr_table);
      echo "<a href='order.php?act=add' ".$stl.">Добавить заказ</a><br /><br /><form name='number' action='order.php' method='get'><input type='hidden' name='act' value='part' /><input type='text' name='Number' value='№' size='5' />&nbsp;&nbsp;<input type='submit' name='send' value='Найти заказ по номеру' /></form><br /><table name='order' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='2' /><col span='1' /><col span='1' width='140px' /><col span='1' width='180px' /><col span='6' /><col span='1' width='200px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>№</td><td>Имя клиента</td><td>Контакты клиента</td><td>Заказанные товары</td><td>На сумму</td><td>Адрес доставки</td><td>Сроки</td><td>Дополнительная информация</td><td>Дата заказа</td><td>Дата изменения</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        $this->FillCells($arr_table[$i]);
        echo "<td><ol><li><a href='order.php?act=basket&id=".$arr_table[$i]['ID']."'>Показать корзину</a></li><li><a href='order.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать заказ</a></li><li><a href='order.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить заказ</a></li></ol></td></tr>";
      }
      echo "</table><br />";
    }
  }
  
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnField($field, $x) {
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    echo '<h2>Таблица - Выбранные заказы</h2>';
    $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, $field, '=', $x);
    if (!$arr_table)     echo "<p>Таблица пуста - клиента можно удалять</p><a href='client.php?act=del&id=".$x."'>Удалить выбранного клиента</a>";
    else {
      $lines = count($arr_table);
      echo "<table name='order' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='2' /><col span='1' /><col span='1' width='140px' /><col span='1' width='180px' /><col span='6' /><col span='1' width='200px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>№</td><td>Имя клиента</td><td>Контакты клиента</td><td>Заказанные товары</td><td>На сумму</td><td>Адрес доставки</td><td>Сроки</td><td>Дополнительная информация</td><td>Дата заказа</td><td>Дата изменения</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        $this->FillCells($arr_table[$i]);
        echo "<td><ol><li><a href='order.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать заказ</a></li><li><a href='order.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить заказ</a></li></ol></td></tr>";
      }
      echo "</table><br />";
    }
  }
  
  //Получение информации из корзины для данного заказа из БД 
  public function GetBasket($id) {
    echo '<h2>Таблица - корзина заказа № '.$id.'</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    $arr_table = $this->db->ReceiveFewFieldsOnCondition(BASKET, $this->basketfields, 'Order_ID', '=', $id);
    if (!$arr_table)     echo "<p>Корзина пуста</p><br /><br />";
    else {
      $lines = count($arr_table);
      echo "<a href='order.php' ".$stl.">Вернуться к заказам</a><br /><br /><table name='basket' cellspacing='0' cellpadding='3' border='1'><tr align='center' ".$stl0."><td>Фото</td><td>Название</td><td>Цена</td><td>Количество</td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        //Получение изображения игрушки
        $cond = "`Product_ID` = '".$arr_table[$i]['Product_ID']."' AND `Priority` = '0'";
        $arr_img = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
        if (!$arr_img) $pic = 'emptytoy';
        else {
          $Image_ID = $arr_img[0];
          $pictures = $this->db->ReceiveFieldsOnId(IMG, array('FileName'), $Image_ID);
          $pic = $pictures['FileName'];
        }
        echo "<td><img src='".SITEURL.PICT."toy70x70/".$pic.".jpg' alt='".$pic."' width='70' height='70' /></td><td>".$arr_table[$i]['Name']."</td><td>".$arr_table[$i]['Price']." руб.</td><td>".$arr_table[$i]['Quantity']." шт</td></tr>";
      }
      echo "</table><br />";
    }
  }
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Эта запись будет удалена</h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    echo "<table name='order' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='11' /><col span='1' width='240px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>№</td><td>Имя клиента</td><td>Контакты клиента</td><td>Заказанные товары</td><td>На сумму</td><td>Адрес доставки</td><td>Сроки</td><td>Дополнительная информация</td><td>Дата заказа</td><td>Дата изменения</td></tr><tr align='center'>";
    $this->FillCells($arr_table);
    echo "</tr></table><br /><form name='deleting' action='deleting.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='hidden' name='choice' value='order' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form><br /><form name='cancel' action='deleting.php' method='post'><input type='hidden' name='choice' value='order' /><input type='submit' name='cancel' value='Отмена' /></form>";
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
    echo '<h2>Изменение данных записи - заказа</h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='editing' cellspacing='0' cellpadding='5' border='1'><form name='editing' action='editing.php' method='post'>";
    echo "<tr><td ".$stl.">ID</td><td colspan='3'><input type='text' name='ordID' value='".$arr_table['ID']."' readonly size='4' /></td></tr>";
    echo "<tr><td ".$stl.">№</td><td colspan='3'><input type='text' name='Number' value='".$arr_table['Number']."' readonly size='4' /></td></tr>";
    //Получение массива данных клиента
    $arr_clnt = $this->db->ReceiveFieldsOnId(CLNTS, array('Name', 'Phone', 'Mail'), $arr_table['Client_ID']);
    echo "<tr><td ".$stl.">Клиент</td><td>".$arr_clnt['Name']."</td><td>".$arr_clnt['Phone']."</td><td>".$arr_clnt['Mail']."</td></tr>";
    echo "<tr><td ".$stl.">Адрес доставки</td><td colspan='3'><input type='text' name='DAddress' value='".$arr_table['DeliveryAddress']."' size='100' /></td></tr><tr><td ".$stl.">Сроки</td><td colspan='3'><input type='text' name='DTime' value='".$arr_table['DeliveryTime']."' size='30' readonly /></td></tr><tr><td ".$stl.">Дополнительная информация</td><td colspan='3'><input type='text' name='Info' value='".$arr_table['Info']."' size='100' /></td></tr><tr align='center'><td ".$stl." colspan='4'>Корзина заказа</td></tr><tr align='center'><td ".$stl.">Игрушка</td><td ".$stl.">Цена</td><td ".$stl.">Количество</td><td></td></tr>";
    //Получение данных о корзине
    $goods = $this->db->ReceiveFewFieldsOnCondition(BASKET, array('ID', 'Product_ID', 'Name', 'Price', 'Quantity'), 'Order_ID', '=', $id);
    $num = count($goods);
    //Получение общего количества игрушек
    $all = $this->db->СountData(TOYS);
    for ($i = 0; $i < $all; $i++) {
      if ($i < $num)      echo "<tr id='toy".$i."'><td>".$goods[$i]['Name']."<input type='hidden' name='baskID".$i."' value='".$goods[$i]['ID']."' /></td><td>".$goods[$i]['Price']." руб.</td><td><input type='text' name='Quantity".$i."' value='".$goods[$i]['Quantity']."' size='5' />шт</td><td><input type='checkbox' name='del".$i."' />Удалить из корзины</td></tr>";
      else      echo "<tr id='toy".$i."' hidden><td><input id='first".$i."' type='hidden' name='Product_ID".$i."' value='' /><input id='second".$i."' type='text' name='Name".$i."' value='' readonly/></td><td><input id='third".$i."' type='text' name='Price".$i."' value='' readonly/></td><td><input id='fourth".$i."' type='text' name='Quantity".$i."' value='' size='5' />шт</td><td><input type='checkbox' name='del".$i."' />Удалить из корзины</td></tr>";
    }
    for ($i = 0; $i < $num; $i++)     $bask_toys[$i] = $goods[$i]['Product_ID'];
    //Получение строки из массива данных и изображений игрушек
    $str_toys = $this->ToyImg2string($bask_toys);
    echo "<tr id='products'><td colspan='4' ondblclick='toylist2(".$num.")'><span style='text-decoration: underline;' >Для добавления игрушек дважды кликни здесь</span><div id='hide0' hidden>".SITEURL.PICT."toy70x70/</div><div id='hide1' hidden>".$str_toys."</div></td></tr>";
    echo "<tr id='edit'><td colspan='4' align='right'><input type='hidden' name='inicount' value='".$num."' /><input id='count' type='hidden' name='count' value='".$num."' /><input type='hidden' name='choice' value='order' /><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></form><tr><td colspan='4' align='right'><form name='cancel' action='editing.php' method='post'><input type='hidden' name='choice' value='order' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
  
  
  
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Новая запись - заказ</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='adding' cellspacing='0' cellpadding='5' border='1'><form name='adding' action='adding.php' method='post' onsubmit='return checkall(this)'>";
    echo "<tr><td ".$stl.">Клиент</td><td colspan='3'><input id='req1' type='text' name='Name' value='' /> (* - обязательно)</td></tr><tr><td ".$stl.">Телефон</td><td colspan='3'><input id='phone' type='text' name='Phone' value='' /> (* - обязательно)</td></tr><tr><td ".$stl.">Почта</td><td colspan='3'><input id= 'mail' type='text' name='Mail' value='' /></td></tr>";
    
    //Определение сроков доставки
    // ---- !!!!! ----- Получение $terms из таблицы 'a_content' и подстановка в value=''   ---- !!!!! -----
    $terms = 2;
    
    echo "<tr><td ".$stl.">Сроки</td><td colspan='3'><input id='DTime' type='text' name='DeliveryTime' value='".$terms."' readonly /></td></tr>";
    
    echo "<tr><td ".$stl.">Адрес доставки</td><td colspan='3'> (* - обязательно)<br /><textarea id='req_text' name='DeliveryAddress' rows='4' cols='80'></textarea></td></tr>";
    echo "<tr><td ".$stl.">Дополнительная<br />информация</td><td colspan='3'><textarea name='Info' rows='4' cols='80'></textarea></td></tr>";
    
    //Получение общего количества игрушек
    $all = $this->db->СountData(TOYS);
    for ($i = 0; $i < $all; $i++) {
      echo "<tr id='toy".$i."' hidden><td><input id='first".$i."' type='hidden' name='Product_ID".$i."' value='' /><input id='second".$i."' type='text' name='Name".$i."' value='' readonly/></td><td><input id='third".$i."' type='text' name='Price".$i."' value='' readonly/></td><td><input id='fourth".$i."' type='text' name='Quantity".$i."' value='' size='5' />шт</td><td><input type='checkbox' name='del".$i."' />Удалить из корзины</td></tr>";
    }
    
    //Получение строки из массива данных и изображений игрушек
    $str_toys = $this->ToyImg2string(NULL);
    
    echo "<tr><td ".$stl.">Игрушки</td><td id='products' colspan='3' ondblclick='toylist()'><span style='text-decoration: underline;' >Для добавления игрушек дважды кликни здесь</span><div id='hide0' hidden>".SITEURL.PICT."toy70x70/</div><div id='hide1' hidden>".$str_toys."</div></td></tr>";
    echo "<tr id='edit'><td colspan='4' align='right'><input id='count' type='hidden' name='count' value='' /><input type='hidden' name='choice' value='order' /><input type='submit' name='add' value='Подтверждаю добавление' /></td></tr></form><tr><td colspan='4' align='right'><form name='cancel' action='adding.php' method='post'><input type='hidden' name='choice' value='order' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
}

?>