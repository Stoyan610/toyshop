<h2>Изменение данных записи - клиента </h2>
<form name='editing' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td style='stl'>ID</td>
      <td>
        <input type='text' name='ID' value='%clientid%' readonly />
      </td>
    </tr>
    <tr><td style='stl'>Имя</td>
      <td>
        <input type='text' name='Name' value='%clientname%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Телефон</td>
      <td>
        <input type='text' name='Phone' value='%clientphone%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Почта</td>
      <td>
        <input type='text' name='Mail' value='%clientmail%' />
      </td>
    </tr>
    <tr>
      <td colspan='2' align='right'>
        <input type='hidden' name='choice' value='client' />
        <input type='submit' name='edit' value='Подтверждаю изменения' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='client' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />