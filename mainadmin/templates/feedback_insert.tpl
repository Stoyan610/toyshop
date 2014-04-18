<h2>Вставка нового отзыва</h2>
<form name='adding' action='adding.php' method='post'>
  <table name='adding' cellspacing='0' cellpadding='5' border='1' width='100%'>
    <tr>
      <td class='stl'>Автор</td>
      <td colspan='3'>
        <input type='text' name='Name' value='' size='50' />
      </td>
    </tr>
    <tr>
      <td class='stl' colspan='4'>Содержание</td>
    </tr>
    <tr>
      <td colspan='4'>
        <div id='editor'></div>
      </td>
    </tr>
    <tr>
      <td class='stl'>Дата</td>
      <td>
        <input type='text' id='pick' name='Date' value='' />
      </td>
      <td class='stl'>Дата публикации</td>
      <td>
        <input type='text' id='pick_next' name='PublishFrom' value='' />
      </td>
    </tr>
    <tr>
      <td colspan='4' align='right'>
        <input type='hidden' name='choice' value='feedback' />
        <input type='submit' name='add' value='Подтверждаю добавление' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='adding.php' method='post'>
  <table name='cancel' cellspacing='0' cellpadding='5' border='1' width='100%'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='feedback' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />