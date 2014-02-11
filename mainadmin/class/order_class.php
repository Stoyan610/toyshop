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
    $this->allfields = array('ID', 'Client_ID', 'DeliveryAddress', 'DeliveryTime', 'Info', 'Created', 'Changed'); 
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
      echo "<a href='order.php?act=add' ".$stl.">Добавить заказ</a><br /><br /><table name='order' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='2' /><col span='1' width='140px' /><col span='7' /><col span='1' width='200px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Имя клиента</td><td>Контакты клиента</td><td>Заказанные товары</td><td>На сумму</td><td>Адрес доставки</td><td>Сроки доставки</td><td>Дополнительная информация</td><td>Дата заказа</td><td>Дата изменения</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        $this->FillCells($arr_table[$i]);
        echo "<td><ol><li><a href='order.php?act=basket&id=".$arr_table[$i]['ID']."'>Показать корзину</a></li><li><a href='order.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать заказ</a></li><li><a href='order.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить заказ</a></li></ol></td></tr>";
      }
      echo "</table><br />";
    }
  }
  
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnField($clientid) {
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    echo '<h2>Таблица - Все заказы выбранного клиента</h2>';
    $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, 'Client_ID', '=', $clientid);
    if (!$arr_table)     echo "<p>Таблица пуста - клиента можно удалять</p><a href='client.php?act=del&id=".$clientid."'>Удалить выбранного клиента</a>";
    else {
      $lines = count($arr_table);
      echo "<table name='order' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='2' /><col span='1' width='140px' /><col span='7' /><col span='1' width='200px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Имя клиента</td><td>Контакты клиента</td><td>Заказанные товары</td><td>На сумму</td><td>Адрес доставки</td><td>Сроки доставки</td><td>Дополнительная информация</td><td>Дата заказа</td><td>Дата изменения</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        $this->FillCells($arr_table[$i]);
        echo "<td><a href='order.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить заказ</a></td></tr>";
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
        $cond = "`Product_ID` = '".$arr_table[0]['Product_ID']."' AND `Priority` = '0'";
        $arr_img = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
        if (!$arr_img) $pic = 'emptytoy';
        else {
          $Image_ID = $arr_img[0];
          $pictures = $this->db->ReceiveFieldsOnId(IMG, array('ThumbnailFile'), $Image_ID);
          $pic = $pictures['ThumbnailFile'];
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
    echo "<table name='order' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='10' /><col span='1' width='240px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Имя клиента</td><td>Контакты клиента</td><td>Заказанные товары</td><td>На сумму</td><td>Адрес доставки</td><td>Сроки доставки</td><td>Дополнительная информация</td><td>Дата заказа</td><td>Дата изменения</td></tr><tr align='center'>";
    $this->FillCells($arr_table);
    echo "</tr></table><br /><form name='deleting' action='deleting.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='hidden' name='choice' value='order' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form><br /><form name='cancel' action='deleting.php' method='post'><input type='hidden' name='choice' value='order' /><input type='submit' name='cancel' value='Отмена' /></form>";
  }
  
  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  //Пока ещё только рыба........................
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение данных записи - заказа</h2>';
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='editing' cellspacing='0' cellpadding='5' border='1'><form name='editing' action='editing.php' method='post'>";
    echo "<tr><td ".$stl.">ID</td><td colspan='3'><input type='text' name='ID' value='".$arr_table['ID']."' readonly /></td></tr>";
    //Получение массива данных клиента
    $arr_clnt = $this->db->ReceiveFieldsOnId(CLNTS, array('Name', 'Phone', 'Mail'), $arr_table['Client_ID']);
    echo "<tr><td ".$stl.">Клиент<input type='hidden' name='Client_ID' valu='".$arr_table['Client_ID']."' /></td><td><input type='text' name='Name' value='".$arr_clnt['Name']."' /></td><td>".$arr_clnt['Phone']."</td><td>".$arr_clnt['Mail']."</td></tr>";
    echo "<tr><td ".$stl.">Адрес доставки</td><td colspan='3'><input type='text' name='ID' value='".$arr_table['DeliveryAddress']."' size='150' /></td></tr><tr><td ".$stl.">Сроки доставки</td><td colspan='3'><input type='text' name='ID' value='".$arr_table['DeliveryTime']."' /></td></tr><tr><td ".$stl.">Дополнительная информация</td><td colspan='3'><input type='text' name='ID' value='".$arr_table['Info']."' size='200' /></td></tr><tr align='center'><td ".$stl." colspan='4'>Корзина заказа</td></tr><tr align='center'><td ".$stl.">Игрушка</td><td ".$stl.">Цена</td><td ".$stl.">Количество</td><td></td></tr>";
    
    //Получение данных о товарах
    $goods = $this->db->ReceiveFewFieldsOnCondition(BASKET, $this->basketfields, 'Order_ID', '=', $id);
    $count = count($goods);
    for ($i = 0; $i < $count; $i++) {
      echo "<tr><td>".$goods[$i]['Name']."</td><td>".$goods[$i]['Price']."</td><td><input type='text' name='Quantity".$i."' value='".$goods[$i]['Quantity']."' size='5' /></td><td><input type='checkbox' name='del".$i."' />Удалить из корзины</td></tr>";
    }
    
    
    echo "<tr><td colspan='4' ondoubleclick=''>Для добавления игрушки в корзину дважды кликни здесь</td></tr>";
       
        
  }
/*  
    echo "<tr><td ".$stl.">Описание</td><td><input type='text' name='Description' value='".$arr_table['Description']."' size='100' /></td></tr><tr><td ".$stl.">Ключевые слова</td><td><input type='text' name='Keywords' value='".$arr_table['Keywords']."' size='50' /></td></tr><tr><td ".$stl.">Приоритет</td><td><input type='text' name='Priority' value='".$arr_table['Priority']."' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='".$arr_table['PublishFrom']."' /></td></tr><tr><td ".$stl.">Цена</td><td><input type='text' id='prc' name='Price' value='".$arr_table['Price']."'  onblur='checknum(".'"prc"'.")' /></td></tr><tr><td ".$stl.">В наличии</td><td><input type='text' name='Quantity' value='".$arr_table['Quantity']."' /></td></tr><tr><td ".$stl.">Сроки</td><td><input type='text' name='Deadline' value='".$arr_table['Deadline']."' /></td></tr><tr><td ".$stl.">Страна</td><td><input type='text' name='Manufacture' value='".$arr_table['Manufacture']."' /></td></tr><tr><td ".$stl.">Материал</td><td><input type='text' name='Material' value='".$arr_table['Material']."' /></td></tr><tr><td ".$stl.">Размеры</td><td><input type='text' name='Dimension' value='".$arr_table['Dimension']."' /></td></tr><tr><td ".$stl.">Вес</td><td><input type='text' id='wgt' name='Weight' value='".$arr_table['Weight']."' onblur='checknum(".'"wgt"'.")' /></td></tr><tr><td ".$stl.">Популярность</td><td><input type='text' name='Popularity' value='".$arr_table['Popularity']."' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='hidden' name='choice' value='product' /><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='editing.php' method='post'><input type='hidden' name='choice' value='product' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
 */ 
  
  
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Новая запись - заказ</h2>';
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='adding' cellspacing='0' cellpadding='5' border='1'><form name='adding' action='adding.php' method='post' onsubmit='return checkall(this)'>";
    echo "<tr><td ".$stl.">Клиент</td><td><input type='text' name='Name' value='' required /></td></tr><tr><td ".$stl.">Телефон</td><td><input id='phone' type='text' name='Phone' value='' required /></td></tr><tr><td ".$stl.">Почта</td><td><input id= 'mail' type='text' name='Mail' value='' /></td></tr>";
    
    //Определение сроков доставки
    //Получение $terms из таблицы 'a_content' и подстановка в value=''
    echo "<tr><td ".$stl.">Сроки доставки</td><td><input id='DTime' type='text' name='DeliveryTime' value='' /></td></tr>";
    echo "<tr><td ".$stl.">Адрес доставки</td><td><textarea name='DeliveryAddress' rows='4' cols='40' required></textarea></td></tr>";
    echo "<tr><td ".$stl.">Дополнительная<br />информация</td><td><textarea name='Info' rows='4' cols='40'></textarea></td></tr>";
    
    //Получение массива данных и изображений игрушек и превращение его в строку
    $arr_toy = $this->db->ReceiveFields(TOYS, array('ID', 'Name', 'Price', 'Quantity', 'Deadline'));
    foreach ($arr_toy as $key => $val) {
      $cond = "`Product_ID` = '".$val['ID']."' AND `Priority` = '0'";
      $arr_img = $this->db->ReceiveFieldOnManyConditions(LIMG, 'Image_ID', $cond);
      if (!$arr_img) $arr_toy[$key]['Pic'] = 'emptytoy';
      else {
        $Image_ID = $arr_img[0];
        $pic = $this->db->ReceiveFieldsOnId(IMG, array('ThumbnailFile'), $Image_ID);
        $arr_toy[$key]['Pic'] = $pic['ThumbnailFile'];
      }
      $str_rows[$key] = implode('~', $arr_toy[$key]);
    } 
    $str_toys = implode('^', $str_rows);
    
    echo "<tr><td ".$stl.">Игрушки<input id='toysinfo' type='hidden' name='Products' value='' /></td><td id='products' ondblclick='toylist()'><span style='text-decoration: underline;' >Для добавления игрушек дважды кликни здесь</span><div id='hide0' hidden>".SITEURL.PICT."toy70x70/</div><div id='hide1' hidden>".$str_toys."</div></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='hidden' name='choice' value='order' /><input type='submit' name='add' value='Подтверждаю добавление' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='adding.php' method='post'><input type='hidden' name='choice' value='order' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
}

?>