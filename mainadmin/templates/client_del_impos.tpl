<h2>Этот клиент НЕ будет удалён</h2>
<table name='deleting' cellspacing='0' cellpadding='3' border='1'>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Имя</td>
    <td>Телефон</td>
    <td>Почта</td>
    <td>Дата создания</td>
    <td>Дата изменения</td>
  </tr>
  <tr align='center'>";
    <td>%clientid%</td>
    <td>%clientname%</td>
    <td>%clientphone%</td>
    <td>%clientmail%</td>
    <td>%clientcreated%</td>
    <td>%clientchanged%</td>
  </tr>
</table><br />
<p>К сожалению, удалить клиента `%clientname%`  невозможно, так как с ним связаны существующие заказы</p>
&nbsp;&nbsp;<a href='order.php?act=part&Client_ID=%clientid%'>Показать список соответствующих заказов?</a>
&nbsp;&nbsp;или
&nbsp;&nbsp;<a href='client.php'>Вернуться к списку клиентов?</a>