<h2>Эта запись будет удалена</h2>
<table name='order' cellspacing='0' cellpadding='3' border='1'>
  <colgroup>
    <col span='11' />
    <col span='1' width='240px' />
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
  </tr>
  <tr align='center'>
    <td>%orderid%</td>
    <td>%ordernumber%</td>
    <td>%clientname%</td>
    <td>%clientphone%<br />%clientmail%</td>
    <td>
      <ol>
        %ordergoods%
      </ol>
    </td>
    <td>%ordersum%</td>
    <td>%address%</td>
    <td>%deliverytime%</td>
    <td>%deliveryinfo%</td>
    <td>%created%</td>
    <td>%changed%</td>
  </tr>
</table><br />
<form name='deleting' action='deleting.php' method='post'>
  <input type='hidden' name='del' value='%orderid%' />
  <input type='hidden' name='choice' value='order' />
  <input type='submit' name='delete' value='Подтверждаю удаление' />
</form><br />
<form name='cancel' action='deleting.php' method='post'>
  <input type='hidden' name='choice' value='order' />
  <input type='submit' name='cancel' value='Отмена' />
</form>