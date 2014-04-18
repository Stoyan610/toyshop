<h2>%title%</h2>
<form name='adding' action='adding.php' method='post' onsubmit='return checkall(this)'>
  <table name='adding' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td style='stl'>Клиент</td>
      <td colspan='3'>
        <input id='req1' type='text' name='Name' value='' /> (* - обязательно)</td>
    </tr>
    <tr>
      <td style='stl'>Телефон</td>
      <td colspan='3'>
        <input id='phone' type='text' name='Phone' value='' /> (* - обязательно)</td>
    </tr>
    <tr>
      <td style='stl'>Почта</td>
      <td colspan='3'>
        <input id= 'mail' type='text' name='Mail' value='' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Сроки</td>
      <td colspan='3'>
        <input id='DTime' type='text' name='DeliveryTime' value='%deliverytime%' readonly />
      </td>
    </tr>
    <tr>
      <td style='stl'>Адрес доставки</td>
      <td colspan='3'> (* - обязательно)<br />
        <textarea id='req_text' name='DeliveryAddress' rows='4' cols='80'></textarea>
      </td>
    </tr>
    <tr>
      <td style='stl'>Дополнительная<br />информация</td>
      <td colspan='3'>
        <textarea name='Info' rows='4' cols='80'></textarea>
      </td>
    </tr>
    %ordertoy%
    <tr>
      <td style='stl'>Игрушки</td>
      <td id='products' colspan='3' ondblclick='toylist()'>
        <span>Для добавления игрушек дважды кликни здесь</span>
        <div id='hide0' hidden>%picpath%toy70x70/</div>
        <div id='hide1' hidden>%strtoys%</div>
      </td>
    </tr>
    <tr id='edit'>
      <td colspan='4' align='right'>
        <input id='count' type='hidden' name='count' value='' />
        <input type='hidden' name='choice' value='order' />
        <input type='submit' name='add' value='Подтверждаю добавление' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='adding.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='order' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />    