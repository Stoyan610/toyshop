<h2>Изменение данных записи - информации</h2>
<form name='editing' action='editing.php' method='post'>
  <table name='editing' cellspacing='0' cellpadding='5' border='1'  width='100%'>
    <tr>
      <td class='stl'>ID</td>
      <td colspan='3'>
        <input type='text' name='ID' value='%contentid%' readonly />
      </td>
    </tr>
    <tr>
      <td class='stl'>Категория</td>
      <td>
        <select name='Category'>
          %options%
        </select>
      </td>
      <td class='stl'>или ввести новую категорию</td>
      <td>
        <input type='text' name='new_Cat' value='' />
      </td>
    </tr>
    <tr>
      <td class='stl'>Заголовок</td>
      <td>
        <input type='text' name='Title' value='%contenttitle%' size='40' />
      </td>
      <td class='stl'>Краткое содержание</td>
      <td>
        <input type='text' name='Brief' value='%contentbrief%' size='80' />
      </td>
    </tr>
    <tr>
      <td class='stl'>Полное содержание</td>
      <td colspan='3'>
        <div id='editor'>%contenttext%</div>
      </td>
    </tr>
    <tr>
      <td class='stl'>Ревизия</td>
      <td>
        <select name='Revision'>
          <option %revisionselect1% value='1'>Действует</option>
          <option %revisionselect2% value='0'>В запасе</option>
        </select>
      </td>
      <td class='stl'>Дата публикации</td>
      <td>
        <input type='text' id='pick' name='PublishFrom' value='%publishfrom%' />
      </td>
    </tr>
    <tr>
      <td colspan='4' align='right'>
        <input type='hidden' name='choice' value='content' />
        <input type='submit' name='edit' value='Подтверждаю изменения' />
      </td>
    </tr>
  </table>
</form>
<form name='cancel' action='adding.php' method='post'>
  <table name='cancel' cellspacing='0' cellpadding='5' border='1' width='100%'>
    <tr>
      <td align='right'>
        <input type='hidden' name='choice' value='content' />
        <input type='submit' name='cancel' value='Отмена' />
      </td>
    </tr>
  </table>
</form><br />