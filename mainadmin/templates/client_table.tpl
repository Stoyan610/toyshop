<h2>Таблица - Клиенты</h2>
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
</table><br />
<table name='client' cellspacing='0' cellpadding='3' border='1'>
  <colgroup>
    <col span='6' />
    <col span='1' width='240px' />
  </colgroup>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Имя</td>
    <td>Телефон</td>
    <td>Почта</td>
    <td>Дата создания</td>
    <td>Дата изменения</td>
    <td></td>
  </tr>
  %table_lines%
</table><br />