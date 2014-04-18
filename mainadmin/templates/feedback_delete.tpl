<h2>Эта запись будет удалена</h2>
<table name='deleting' cellspacing='0' cellpadding='3' border='1'>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Дата</td>
    <td>Автор</td>
    <td>Дата пуб-ции</td>
  </tr>
  <tr align='center'>
    <td rowspan='2'>%commentid%</td>
    <td>%commentdate%</td>
    <td>%commentname%</td>
    <td>%publishfrom%</td>
  </tr>
  <tr>
    <td class='stl3' colspan='3'>%commenttext%</td>
  </tr>
</table><br />
<form name='deleting' action='deleting.php' method='post'>
  <input type='hidden' name='del' value='%commentid%' />
  <input type='hidden' name='choice' value='feedback' />
  <input type='submit' name='delete' value='Подтверждаю удаление' />
</form><br />
<form name='cancel' action='deleting.php' method='post'>
  <input type='hidden' name='choice' value='feedback' />
  <input type='submit' name='cancel' value='Отмена' />
</form>