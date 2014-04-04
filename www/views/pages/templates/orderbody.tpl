<h2>Оформление заказа</h2>
<form name='ordering' action='orderproceed.php' method='post'>
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
      <tr>
        <td colspan='5' align='center'><i style='font-size: 80%;'>* Все внесённые Вами изменения будут сохранены в корзине только при пересчёте или при подтверждении заказа *</i></td>
      </tr>
      <tr>
        <td colspan='5' align='center'><img class='dim' src='%site_url%pictures/clearbasket.png' height='70px' width='400px' title='Удалить всё' alt='Clearbasket' style='margin-top: 20px;' onclick='delall()' /></td>
      </tr>
    </table>
  </div>

<script type='text/javascript' src='views/pages/js/ordering.js'></script>
    
    <br />
  <div class='ord'> <!-- Продолжение оформления заказа -->
    <table cellpadding='5px' cellspacing='0' border='0' align='center'>
      <tr>
        <td class='td3' colspan='2' align='center'>Информация в службу доставки</td>
      </tr>
      <tr>
        <td class='td1'>Получатель заказа</td>
        <td><input type='text' name='name' value='%Name%' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>Контактный телефон</td>
        <td><input type='text' name='phone' value='%Phone%' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>e-mail</td>
        <td><input type='text' name='email' value='%Mail%' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>Адрес доставки</td>
        <td>
          <input type='radio' name='city' value='%Mos%' %Moscheck% />Москва&nbsp;&nbsp;&nbsp;
          <input type='radio' name='city' value='%Area%' %Areacheck% />Московская область не далее 15 км от МКАД<br />
          <input type='text' name='addr' value='%DeliveryAddress%' size='40' />
        </td>
      </tr>
      <tr>
        <td class='td1'>Время доставки</td>
        <td><input type='text' name='time' value='%DeliveryTime%' size='40' /></td>
      </tr>
      <tr>
        <td class='td1'>Дополнительная<br />информация</td>
        <td><textarea name='extra' value='' cols='39' rows='4'>%Info%</textarea></td>
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
