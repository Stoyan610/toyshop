<h2>Таблица - %title%</h2>
<a class='stl' href='content.php?act=add'>Добавить информационную запись</a><br /><br />
<form name='cat' action='content.php' method='get'>
  <input type='hidden' name='act' value='part' />
  <select name='Cat'>
    %options%
  </select>&nbsp;&nbsp;
  <input type='submit' name='send' value='Найти записи по категории' />
</form><br />
<table name='info' cellspacing='0' cellpadding='3' border='1'>
  <colgroup>
    <col span='6' />
    <col span='1' width='240px' />
  </colgroup>
  <tr class='stl0' align='center'>
    <td>ID</td>
    <td>Категория</td>
    <td>Заголовок</td>
    <td>Краткое содержание</td>
    <td>Ревизия</td>
    <td>Дата пуб-ции</td>
    <td></td>
  </tr>
  %table_lines%
</table><br />