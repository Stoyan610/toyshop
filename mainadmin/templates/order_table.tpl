<h2>Таблица - %table_name%</h2>
<a class='stl' href='order.php?act=add'>Добавить заказ</a><br /><br />
<form name='number' action='order.php' method='get'>
  <input type='hidden' name='act' value='part' />
  <input type='text' name='Number' value='№' size='5' />&nbsp;&nbsp;
  <input type='submit' name='send' value='Найти заказ по номеру' />
</form><br />
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