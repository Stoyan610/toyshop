<?php
//Запрет прямого обращения
defined('ACCESS') or die('Access denied');
//Подключение абстрактного класса админки
require_once PATH.'mainadmin/class/admin_class.php';

class Order extends Admin {
  protected $tbl;
  protected $allfields;
  protected $limgfields;

  public function __construct($user, $pass) {
    parent::__construct($user, $pass);
    $this->tbl = ORDS;
    $this->allfields = array('ID', 'Client_ID', 'DeliveryAddress', 'DeliveryTime', 'Info', 'Created', 'Changed'); 
    $this->basketfields = array('Name', 'Price', 'Quantity');
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
        //Получение данных о товарах
        $goods = $this->db->ReceiveFewFieldsOnCondition(BASKET, $this->basketfields, 'Order_ID', '=', $val);
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
      echo "<a href='order.php?act=add' ".$stl.">Добавить заказ</a><br /><br /><table name='order' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='10' /><col span='1' width='240px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Имя клиента</td><td>Контакты клиента</td><td>Заказанные товары</td><td>На сумму</td><td>Адрес доставки</td><td>Сроки доставки</td><td>Дополнительная информация</td><td>Дата заказа</td><td>Дата изменения</td><td></td></tr>";
      for ($i = 0; $i < $lines; $i++) {
        echo "<tr align='center'>";
        $this->FillCells($arr_table[$i]);
        echo "<td><ol><li><a href='order.php?act=edit&id=".$arr_table[$i]['ID']."'>Редактировать заказ</a></li><li><a href='order.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить заказ</a></li></ol></td></tr>";
      }
      echo "</table><br />";
    }
  }
  
  
/*  
  //Вывод информации требуемого вида из данной таблицы БД 
  public function GetTableOnField($field, $value) {
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    if ($field == 'Catalog_ID') {
      echo '<h2>Таблица - Все игрушки с выбранным мультфильмом</h2>';
      $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, $field, '=', $value);
      if (!$arr_table)     echo "<p>Таблица пуста - мультфильм можно удалять</p><a href='catalog.php?act=del&id=".$value."'>Удалить выбранный мультфильм</a>";
      else {
        $lines = count($arr_table);
        echo "<table name='toy' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='13' /><col span='1' width='240px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Имя</td><td>Фото</td><td>Мульт.</td><td>Описание</td><td>Ключ. слова</td><td>Пр-тет</td><td>Дата пуб-ции</td><td>Цена</td><td>Есть</td><td>Срок</td><td>Страна<br />Материал<br />Размеры<br />Вес</td><td>Поп-сть</td><td></td></tr>";
        for ($i = 0; $i < $lines; $i++) {
          echo "<tr align='center'>";
          $this->GetImgCells($arr_table[$i]);
          echo "<td><ol><li><a href='product.php?act=changeimg&id=".$arr_table[$i]['ID']."'>Заменить изображения</a></li><li><a href='product.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить игрушку</a></li></ol></td></tr>";
        }
        echo "</table><br />";
      }
    }
    elseif ($field == 'Image_ID') {
      echo '<h2>Таблица - Все игрушки с выбранным изображением</h2>';
      $product_id = $this->db->ReceiveFieldOnCondition(LIMG, 'Product_ID', $field, '=', $value);
      $arr_table = $this->db->ReceiveFewFieldsOnCondition($this->tbl, $this->allfields, 'ID', '=', $product_id[0]);
      if (!$arr_table)     echo "<p>Таблица пуста - фото можно удалять</p><a href='image.php?act=del&id=".$value."'>Удалить выбранное изображение</a>";
      else {
        $lines = count($arr_table);
        echo "<table name='toy' cellspacing='0' cellpadding='3' border='1'><colgroup><col span='13' /><col span='1' width='240px' /></colgroup><tr align='center' ".$stl0."><td>ID</td><td>Имя</td><td>Фото</td><td>Мульт.</td><td>Описание</td><td>Ключ. слова</td><td>Пр-тет</td><td>Дата пуб-ции</td><td>Цена</td><td>Есть</td><td>Срок</td><td>Страна<br />Материал<br />Размеры<br />Вес</td><td>Поп-сть</td><td></td></tr>";
        for ($i = 0; $i < $lines; $i++) {
          echo "<tr align='center'>";
          $this->GetImgCells($arr_table[$i]);
          echo "<td><ol><li><a href='product.php?act=changeimg&id=".$arr_table[$i]['ID']."'>Заменить изображения</a></li><li><a href='product.php?act=del&id=".$arr_table[$i]['ID']."'>Удалить игрушку</a></li></ol></td></tr>";
        }
        echo "</table><br />";
      }
    }
  }
*/
  
  //Удаление записи в таблице БД
  public function DeleteItem($id){
    echo '<h2>Эта запись будет удалена</h2>'.$id;
  }
/*
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    if (!$arr_table)     exit('Нечего удалять');
    $stl = "style='font-size: 120%; font-weight: bold;'";
    $stl0 = "style='background-color: #88DD7B; font-size: 120%; font-weight: bold;'";
    echo "<table name='deleting' cellspacing='0' cellpadding='3' border='1'><tr align='center' ".$stl0."><td>ID</td><td>Название</td><td>Фото</td><td>Мульт.</td><td>Описание</td><td>Ключевые слова</td><td>Пр-тет</td><td>Дата публикации</td><td>Цена</td><td>Есть</td><td>Сроки</td><td>Страна<br />Материал<br />Размеры<br />Вес</td><td>Поп-сть</td></tr><tr align='center'>";
    $this->GetImgCells($arr_table);
    echo "</tr></table><br /><form name='deleting' action='deleting.php' method='post'><input type='hidden' name='del' value='".$id."' /><input type='hidden' name='choice' value='product' /><input type='submit' name='delete' value='Подтверждаю удаление' /></form><br /><form name='cancel' action='deleting.php' method='post'><input type='hidden' name='choice' value='product' /><input type='submit' name='cancel' value='Отмена' /></form>";
  
*/
  
  //Изменение записи в таблице БД
  public function EditItem($id) {
    echo '<h2>Изменение данных записи - игрушки</h2>'.$id;
  }
/*  
    $arr_table = $this->db->ReceiveFieldsOnId($this->tbl, $this->allfields, $id);
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='editing' cellspacing='0' cellpadding='5' border='1'><form name='editing' action='editing.php' method='post'>";
    echo "<tr><td ".$stl.">ID</td><td><input type='text' name='ID' value='".$arr_table['ID']."' readonly /></td></tr><tr><td ".$stl.">Название</td><td><input type='text' name='Name' value='".$arr_table['Name']."' /></td></tr>";
    //Получение массива названий мультфильмов
    $arr_mult = $this->db->ReceiveIDFieldsOnCondition(MULTS, 'Name');
    $arr_mult['0'] = '';
    
    echo "<tr><td ".$stl.">Мультфильм</td><td><select name='Catalog_ID'>";
    foreach ($arr_mult as $key => $val) {
      echo "<option value='".$key."'";
      if ($key == $arr_table['Catalog_ID']) echo " selected";
      echo ">".$val."</option>";
    }
    echo "</select></td></tr>";
    
    echo "<tr><td ".$stl.">Описание</td><td><input type='text' name='Description' value='".$arr_table['Description']."' size='100' /></td></tr><tr><td ".$stl.">Ключевые слова</td><td><input type='text' name='Keywords' value='".$arr_table['Keywords']."' size='50' /></td></tr><tr><td ".$stl.">Приоритет</td><td><input type='text' name='Priority' value='".$arr_table['Priority']."' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='".$arr_table['PublishFrom']."' /></td></tr><tr><td ".$stl.">Цена</td><td><input type='text' id='prc' name='Price' value='".$arr_table['Price']."'  onblur='checknum(".'"prc"'.")' /></td></tr><tr><td ".$stl.">В наличии</td><td><input type='text' name='Quantity' value='".$arr_table['Quantity']."' /></td></tr><tr><td ".$stl.">Сроки</td><td><input type='text' name='Deadline' value='".$arr_table['Deadline']."' /></td></tr><tr><td ".$stl.">Страна</td><td><input type='text' name='Manufacture' value='".$arr_table['Manufacture']."' /></td></tr><tr><td ".$stl.">Материал</td><td><input type='text' name='Material' value='".$arr_table['Material']."' /></td></tr><tr><td ".$stl.">Размеры</td><td><input type='text' name='Dimension' value='".$arr_table['Dimension']."' /></td></tr><tr><td ".$stl.">Вес</td><td><input type='text' id='wgt' name='Weight' value='".$arr_table['Weight']."' onblur='checknum(".'"wgt"'.")' /></td></tr><tr><td ".$stl.">Популярность</td><td><input type='text' name='Popularity' value='".$arr_table['Popularity']."' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='hidden' name='choice' value='product' /><input type='submit' name='edit' value='Подтверждаю изменения' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='editing.php' method='post'><input type='hidden' name='choice' value='product' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
  
  //Замена изображения в записи
  public function ChangeImage($Product_ID) {
    echo '<h2>Редактированите приоритетов или удаление картинок данной игрушки</h2>';
    //Получение массива изображений данной игрушки
    $arr_img = $this->db->ReceiveFewFieldsOnCondition(LIMG, $this->limgfields, 'Product_ID', '=', $Product_ID);
    echo "<h3><a href='product.php?act=addimg&id=".$Product_ID."'>Добавить изображение</a></h3>";
    if (!$arr_img)     echo "<p>Нет фото у данной игрушки</p><a href='product.php'>Вернуться к списку игрушек</a>";
    else {
      echo "<form name='change' action='imgchtoy.php' method='post'>";
      $stl = "style='display: inline-block; width: 170px; vertical-align: top; text-align: center;'";
      $count = 0;
      foreach ($arr_img as $num => $toyimage) {
        $count++;
        echo "<div ".$stl.">";
        foreach ($toyimage as $key => $val) {
          if ($key == 'ID')     echo "<input type='hidden' name='ID-".$num."' value='".$val."' />";
          if ($key == 'Image_ID') {
            if ($val == 0)    $pic[0] = 'emptytoy';
            else    $pic = $this->db->ReceiveFieldOnCondition(IMG, 'ThumbnailFile', 'ID', '=', $val);
            echo "<img src='".SITEURL.PICT."toy70x70/".$pic[0].".jpg' alt='".$pic[0]."' width='70' height='70' /><input type='hidden' name='Image_ID-".$num."' value='".$val."' /><br />";
          }
          if ($key == 'Priority')     echo "<input type='text' name='Priority-".$num."' value='".$val."' size='5' />&nbsp;Приор.<br />";
        }
        echo "<input type='checkbox' name='del-".$num."' />Удалить";  
        echo "</div>";
      }
      echo "<br /><br /><input type='hidden' name='count' value='".$count."' /><input type='submit' name='changing' value='Подтверждаю изменения' /></form><br /><form name='cancel' action='imgchtoy.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form>";
    }  
  }
    
  //Добавление изображения в запись
  public function AddImage($Product_ID) {
    echo '<h2>Добавление картинок к данной игрушке</h2>';
    //Получение массива изображений игрушек
    $field_list = array('ID', 'ThumbnailFile');
    $arr_img = $this->db->ReceiveFewFieldsOnCondition(IMG, $field_list, 'Kind', ' LIKE ', 'Игр%');
    if (!$arr_img)     echo "<p>Не из чего выбирать</p><a href='image.php'>К списку изображений</a>";
    $stl = "style='display: inline-block; width: 150px; vertical-align: top; text-align: center;'";
    echo "<form name='change' action='imgchtoy.php' method='post'><div id='toys'>";
    foreach ($arr_img as $row) {
      $img_id = $row['ID'];
      $pic = $row['ThumbnailFile'];
      echo "<div ".$stl."><img id='".$img_id."'src='".SITEURL.PICT."toy70x70/".$pic.".jpg' alt='".$pic."' width='70' height='70' onclick='gettoyimg(".$img_id.")' /></div>";
    }
    echo "</div><br /><input type='text' name='Priority' value='0' size='5' /> - Приоритет<br />";
    
    echo "<br /><input type='hidden' name='toy_id' value='".$Product_ID."' /><input id='imageid' type='hidden' name='toyimg_id' value='' /><input type='submit' name='adding' value='Подтверждаю выбор изображения' /></form><br /><form name='cancel' action='imgchtoy.php' method='post'><input type='submit' name='cancel' value='Отмена' /></form>";
  }
*/
  
  //Добавление новой записи в таблицу БД
  public function InsertItem() {
    echo '<h2>Новая запись - заказ</h2>';
  }
/*  
    $stl = "style='font-size: 120%; font-weight: bold;'";
    echo "<table name='adding' cellspacing='0' cellpadding='5' border='1'><form name='adding' action='adding.php' method='post'>";
    echo "<tr><td ".$stl.">Название</td><td><input type='text' name='Name' value='' required /></td></tr>";
    //Получение массива названий мультфильмов
    $arr_mult = $this->db->ReceiveIDFieldsOnCondition(MULTS, 'Name');
    $arr_mult['0'] = '';
    
    echo "<tr><td ".$stl.">Мультфильм</td><td><select name='Catalog_ID'>";
    foreach ($arr_mult as $key => $val) {
      echo "<option value='".$key."'";
      if ($key == '0') echo " selected";
      echo ">".$val."</option>";
    }
    echo "</select></td></tr>";
    
    echo "<tr><td ".$stl.">Описание</td><td><input type='text' name='Description' value='' size='100' /></td></tr><tr><td ".$stl.">Ключевые слова</td><td><input type='text' name='Keywords' value='' size='50' /></td></tr><tr><td ".$stl.">Приоритет</td><td><input type='text' name='Priority' value='' /></td></tr><tr><td ".$stl.">Дата публикации</td><td><input type='text' id='pick' name='PublishFrom' value='' /></td></tr><tr><td ".$stl.">Цена</td><td><input type='text' id='prc' name='Price' value=''  onblur='checknum(".'"prc"'.")' /></td></tr><tr><td ".$stl.">В наличии</td><td><input type='text' name='Quantity' value='' /></td></tr><tr><td ".$stl.">Сроки</td><td><input type='text' name='Deadline' value='' /></td></tr><tr><td ".$stl.">Страна</td><td><input type='text' name='Manufacture' value='' /></td></tr><tr><td ".$stl.">Материал</td><td><input type='text' name='Material' value='' /></td></tr><tr><td ".$stl.">Размеры</td><td><input type='text' name='Dimension' value='' /></td></tr><tr><td ".$stl.">Вес</td><td><input type='text' id='wgt' name='Weight' value='' onblur='checknum(".'"wgt"'.")' /></td></tr><tr><td ".$stl.">Популярность</td><td><input type='text' name='Popularity' value='' /></td></tr>";
    echo "<tr><td colspan='2' align='right'><input type='hidden' name='choice' value='product' /><input type='submit' name='add' value='Подтверждаю добавление' /></td></tr></form><tr><td colspan='2' align='right'><form name='cancel' action='adding.php' method='post'><input type='hidden' name='choice' value='product' /><input type='submit' name='cancel' value='Отмена' /></form></td></tr></table><br />";
  }
*/
  
}

?>