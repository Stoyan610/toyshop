<tr align='center'>
  <td>%clientid%</td>
  <td>%clientname%<br />(заказов: %ordercount%)</td>
  <td>%clientphone%</td>
  <td>%clientmail%</td>
  <td>%clientcreated%</td>
  <td>%clientchanged%</td>
  <td>
    <ol>
      <li>
        <a href='client.php?act=edit&id=%clientid%'>Редактировать данные</a>
      </li>
      <li>
        <a href='order.php?act=part&Client_ID=%clientid%'>Заказы клиента</a>
      </li>
      <li>
        <a href='client.php?act=del&id=%clientid%'>Удалить клиента</a>
      </li>
    </ol>
  </td>
</tr>