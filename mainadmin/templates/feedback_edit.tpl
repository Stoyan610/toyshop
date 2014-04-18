<h2>Изменение данных записи - информации</h2>
<form name='editing' action='editing.php' method='post'>
  <table name='editing' cellspacing='0' cellpadding='5' border='1'  width='100%'>
    <tr>
      <td style='stl'>ID</td>
      <td>
        <input type='text' name='ID' value='%commentid%' readonly />
      </td>
      <td style='stl'>Автор</td>
      <td>
        <input type='text' name='Name' value='%commentname%' size='50' readonly />
      </td>
    </tr>
    <tr>
      <td class='stl' colspan='4'>Содержание</td>
    </tr>
    <tr>
      <td colspan='4'>
        <div id='editor'>%commenttext%</div>
      </td>
    </tr>
    <tr>
      <td style='stl'>Дата</td>
      <td>
        <input type='text' name='Date' value='%commentdate%' readonly />
      </td>
      <td style='stl'>Дата публикации</td>
      <td>
        <input type='text' id='pick_next' name='PublishFrom' value='%publishfrom%' />
      </td>
    </tr>
    <tr>
      <td colspan='4' align='right'>
        <input type='hidden' name='choice' value='feedback' />
        <input type='submit' name='edit' value='Подтверждаю изменения' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='editing.php' method='post'>
  <table name='cancel' cellspacing='0' cellpadding='5' border='1' width='100%'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='feedback' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />