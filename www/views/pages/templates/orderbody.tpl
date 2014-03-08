<h2>Оформление заказа</h2>
<form name='ordering' action='#' method='post'>
  <div class='ord'> <!-- Оформление заказа -->
    <table id='dtls' cellpadding='2px' cellspacing='0' border='0' align='center'>
      <tr>
        <td class='td3' colspan='5' align='center'>Вы собираетесь заказать</td>
      </tr>
      %ordertoy%
      <tr class='td3'>
        <td colspan='3' align='right'><span>Итого</span></td>
        <td>%amount% руб.</td>
        <td><img id='calc' src='%site_url%pictures/recalc_button.png' height='70px' width='210px' title='Пересчитать' alt='Calc' onclick='recalc()' /></td>
      </tr>
      
    </table>
  </div>

<script type='text/javascript' src='js/ordering.js'></script>

    <br />
  <div class='ord'> <!-- Продолжение оформления заказа -->
    <table cellpadding='5px' cellspacing='0' border='0' align='center'>
      <tr>
        <td class='td3' colspan='2' align='center'>Информация в службу доставки</td>
      </tr>
      <tr>
        <td class='td1'>Получатель заказа</td>
        <td><input type='text' name='name' value='' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>Контактный телефон</td>
        <td><input type='text' name='phone' value='' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>e-mail</td>
        <td><input type='text' name='email' value='' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>Адрес доставки</td>
        <td><input type='text' name='addr' value='' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>Время доставки</td>
        <td><input type='text' name='time' value='' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>Дополнительная<br />информация</td>
        <td><textarea name='extra' value='' cols='39' rows='4'></textarea></td>
      </tr>
      <tr height='45px' valign='bottom'>
        <td colspan='2' align='center'>
          <img class='dim' src='%site_url%pictures/confirm_button.png' height='70px' width='210px' alt='Confirm' onclick='confirmer()' />
        </td>
      </tr>
    </table>
  </div>
</form>
<br />
  </div>
</form>
<br /><br />
