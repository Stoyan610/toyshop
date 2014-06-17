<h2>Таблица - %table_name%</h2>
<a class='stl' href='order.php?act=add'>Добавить заказ</a><br /><br />
<table name='find' cellspacing='0' cellpadding='3' border='0'>
  <tr>
    <td>
      <form name='number' action='order.php' method='get'>
        <input type='hidden' name='act' value='part' />
        <input type='text' name='Number' value='№' size='5' />&nbsp;
        <input type='submit' name='send' value='Найти заказ по номеру' />
      </form>
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
      <form name='status' action='order.php' method='get'>
        <input type='hidden' name='act' value='part' />
        <select name='Status'>
          %options%
        </select>&nbsp;
        <input type='submit' name='send' value='Найти заказ по статусу' />
      </form>
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
      <form name='name' action='order.php' method='get'>
        <input type='hidden' name='act' value='find_client' />
        <input type='text' name='Name' value='' />&nbsp;
        <input type='submit' name='send' value='Найти заказ по имени клиента' />
      </form>
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
      <form name='name' action='order.php' method='get'>
        <input type='hidden' name='act' value='find_client' />
        <input type='text' name='Phone' value='' />&nbsp;
        <input type='submit' name='send' value='Найти заказ по телефону клиента' />
      </form>
    </td>
  </tr>
</table><br />
<table name='order' cellspacing='0' cellpadding='3' border='1'>
  <colgroup>
    <col span='2' />
    <col span='1' />
    <col span='1' width='140px' />
    <col span='1' width='180px' />
    <col span='6' />
    <col span='1' width='200px' />
  </colgroup>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>№</td>
    <td>Статус</td>
    <td>Имя клиента</td>
    <td>Контакты клиента</td>
    <td>Заказанные товары</td>
    <td>На сумму</td>
    <td>Адрес доставки</td>
    <td>Сроки</td>
    <td>Дополнительная информация</td>
    <td>Дата заказа</td>
    <td>Дата изменения</td>
    <td></td>
  </tr>
  %table_lines%
</table><br />