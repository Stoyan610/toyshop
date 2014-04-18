<h2>Эта запись будет удалена</h2>
<table name='deleting' cellspacing='0' cellpadding='3' border='1'>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Категория</td>
    <td>Заголовок</td>
    <td>Краткое содержание</td>
    <td>Ревизия</td>
    <td>Дата пуб-ции</td>
  </tr>
  <tr align='center'>
    <td rowspan='2'>%contentid%</td>
    <td>%contentcat%</td>
    <td>%contenttitle%</td>
    <td>%contentbrief%</td>
    <td>%contentrevision%</td>
    <td>%publishfrom%</td>
  </tr>
  <tr>
    <td class='stl3' colspan='5'>%contenttext%</td>
  </tr>
</table><br />
<form name='deleting' action='deleting.php' method='post'>
  <input type='hidden' name='del' value='%contentid%' />
  <input type='hidden' name='choice' value='content' />
  <input type='submit' name='delete' value='Подтверждаю удаление' />
</form><br />
<form name='cancel' action='deleting.php' method='post'>
  <input type='hidden' name='choice' value='content' />
  <input type='submit' name='cancel' value='Отмена' />
</form>