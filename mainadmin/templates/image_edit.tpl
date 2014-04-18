<h2>Изменение записи - изображения</h2>
<form name='editing' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='3' border='1'>
    <tr class='stl0' align='center'>
      <td>ID</td>
      <td>Тип изображения</td>
      <td>Изображение</td>
      <td>Описание</td>
      <td></td></td>
    </tr>
    <tr align='center'>
      <td>%imageid%<input type='hidden' name='ID' value='%imageid%' /></td>
      <td>%imagekind%</td>
      <td>
        <img src='%pictpath%%pictname%.jpg' alt='%pictname%' width='%minwidth%' height='%minheight%' />
      </td>
      <td>
        <input type='text' name='Alt' value='%imagealt%' size='100' />
      </td>
      <td>
        <input type='hidden' name='choice' value='image' />
        <input type='submit' name='edit' value='Подтверждаю изменения' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='editing.php' method='post'>
  <table name='editing' width='100%' cellspacing='0' cellpadding='3' border='1'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='image' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />