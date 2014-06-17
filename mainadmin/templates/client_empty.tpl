<h2>Таблица - Выбранный клиент</h2>
<p>Таблица пуста - клиента с параметром: <b style="text-decoration: underline;">%delid%</b> не существует</p>
<table name='find' cellspacing='0' cellpadding='3' border='0'>
  <tr>
    <td>
      <form name='name' action='client.php' method='get'>
        <input type='hidden' name='act' value='find_client' />
        <input type='text' name='Name' value='' />&nbsp;
        <input type='submit' name='send' value='Найти клиента по имени' />
      </form>
    </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
      <form name='name' action='client.php' method='get'>
        <input type='hidden' name='act' value='find_client' />
        <input type='text' name='Phone' value='' />&nbsp;
        <input type='submit' name='send' value='Найти клиента по телефону' />
      </form>
    </td>
  </tr>
</table>