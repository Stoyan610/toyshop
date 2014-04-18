<h2>%title%</h2>
<form name='editing' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td style='stl'>ID</td>
      <td>
        <input type='text' name='ID' value='%catalogid%' readonly />
      </td>
    </tr>
    <tr>
      <td style='stl'>Название</td>
      <td>
        <input type='text' name='Name' value='%catalogname%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Описание</td>
      <td>
        <input type='text' name='Description' value='%description%' size='100' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Ключевые слова</td>
      <td>
        <input type='text' name='Keywords' value='%keywords%' size='50' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Приоритет</td>
      <td>
        <input type='text' name='Priority' value='%priority%' />
      </td>
    </tr>
    <tr>
      <td style='stl'>Дата публикации</td>
      <td>
        <input type='text' id='pick' name='PublishFrom' value='%publishfrom%' />
      </td>
    </tr>
    <tr>
      <td colspan='2' align='right'>
        <input type='hidden' name='choice' value='catalog' />
        <input type='submit' name='edit' value='Подтверждаю изменения' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='5' border='1'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='catalog' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />    