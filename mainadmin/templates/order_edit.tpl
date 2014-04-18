<h2>%title%</h2>
<form name='editing' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td style='stl'>ID</td>
      <td colspan='3'>
        <input type='text' name='ordID' value='%orderid%' readonly size='4' />
      </td>
    </tr>
    <tr>
      <td style='stl'>№</td>
      <td colspan='3'>
        <input type='text' name='Number' value='%ordernumber%' readonly size='4' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Клиент</td>
      <td>%clientname%</td>
      <td>%clientphone%</td>
      <td>%clientmail%</td>
    </tr>
    <tr>
      <td style='stl'>Адрес доставки</td>
      <td colspan='3'>
        <input type='text' name='DAddress' value='%orderaddress%' size='100' /></td>
    </tr>
    <tr>
      <td style='stl'>Сроки</td>
      <td colspan='3'>
        <input type='text' name='DTime' value='%ordertime%' size='30' readonly />
      </td>
    </tr>
    <tr>
      <td style='stl'>Дополнительная информация</td>
      <td colspan='3'>
        <input type='text' name='Info' value='%orderinfo%' size='100' />
      </td>
    </tr>
    <tr align='center'>
      <td style='stl' colspan='4'>Корзина заказа</td>
    </tr>
    <tr align='center'>
      <td style='stl'>Игрушка</td>
      <td style='stl'>Цена</td>
      <td style='stl'>Количество</td>
      <td></td>
    </tr>
    %ordertoy%
    <tr id='products'>
      <td colspan='4' ondblclick='toylist2(%toynum%)'>
        <span>Для добавления игрушек дважды кликни здесь</span>
        <div id='hide0' hidden>%picpath%toy70x70/</div>
        <div id='hide1' hidden>%strtoys%</div>
      </td>
    </tr>
    <tr id='edit'>
      <td colspan='4' align='right'>
        <input type='hidden' name='inicount' value='%toynum%' />
        <input id='count' type='hidden' name='count' value='%toynum%' />
        <input type='hidden' name='choice' value='order' />
        <input type='submit' name='edit' value='Подтверждаю изменения' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='order' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />    