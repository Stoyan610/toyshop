<h2>Эта запись будет удалена</h2>
<table name='deleting' cellspacing='0' cellpadding='3' border='1'>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Название</td>
    <td>Описание</td>
    <td>Ключевые слова</td>
    <td>Изображение</td>
    <td>Приоритет</td>
    <td>Дата публикации</td>
  </tr>
  <tr align='center'>
    <td>%catalogid%</td>
    <td>%catalogname%</td>
    <td>%description%</td>
    <td>%keywords%</td>
    <td>
      <img src='%pictpath%mult114x86/%pictname%.jpg' alt='%pictname%' width='114' height='86' />
    </td>
    <td>%priority%</td>
    <td>%publishfrom%</td>
  </tr>
</table><br />
<form name='deleting' action='deleting.php' method='post'>
  <input type='hidden' name='del' value='%catalogid%' />
  <input type='hidden' name='choice' value='catalog' />
  <input type='submit' name='delete' value='Подтверждаю удаление' />
</form><br />
<form name='cancel' action='deleting.php' method='post'>
  <input type='hidden' name='choice' value='catalog' />
  <input type='submit' name='cancel' value='Отмена' />
</form>