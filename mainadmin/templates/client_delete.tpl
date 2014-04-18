<h2>Этот клиент будет удалён</h2>
<table name='deleting' cellspacing='0' cellpadding='3' border='1'>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Имя</td>
    <td>Телефон</td>
    <td>Почта</td>
    <td>Дата создания</td>
    <td>Дата изменения</td>
  </tr>
  <tr align='center'>";
    <td>%clientid%</td>
    <td>%clientname%</td>
    <td>%clientphone%</td>
    <td>%clientmail%</td>
    <td>%clientcreated%</td>
    <td>%clientchanged%</td>
  </tr>
</table><br />
<form name='deleting' action='deleting.php' method='post'>
  <input type='hidden' name='del' value='%clientid%' />
  <input type='hidden' name='choice' value='client' />
  <input type='submit' name='delete' value='Подтверждаю удаление' />
</form><br />
<form name='cancel' action='deleting.php' method='post'>
  <input type='hidden' name='choice' value='client' />
  <input type='submit' name='cancel' value='Отмена' />
</form>