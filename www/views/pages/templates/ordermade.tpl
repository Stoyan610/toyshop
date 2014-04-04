<h2>Ваш заказ</h2>
<div id='ordermade' class='ord'> <!-- Оформление заказа -->
  <table id='dtls' cellpadding='2px' cellspacing='0' border='0' align='center'>
    <tr>
      <td class='td3' colspan='4' align='center'>Вы заказали</td>
    </tr>
    %ordertoy%
    <tr class='td3'>
      <td colspan='3' align='right'><span>На сумму с доставкой</span></td>
      <td>%amount% руб.</td>
    </tr>
    <tr>
      <td class='td3' colspan='4' align='center'>Номер Вашего заказа %ordernumber%</td>
    </tr>
    <tr>
      <td colspan='4' align='center'><i style='font-size: 80%;'>* Ваш заказ в обработке. Наш менеджер свяжется с Вами в самое ближайшее время *<br />* Если Вы хотите внести изменения в заказ, нажмите кнопку "Добавить ещё игрушки" *</i></td>
    </tr>
    <tr>
      <td colspan='3' align='center'>
        <form name='continue' action='ordercontinue.php' method='post'>
          <input class='dim' type='image' name='continue' src='%site_url%pictures/add_toys.png' style='margin-top: 20px;' /> 
        </form>
      </td>
      <td align='center'>
        <form name='cancel' action='ordercancel.php' method='post'>
          <input class='dim' type='image' name='cancel' src='%site_url%pictures/terminate.png' style='margin-top: 20px;' /> 
        </form>
      </td>
    </tr>
  </table>
</div>
<br />
